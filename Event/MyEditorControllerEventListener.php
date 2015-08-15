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
 * プラグインのモデル名
 * 
 * @var string
 */
	private $pluginModelName = 'MyEditor';
	
/**
 * 処理対象とするアクション
 * 
 * @var array
 */
	private $targetAction = array('admin_edit', 'admin_add');
	
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
		
		if (!isset($user[$this->pluginModelName]) || empty($user[$this->pluginModelName])) {
			return;
		}
		
		if (ClassRegistry::isKeySet($this->plugin .'.'. $this->pluginModelName)) {
			$MyEditorModel = ClassRegistry::getObject($this->plugin .'.'. $this->pluginModelName);
		} else {
			$MyEditorModel = ClassRegistry::init($this->plugin .'.'. $this->pluginModelName);
		}
		$data = $MyEditorModel->find('first', array(
			'conditions' => array($this->pluginModelName .'.user_id' => $user['id']),
			'recursive' => -1,
		));
		if ($data) {
			$Controller->siteConfigs['editor'] = $data[$this->pluginModelName]['editor'];
			$Controller->siteConfigs['editor_enter_br'] = $data[$this->pluginModelName]['editor_enter_br'];
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
		if (!in_array($Controller->request->params['action'], $this->targetAction)) {
			return;
		}
		
		if ($Controller->request->params['action'] == 'admin_add') {
			$Controller->request->data[$this->pluginModelName] = array(
				'editor' => $Controller->siteConfigs['editor'],
				'editor_enter_br' => $Controller->siteConfigs['editor_enter_br'],
			);
			return;
		}
		
		if (isset($Controller->request->data[$this->pluginModelName]) && empty($Controller->request->data[$this->pluginModelName])) {
			$Controller->request->data[$this->pluginModelName] = array(
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
		if (isset($newUserData[$this->pluginModelName])) {
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
