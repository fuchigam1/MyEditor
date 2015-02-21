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
		'Form.afterEnd',
	);
	
/**
 * formAfterEnd
 * ユーザー編集・登録画面にエディター指定欄を追加する
 * 
 * @param CakeEvent $event
 * @return string
 */
	public function formAfterEnd (CakeEvent $event) {
		$View = $event->subject();
		if (BcUtil::isAdminSystem()) {
			if ($View->request->params['controller'] == 'users') {
				if ($View->request->params['action'] == 'admin_edit' || $View->request->params['action'] == 'admin_add') {
					if ($event->data['id'] == 'UserAdminEditForm' || $event->data['id'] == 'UserAdminAddForm') {
						$event->data['out'] .= $View->element('MyEditor.admin/my_editor_form');
						return $event->data['out'];
					}
				}
			}
		}
	}
	
}
