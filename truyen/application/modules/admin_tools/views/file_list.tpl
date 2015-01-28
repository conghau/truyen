{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/file'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_file_management'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><p>
					<label class="" for="upload_id">{'label_upload_id_srch'|lang}</label>
					<input type="text" class="number" value="{$upload_id}" name="upload_id">	
				</p></li>
				<li><p>
					<label class="" for="user_id">{'label_upload_user_id'|lang}</label>
					<input type="text" class="number" value="{$user_id}" name="user_id">
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="post_id">{'label_upload_post_id'|lang}</label>
					<input type="text" class="number" value="{$post_id}" name="post_id">
				</p></li>
				<li><p>
					<label class="select_width" for="expired_type">{'label_upload_expired_type'|lang}</label>
					<select name="expired_type" style="width: 100px">
						<option></option>
						{foreach from=$expired_types item = item}
							{if $expired_type != '' and $expired_type == $item.id}
								<option value="{$item.id}" selected="true">{$item.label|lang}</option>
							{else}
								<option value="{$item.id}">{$item.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</p></li>
				<li><p>
					<label for="status">{'label_upload_status_srch'|lang}</label>
					<select name="status" style="width: 100px">
						<option value></option>
						{foreach from=$status_types item = item}
							{if $status eq $item.id}
								<option value="{$item.id}" selected="true">{$item.label|lang}</option>
							{else}
								<option value="{$item.id}">{$item.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="created_date">{'label_upload_created_at'|lang}</label>
					<input type="text" class="date" name="created_date_start" value="{$created_date_start}"/> 〜
					<input type="text" class="date" name="created_date_end" value="{$created_date_end}"/>
				</p></li>
			</ul>
			{form_error('created_date_start','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('created_date_end','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<ul class="form clearfix">
				<li><p>
					<label class="" for="expired_date">{'label_upload_expired_at'|lang}</label>
					<input type="text" class="date" name="expired_date_start" value="{$expired_date_start}"/> 〜
					<input type="text" class="date" name="expired_date_end" value="{$expired_date_end}"/>
				</p></li>
			</ul>
			{form_error('expired_date_start','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('expired_date_end','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('upload_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('user_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('post_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<p class="alignR"><a id="btn_search" href=""><img src="{$fixed_base_url}assets-admin/img/search.png"></a></p>
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->
	
	{if isset($has_data) and $has_data eq true }
	<div class="ctbox">
		<div class="ctinner tround">
			<div class="alignR">
				<div style="float:left; margin-left: 70px;padding-top: 3px;">{'label_type_export_tsv'|lang}
					<select name="type_tsv" id="type_tsv" style="width:200px">
						{foreach from=$format_tsv item=item}
							<option value="{$item['id']}">{$item['label']|lang}</option>
						{/foreach}
					</select>
				</div>
				<a id = "btn_export_all"><img src="{$fixed_base_url}assets-admin/img/dl_all.png"></a>
				{if isset($total_records) and $total_records > 0 }
					<a id = "btn_export_search"><img src="{$fixed_base_url}assets-admin/img/dl_result.png"></a>
				{/if}
			</div>
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->
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
	{if isset($total_records) && $total_records > 0}
	<table class="box_table">
		<tr>
			<th style="width:4.5%">{'label_upload_id'|lang}</th>
			<th style="width:8%">{'label_upload_user_id'|lang}</th>
			<th style="width:6%">{'label_upload_post_id'|lang}</th>
			<th style="width:9%">{'label_upload_expired_type'|lang}</th>
			<th style="width:8%">{'label_upload_file_name'|lang}</th>
			<th style="width:8%">{'label_upload_file_type'|lang}</th>
			<th style="width:7%">{'label_upload_file_size'|lang}</th>
			<th style="width:7.95%">{'label_upload_status'|lang}</th>
			<th style="width:8%">{'label_link_edit'|lang}</th>
		</tr>
		{foreach $list_files as $file}
		<tr>
			<td>{$file->id}</td>
			<td>{$file->user_id}</td>
			<td>{$file->post_id}</td>
			<td>
				{foreach $expired_types as $item}
					{if $item.id eq $file->expired_type}
						{$item.label|lang}
					{/if}
				{/foreach}
			</td>
			<td>{$file->original_file_name}</td>
			<td>
				{foreach $file_types as $item}
					{if $item.id eq $file->file_type}
						{$item.label|lang}
					{/if}
				{/foreach}
			</td>
			<td>{$file->file_size|file_size_format:0:'B'}</td>
			<td style="text-align: center">
				{$status_types[$file->status].label|lang}
			</td>
			<td style="text-align: center">
				<a href="{$fixed_base_url}admin_tools/file/{$file->id}/edit"><img src="{$fixed_base_url}assets-admin/img/edit.png" ></a>
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
		color: red;
	}
</style>
{/block}

{block name=javascript}
{literal}
<script type="text/javascript">
	$(document).ready(function(){
		$(".date").datepicker({showButtonPanel: true});

		$('#btn_search').click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			$('#form-main').submit();
			return false;
		});

		$('#txt_per_page').change(function(){
			$('#btn_search').click();
		});

		$("#btn_export_all").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/file/export_all?encoding="+$('#type_tsv').val(); 
			$("#btn_export_all").attr('href', href);
		});

		$("#btn_export_search").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/file/export_search?encoding="+$('#type_tsv').val(); 
			$("#btn_export_search").attr('href', href);
		});

		$('.number').keypress(function (evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 || charCode == 45) {
				return true;
			}
			return false;
		});
	});	
</script>
{/literal}
{/block}