{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/admin/store'|form_open:'role=form class=form-horizontal id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_admin_management'|lang}</h2></div>
		<table id="box_table" class="box_table_h">
			<tbody>
				<!-- email -->
				<tr>
					<th>{'label_admin_email'|lang}</th>
					<td>
						<p class="word-wrap" name="email" >{$admin_info['email']|h}</p>
					</td>
				</tr>
				<!-- login id -->
				<tr>
					<!-- LoginId -->
					<th>{'label_admin_login_id'|lang}</th>
					<td>
						<p class="word-wrap" name="login_id" >{$admin_info['login_id'|h]}</p>
					</td>
				</tr>
				<!-- password -->
				<tr>
					<th><label for="password" class="control-label">{'label_user_password'|lang}</label></th>
					<td style="padding:0 6px"><input type="password" value ="{$admin_info['password'|h]}" readonly style="border: none;padding: 0px" /></td>
				</tr>
				<!-- full name ja -->
				<tr>
					<th>{'label_admin_fullname'|lang}</th>
					<td>
						<p class="word-wrap" name="last_name_ja" >{$admin_info['last_name_ja'|h]}　{$admin_info['first_name_ja'|h]}</p>
					</td>
				</tr>
				<!-- full name romaji -->
				<tr>
					<th>{'label_admin_fullname_ro'|lang}</th>
					<td>
						<p class="word-wrap" name="last_name" >{$admin_info['last_name']|h} {$admin_info['first_name']|h}</p>
					</td>
				</tr>
				
				<!-- Gender -->
				<tr>
					<th>{'label_admin_gender'|lang}</th>
					<td>
						{foreach from=$gender_types item=gender_item}
							{if isset($admin_info['gender']) && $gender_item.id eq $admin_info['gender']}
								<label class="radio-inline">{$gender_item.label|lang}</label>
							{/if}
						{/foreach}
					</td>
				</tr>
				<!-- birthday -->
				<tr>
					<th>{'label_admin_birthday'|lang}</th>
					<td>
						<p class="word-wrap" name="birthday" >{$admin_info['birthday']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</p>
					</td>
				</tr>
				<!-- organization -->
				<tr>
					<th>{'label_admin_organization'|lang}</th>
					<td>
						<p class="word-wrap" name="organization" >{$admin_info['organization']|h}</p>
					</td>
				</tr>
				<!-- department -->
				<tr>
					<th>{'label_admin_department'|lang}</th>
					<td>
						<p class="word-wrap" name="department" >{$admin_info['department']|h}</p>
					</td>
				</tr>
				<!-- position -->
				<tr>
					<th>{'label_admin_position'|lang}</th>
					<td>
						<p class="word-wrap" name="position" >{$admin_info['position']|h}</p>
					</td>
				</tr>
				<!-- phone_number -->
				<tr>
					<th>{'label_admin_phone_number'|lang} </th>
					<td>
						<p class="word-wrap" name="phone_number" >{$admin_info['phone_number']|h}</p>
					</td>
				</tr>
				<!-- info -->
				<tr>
					<th>{'label_admin_txtinfo'|lang}</th>
					<td>
						<p class="word-wrap" name="info" >{$admin_info['info']|h|nl2br}</p>
					</td>
				</tr>
				<!-- laguage -->
				<tr>
					<th>{'label_language'|lang}</th>
					<td>
						<p>{$languages[$admin_info['language']]['label']|lang}</p>
					</td>
				</tr>
				<!-- Status -->
				<tr>
					<th>{'label_admin_status'|lang}</th>
					<td>
						{foreach from=$status_types item=status_item}
							{if isset($admin_info['status']) && $status_item.id eq $admin_info['status']}
								<p>{$status_item.label|lang}</p>
							{/if}
						{/foreach}
					</td>				
				</tr>
			</tbody>
		</table>
		<div id="dialog-alert"><p style="text-align :center;"></p></div>
		<div class="ct mtb20">
			<a href="" id="btn-cancel"><img src="{$fixed_base_url}assets-admin/img/cancel.png" ></a>
			<a href="" id="btn-create"><img src="{$fixed_base_url}assets-admin/img/complete.png" ></a>
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
					<p id="dialog_p"></p>
					<p class="ct m20"><a href="{$fixed_base_url}admin_tools/admin"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
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
		$("#btn-cancel").click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
			}
			var urlAction = "{/literal}{$fixed_base_url}admin_tools/admin/create{literal}";
			$("#form-main").attr('action',urlAction);
			$("#form-main").submit();
			return false;
		});

		$("#btn-create").click(function(){
			var urlAction = $('#form-main').attr('action');
			var cct = $("input[name=csrf_token]").val();
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct},
				dataType :'html',
				success : function(data){
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);
					$('#dialog_p').append(obj.message);
					$('#btn-dialog').click();
				},
				error: function (data, status, e){
					if (data.status == '417') {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/admin/create{literal}");
						return;
					}
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
			});
			return false;
		});
	});
</script>
{/literal}
{/block}