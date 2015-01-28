{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/grouplog'|form_open:'class =form-horizontal id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_log_management'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li>
					<label class="" for="id">{'label_post_group_id'|lang}</label>
					<input type="text" class="number" value="{$id}" name="id" />
				</li>
				<li>
					<label class="" for="id">{'label_group_name'|lang}</label>
					<input type="text" value="{$name}" name="name" />
				</li>
			</ul>
			<ul class="form clearfix">
				<li>
					<label class="" for="first_name_ja">{'label_group_owner_name'|lang}</label>
					<input type="text" value="{$last_name_ja}" name="last_name_ja" />
					<input type="text" value="{$first_name_ja}" name="first_name_ja" />
				</li>	
				<li>
					<label class="" for="label_group_status">{'label_group_public_status'|lang}</label>
					<select id="public_status" name="public_status">
						<option value=""></option>
							{foreach from=$lst_public_status item=itemStatus}
								{if isset($public_status) && $public_status eq $itemStatus.id}
									<option selected="selected" value="{$itemStatus.id}">{$itemStatus.label|lang}</option>
								{else}
									<option value="{$itemStatus.id}">{$itemStatus.label|lang}</option>
								{/if}
							{/foreach}
					</select>
				</li>		
			</ul>
			<ul class="form clearfix">
				<li>
					<label>{'label_created_date'|lang}</label>
					<input type="text" name="date_from" class="date" value="{$date_from}" /> 〜
					<input type="text" name="date_to" class="date" value="{$date_to}"/>
				</li>	
			</ul>
			{form_error('id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('date_from','<ul><li class="alert-error"> ※ ','</li></ul>')}			
			{form_error('date_to','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<p class="alignR">
				<a href="" id="btn_search"/><img src="{$fixed_base_url}assets-admin/img/search.png" alt="{'label_button_search'|lang}"></a>
			</p>	
		</div>
	</div>
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
				<th style="width:7%"> {'label_user_id'|lang}</th>
				<th style="width:20%">{'label_group_name'|lang}</th> 
	 			<th style="width:20%">{'label_grouplog_owner'|lang}</th>
	 			<th style="width:15%">{'label_status'|lang}</th>
	 			<th style="width:8%">{'label_activity_link_detail'|lang}</th>	
			</tr>
			{foreach $list_groups as $group}
			<tr>
				<!-- table group -->
				<td>{$group->id|h}</td>
				<td>{$group->name|h}</td> 
				<td>{$group->last_name_ja|h}{$group->first_name_ja|h}</td>
				<td>
					{$status_group[$group->status].label|lang} 
				</td>
				<td><a href="{$fixed_base_url}admin_tools/grouplog/{$group->id}/detail"><img src="{$fixed_base_url}assets-admin/img/detail.png" alt="{'label_activity_link_detail'|lang}"></a></td>
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
