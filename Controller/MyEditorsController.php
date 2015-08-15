<?php
/**
 * [Controller] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditorsController extends BcPluginAppController {
/**
 * ControllerName
 * 
 * @var string
 */
	public $name = 'MyEditors';
	
/**
 * コンポーネント
 * 
 * @var     array
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
	
/**
 * Model
 * 
 * @var array
 */
	public $uses = array('MyEditor.MyEditor', 'User');
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'マイエディター';
	
/**
 * [ADMIN] ツールバーのエディター選択欄から、利用エディター切替えを行う
 * 
 */
	public function admin_change($editor = '') {
		$this->pageTitle = $this->adminTitle . '切替え';
		if (!$editor) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect($this->request->referer());
		}
		
		$user = $this->BcAuth->user();
		if (empty($user['MyEditor'])) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect($this->request->referer());
		}
		
		$this->request->data['MyEditor'] = $user['MyEditor'];
		$this->request->data['MyEditor']['editor'] = $editor;
		$this->MyEditor->set($this->request->data);
		if ($this->MyEditor->save($this->request->data)) {
			$message = '利用エディターを更新しました。';
			$this->setMessage($message, false);
			// saveしたあとの新しいユーザー情報（更新後のMyEditor情報）を取得する
			$newUserData = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $user['id'],
				),
			));
			// Session更新のためのデータ形式に変更する
			$newData = $newUserData['User'];
			unset($newUserData['User']);
			$newUser = array_merge($newUserData, $newData);
			// セッション情報を更新し、新しいユーザー情報（MyEditor情報）をセッションに書き込む
			$this->Session->renew();
			$this->Session->write(BcAuthComponent::$sessionKey, $newUser);
			
			// ログアウトしてしまうため以下は利用しない
			//$this->Session->write('Auth', $newUserData);
		} else {
			$message = '利用エディターの更新に失敗しました。';
			$this->setMessage($message, true);
		}
		$this->redirect($this->request->referer());
	}
	
/**
 * [ADMIN] ユーザーグループにマイエディター権限を付与する
 * - システム管理グループ以外のユーザーも、ツールバーからのエディター切替えができるようになる
 * 
 */
	public function admin_init() {
		App::uses('Model', 'UserGroup');
		$UserGroupModel = new UserGroup();
		$userGroupDataList = $UserGroupModel->find('all', array('recursive' => -1));
		if ($userGroupDataList) {
			App::uses('Model', 'Permission');
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
				$judgeExists = false;
				foreach ($permissions as $perm) {
					if ($perm['Permission']['url'] == '/'. $permissionAuthPrefix . '/my_editor/*') {
						$judgeExists = true;
					}
				}
				$saveData = array();
				if (!$judgeExists) {
					//「MyEditor権限」を追加する
					$saveData['Permission']['url'] = '/'. $permissionAuthPrefix . '/my_editor/*';
					$saveData['Permission']['name'] = 'マイエディター権限';
					$saveData['Permission']['user_group_id'] = $id;
					$saveData['Permission']['auth'] = true;
					$saveData['Permission']['status'] = true;
					$saveData['Permission']['no'] = $PermissionModel->getMax('no', array('user_group_id' => $id)) + 1;
					$saveData['Permission']['sort'] = $PermissionModel->getMax('sort', array('user_group_id' => $id)) + 1;
					$PermissionModel->create($saveData);
					$PermissionModel->save($saveData, false);
				}
			}
		}
		$message = 'ユーザーグループにマイエディター権限を付与しました。';
		$message .= 'システム管理グループ以外のユーザーも、ツールバーからエディターの切替えができるようになります。';
		$this->setMessage($message, false);
		$this->redirect($this->request->referer());
	}
	
}
