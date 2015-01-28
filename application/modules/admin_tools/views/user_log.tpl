{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/userlog'|form_open:'id=form-main'}
		<div class="ctbox">
		<div class="ttl"><h2>{'label_user_manager'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><p>
					<label class="" for="id">{'label_user_id'|lang}</label>
					<input type="text" style="width:130px" class="number" value="{$id}" name="id" />
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
		<!-- table list user -->	
		<table class="box_table">
			<tr>
				<th style="width:3.5%">{'label_user_id'|lang}</th>
				<th style="width:7.5%">{'label_user_login_id'|lang}</th>
				<th style="width:10%">{'label_user_email'|lang}</th> 
	 			<th style="width:5%">{'label_user_last_name_ja'|lang}</th>
	 			<th style="width:5%">{'label_user_first_name_ja'|lang}</th> 
	 			<th style="width:9%">{'label_user_organization'|lang}</th>
	 			<th style="width:9%">{'label_user_qualification'|lang}</th>
	 			<th style="width:8%">{'label_status'|lang}</th>
	 			<th style="width:8%">{'label_activity_link_detail'|lang}</th>
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
				<td>
				{if $user->status|h eq $status_types[1]['id'] }
					{$status_types[1]['label']|lang}
				{elseif $user->status|h eq $status_types[2]['id']}
					{$status_types[2]['label']|lang}
				{/if}
				</td>
				<td><a href="{$fixed_base_url}admin_tools/userlog/{$user->id}/detail"><img src="{$fixed_base_url}assets-admin/img/detail.png" alt="{'label_activity_link_detail'|lang}"></a></td>
			</tr>
			{/foreach}
		</table>
		<div class="page clearfix">{$links}</div>
	{/if}
	<input type="hidden" name="action_type" id="action_type" value="">
{''|form_close}
{/block}

{block name=stylesheet}
<style>
	.txt90 {
		width:90px;
	}
	.alert-error{
		color:red;
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
