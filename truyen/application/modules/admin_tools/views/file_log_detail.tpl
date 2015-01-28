{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/filelog/'|cat:$user_id|cat:'/detail'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_file_log_management'|lang} > {$user_id}</h2></div>
		<div class="ctinner">
			<ul class="form clearfix">
				<div class="clearfix">
					<li><p>
						{'label_activity_date_search'|lang}
						<input type="text" name="created_date_start" class="date" value="{$created_date_start}"/> 〜
						<input type="text" name="created_date_end" class="date" value="{$created_date_end}"/>
					</p></li>
					<div class="fr">
						<a href="" id="btn_search"><img src="{$fixed_base_url}assets-admin/img/log.png" atl="{'label_activity_search_log'|lang}"></a>
					</div>
				</div>
				{form_error('created_date_start','<ul><li class="alert-error"> ※ ','</li></ul>')}
				{form_error('created_date_end','<ul><li class="alert-error"> ※ ','</li></ul>')}
			</ul>
		</div>
	</div>
	{if isset($total_records) && $total_records > 0}
		<div class="ct">
			<div style="float:left; margin-left: 100px;padding-top: 3px;">{'label_type_export_tsv'|lang}
				<select name="type_tsv" id="type_tsv" style="width:200px">
					{foreach from=$format_tsv item=item}
							<option value="{$item['id']}">{$item['label']|lang}</option>
					{/foreach}
				</select>
			</div>
			<a id='btn_export_search' href="{$fixed_base_url}admin_tools/filelog/export_search/?encoding=1"><img src="{$fixed_base_url}assets-admin/img/result.png" alt="{'label_button_download_srch_result'|lang}"></a>
		</div>
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
		<tr style="background-color:gray;">
			<th width="15%">{'label_activity_upload_created_at'|lang}</th>
			<th width="10%">{'label_activity_upload_user_id'|lang}</th>
			<th width="10%">{'label_activity_upload_id'|lang}</th>
			<th width="10%">{'label_activity_upload_post_id'|lang}</th>
			<th width="10%">{'label_activity_upload_expired_type'|lang}</th>
			<th width="15%">{'label_activity_upload_file_info'|lang}</th>
			<th width="10%">{'label_activity_upload_file_type'|lang}</th>
			<th width="10%">{'label_activity_upload_file_size'|lang}</th>
			<th width="10%">{'label_activity_upload_totalDL'|lang}</th>
		</tr>
		{if isset($uploads)}
			{foreach $uploads as $upload}
				<tr>
					<td>{$upload['created_at']|date_format:"Y/m/d H:i"}</td>
					<td>{$upload['user_id']}</td>
					<td>{$upload['id']}</td>
					<td>{$upload['post_id']}</td>
					<td>{$expired_types[$upload['expired_type']].label|lang}</td>
					<td>{$upload['file_name']}</td>
					<td>{$upload['file_extension']|upper}</td>
					<td class="alignR">{$upload['file_size']|file_size_format:0}</td>
					<td class="alignR">{$upload['totalDL']}</td>
				</tr>
			{/foreach}
		{/if}
	</table>
	{/if}
	<div class="page clearfix">{$links}</div>
{''|form_close}
{/block}

{block name=stylesheet}
<style>
	.alert-error{
		color:red;
	}
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
				$('#btn_search').click();
			});
			
			$("#btn_search").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$('#form-main').submit();
				return false;
			});
			
			$("#btn_export_search").click(function(){
				var href="{/literal}{$fixed_base_url}{literal}admin_tools/filelog/export_search?encoding="+$('#type_tsv').val(); 
				$("#btn_export_search").attr('href', href);
			});
		});
	</script>
{/literal}
{/block}