{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{if isset($error_access_denied)}
	<div class="ctbox">
		<div class="ctinner">
			<ul class="form clearfix">
				<li>{$error_access_denied}</li>
			</ul>
		</div> <!--  ctinner -->
	</div> <!-- ctbox -->
{else}
	{$fixed_base_url|cat:'admin_tools/admin/'|cat:$admin_info_edit['id']|cat:'/update'|form_open:'id=form-main class=form-horizontal'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_admin_management'|lang} > {$admin_info_edit['id']}</h2></div>
		<table id="box_table" class="box_table_h">
		<tbody>
			<!-- ID -->
			<tr>
				<th>{'label_admin_id'|lang}</th>
				<td>{$admin_info_edit['id']}</td>
			</tr>
			<!-- email -->
			<tr>
				<th>{'label_admin_email'|lang}</th>
				<td>
					<p class="word-wrap" name="email" >{$admin_info_edit['email']|h}</p>
				</td>
			</tr>
			<!-- LoginId -->
			<tr>
				<th>{'label_admin_login_id'|lang}</th>
				<td>
					<p class="word-wrap" name="login_id" >{$admin_info_edit['login_id'|h]}</p>
				</td>
			</tr>
			<!-- password -->
			<tr>
				<th><label for="password" class="control-label">{'label_user_password'|lang}</label></th>
				<td style="padding: 0"><input type="password" value ="{(isset($admin_info_edit['password'|h]))?$admin_info_edit['password'|h]:''}" readonly style="border: none;padding: 0px" /></td>
			</tr>
			<!-- full name ja -->
			<tr>
				<th>{'label_admin_fullname'|lang}</th>
				<td>
					<p class="word-wrap" name="last_name_ja" >{$admin_info_edit['last_name_ja'|h]}　{$admin_info_edit['first_name_ja'|h]}</p>
				</td>
			</tr>
			<!-- last name romaji -->
			<tr>
				<th>{'label_admin_fullname_ro'|lang}</th>
				<td>
					<p class="word-wrap" name="last_name" >{$admin_info_edit['last_name']|h} {$admin_info_edit['first_name']|h}</p>
				</td>
			</tr>
			<!-- Gender -->
			<tr>
				<th>{'label_admin_gender'|lang}</th>
				<td>
					{foreach from=$gender_types item=gender_item}
						{if isset($admin_info_edit['gender']) && $gender_item.id eq $admin_info_edit['gender']}
							<label class="radio-inline">{$gender_item.label|lang}</label>
						{/if}
					{/foreach}
				</td>
			</tr>
			<!-- birthday -->
			<tr>
				<th>{'label_admin_birthday'|lang}</th>
				<td>
					<p class="word-wrap" name="birthday" >{$admin_info_edit['birthday']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</p>
				</td>
			</tr>
			<!-- organization -->
			<tr>
				<th>{'label_admin_organization'|lang}</th>
				<td>
					<p class="word-wrap" name="organization" >{$admin_info_edit['organization']|h}</p>
				</td>
			</tr>
			<!-- department -->
			<tr>
				<th>{'label_admin_department'|lang}</th>
				<td>
					<p class="word-wrap" name="department" >{$admin_info_edit['department']|h}</p>
				</td>
			</tr>
			<!-- position -->
			<tr>
				<th>{'label_admin_position'|lang}</th>
				<td>
					<p class="word-wrap" name="position" >{$admin_info_edit['position']|h}</p>
				</td>
			</tr>
			<!-- phone_number -->
			<tr>
				<th>{'label_admin_phone_number'|lang}</th>
				<td>
					<p class="word-wrap" name="phone_number" >{$admin_info_edit['phone_number']|h}</p>
				</td>
			</tr>
			<!-- info -->
			<tr>
				<th>{'label_admin_txtinfo'|lang}</th>
				<td>
					<p class="word-wrap" name="info" >{$admin_info_edit['info']|h|nl2br}</p>
				</td>
			</tr>
			<!-- language -->
			<tr>
				<th>{'label_language'|lang}</th>
				<td>
					<p>{$languages[$admin_info_edit['language']]['label']|lang}</p>
				</td>
			</tr>
			<!-- Status -->
			<tr>
				<th>{'label_admin_status'|lang}</th>
				<td>
					<p>{$status_types[$admin_info_edit['status']]['label']|lang}</p>
				</td>
			</tr>
		</tbody>
		</table>
	</div>
	<div class="ct mtb20">
		<a href="" id="btn-back"><img src="{$fixed_base_url}assets-admin/img/cancel.png" ></a>
		<a href="" id="btn-update"><img src="{$fixed_base_url}assets-admin/img/complete.png" ></a>
		<a href="" class="modalOpen" id="btn-dialog"></a>
	</div>
{''|form_close}
	
	<div class="modalBase">
 		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ctinner_m">
						<p id="dialog_p"></p>
						<p class="ct m20"><a href="{$fixed_base_url}admin_tools/admin"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/if}
{/block}
{block name=stylesheet}
	<style>
		table td {
			word-break:break-word;
		}
	</style>
{/block}
{block name=javascript}
{literal}
<script language="Javascript">
	$(document).ready(function(){
		$("#btn-back").click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
			}
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/admin/{$admin_info_edit['id']}/edit{literal}";
			$("#form-main").attr('action',urlAction);
			$("#form-main").submit();
			return false;
		});

		$('#btn-update').click(function(){
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/admin/{$admin_info_edit['id']}/update{literal}";
			var cct = $("input[name=csrf_token]").val();
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct},
				dataType :'html',
				success : function(data){
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);
					$('#dialog_p').append(obj);
					$('#btn-dialog').click();
				},
				error: function (data, status, e){
					if (data.status == '417') {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/admin{literal}");
						return;
					}
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				}
			});
			return false;
		});
	});
</script>
{/literal}
{/block}