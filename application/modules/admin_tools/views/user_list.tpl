{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/user'|form_open:'id=form-main enctype=multipart/form-data'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_user_manager'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><p>
					<label class="" for="id">{'label_user_id'|lang}</label>
					<input type="text" class="number" style="width:130px" value="{$id}" name="id" />
				</p></li>
				<li><p>
					<label class="" for="id">{'label_user_email'|lang}</label>
					<input type="text" style="width:250px" value="{$email}" name="email" />
				<p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="id">{'label_user_login_id'|lang}</label>
					<input type="text" style="width:100px" value="{$login_id}" name="login_id" />
				</p></li>
	
				<li><p>
					<label class="" for="first_name_ja">{'label_user_users_name'|lang}</label>
					<input type="text" style="width:80px" value="{$last_name_ja}" name="last_name_ja" />
					<input type="text" style="width:80px" value="{$first_name_ja}" name="first_name_ja" />
				</p></li>
				<li><p>
					<label class="label_gender" for="gender">{'label_user_gender'|lang}</label>
					<select name="gender" style="width:80px">
					<option value=""></option>
					{foreach from=$gender_types item=item}
						{if $gender eq $item.id}
							<option value="{$item.id}" selected = "true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="label_qualification">{'label_user_qualification'|lang}</label>
					<select name ="qualification_id" style="width:160px">
					<option value=""></option>
					{foreach from=$list_qualification item = item}
						{if $qualification_id eq $item['id']}
							<option value="{$item['id']}" selected="true">{$item['name']}</option>
						{else}
							<option value="{$item['id']}" >{$item['name']}</option>
						{/if}
					{/foreach}
					</select>
				</p></li>

				<li><p>
					<label class="" for="label_organization">{'label_user_organization'|lang}</label>
					<input type="text" value="{$organization}" name="organization" style="width:100px"/>
				</p></li>
				<li class="phone"> <p>
					<label class="" for="label_phone">{'label_user_phone_number'|lang}</label>
					<input type="text" value="{$phone_number}" name="phone_number" style="width:80px"/>
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="label_registered_type">{'label_user_registered_type'|lang}</label>
					<select name="registered_type">
						<option value=""></option>
						{foreach from=$list_registered_type item = item}
							{if $registered_type eq $item.id}
								<option value="{$item.id}" selected="true">{$item.label|lang}</option>
							{else}
								<option value="{$item.id}" >{$item.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</p></li>
				<li>
					<label class="" for="label_status">{'label_status'|lang}</label>
					<select name="status" style="width:80px">
					<option value=""></option>
					{foreach from=$status_types item=item}
						{if $status eq $item.id}
							<option value="{$item.id}" selected="true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
				</li>
			</ul>
			<ul class="form clearfix">
				<li>
					<label>{'label_user_regist_date'|lang}</label>
					<input type="text" name="start_date" class="date start-date" value="{$start_date}" /> 〜 
					<input type="text" name="end_date" class="date end-date" value="{$end_date}"/>
				</li>
			</ul>
			{form_error('id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('start_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('end_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<p class="alignR"><a id="btn_search" href=""><img src="{$fixed_base_url}assets-admin/img/search.png"></a></p>
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->
	
	<div class="ctbox">
		<div class="ctinner tround">
			<a href="{$fixed_base_url}admin_tools/user/create"><img src="{$fixed_base_url}assets-admin/img/add.png"></a>
			<div class="mtb10"><img src="{$fixed_base_url}assets-admin/img/hr.png" width="100%"></div>
			
			{'label_upload_format_guidance'|lang}
			(<a href="#" id="format_toggle">{'label_format_example'|lang}</a>)
			<br>
			<div id="format" style="display: none;margin-top: 5px">
				<table class="box_table" style="width:0">
					<tr>
						<th id="col1">{'label_format_column_number'|lang}</th>
						<th id="col2">{'label_format_column_name'|lang}</th>
						<th id="col3">{'label_format_description'|lang}</th>
					</tr>
				</table>
				<div id ="table-scroll">
					<table class="box_table">
						<col width="20%">
						<col width="40%">
						<col width="40%">
						{foreach $tsv_format as $key => $rset}
							<tr>
								<td class="col1">{($key + 1)}</td>
								<td class="col2">{'column'|array_selector:$rset|h}</td>
								<td class="col3">{'description'|array_selector:$rset|h}</td>
							</tr>
						{/foreach}
					</table>
				</div>
			</div>
			<br>
			<div style="float:left">
				<input type="file" id="upload" name= "upload" style="width:200px" accept=".tsv" />
			</div>
			<div>
				<input type="image" id="btn_upload" class="btn-disabled"  disabled
					style="border: none; padding-top:2px" src="{$fixed_base_url}assets-admin/img/upload.png" />
			</div>
			{if isset($is_has_data) and $is_has_data eq true }
				<div class="mtb10"><img src="{$fixed_base_url}assets-admin/img/hr.png" width="100%"></div>
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
			{/if}
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->
	
	{if isset($case)}
		<div class="upload_chk">
			{if $case == "upload_confirm"}
				{if isset($msg_error) && $msg_error != ""}
					<h4>{$msg_error}</h4>
				{else}
					<h4>{'label_user_upload_results'|lang} </h4>
					<p> {$msg_result}</p>
					{if isset($error_details)}
						<p style="white-space:pre;">{$error_details}</p>
					{elseif $flag_error == true}
						<a href="{$fixed_base_url}admin_tools/user/download_file_error">{'label_button_download'|lang}</a>
					{elseif $flag_error == false}
						{'label_user_upload'|lang}
						<div class="chk_data">
							{'label_user_upload_new'|lang} <span>{$type_row['new']} {'label_item'|lang}</span> <br>
							{'label_user_upload_update'|lang} <span>{$type_row['update']} {'label_item'|lang}</span><br>
							{'label_user_upload_delete'|lang} <span>{$type_row['delete']} {'label_item'|lang}</span>
						</div>
					{/if}
				{/if}	
			{elseif $case == "upload_done"}
				{if isset($message) && $message != ""}
					<h4>{$message}</h4>
				{/if}
			{/if}
		</div>
		{if $case == "upload_confirm" && $flag_error == false}
			<div class="ct mtb10">
				<input type="image" id="btn_confirm" style="border: none; padding-top:2px"
					src="{$fixed_base_url}assets-admin/img/btn_upload_exec.png" />
			</div>
		{/if}
	{else}
		<div class="clearfix">
			<div class="hit fl" style="margin-top: 10px;">
				{if isset($total_records) && $total_records > 0}
					<p>{sprintf(('label_user_number_people'|lang),$total_records)}</p>
				{else}
					<p>{'label_no_data'|lang}</p>
				{/if}
			</div>
			<div class="fr">
				{if isset($total_records) && $total_records > 0}
					<td style="text-align:right">{'label_number_record_per_page'|lang}
						<select name="per_page" id="txt_per_page" style="width:74px">
							{foreach $pagination_per_page_option as $row}
								{if $per_page eq $row}
									<option value="{$row}" selected="true">{$row}{'label_item'|lang}</option>
								{else}
									<option value="{$row}">{$row}{'label_item'|lang}</option>
								{/if}
							{/foreach}
						</select>
					</td>
				{/if}
			</div>
		</div>
		{if isset($total_records) and $total_records > 0}
			<table class="box_table">
				<tr>
					<th style="width: 3.8%">{'label_user_id'|lang}</th>
					<th style="width: 8.29%">{'label_user_login_id'|lang}</th>
					<th style="width: 14.73%">{'label_user_email'|lang}</th> 
				 	<th style="width: 6%">{'label_user_last_name_ja'|lang}</th>
				 	<th style="width: 6%">{'label_user_first_name_ja'|lang}</th> 
					<th style="width: 10.35%">{'label_user_organization'|lang}</th>
			 		<th style="width: 9.9%">{'label_user_qualification'|lang}</th>
			 		<th style="width: 7.95%">{'label_status'|lang}</th>
			 		<th style="width: 8%">{'label_link_edit'|lang}</th>
				</tr>
				{foreach $list_users as $user}
				<tr>
					<td>{$user->id|h}</td>
					<td>{$user->login_id|h}</td>
					<td>{$user->email|h}</td>
					<td>{$user->last_name_ja|h}</td>
					<td>{$user->first_name_ja|h}</td>
					<td>{$user->organization|h}</td>
					<td>{$user->qualification|h}</td>
					<td class="ct">
						{$status_types[$user->status]['label']|lang}
					</td>
					<td align="center"><a href="{$fixed_base_url}admin_tools/user/{$user->id}/edit"><img src="{$fixed_base_url}assets-admin/img/edit.png" ></a></td>
				</tr>
				{/foreach}
			</table>
			<div class="page clearfix">{$links}</div>
		{/if}
	{/if}
{''|form_close}
{/block}

{block name=stylesheet}
<style>
	.alert-error{
		color:red;
	}
	#table-scroll td{
		height:16px;
	}
	#table-scroll {
		max-width:740px;
		max-height:300px;
		overflow:auto;
		font-size:14px;
		background-color: #F4F4F4;
	}
	label {
		width:50px;
	}
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
	a {
		cursor:pointer;
	}
</style>
{/block}

{block name=javascript}
{literal}
<script language="javascript">
	$(document).ready(function(){
		$(".date").datepicker({showButtonPanel: true});
		
		$('#txt_per_page').change(function(){
			$('#btn_search').click();
		});
		
		$("#btn_upload").click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/user/import_confirm{literal}";
			$("#form-main").attr('action',urlAction);
			$("#form-main").submit();
		});
		
		$("#btn_confirm").click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/user/import_done{literal}";
			$("#form-main").attr('action',urlAction);
			$("#form-main").submit();
		});

		$('#btn_search').click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
			}
			$("#form-main").submit();
			return false;
		});

		$("#format_toggle").click(function () {
			$("div#format").toggle();
			fix_table();
		});

		$("#upload").change(function(){
			if($(this).val() == ''){
				$('#btn_upload').attr("disabled",'disabled');
				$('#btn_upload').addClass('btn-disabled');
			} else {
				$('#btn_upload').removeAttr('disabled');
				$('#btn_upload').removeClass('btn-disabled');
			}
		});

		$("#btn_export_all").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/user/export_all?encoding="+$('#type_tsv').val(); 
			$("#btn_export_all").attr('href', href);
		});

		$("#btn_export_search").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/user/export_search?encoding="+$('#type_tsv').val(); 
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
	function fix_table(){
		$('#col1').css('width', $('.col1').css('width'));
		$('#col2').css('width', $('.col2').css('width'));
		$('#col3').css('width', $('.col3').css('width'));
	}
</script>
{/literal}
{/block}
