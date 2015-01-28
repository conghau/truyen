<!--作成済みスレッドの編集【B-8】-->
<div class="ctinner clearfix">
	{$form_id = 'id=form_edit_post_'|cat:$post_id}
	{''|form_open:$form_id}
	<div class="entry_view">
		<div class="enter_head">
			<p class="name">{'label_post_send_to'|lang} : {$post_edit_to}</p>
		</div><!--/enter_head-->
		<div class="th_edit">
			<textarea id="post_body_edit_{$post_id}" name="post_body" rows="5" cols="" style="width:100%; resize: none">{$post_edit_body}</textarea>
			<div id="post_error" style="color:red"></div>
		</div>
		<div class="btn ">
			<a href="#" id="btn_complete" onclick="update_post({$post_id})" name="btn_complete">{'label_button_finish_edit'|lang}</a>
		</div>
	</div><!--/entry_view-->
	{''|form_close}
</div><!--/ctinner-->
<!--/作成済みスレッドの編集【B-8】-->
{literal}
<script type="text/javascript">
	$("#post_body_edit_" + "{/literal}{$post_id}{literal}").focus();
</script>
{/literal}