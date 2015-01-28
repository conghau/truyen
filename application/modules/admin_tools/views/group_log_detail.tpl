{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/grouplog/'|cat:$id|cat:'/detail'|form_open:' id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_log_management'|lang} > {$id}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<div class="clearfix">
					<li>
						<p>
						<label style="width :200px">{'label_grouplog_time'|lang}</label>
						<input type="text" class ="day_search" name ="from_date" class="date" value="{$from_date}"/> 〜
						<input type="text" class="day_search" name="to_date" class="date" value="{$to_date}"/>
							
						</p>
					</li>
					
					<div class="fr">
						<a href="" id="btn_search"/><img src="{$fixed_base_url}assets-admin/img/log.png" alt="{'label_button_search'|lang}"></a>
					</div>
				</div>
			</ul>
			{form_error('from_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('to_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
		</div>
	</div>
	{if isset($total_records) && $total_records > 0}
	<p class="ct">
		<div style="float:left; margin-left: 70px;padding-top: 3px;">{'label_type_export_tsv'|lang}
				<select name="type_tsv" id="type_tsv" style="width:200px">
					{foreach from=$format_tsv item=item}
							<option value="{$item['id']}">{$item['label']|lang}</option>
					{/foreach}
				</select>
		</div>
		<a id="btn_export_search" class ="button" href="{$fixed_base_url}admin_tools/grouplog/export_search">
			<img src="{$fixed_base_url}assets-admin/img/result.png" alt="{'label_button_download_srch_result'|lang}"></a>
	</p>
	{/if}
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
			<tr>
				<th style="width: 13%">{'label_grouplog_created_at'|lang}</th>
				<th style="width: 8%">{'label_grouplog_id'|lang}</th>
				<th style="width: 12%">{'label_grouplog_user_id'|lang}</th>
				<th style="width: 12%">{'label_grouplog_type'|lang}</th>
				<th style="width: 10%">{'label_grouplog_parent_id'|lang}</th>
				<th style="width: 20%">{'label_grouplog_body'|lang}</th>
				<th style="width: 10%">{'label_grouplog_deleted_at'|lang}</th>
				<th style="width: 10%">{'label_grouplog_thread_download_view'|lang}</th>
				<th style="width: 10%">{'label_grouplog_thread_view'|lang}</th>
			</tr>
			{foreach $list_detail as $detail}
				<tr>			
					<td>{$detail['created_at']|date_format:"Y/m/d H:i"}</td>
					<td>{$detail['id']}</td>
					<td>{$detail['user_id']}</td>
					<td>{$post_types[$detail['type']].label|lang}</td>
					<td>{if $detail['parent_id'] == 0 } {''} {else} {$detail['parent_id']} {/if}</td>
					<td class ="ellipsisline">{$detail['body']|truncate:50}</td>
					<td>{if isset($detail['deleted_at'])} {$flag_delete[2].label|lang} {else} {$flag_delete[1].label|lang} {/if}</td>
					<td> {$detail['thread_download_view']}</td>
					<td> {$detail['thread_view']}</td>
				</tr>
			{/foreach}
		</table>
		<div class="page clearfix">{$links}</div>
	{/if}
{''|form_close}
{/block}

{block name=stylesheet}
<style>
	table {
		table-layout:fixed;
		font-size: inherit;
	}
	table th {
		height: 20px;
	}
	table td {
		overflow: hidden;
		word-wrap:break-word;
	}
	.alert-error{
		color:red;
	}
</style>
{/block}

{block name=javascript}
	{literal}
		<script type="text/javascript">
		$(document).ready(function(){
			$(".day_search").datepicker({showButtonPanel: true});

			$('#txt_per_page').change(function(){
				$('#btn_search').click();
			});

			$('#btn_search').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$('#form-main').submit();
				return false;
			});

			$("#btn_export_search").click(function(){
				var href="{/literal}{$fixed_base_url}{literal}admin_tools/grouplog/export_search?encoding="+$('#type_tsv').val(); 
				$("#btn_export_search").attr('href', href);
			});
		});
		</script>
	{/literal}
{/block}
