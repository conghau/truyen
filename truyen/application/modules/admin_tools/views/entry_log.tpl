{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/entrylog'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_entry_log_management'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li>
					<label for="qualification">{'label_entry_qualification'|lang}</label>
					<select name="qualification">
						<option value></option>
						{foreach $list_qualification as $item}
							{if $qualification eq $item.id}
								<option value="{$item.id}" selected="selected">{$item.name}</option>
							{else}
								<option value="{$item.id}">{$item.name}</option>
							{/if}
						{/foreach}
					</select>
				</li>
				<li>
					<label for="gender">{'label_entry_gender'|lang}</label>
					<select name="gender">
						<option></option>
						{foreach $gender_types as $item}
							{if $gender eq $item.id}
								<option value="{$item.id}" selected="selected">{$item.label|lang}</option>
							{else}
								<option value="{$item.id}">{$item.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</li>
				<li>
					<label for="company_code">{'label_entry_company_code'|lang}</label>
					<input name="company_code" type="text" class="txt70" value="{$company_code}">
				</li>
			</ul >
			<ul class="form clearfix">
				<li>
					<label for="registered_type">{'label_entry_registered_type'|lang}</label>
					<input name="registered_type" type=text class="txt90" value="{$registered_type}">
				</li>
				<li>
					<label for="status">{'label_entry_status'|lang}</label>
					<select name="status">
						<option value></option>
						{foreach $status_types as $item}
							{if $status eq $item.id}
								<option value="{$item.id}" selected="selected">{$item.label|lang}</option>
							{else}
								<option value="{$item.id}"> {$item.label|lang} </option>
							{/if}
						{/foreach}
					</select>
				</li>
			</ul>
			<ul class="form clearfix">
				<li>
					<label for="created_at">{'label_entry_created_at'|lang}</label>
					<input type="text" name="start_date" class="date" value="{$start_date}"> 〜	
					<input type="text" name="end_date" class="date" value="{$end_date}">
				</li>
			</ul>	
				{form_error('start_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
				{form_error('end_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			
			<div class="alignR">
				<a href="" id="btn_search"><img src="{$fixed_base_url}assets-admin/img/search.png" alt="{'label_activity_search_log'|lang}"></a>
			</div>
			<p><img src="{$fixed_base_url}assets-admin/img/hr.png"></p>
				<div class="ct">
					
					{if isset($has_data) && $has_data eq true}
					<a href="{$fixed_base_url}admin_tools/entrylog/detail_all"><img src="{$fixed_base_url}assets-admin/img/log_all.png" alt="{'label_entry_button_all_cases'|lang}"></a>
					{/if}
					{if isset($total_records) && $total_records > 0}
					<a href="{$fixed_base_url}admin_tools/entrylog/detail_search" ><img src="{$fixed_base_url}assets-admin/img/log_search.png" alt="{'label_entry_button_search_result'|lang}"></a>
					{/if}
					<br/>
					{if (isset($has_data) && $has_data eq true) || (isset($total_records) && $total_records > 0)}
					<div style="float:left; margin-left: 40px;padding-top: 3px;">{'label_type_export_tsv'|lang}
							<select name="type_tsv" id="type_tsv" style="width:200px">
								{foreach from=$format_tsv item=item}
										<option value="{$item['id']}">{$item['label']|lang}</option>
								{/foreach}
							</select>
					</div>
					{/if}
					
					{if isset($has_data) && $has_data eq true}
					<a id="btn_export_all" href="{$fixed_base_url}admin_tools/entrylog/export_all"><img src="{$fixed_base_url}assets-admin/img/log_dl.png" alt="{'label_button_download_all'|lang}"></a>
					{/if}
					{if isset($total_records) && $total_records > 0}
					<a id="btn_export_search" href="{$fixed_base_url}admin_tools/entrylog/export_search"><img src="{$fixed_base_url}assets-admin/img/search_dl.png" alt="{'label_button_download_srch_result'|lang}"></a>
					{/if}
				</div>
		</div>
	</div>
	
	<!-- Info about record -->
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
	<!-- Table -->
	{if isset($total_records) && $total_records > 0}
	<table class="box_table">
			<tr>
				<th style="width:9%">{'label_entry_created_date'|lang}</th>
				<th style="width:6.4%">{'label_entry_user_id'|lang}</th>
				<th style="width:10%">{'label_entry_email'|lang}</th> 
	 			<th style="width:5%">{'label_entry_last_name_ja'|lang}</th>
	 			<th style="width:5%">{'label_entry_first_name_ja'|lang}</th> 
	 			<th style="width:8.5%">{'label_entry_organization'|lang}</th>
	 			<th style="width:8%">{'label_entry_qualification'|lang}</th>
	 			<th style="width:7.5%">{'label_entry_company_code'|lang}</th>
	 			<th style="width:6%">{'label_entry_status'|lang}</th>
	 			<th style="width:4.5%">{'label_entry_kind'|lang}</th>
			</tr>
			{foreach $list_users as $user}
			<tr>
				<td>{$user['created_at']|date_format:"Y/m/d H:i"}</td>
				<td>{$user['id']}</td>
				<td>{$user['email']}</td>
				<td>{$user['last_name_ja']}</td>
				<td>{$user['first_name_ja']}</td>
				<td>{$user['organization']}</td>
				<td>{$user['qualification']}</td>
				<td>{$user['company_code']}</td>
				<td>
					{foreach $status_types as $item}
						{if $user['status'] eq $item.id}
							{$item.label|lang}
						{/if}
					{/foreach}
				</td>
				<td>
					{$user['summary_id']}
				</td>
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
	.alert-error {
		color:red;
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
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/entrylog/export_search?encoding="+$('#type_tsv').val(); 
			$("#btn_export_search").attr('href', href);
		});

		$("#btn_export_all").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/entrylog/export_all?encoding="+$('#type_tsv').val(); 
			$("#btn_export_all").attr('href', href);
		});
	});	
</script>
{/literal}
{/block}