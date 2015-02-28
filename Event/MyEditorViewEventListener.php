<?php
/**
 * [ViewEventListener] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class MyEditorViewEventListener extends BcViewEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'afterElement',
	);
	
/**
 * afterElement
 * ツールバーにエディタ切替えを追加する
 * 
 * @param CakeEvent $event
 */
	public function afterElement(CakeEvent $event) {
		$View = $event->subject();
		if (preg_match('/^toolbar$/', $event->data['name'])) {
			$element = $View->element('MyEditor.admin/my_editor_choice');
			$element ='<div id="UserMenu"><ul class="clearfix">'. $element .'<li>';
			
			$regex = '/<div id="UserMenu">(.+?)<li>/s';
			$output = preg_replace($regex, $element, $event->data['out']);
			return $output;
		}
	}
	
}
