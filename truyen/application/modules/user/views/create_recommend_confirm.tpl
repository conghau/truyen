{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body1}
<div class="static_all_wrap">
	{if isset($user)}
		<h2 class="ttl">{sprintf(('label_user_error_had_login'|lang),$user->login_id,$user->login_id)}</h2>
	{else}
		<h2 class="ttl">{'label_user_regist_confirm'|lang}</h2>
		<p class="mb10">{'label_user_regist_confirm_introduce_top'|lang}</p>
		<div class="static_wrap">
		{$fixed_base_url|cat:'user/store_recommend'|form_open:'class=form-horizontal id=form-main method=POST'}
			<table class="mem_input">
				<!-- email -->
				<tr>
					<th><label for="label_email" class="control-label">{'label_user_email'|lang}</label></th>
					<td><label class="control-label" name="" >{$user_info['email']|h}</label><input type="hidden" name="email" value="{$user_info['email']|h}"></td>
				</tr>
				<!-- LoginId -->
				<tr>
					<th>
						<label for="login_id" class="control-label">{'label_user_login_id'|lang}</label><br>
						<span>※{sprintf(('label_alpha_numbeic'|lang),('label_user_only'|lang))}</span>
					</th>
					<td><label class="control-label" name="" >{$user_info['login_id'|h]}</label><input type="hidden" name="login_id" value="{$user_info['login_id']|h}"></td>
				</tr>
				<!-- password -->
				<tr>
					<th><label for="password" class="control-label">{'label_user_password'|lang}</label></th>
					<td><input type="password" value ="{$user_info['password'|h]}" readonly style="border: none; padding: 0px" /><input type="hidden" name="password" value="{$user_info['password']|h}"><input type="hidden" name="re_password" value="{$user_info['re_password']|h}"></td>
				</tr>
				<!-- name ja -->
				{if $language eq 'japanese' && $user_info['language'] eq 'japanese'}
					<tr>
						<th><label for="first_name_ja" class=" control-label">{'label_user_name_ja'|lang}</label></th>
						<td>
							<label class="control-label" name="" >{$user_info['last_name_ja']|h}　{$user_info['first_name_ja']|h} <input type="hidden" name="last_name_ja" value="{$user_info['last_name_ja']|h}"><input type="hidden" name="first_name_ja" value="{$user_info['first_name_ja']|h}"></label>
						</td>
					</tr>
					<!-- name romaji -->
					<tr>
						<th>
							<label for="first_name" class=" control-label">{'label_user_name'|lang}</label><br>
							<span>※{sprintf(('label_alpha_numbeic'|lang),('label_user_only'|lang))}</span>
						</th>
						<td>
							<label class="control-label" name="" >{$user_info['last_name']|h} <input type="hidden" name="last_name" value="{$user_info['last_name']|h}"></label>
							<label class="control-label" name="" >{$user_info['first_name']|h} <input type="hidden" name="first_name" value="{$user_info['first_name']|h}"></label>
						</td>
					</tr>
				{else}
					<!-- name romaji -->
					<tr>
						<th>
							<label for="first_name" class=" control-label">{'label_user_name_ja'|lang}</label><br>
							<span>※{sprintf(('label_alpha_numbeic'|lang),('label_user_only'|lang))}</span>
						</th>
						<td>
							<label class="control-label" name="" >{$user_info['last_name']|h} <input type="hidden" name="last_name" value="{$user_info['last_name']|h}"></label>
							<label class="control-label" name="" >{$user_info['first_name']|h} <input type="hidden" name="first_name" value="{$user_info['first_name']|h}"></label>
						</td>
					</tr>
				{/if}
				<!-- sex -->
				<tr>
					<th><label for="gender" class=" control-label">{'label_user_gender'|lang}</label></th>
					<td>
						{$gender_types[$user_info['gender']]['label']|lang}<input type="hidden" name="gender" value="{$user_info['gender']|h}">
					</td>
				</tr>
				<!-- birthday -->
				<tr>
					<th><label for="birthday" class=" control-label">{'label_user_birthday'|lang}</label></th>
					<td><label class="control-label" name="" >{$user_info['birthday']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</label><input type="hidden" name="birthday" value="{$user_info['birthday']|h}"><input type="hidden" name="txt_year" value="{$user_info['txt_year']|h}"><input type="hidden" name="txt_month" value="{$user_info['txt_month']|h}"><input type="hidden" name="txt_day" value="{$user_info['txt_day']|h}"></td>
				</tr>
		
				<tr>
					<th>
						<label class="" for="label_qualification">{'label_user_qualifications_job'|lang}</label><br>
						<span>{'label_user_qualifications_etc'|lang}</span>
					</th>
					<td>
						<label>{$user_info['qualification']}<input type="hidden" name="qualification_id" value="{$user_info['qualification_id']|h}"><input type="hidden" name="qualification" value="{$user_info['qualification']|h}"></label>
					</td>
				</tr>
					
				<!-- organization -->
				<tr>
					<th>
						<label for="organization" class="control-label">{'label_user_organization'|lang}</label><br>
						<span>{'label_user_organization_etc'|lang}</span>
					</th>
					<td><label>{$user_info['organization']}</label><input type="hidden" name="organization" value="{$user_info['organization']|h}"></td>
				</tr>
				<!-- language -->
				<tr>
					<th>{'label_language'|lang}</th>
					<td>
						<label>{$languages[$user_info['language']]['label']|lang}</label><input type="hidden" name="user_language" value="{$user_info['language']|h}">
					</td>
				</tr>
			</table>
		{''|form_close}
		<p class="mt20">{'label_user_regist_confirm_introduce_bottom'|lang}</p>
			<div class="mt20 ct">
				<input type="image" src="{$fixed_base_url}assets/img/{$language}/btn_back.png" id="btn_back" value="{'label_button_member_back'|lang}" style="border:none">
				<input type="image" src="{$fixed_base_url}assets/img/{$language}/touroku.png" id="btn_create" value="{'label_button_member_regist'|lang}" style="border:none">
			</div>
		</div>
	<div class="modalOpen"></div>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ctinner_m">
						<p id="dialog_p"  style="text-align:left"></p>
						<p class="ct m20">
							<a id="btn_yes" class="modalClose" href="{$fixed_base_url}"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
	{/if}
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
			$("#btn_back").on('click', function() {
				$('#form-main').attr('action', '{/literal}{$fixed_base_url|cat:'user/recommend/'|cat:$token}{literal}');
				$('#form-main').submit();
			});
			$('#btn_yes').click(function(){
				window.location.replace('{/literal}{$fixed_base_url}{literal}');
			});

			$("#btn_create").click(function(){
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
						$(".modalOpen").click();
					},
					error: function (data, status, e){
						if (data.status == '417' || data.status == '410') {
							window.location.replace("{/literal}{$fixed_base_url}user/create{literal}");
							return;
						}
						window.location.replace("{/literal}{$fixed_base_url}login{literal}");
						return;
					}
				});
			});
		});
	</script>
	{/literal}
{/block}