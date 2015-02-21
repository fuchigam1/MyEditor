<?php
/**
 * [ADMIN] MyEditor
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
?>
<script type="text/javascript">
$(function () {
	$('#MyEditorTable').insertBefore('#BtnSave');

	siteConfigEditorClickHandler();	
	$('input[name="data[MyEditor][editor]"]').on('click', siteConfigEditorClickHandler);	
	function siteConfigEditorClickHandler() {
		if($('input[name="data[MyEditor][editor]"]:checked').val() === 'BcCkeditor') {
			$(".ckeditor-option").show();
		} else {
			$(".ckeditor-option").hide();
		}
	}
});
</script>

<div id="MyEditorTable">
	<div class="section">
		<?php echo $this->BcForm->input('MyEditor.id', array('type' => 'hidden')) ?>
		<table cellpadding="0" cellspacing="0" class="form-table">
			<tr>
				<th class="col-head">
					<?php echo $this->BcForm->label('MyEditor.editor', 'エディタタイプ') ?>
				</th>
				<td class="col-input" style="text-align: left;">
					<?php echo $this->BcForm->input('MyEditor.editor', array('type' => 'radio', 'options' => Configure::read('BcApp.editors'))) ?>
					<?php echo $this->BcForm->error('MyEditor.editor') ?>
				</td>
			</tr>
			<tr class="ckeditor-option">
				<th class="col-head">
					<?php echo $this->BcForm->label('MyEditor.editor_enter_br', '改行モード') ?>
				</th>
				<td class="col-input" style="text-align: left;">
					<?php echo $this->BcForm->input('MyEditor.editor_enter_br', array('type' => 'radio',
						'options' => array(
							'0' => '改行時に段落を挿入する',
							'1' => '改行時にBRタグを挿入する'
						)
					)) ?>
				</td>
			</tr>
		</table>
	<!-- /.section --></div>
<!-- /#MyEditorTable --></div>
