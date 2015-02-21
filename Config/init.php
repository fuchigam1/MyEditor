<?php
/**
 * MyEditor プラグイン用
 * データベース初期化
 */
$this->Plugin->initDb('plugin', 'MyEditor');
/**
 * ユーザー情報を元にデータを作成する
 *   ・設定データがないユーザー用のデータのみ作成する
 * 
 */
	App::uses('User', 'Model');
	$UserModel = new User();
	$userDatas = $UserModel->find('list', array('recursive' => -1));
	if ($userDatas) {
		
		if (ClassRegistry::isKeySet('SiteConfig')) {
			$SiteConfig = ClassRegistry::getObject('SiteConfig');
		} else {
			$SiteConfig = ClassRegistry::init('SiteConfig');
		}
		$siteConfig = $SiteConfig->findExpanded();
		
		CakePlugin::load('MyEditor');
		App::uses('MyEditor', 'MyEditor.Model');
		$MyEditorModel = new MyEditor();
		foreach ($userDatas as $key => $user) {
			$myEditorData = $MyEditorModel->findByUserId($key);
			$savaData = array();
			if (!$myEditorData) {
				$savaData['MyEditor']['user_id'] = $key;
				$savaData['MyEditor']['editor'] = $siteConfig['editor'];
				$savaData['MyEditor']['editor_enter_br'] = $siteConfig['editor_enter_br'];
				$MyEditorModel->create($savaData);
				$MyEditorModel->save($savaData, false);
			}
		}
	}
