{foreach $posts as $post}
<div class="ctinner tround">
	<div class="entry_view">
		<div class="clearfix">
			<div class="enter_head">
				<div class="clearfix">
					<p class="name">From : {$post['owner_name']}</p>
					<p class="date">{$post['updated_at']|date_format:"m{'label_month'|lang}d{'label_day'|lang} H:i"}</p>
				</div><!--/clearfix-->
				<p class="to">To : 
				{foreach $post['forwards'] as $forward}
					{$forward['name']}{if $forward@last}&nbsp;&nbsp;&nbsp;{else}、{/if} 
				{/foreach}
				</p>
					
				<div class="kidoku {$language}">{'label_user_thread_read'|lang}<span class="pop1">{$post['openlogs']|count}</span>
					<p class="arrow_box" style="display: none;">
					{foreach $post['openlogs'] as $open_user}
						{$open_user}<br>
					{/foreach}
					</p>
				</div>
			</div><!--/enter_head-->
		</div><!--/clearfix-->
		
		<div class="entry_body">
			<p>{$post['body']|h|addTagA}</p>
		</div>
		
		{include file="../../layout/views/master_post_upload_layout_admin.tpl"}
		
		<div class="file_action clearfix">
			<a id="show_{$post['id']}" {($post['count_comment'] == 0) ? 'style="display:none;"' : ''}>
				{sprintf(('label_show_comments'|lang), $post['count_comment'])}
			</a>
			<a id="hide_{$post['id']}" style="display:none;">
				{sprintf(('label_hide_comments'|lang), $post['count_comment'])}
			</a>
		</div>
		<!--/file_action-->
		
		<div id="list_comment_{$post['id']}" style="display:none;"></div>
	</div>
	<!-- /entry_view -->
</div>
{/foreach}
