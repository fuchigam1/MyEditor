<?php
/**
 * [ModelEventListener] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditorModelEventListener extends BcModelEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'User.beforeFind',
		'User.afterSave',
		'User.afterDelete',
	);
	
/**
 * userBeforeFind
 * ユーザー情報取得の際に、MyEditor 情報も併せて取得する
 * 
 * @param CakeEvent $event
 */
	public function userBeforeFind (CakeEvent $event) {
		$Model = $event->subject();
		$association = array(
			'MyEditor' => array(
				'className' => 'MyEditor.MyEditor',
				'foreignKey' => 'user_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}
	
/**
 * userAfterSave
 * ユーザー情報保存時に、MyEditor 情報を保存する
 * 
 * @param CakeEvent $event
 */
	public function userAfterSave (CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		$saveData = array();
		
		if ($created) {
			$saveData['MyEditor'] = $Model->data['MyEditor'];
			$saveData['MyEditor']['user_id'] = $Model->getLastInsertId();
		} else {
			$saveData['MyEditor'] = $Model->data['MyEditor'];
		}
		
		if (isset($saveData['MyEditor']['id'])) {
			$Model->MyEditor->set($saveData);
		} else {
			$Model->MyEditor->create($saveData);
		}
		if (!$Model->MyEditor->save()) {
			$this->log(sprintf('ID：%s のMyEditorの保存に失敗しました。', $Model->data['MyEditor']['id']));
		}
	}
	
/**
 * userAfterDelete
 * ユーザー情報削除時、そのユーザーが持つ MyEditor 情報を削除する
 * 
 * @param CakeEvent $event
 */
	public function userAfterDelete (CakeEvent $event) {
		$Model = $event->subject();
		$data = $Model->MyEditor->find('first', array(
			'conditions' => array('MyEditor.user_id' => $Model->id),
			'recursive' => -1
		));
		if ($data) {
			if (!$Model->MyEditor->delete($data['MyEditor']['id'])) {
				$this->log('ID:' . $data['MyEditor']['id'] . 'のMyEditorの削除に失敗しました。');
			}
		}
	}
	
}
