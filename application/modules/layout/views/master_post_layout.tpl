{if isset($msg_no_exist)}
	<div>{$msg_no_exist}</div>
{else}
{foreach $posts as $post}
<div style="display:none" id="post_edit_{$post['id']}"></div>
<div id="post_detail_{$post['id']}">
<div class="ctinner tround">
	<div class="entry_view entrybox" post-id="{$post['id']}" owner="{($post['is_owned'] eq TRUE)?1:0}">
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
			{if $post['is_owned'] eq TRUE}
			<div class="entry_btn">
				<ul>
					<li><a onclick="edit_post({$post['id']})"><img src="{$fixed_base_url}assets/img/{$language}/btn_entry_edit.png"></a></li>
					<li><a onclick="confirm_delete_post({$post['id']})"><img src="{$fixed_base_url}assets/img/{$language}/btn_entry_delete.png"></a></li>
					<li><a onclick="copy_post({$post['id']})"><img src="{$fixed_base_url}assets/img/{$language}/btn_entry_copy.png"></a></li>
				</ul>
			</div><!--/entry_btn-->
			{/if}
		</div><!--/clearfix-->
		
		<div class="entry_body">
			<p>{$post['body']|h|addTagA}</p>
		</div>
		
		{include file="../../layout/views/master_post_upload_layout.tpl"}
		
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
		
		<div class="comment_wrap" style="display:inline-block; width: 100%;">
			{$form_id = 'id = form_post_'|cat:$post['id']}
			{''|form_open: $form_id}
				<div class="th_edit" style="padding-right:5px; margin-bottom: 5px">
					<textarea id="comment_body_{$post['id']}" name="comment_body[]" rows="5" style="resize: none;"></textarea>
				</div>
				<div id="error_{$post['id']}" style="color:red"></div>
				<div class="alignR"><a onclick="send_comment({$post['id']})"><img src="{$fixed_base_url}assets/img/{$language}/com_send.png"></a></div>
				<input type="hidden" name="hdn_body[]" id="hidden_comment_body_{$post['id']}"/>
			{''|form_close}
		</div>
	</div>
	<!-- /entry_view -->
</div>
</div>
{/foreach}
{/if}
