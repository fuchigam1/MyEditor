<?php
/**
 * [ADMIN] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
$user = BcUtil::loginUser();
?>
<?php if (!empty($user['MyEditor'])): ?>
<?php $editorList = Configure::read('BcApp.editors') ?>
<li id="MyEditorChoiceMenu"><?php $this->BcBaser->link('利用中：'. $this->BcText->arrayValue($user['MyEditor']['editor'], $editorList) . $this->BcBaser->getImg('admin/btn_dropdown.png', array('width' => 8, 'height' => 11, 'class' => 'bc-btn')), 'javascript:void(0)', array('class' => 'title')) ?>
	<ul><?php foreach ($editorList as $key => $editor): ?>
		<li><?php $this->BcBaser->link($editor, array(
				'admin' => true, 'plugin' => 'my_editor', 'controller' => 'my_editors', 'action' => 'change', $key
			)) ?></li>
		<?php endforeach ?>
	</ul>
</li>
<script>
	$(function () {
		$('#MyEditorChoiceMenu li a').on('click', function () {
			if (!confirm('記事内容を変更している場合、入力している内容は引き継がれません。\n本当によろしいですか？')) {
				return false;
			}
			return;
		});
	});
</script>
<?php endif ?>
