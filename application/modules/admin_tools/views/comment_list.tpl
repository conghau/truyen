{foreach $comments as $comment}
<div id="display_comment_{$comment['id']}" name="display_comment_{$post_id}">
	<div class="comment_wrap">
		<div class="comment_header">
			<div class="clearfix">
				<p class="name">From : {$comment['last_name_ja']}{$comment['first_name_ja']}</p>
				<p class="date" id="comment_updated_at_{$comment['id']}">{$comment['updated_at']|date_format:"m{'label_month'|lang}d{'label_day'|lang} H:i"}</p>
			</div><!--/clearfix-->
		</div>
		<p id="text_comment_{$comment['id']}">{$comment['body']}</p>
	</div><!--/comment_wrap-->
</div>
{/foreach}