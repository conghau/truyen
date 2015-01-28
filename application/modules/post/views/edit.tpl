<div id="edit_post_content">
	{$form_id = 'id=form_edit_post_'|cat:$post_id}
	{''|form_open:$form_id}
	<div class="ctinner tround">
		<div class="entry_view">
			<div class="clearfix">
			<div class="enter_head">
				<p class="name">{'label_post_send_to'|lang} : {$post_edit_to}</p>
			</div><!--/enter_head-->
				<p class="complete">
					<a id="btn_complete" onclick="update_post({$post_id})" name="btn_complete">
						<img src="{$fixed_base_url}assets/img/{$language}/complete_s.png">
					</a>
				</p>
			</div><!--/clearfix-->
			<div class="th_edit">
				<textarea id="post_body_edit_{$post_id}" name="post_body" rows="5" cols="" style="width:100%">{$post_edit_body}</textarea>
				<div id="post_error" style="color:red"></div>
				{if $is_expired_72 eq TRUE} 
				<div class="alignR">{'label_post_extend_72_hour'|lang}<input type="checkbox" name="chk_extend_72" id="chk_extend_72" value="1"></div>
				{/if}
			</div>
		</div><!--/entry_view-->
	</div><!--/ctinner-->
	{''|form_close}
</div>
{literal}
<script type="text/javascript">
	$("#post_body_edit_" + "{/literal}{$post_id}{literal}").focus();
</script>
{/literal}