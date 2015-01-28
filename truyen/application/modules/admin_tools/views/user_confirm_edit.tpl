{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/user/'|cat:$data_user_edit['id']|cat:'/update'|form_open:'id=form-main class=form-horizontal'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_user_manager'|lang} > {$data_user_edit['id']}</h2></div>
		<table id="box_table" class="box_table_h">
		<tbody>
			<!-- Id -->
			<tr>
				<th><label for="id" >{'label_user_id'|lang}</label></th>
				<td><p class="word-wrap" >{$data_user_edit['id'|h]}</p></td>
			</tr>
			<!-- email -->
			<tr>
				<th><label for="label_email" >{'label_user_email'|lang}</label></th>
				<td><p class="word-wrap" >{$data_user_edit['email']|h}</p></td>
			</tr>
			<!-- LoginId -->
			<tr>
				<th><label for="login_id" >{'label_user_login_id'|lang}</label></th>
				<td><p class="word-wrap" >{$data_user_edit['login_id'|h]}</p></td>
			</tr>
			<!-- password -->
			<tr>
				<th><label for="password" >{'label_user_password'|lang}</label></th>
				<td style="padding: 0"><input type="password" value ="{(isset($data_user_edit['password'|h]))?$data_user_edit['password'|h]:''}" readonly style="border: none; padding: 0px" /></td>
			</tr>
			<!-- first name ja -->
			<tr>
				<th><label for="first_name_ja" class=" ">{'label_user_name_ja'|lang}</label></th>
				<td>
					<p class="word-wrap" >{$data_user_edit['last_name_ja']|h}　{$data_user_edit['first_name_ja']|h} </p>
				</td>
			</tr>
	
			</tr>
			<!-- first name romaji -->
			<tr>
				<th><label for="" class=" ">{'label_user_name'|lang}</label></th>
				<td>
					<p class="word-wrap" >{$data_user_edit['last_name']|h} {$data_user_edit['first_name']|h} </p>
				</td>
			</tr>
			<!-- sex -->
			<tr>
				<th><label for="gender" class=" ">{'label_user_gender'|lang}</label></th>
				<td>
				{foreach from=$gender_types item=gender_item}
					{if $data_user_edit['gender'] eq $gender_item.id}
						<label >{$gender_item.label|lang}</label>
					{/if}
				{/foreach}
				</td>
			</tr>
			<!-- birthday -->
			<tr>
				<th><label for="birthday" class=" ">{'label_user_birthday'|lang}</label></th>
				<td><label>{$data_user_edit['birthday']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</label></td>
			</tr>

			<tr>
				<th><label  for="qualification">{'label_user_qualifications_job'|lang}</label></th>
				<td>
					<label>{$data_user_edit['qualification']}</label>
				</td>
			</tr>
			
			<!-- organization -->
			<tr>
				<th><label for="organization" >{'label_user_organization'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['organization']}</p></td>
			</tr>
			<!-- department -->
			<tr>
				<th><label for="department" >{'label_user_department'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['department']}</p></td>
			</tr>
			<!-- department -->
			<tr>
				<th><label for="position" >{'label_user_position'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['position']}</p></td>
			</tr>
			<!-- phone -->
			<tr>
				<th><label for="phone" class=" ">{'label_user_phone_number'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['phone_number']}</p></td>
			</tr>
			<!-- domain -->
			<tr>
				<th><label for="domain" >{'label_user_domain'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['domain']}</p></td>
			</tr>		
			<!-- history -->
			<tr>
				<th><label for="history" >{'label_user_history'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['history']}</p></td>
			</tr>
			<!-- university -->
			<tr>
				<th><label for="university" >{'label_user_university'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['university']}</p></td>
			</tr>
			
			<!-- scholar -->
			<tr>
				<th><label for="scholar" >{'label_user_scholar'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['scholar']|h|nl2br}</p></td>
			</tr>
			
			<!-- author -->
			<tr>
				<th><label for="author" >{'label_user_author'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['author']|h|nl2br}</p></td>
			</tr>
			<!-- society -->
			<tr>
				<th><label for="society" >{'label_user_society'|lang}</label></th>
				<td><p class="word-wrap" >{$data_user_edit['society']|h|nl2br}</p></td>
			</tr>
			<!-- hobby -->
			<tr>
				<th><label for="hobby" >{'label_user_hobby'|lang}</label></th>
				<td><p class="word-wrap" >{$data_user_edit['hobby']|h|nl2br}</p></td>
			</tr>
			
			<!-- message -->
			<tr>
				<th><label for="message" >{'label_user_message'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['message']|h|nl2br}</p></td>
			</tr>
			<!-- auth_method -->
			<tr>
				<th><label for="label_auth_method" >{'label_user_auth_method'|lang}</label></th>
				<td>{$list_auth_method[$data_user_edit['auth_method']].label|lang}</td>
			</tr>
			<!-- company_code -->
			<tr>
				<th><label for="company_code" >{'label_user_company_code'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['company_code']}</p></td>
			</tr>
			<!-- recommend_user_id -->
			<tr>
				<th><label for="label_recommend_user_id" >{'label_user_recommend_user_id'|lang}</label></th>
				<td><p class="word-wrap">{$data_user_edit['recommend_user_id']}</p></td>
			</tr>
			<!-- joined_at -->
			<tr>
				<th><label for="label_joined_at" >{'label_user_joined_at'|lang}</label></th>
				<td><label>{$data_user_edit['joined_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H{'label_hours'|lang}i{'label_minutes'|lang}"}</label></td>
			</tr>
			<!-- language -->
			<tr>
				<th>{'label_language'|lang}</th>
				<td>
					<label>{$languages[$data_user_edit['language']]['label']|lang}</label>
				</td>
			</tr>
			<!-- status -->
			<tr>
				<th><label for="label_status">{'label_status'|lang}</label></th>
				<td>
					<label>{$status_types[$data_user_edit['status']].label|lang}</label>
				</td>
			</tr>
		</tbody>
		</table>		
		<div class="ct mtb20">
			<a href="" id="btn-cancel"><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="{'label_button_cancel'|lang}"></a>
			<a href="" id="btn-update"><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="{'label_button_update'|lang}"></a>
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
					<p class="ct m20"><a href="{$fixed_base_url}admin_tools/user"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
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
					return false;
				}
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/user/{$data_user_edit['id']}/edit{literal}";
				$("#form-main").attr('action',urlAction);
				$("#form-main").submit();
				return false;
			});
			
			$("#btn-update").click(function(){
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/user/{$data_user_edit['id']}/update{literal}";
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
							window.location.replace("{/literal}{$fixed_base_url}admin_tools/user{literal}");
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