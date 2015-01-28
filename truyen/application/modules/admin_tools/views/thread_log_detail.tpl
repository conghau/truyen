{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/threadlog/'|cat:$parent_id|cat:'/detail'|form_open:'id=form_main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_thread_log_management'|lang} > {$parent_id}</h2></div>
	</div>
	<p class="ct" style="margin-top: 2%">
		{if isset($total_records) && $total_records > 0}
			<div style="float:left; margin-left: 70px;padding-top: 3px;">{'label_type_export_tsv'|lang}
					<select name="type_tsv" id="type_tsv" style="width:200px">
						{foreach from=$format_tsv item=item}
								<option value="{$item['id']}">{$item['label']|lang}</option>
						{/foreach}
					</select>
			</div>
			<a id="btn_export_search" class="" href="{$fixed_base_url}admin_tools/threadlog/export_search">
				<img src="{$fixed_base_url}assets-admin/img/result.png" alt="{'label_button_download_srch_result'|lang}">
			</a>
		{/if}	
	</p>
	<div class="clearfix">
		<div class="fr">
			{if isset($total_records) && $total_records > 0}
				<li style="float: right">{'label_number_record_per_page'|lang}
					<select name="per_page" id="txt_per_page" style="width:75px">
						{foreach $pagination_per_page_option as $row}
							{if $per_page eq $row}
								<option value="{$row}" selected="true">{$row}{'label_item'|lang}</option>
							{else}
								<option value="{$row}">{$row}{'label_item'|lang}</option>
							{/if}
						{/foreach}
					</select>
				</li>
			{/if}
		</div>
		<div class="hit fl">
			{if isset($total_records) && $total_records > 0}
				<p>{$total_records}{'label_item'|lang}</p>
			{else}
				<p>{'label_no_data'|lang}</p>
			{/if}
		</div>	
	</div>
	{if isset($total_records) && $total_records > 0}
	<table class="box_table">
			<tr style="background-color:gray;">
				<th width="15%">{'label_activity_thread_created_at'|lang}</th>
				<th width="10%">{'label_activity_thread_id'|lang}</th>
				<th width="10%">{'label_activity_thread_user_id'|lang}</th>
				<th width="10%">{'label_activity_thread_type'|lang}</th>
				<th width="10%">{'label_activity_thread_parent_id'|lang}</th>
				<th width="20%">{'label_activity_thread_body'|lang}</th>
				<th width="15%">{'label_activity_thread_deleted_at'|lang}</th>
				<th width="10%">{'label_activity_thread_thread_view'|lang}</th>
			</tr>
			{foreach $posts as $post}
			<tr>
				<td>{$post['created_at']|date_format:"Y/m/d H:i"}</td>
				<td>{$post['id']}</td>
				<td>{$post['user_id']}</td>
				<td>{$post_types[$post['type']].label|lang}</td>
				<td>{$post['parent_id']}</td>
				<td class ="ellipsisline">{$post['body']|truncate:30}</td>
				<td>{if isset($post['deleted_at'])} {$flag_deletes[2].label|lang} {else} 
													{$flag_deletes[1].label|lang} {/if}</td>
				<td>{$post['thread_view']}</td>
			</tr>
			{/foreach}
	</table>
	{/if}
	<div class="page clearfix">
		{$links}
	</div>
	{''|form_close}
{/block}
{block name=stylesheet}
	<style>
		table {
			table-layout: fixed;
		}
		table td {
			word-wrap:break-word;
		}
	</style>
{/block}
{block name=javascript}
{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			$(".date").datepicker({showButtonPanel: true});
			$('#txt_per_page').change(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$('#form_main').submit();
			});

			$("#btn_export_search").click(function(){
				var href="{/literal}{$fixed_base_url}{literal}admin_tools/threadlog/export_search?encoding="+$('#type_tsv').val(); 
				$("#btn_export_search").attr('href', href);
			});
		});
	</script>
{/literal}
{/block}