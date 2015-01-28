{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/group'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_list_title'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li>
					<p>
						<label for="id">{'label_group_id'|lang}</label>
						<input type="text" class="number" name="id" id="id" value="{$id|h}" maxlength="255"/>
					</p>
				</li>
				<li>
					<p>
						<label for="name">{'label_group_name'|lang}</label>
						<input type="text" name="name" id="name" value="{$name|h}" maxlength="255"/>
					</p>
				</li>
				<li>
					<p>
						<label for="fullname">{'label_group_owner_name'|lang}</label>
						<input type="text" name="last_name_ja" value="{$last_name_ja|h}"/>
						<input type="text" name="first_name_ja" value="{$first_name_ja|h}"/>
					</p>
				</li>
				<li>
					<p>
						<label for="status">{'label_group_public_status'|lang}</label>
						<select id="status" name="status">
							<option value=""></option>
							{foreach from=$lst_public_status item=itemStatus}
								{if isset($status) && $status eq $itemStatus.id}
									<option selected="selected" value="{$itemStatus.id}">{$itemStatus.label|lang}</option>
								{else}
									<option value="{$itemStatus.id}">{$itemStatus.label|lang}</option>
								{/if}
							{/foreach}
						</select>
					</p>
				</li>
				<li>
					<p>
						<label for="created_date">{'label_created_date'|lang}</label>
						<input type="text" name="date_from" id="date_from" value="{$date_from}" maxlength="10"/>
						〜
						<input type="text" name="date_to" id="date_to" value="{$date_to}" maxlength="10"/>
					</p>
				</li>
			</ul>
			{form_error('id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('date_from','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('date_to','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<p class="alignR"><a id="btn_search" href=""><img src="{$fixed_base_url}assets-admin/img/search.png"></a></p>
		</div> <!-- ctinnet -->
	</div> <!-- ctbox -->
	
	<div class="ctbox">
		<div class="ctinner tround">
			<a href="{$fixed_base_url}admin_tools/group/create"><img src="{$fixed_base_url}assets-admin/img/add.png"></a> </p>
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
					<a id="btn_export_all"><img src="{$fixed_base_url}assets-admin/img/dl_all.png"></a>
					{if isset($total_records) and $total_records > 0 }
						<a id="btn_export_search"><img src="{$fixed_base_url}assets-admin/img/dl_result.png"></a>
					{/if}
				</div>
			{/if}
		</div><!--/ctinner-->
	</div><!--/ctbox-->
	
	<div class="clearfix">
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
		<div class="hit fl">
			{if isset($total_records) && $total_records > 0}
					<p>{$total_records}{'label_item'|lang}</p>
				{else}
					<p>{'label_no_data'|lang}</p>
				{/if}
			
		</div>
	</div>
	{if isset($total_records) and $total_records > 0}
		<table class="box_table" style="table-layout:fixed">
			<tr>
				<th width="8%%">ID</th>
				<th width="47%">{'label_group_name'|lang}</th>
				<th width="20%">{'label_group_owner_id'|lang}</th>
				<th width="15%">{'label_status'|lang}</th>
				<th width="10%">{'label_link_edit'|lang}</th>
			</tr>
			{foreach $list_groups as $group}
				<tr>
				<td class="text-word-break" align="center">{$group->id|h}</td>
				<td class="text-word-break"> <p> {if $group->name|h neq ''}{$group->name|h}{else}&nbsp;{/if} </p> </td>
				<td> <p> {$group->user_id|h} </p> </td>
				<td align="center"> <p> {{$group->status|h}|lang} </p> </td>
				<td align="center">
					<a href="{$fixed_base_url}admin_tools/group/{$group->id|h}/edit"><img src="{$fixed_base_url}assets-admin/img/edit.png" ></a>
				</td>
				</tr>
			{/foreach}
		</table>
		<div class="page clearfix">{$links}</div>
	{/if}
{''|form_close}

<style>
	.alert-error{
		color:red;
	}
</style>
{/block}

{block name=javascript}
{literal}
<script language="Javascript">
	$(function() {
		$("#date_from").datepicker({showButtonPanel: true});
		$("#date_to").datepicker({showButtonPanel: true});
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

		$("#btn_export_all").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/group/export_all?encoding="+$('#type_tsv').val(); 
			$("#btn_export_all").attr('href', href);
		});

		$("#btn_export_search").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/group/export_search?encoding="+$('#type_tsv').val(); 
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
