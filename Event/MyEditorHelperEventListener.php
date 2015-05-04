<?php
/**
 * [HelperEventListener] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditorHelperEventListener extends BcHelperEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'Form.afterForm',
	);
	
/**
 * 処理対象アクション
 * 
 * @var array
 */
	public $targetAction = array(
		'admin_edit',
		'admin_add',
	);
	
/**
 * 処理対象フォームID
 * 
 * @var array
 */
	public $targetFormId = array(
		'UserAdminEditForm',
		'UserAdminAddForm',
	);
	
/**
 * formAfterForm
 * ユーザー編集・登録画面にエディター指定欄を追加する
 * 
 * @param CakeEvent $event
 */
	public function formAfterForm (CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		
		$View = $event->subject();
		if ($View->request->params['controller'] != 'users') {
			return;
		}
		
		if (in_array($View->request->params['action'], $this->targetAction)) {
			if (in_array($event->data['id'], $this->targetFormId)) {
				echo $View->element('MyEditor.admin/my_editor_form');
			}
		}
	}
	
}
