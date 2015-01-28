{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
{''|form_open:'id = form_main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_management'|lang} > {$user_info->id}</h2></div>
		<table class="box_table_h">
		<tbody>
			<tr>
				<th> {'label_user_email'|lang} </th>
				<td class="text-word-break"> {$user_info->email} </td>
			</tr>
			<tr>
				<th> {'label_user_login_id'|lang} </th>
				<td class="text-word-break"> {$user_info->login_id} </td>
			</tr>
			<tr>
				<th> {'label_user_name_ja'|lang} </th>
				<td class="text-word-break"> {$user_info->last_name_ja} {$user_info->first_name_ja} </td>
			</tr>
			<tr>
				<th> {'label_user_name'|lang} </th>
				<td class="text-word-break"> {$user_info->last_name} {$user_info->first_name} </td>
			</tr	
			<tr>
				<th> {'label_user_gender'|lang} </th>
				<td> {$gender_types[$user_info->gender].label|lang} </td>
			</tr>
			<tr>
				<th> {'label_user_birthday'|lang} </th>
				<td>
					{$user_info->birthday|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}
				</td>
			</tr>
			<tr>
				<th> {'label_user_qualifications_job'|lang} </th>
				<td class="text-word-break"> {$qualification_name} </td>
			</tr>
			<tr>
				<th> {'label_user_organization'|lang} </th>
				<td class="text-word-break"> {$user_info->organization} </td>
			</tr>
			<tr>
				<th> {'label_user_auth_method'|lang} </th>
				<td> {$auth_method[$user_info->auth_method].label|lang} </td>
			</tr>
			{if $user_info->auth_method == 1 }
			<tr>
				<th></th>
				<td>
					{if isset($user_info->confirm_image_url)}
						<img src="{$fixed_base_url}{$user_info->confirm_image_url}" style="max-height: 100%; max-width: 500px" />
			 		{else}
			 			<img src="{$fixed_base_url}assets-admin/img/no-image.gif" style="max-height: 100%; max-width: 500px" />
					{/if}
				</td>
			</tr>
			{else}
			<tr>
				<th> {'label_user_confirm_organization'|lang} </th>
				<td class="text-word-break"> {$user_info->confirm_organization} </td>
			</tr>
			<tr>
				<th> {'label_user_confirm_phone_number'|lang} </th>
				<td> {$user_info->confirm_phone_number} </td>
			</tr>
			{/if}
		</tbody>
		</table>
		<div class="ct mtb10">
			<input type="image" id="btn-approve" style="border: none;"
				src="{$fixed_base_url}assets-admin/img/chk_ok.png" />
			<input type="image" id="btn-hold" style="border: none;" 
				src="{$fixed_base_url}assets-admin/img/chk_keep.png" />
			<input type="image" id="btn-denial" style="border: none;"
				src="{$fixed_base_url}assets-admin/img/chk_ng.png" />
			{if $user_info->registered_status == 2 or $user_info->registered_status == 3}
			<input type="image" id="btn-delete" style="border: none;"
				src="{$fixed_base_url}assets-admin/img/delete.png" />
			{/if}
			
			<a href="" class="modalOpen" id="btn-dialog"></a>
		</div>
	</div>
{''|form_close}
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p">{'L-A-0022-Q'|lang}</p>
					<p class="ct m20">
							<a id="btn_no" class="modalClose">
								<img src="{$fixed_base_url}assets-admin/img/modal_iie.png" alt="{'label_no'|lang}">
							</a>
							<a id="btn_yes" class="modalClose">
								<img src="{$fixed_base_url}assets-admin/img/modal_hai.png" alt="{'label_yes'|lang}">
							</a>
							<a id="btn_ok" href="{$fixed_base_url}admin_tools/approve/index">
								<img src="{$fixed_base_url}assets-admin/img/ok.png">
							</a>
						</p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
{/block}

{block name=stylesheet}
	<style type="text/css">
		.no-close .ui-dialog-titlebar-close {
			display: none;
		}

		.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
			text-align: center;
			float:none;
		}
		a {
			cursor: pointer;
		}
	</style>
{/block}

{block name=javascript}
	{literal}
		<script language="Javascript">
			$(document).ready(function(){
				$("#btn-approve").click(function(){
					approve(1);
					return false;
				});

				$("#btn-hold").click(function(){
					approve(3);
					return false;
				});

				$("#btn-denial").click(function(){
					approve(2);
					return false;
				});

				$( "#btn-delete" ).click(function() {
					$('#btn-dialog').click();
					$('#btn_ok').hide();
					return false;
				});

				$("#btn_yes").click(function() {
					var cct_cookie = $.cookie('csrf_token');
					if(cct_cookie == undefined) {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
						return;
					}
					$('#btn_ok').show();
					$('#btn_yes').hide();
					$('#btn_no').hide();
					delete_id();
					return false;
				});

				var status = '{/literal}{$user_info->registered_status}{literal}';
				if (status == 3) {
					$('#btn-hold').attr("disabled",'disabled');
					$('#btn-hold').addClass('btn-disabled');
				}

				if (status == 2) {
					$('#btn-denial').attr("disabled",'disabled');
					$('#btn-denial').addClass('btn-disabled');
				}
			});

			function delete_id() {
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/approve/{$user_info->id}/delete{literal}";
				var cct = $("input[name=csrf_token]").val();
				$.ajax({
					type: 'POST',
					url: urlAction,
					data: {csrf_token: cct},
					dataType :'html',
					success : function(data){
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#dialog_p').empty();
						$('#dialog_p').append(obj);
						$('#btn-dialog').click();
					},
					error: function (data, status, e){
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					}
				});
			}

			function approve(status) {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/approve/update{literal}";
				var cct = $("input[name=csrf_token]").val();
				$.ajax({
					type: 'POST',
					url: urlAction,
					data: {csrf_token: cct, flag: status},
					dataType :'html',
					success : function(data){
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#btn_yes').hide();
						$('#btn_no').hide();
						$('#btn_ok').show();
						$('#dialog_p').empty();
						$('#dialog_p').append(obj);
						$('#btn-dialog').click();
					},
					error: function (data, status, e){
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					}
				});
				return false;
			}
		</script>
	{/literal}
{/block}