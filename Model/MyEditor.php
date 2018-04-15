<?php
/**
 * [Model] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditor extends AppModel {
/**
 * ModelName
 * 
 * @var string
 */
	public $name = 'MyEditor';
	
/**
 * PluginName
 * 
 * @var string
 */
	public $plugin = 'MyEditor';

/**
 * belongsTo
 * 
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className'	=> 'User',
			'foreignKey' => 'user_id'
		)
	);
	
}
