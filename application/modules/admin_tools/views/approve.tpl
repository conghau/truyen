{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
{''|form_open:'id = form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_approve_management'|lang}</h2></div>
		<h3>
			<ul class="tabs clearfix">
				<li>
					<a id="btn-untreated" style="width:140px" href="">{'label_approve_untreated'|lang} ({$total_records[0]} {'label_item'|lang})</a>
				</li>
				<li><a id="btn-hold" style="width:140px" href="">{'label_approve_hold'|lang} ({$total_records[3]} {'label_item'|lang})</a></li>
				<li><a id="btn-denial" style="width:140px" href="">{'label_approve_denial'|lang} ({$total_records[2]} {'label_item'|lang})</a></li>
			</ul>
		</h3>
	</div> <!-- /ctbox -->
	
	<input type="hidden" id="flag" name="flag">
	{if $total_pages > 0}
		<table class="box_table" style="table-layout:fixed">
			<col width="6%">
			<col width="12%">
			<col width="15%">
			<col width="8%">
			<col width="8%">
			<col width="12%">
			<col width="16%">
			<col width="11%">
			<col width="10%">
			<thead>
				<tr>
					<th> {'label_user_id'|lang} </th>
					<th> {'label_user_login_id'|lang} </th>
					<th> {'label_user_email'|lang} </th>
					<th> {'label_user_last_name_ja'|lang} </th>
					<th> {'label_user_first_name_ja'|lang} </th>
					<th> {'label_approve_facility_name'|lang} </th>
					<th> {'label_user_auth_method'|lang} </th>
					<th> {'label_status'|lang} </th>
					<th> {'label_link_edit'|lang}</th>
				</tr>
			</thead>
			<tbody>
				{foreach $list_users as $user}
					<tr>
						<td class="text-word-break"> {$user->id|h}</td>
						<td class="text-word-break"> {$user->login_id|h}</td>
						<td class="text-word-break"> {$user->email|h}</td>
						<td class="text-word-break"> {$user->last_name_ja|h}</td>
						<td class="text-word-break"> {$user->first_name_ja|h}</td>
						<td class="text-word-break"> {$user->organization|h}</td>
						<td class="text-word-break"> {$auth_method[$user->auth_method].label|lang|h}</td>
						<td class="text-word-break"> {$status_types[$user->status].label|lang}</td>
						<td align="center">
							<a class="edit" href="" data-rel="{$fixed_base_url}admin_tools/approve/{$user->id}/edit">
								<img src="{$fixed_base_url}assets-admin/img/edit.png" >
							</a>
						</td>
					</tr>
				{/foreach}
		</tbody>
	</table>
	<div class="page clearfix">{$links}</div>
	{/if}
{''|form_close}
{/block}

{block name=javascript}
{literal}
	<script language="Javascript">
		$(document).ready(function(){
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/approve{literal}";
			$("#btn-untreated").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$("#form-main").attr('action',urlAction);
				$("#flag").val(0);
				$("#form-main").submit();
				return false;
			});

			$("#btn-hold").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$("#form-main").attr('action',urlAction);
				$("#flag").val(3);
				$("#form-main").submit();
				return false;
			});

			$("#btn-denial").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$("#form-main").attr('action',urlAction);
				$("#flag").val(2);
				$("#form-main").submit();
				return false;
			});

			$(".edit").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				var urlAction = $(this).attr('data-rel');
				$("#form-main").attr('action',urlAction);
				$("#form-main").submit();
				return false;
			});

			if ("{/literal}{$flag}{literal}" == 0) {
				$("#btn-untreated").parent().addClass('select');
				$("#btn-untreated").parent().css('width','140px')
				$("#btn-untreated").parent().html("{/literal}{'label_approve_untreated'|lang} ({$total_records[0]} {'label_item'|lang}){literal}")
				$("#btn-untreated").remove();
			}

			if ("{/literal}{$flag}{literal}" == 2) {
				$("#btn-denial").parent().addClass('select');
				$("#btn-denial").parent().css('width','140px')
				$("#btn-denial").parent().html("{/literal}{'label_approve_denial'|lang} ({$total_records[2]} {'label_item'|lang}){literal}")
				$("#btn-denial").remove();
			}

			if ("{/literal}{$flag}{literal}" == 3) {
				$("#btn-hold").parent().addClass('select');
				$("#btn-hold").parent().css('width','140px')
				$("#btn-hold").parent().html("{/literal}{'label_approve_hold'|lang} ({$total_records[3]} {'label_item'|lang}){literal}")
				$("#btn-hold").remove();
			}
		});
	</script>
{/literal}
{/block}