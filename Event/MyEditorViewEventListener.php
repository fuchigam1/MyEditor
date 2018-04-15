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
			$element = $View->element($this->plugin .'.admin/my_editor_change');
			$element ='<div id="UserMenu"><ul class="clearfix">'. $element .'<li>';
			
			$regex = '/<div id="UserMenu">(.+?)<li>/s';
			$output = preg_replace($regex, $element, $event->data['out']);

			// 左上側の例
			//$regex = '/(<div id="ToolMenu">.+?)(<\/ul>)/s';
			//$output = preg_replace($regex, '$1<li class="tool-menu"><span id="ToolMyEditor">現在のエディタ</span></li>$2', $output);
			return $output;
		}
	}
	
}
