{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/entrylog/'|cat:$case|form_open:'class form-horizontal id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_entry_log_management'|lang} > {'label_entry_result'|lang} </h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><label for="qualification">{'label_entry_qualification'|lang}</label>
					<select name="qualification">
						<option></option>
						{foreach $list_qualification as $item}
							{if $item.id eq $qualification}
								<option value="{$item.id}" selected="selected">{$item.name}</option>
							{else}
								<option value="{$item.id}">{$item.name}</option>
							{/if}
						{/foreach}
					</select>
				</li>
				<li>
					<label for="company_code">{'label_entry_company_code'|lang}</label>
					<input type="text" name="company_code" value="{$company_code}">
				</li>
				<li>
					<label for="year_month">{'label_entry_monthly'|lang}</label>
					<select name="year_month">
						<option></option>
						{foreach $list_yearmonth as $ym}
						<option value="{$ym}"{if $ym == $year_month} selected="selected"{/if}>{$ym}</option>
						{/foreach}
					</select>
				</li>
				<div class="fr">
					<a href="" id="btn_search">
						<img src="{$fixed_base_url}assets-admin/img/research.png">
					</a>
				</div>
			</ul>
		</div>
	</div>
	<!-- Export User -->
	{if isset($total_records) && $total_records > 0}
	<p class="ct">
	<div style="float:left; margin-left: 100px;padding-top: 3px;">{'label_type_export_tsv'|lang}
		<select name="type_tsv" id="type_tsv" style="width:200px">
			{foreach from=$format_tsv item=item}
					<option value="{$item['id']}">{$item['label']|lang}</option>
			{/foreach}
		</select>
	</div>
		<a id="btn_export_detail_all" class="" href="{$fixed_base_url}admin_tools/entrylog/detail_all/export"><img src="{$fixed_base_url}assets-admin/img/result.png" alt="{'label_entry_download_detail'|lang}" ></a>
	</p>
	{/if}
	
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
				<th style="width:15%">{'label_entry_date'|lang}</th>
				<th style="width:20%">{'label_entry_num_user'|lang}</th>
				<th style="width:15%">{'label_entry_num_change'|lang}</th> 
	 			<th style="width:15%">{'label_entry_num_joined'|lang}</th>
	 			<th style="width:15%">{'label_entry_num_leaved'|lang}</th>
	 			<th style="width:10%">{'label_entry_num_temp'|lang}</th> 
			</tr>
			{foreach $list_log as $item}
			<tr>
				<td>{$item['date']}</td>
				<td style="text-align: right">{$item['num_user']}</td>
				<td style="text-align: right">{$item['num_change']}</td>
				<td style="text-align: right">{$item['num_joined']}</td>
				<td style="text-align: right">{$item['num_leaved']}</td>
				<td style="text-align: right">{$item['num_temp']}</td>
			</tr>
			{/foreach}
			
	</table>
	<div class="page clearfix">{$links}</div>
	{/if}
{''|form_close}
{/block}
{block name=stylesheet}
<style>
	.txt90 {
		width:90px;
	}
	.txt120 {
		width:120px;
	}
	table {
		table-layout:fixed;
		font-size: inherit;
	}
	table th {
		height: 20px;
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

			$("#btn_export_detail_all").click(function(){
				var href="{/literal}{$fixed_base_url}{literal}admin_tools/entrylog/detail_all/export?encoding="+$('#type_tsv').val(); 
				$("#btn_export_detail_all").attr('href', href);
			});
		});
	</script>
{/literal}
{/block}