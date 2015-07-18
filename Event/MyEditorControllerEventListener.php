<?php
/**
 * [ControllerEventListener] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditorControllerEventListener extends BcControllerEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'initialize',
		'Users.beforeRender',
	);
	
/**
 * initialize
 * SiteConfigデータのエディタ設定を、ユーザー別の MyEditor 情報に書換える
 * ログアウトするまでユーザー別エディタは反映されない。そのため、MyEditor データを取得してない場合は強制的に取得する
 * startupでは書換えできない
 * 
 * @param CakeEvent $event
 */
	public function initialize (CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		
		$Controller = $event->subject();
		$user = BcUtil::loginUser();
		
		if (!isset($user['MyEditor']) || empty($user['MyEditor'])) {
			return;
		}
		
		if (ClassRegistry::isKeySet('MyEditor.MyEditor')) {
			$MyEditorModel = ClassRegistry::getObject('MyEditor.MyEditor');
		} else {
			$MyEditorModel = ClassRegistry::init('MyEditor.MyEditor');
		}
		$data = $MyEditorModel->find('first', array(
			'conditions' => array('MyEditor.user_id' => $user['id']),
			'recursive' => -1,
		));
		if ($data) {
			$Controller->siteConfigs['editor'] = $data['MyEditor']['editor'];
			$Controller->siteConfigs['editor_enter_br'] = $data['MyEditor']['editor_enter_br'];
		}
	}
	
/**
 * usersBeforeRender
 * ユーザー情報追加画面で実行し、MyEditorの初期値を設定する
 * 
 * @param CakeEvent $event
 */
	public function usersBeforeRender (CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		
		$Controller = $event->subject();
		
		if ($Controller->request->params['action'] == 'admin_edit') {
			if (isset($Controller->request->data['MyEditor']) && empty($Controller->request->data['MyEditor'])) {
				$Controller->request->data['MyEditor'] = array(
					'editor' => $Controller->siteConfigs['editor'],
					'editor_enter_br' => $Controller->siteConfigs['editor_enter_br'],
				);
			}
			return;
		}
		
		if ($Controller->request->params['action'] == 'admin_add') {
			$Controller->request->data['MyEditor'] = array(
				'editor' => $Controller->siteConfigs['editor'],
				'editor_enter_br' => $Controller->siteConfigs['editor_enter_br'],
			);
		}
	}
	
/**
 * pluginsBeforeRender
 * インストール後、セッション情報を書換えてツールバーのエディター切替えを表示する
 * ※未使用
 * 　→ インストール直後のプラグイン一覧画面でフックはできるが、毎回書換えが発生するため安全性に確証が取れないため
 * 
 * @param CakeEvent $event
 */
	public function pluginsBeforeRender (CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		
		$Controller = $event->subject();
		if ($Controller->request->params['action'] != 'admin_index') {
			return;
		}
		
		$user = BcUtil::loginUser();
		// saveしたあとの新しいユーザー情報（更新後のMyEditor情報）を取得する
		App::uses('User', 'Model');
		$UserModel = new User();
		$newUserData = $UserModel->find('first', array(
			'conditions' => array(
				'User.id' => $user['id'],
			),
		));
		if (isset($newUserData['MyEditor'])) {
			// Session更新のためのデータ形式に変更する
			$newData = $newUserData['User'];
			unset($newUserData['User']);
			$newUser = array_merge($newUserData, $newData);
			// セッション情報を更新し、新しいユーザー情報（MyEditor情報）をセッションに書き込む
			$Controller->Session->renew();
			$Controller->Session->write(BcAuthComponent::$sessionKey, $newUser);
		}
	}
	
}
