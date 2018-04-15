<?php
/**
 * MyEditor プラグイン用
 * データベース初期化
 */
$this->Plugin->initDb('MyEditor');
/**
 * ユーザー情報を元にデータを作成する
 *   ・設定データがないユーザー用のデータのみ作成する
 * 
 */
	clearAllCache();
	App::uses('User', 'Model');
	$UserModel = new User();
	$userDatas = $UserModel->find('list', array('recursive' => -1));
	if ($userDatas) {
		$SiteConfig = ClassRegistry::init('SiteConfig');
		$siteConfig = $SiteConfig->findExpanded();
		
		CakePlugin::load('MyEditor');
		App::uses('MyEditor', 'MyEditor.Model');
		$MyEditorModel = new MyEditor();
		foreach ($userDatas as $key => $user) {
			$hasData = $MyEditorModel->findByUserId($key);
			$savaData = array();
			if (!$hasData) {
				$savaData['MyEditor']['user_id'] = $key;
				$savaData['MyEditor']['editor'] = $siteConfig['editor'];
				$savaData['MyEditor']['editor_enter_br'] = $siteConfig['editor_enter_br'];
				$MyEditorModel->create($savaData);
				$MyEditorModel->save(null, array('callbacks' => false, 'validate' => false));
			}
		}
	}
	
/**
 * ツールバーのエディター切替え表示のため、ユーザーグループに権限を作成する
 *   ・権限データがないグループのデータのみ作成する
 * 
 */
	clearAllCache();
	App::uses('UserGroup', 'Model');
	$UserGroupModel = new UserGroup();
	$userGroupDataList = $UserGroupModel->find('all', array('recursive' => -1));
	if ($userGroupDataList) {
		App::uses('Permission', 'Model');
		$PermissionModel = new Permission();
		foreach ($userGroupDataList as $key => $userGroupData) {
			$id = $userGroupData['UserGroup']['id'];
			// 管理グループの場合は必要ないので権限追加処理をスキップする
			if ($id == 1) {
				continue;
			}
			// グループ別のアクセス制限設定を取得する
			$permissionAuthPrefix = $PermissionModel->UserGroup->getAuthPrefix($id);
			$permissions = $PermissionModel->find('all', array('conditions' => array(
				'Permission.user_group_id' => $id
			)));
			// MyEditor用権限の存在をチェックする
			$hasPermission = false;
			foreach ($permissions as $perm) {
				if ($perm['Permission']['url'] == '/'. $permissionAuthPrefix . '/my_editor/*') {
					$hasPermission = true;
					break;
				}
			}
			$saveData = array();
			if (!$hasPermission) {
				//「MyEditor権限」を追加する
				$saveData['Permission']['url'] = '/'. $permissionAuthPrefix . '/my_editor/*';
				$saveData['Permission']['name'] = 'マイエディター権限';
				$saveData['Permission']['user_group_id'] = $id;
				$saveData['Permission']['auth'] = true;
				$saveData['Permission']['status'] = true;
				$saveData['Permission']['no'] = $PermissionModel->getMax('no', array('user_group_id' => $id)) + 1;
				$saveData['Permission']['sort'] = $PermissionModel->getMax('sort', array('user_group_id' => $id)) + 1;
				$PermissionModel->create($savaData);
				$PermissionModel->save(null, array('callbacks' => false, 'validate' => false));
			}
		}
	}
