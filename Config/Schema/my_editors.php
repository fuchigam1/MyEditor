<?php 
class MyEditorsSchema extends CakeSchema {

	public $file = 'my_editors.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $my_editors = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザーID'),
		'editor' => array('type' => 'string', 'null' => true, 'default' => null, 'comment' => '利用エディター', 'charset' => 'utf8'),
		'editor_enter_br' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2, 'unsigned' => false, 'comment' => '改行モード'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
