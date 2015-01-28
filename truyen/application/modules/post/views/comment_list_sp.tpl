{foreach $comments as $comment}
<div id="display_comment_{$comment['id']}" name="display_comment_{$post_id}">
	<div class="comment_wrap">
		<div class="comment_header">
			<div class="clearfix">
				<p class="name">From : {$comment['user_name']}</p>
				<p class="date" id="comment_updated_at_{$comment['id']}">{$comment['updated_at']|date_format:"m{'label_month'|lang}d{'label_day'|lang} H:i"}</p>
			</div><!--/clearfix-->
		</div>
		{if $comment['is_owned'] eq TRUE}
			<div id="edit_delete_comment_{$comment['id']}" class="entry_btn">
				<a onclick="edit_comment({$comment['id']})">{'label_user_thread_edit'|lang}</a> |
				<a onclick="confirm_delete_comment({$post_id}, {$comment['id']})">{'label_user_thread_delete'|lang}</a>
			</div>
			<div class="clearfix"></div>
		{/if}
		<p id="text_comment_{$comment['id']}">{$comment['body']|h|addTagA}</p>
		<!--投稿済みコメントの編集【B-9】-->
		<div class="th_edit">
			<textarea id="comment_edit_{$comment['id']}" name="comment_body_edit[]" rows="5" style="display:none; resize: none; width:99%"></textarea>
			<div id="message_error_{$comment['id']}" style="color:red"></div>
		</div>
		<div class="btn ">
			<a style="display:none" id="complete_comment_{$comment['id']}" onclick="complete_comment({$post_id}, {$comment['id']})">{'label_button_finish_edit'|lang}</a>
		</div>
		<!--/投稿済みコメントの編集【B-9】-->
	</div><!--/comment_wrap-->
</div>
{/foreach}
<input type="hidden" id="hdn_number_comment_{$post_id}" value="{(isset($number_comment))?$number_comment:''}"/>
<input type="hidden" id="hdn_add_new" value="{(isset($add_new))?$add_new:''}"/>
{literal}
<script type="text/javascript">
	var post_item = "{/literal}{$post_id}{literal}";
	$("#hide_" + post_item).text("{/literal}{$number_comment_show}{literal}");
	$("#show_" + post_item).text("{/literal}{$number_comment_hide}{literal}");
	var number_comment = Number($("#hdn_number_comment_" + post_item).val());
	if ((number_comment > 0) && (!$("#number_comment_" + post_item).is(":visible"))) {
		if ((number_comment == 1) && ($('#hdn_add_new').val() == 1)) {
			$("#hide_" + post_item).show();
			$("#show_" + post_item).hide();
		} else { 
			$("#hide_" + post_item).hide();
			$("#show_" + post_item).show();
		}
	}
</script>
{/literal}