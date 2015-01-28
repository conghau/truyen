{if isset($msg_no_exist)}
	<div>{$msg_no_exist}</div>
{else}
{foreach $posts as $post}
<div style="display:none" id="post_edit_{$post['id']}"></div>
<div class="ctinner" id="post_detail_{$post['id']}">
	{if $post['is_owned'] eq TRUE}
	<div class="entry_btn">
		<ul>
		<li><a onclick="edit_post({$post['id']})"><img src="{$fixed_base_url}assets_sp/img/{$language}/btn_entry_edit.png"></a></li>
		<li><a onclick="confirm_delete_post({$post['id']})"><img src="{$fixed_base_url}assets_sp/img/{$language}/btn_entry_delete.png"></a></li>
		<li><a onclick="copy_post({$post['id']})"><img src="{$fixed_base_url}assets_sp/img/{$language}/btn_entry_copy.png"></a></li>
		</ul>
	</div><!--/entry_btn-->
	{/if}
	<div class="entry_view entrybox" post-id="{$post['id']}" owner="{($post['is_owned'] eq TRUE)?1:0}">
		<div class="clearfix">
		<div class="enter_head">
			<div class="clearfix">
			<p class="name">From : {$post['owner_name']}</p>
			<p class="date">{$post['updated_at']|date_format:"m{'label_month'|lang}d{'label_day'|lang} H:i"}</p>
			</div><!--/clearfix-->
			<p class="to">To : 
			{foreach $post['forwards'] as $forward}
				{$forward['name']}{if not $forward@last}、{else}&nbsp;{/if} 
			{/foreach}
			</p>
			<span class="kidoku">{'label_user_thread_read'|lang}: {$post['openlogs']|count}</span>
		</div><!--/enter_head-->
		</div><!--/clearfix-->
		<div class="entry_body">
			<p>{$post['body']|h|addTagA}</p>
		</div><!--/entry_body-->

		{include file="../../layout/views/master_post_upload_layout_sp.tpl"}

		<div class="file_action">
			<a  onclick="show_click(this)" id="show_{$post['id']}" {($post['count_comment'] == 0) ? 'style="display:none;"' : ''} class="com">
				{sprintf(('label_show_comments'|lang), $post['count_comment'])}
			</a>
			<a  onclick="hide_click(this)" id="hide_{$post['id']}" style="display:none;" class="com">
				{sprintf(('label_hide_comments'|lang), $post['count_comment'])}
			</a>
		</div><!--/file_action-->

		<div id="list_comment_{$post['id']}" style="display:none;"></div>

		{$form_id = 'id = form_post_'|cat:$post['id']}
		{''|form_open: $form_id}
		<div class="th_edit">
			<textarea id="comment_body_{$post['id']}" name="comment_body[]" rows="5" cols="" style="width:100%"></textarea>
			<div id="error_{$post['id']}" style="color:red"></div>
		</div>
		<div class="file_action">
			<a class="send" onclick="send_comment({$post['id']})">{'label_post_button_send_comment'|lang}</a>
		</div>
		<input type="hidden" name="hdn_body[]" id="hidden_comment_body_{$post['id']}"/>
		{''|form_close}
	</div><!--/entry_view-->
</div><!--/ctinner-->
{/foreach}
{/if}
