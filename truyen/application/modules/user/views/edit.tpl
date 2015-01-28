{literal}
<script type="text/javascript">
var process_status = '{/literal}{(isset($validation_status)) ? $validation_status : ''}{literal}';
var action_confirm = '{/literal}{(isset($action_confirm)) ? $action_confirm : ''}{literal}';
$(function(){
	if(process_status == 1){
		$(".modalOpen").click();
	};
	if(action_confirm == 1 && process_status == 0){
		ENABLE_EDIT = true;
		$('.btn_save_edit').removeAttr('disabled');
		$('.btn_save_edit').removeClass('btn-disabled');
	};
});

</script>
{/literal}
<div>
	{$fixed_base_url|cat:'user/confirm_edit'|form_open:'class=form-horizontal id=form_main'}
		<table class="mem_input">
			<!-- email -->
			<tr>
				<th><label name ="label_email" class=" control-label">{'label_user_email'|lang}</label></th>
				<td>
					{form_error('email','<p class="alert-error"> ※ ','</p>')}
					{if $is_mail_wait_active eq TRUE}
						<input type="email" class="txt-disabled" name="email" size="30" value="{set_value(email,$user_info_edit['email'])}" readonly >
					{else}
						<input type="email" class="check" name="email" id="email" size="30" value="{set_value(email,$user_info_edit['email'])}" maxlength="128">
						<input type="hidden"  name="h-email" id="h-email" value="{set_value(email,$user_info_edit['email'])}">
					{/if}
					<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
				</td>
			</tr>
			<!-- login id -->
			<tr>
				<th><label for="label_login_id" class="control-label">{'label_user_login_id'|lang} </label></th>
				<td>
					{form_error('login_id','<p class="alert-error"> ※ ','</p>')}
					<input type="text" class="check" name="login_id" id="login_id" size="30" value="{set_value(login_id,$user_info_edit['login_id'])}" maxlength="64">
					<input type="hidden"  name="" id="h-login_id" value="{set_value(login_id,$user_info_edit['login_id'])}" >
					<p class="upper_first_letter">※{'label_user_login_id_guidance'|lang}</p>
				</td>
			</tr>
			<!--password -->
			<tr>
				<th>
					<label>{'label_user_password'|lang}</label>
				</th>
				<td>
					{form_error('password','<p class="alert-error"> ※ ','</p>')}
					<input type="password" name="hpassword" style="display: none">
					<input type="password" class="check" name="password" id="password" size="30" value="{(isset($password))?$password:''}" maxlength="255" />
					<input type="hidden" id = "h-password" value="{(isset($password))?$password:''}" />
					<p class="upper_first_letter">※{'label_user_password_guidance'|lang}</p>
					<p class="upper_first_letter alert-error">※{sprintf(('label_only_if_change_password'|lang),'')}</p>
				</td>
			</tr>
			<!-- re-password -->
			<tr>
				<th>
					<label>{'label_user_re_password'|lang}</label>
				</th>
				<td>
					{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
					<input type="password" class="check" name="re_password" id="re_password" size="30" value="{(isset($re_password))?$re_password:''}" maxlength="255"/>
					<input type="hidden" id="h-re_password" value="{(isset($re_password))?$re_password:''}" />
					<p class="upper_first_letter">※{'label_user_password_guidance'|lang}</p>
					<p class="upper_first_letter alert-error">※{sprintf(('label_only_if_change_password'|lang),'')}</p>
				</td>
			</tr>
			<!-- name ja -->
			{if $language eq 'japanese'}
				<!-- name ja -->
				<tr>
					<th>
						<label for="label_name_ja" class=" control-label">{'label_user_name_ja'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
					<td>
						{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
						<input type="text" class="check txt_width90" name="last_name_ja" id="last_name_ja" value="{set_value(last_name_ja,$user_info_edit['last_name_ja'])}" maxlength="64"/>
						<input type="hidden"  id="h-last_name_ja" value="{set_value(last_name_ja,$user_info_edit['last_name_ja'])}" />
						<input type="text" class="check txt_width90" name="first_name_ja" id="first_name_ja" value="{set_value(first_name_ja,$user_info_edit['first_name_ja'])}" maxlength="64"/>
						<input type="hidden" id="h-first_name_ja" value="{set_value(first_name_ja,$user_info_edit['first_name_ja'])}" />
						<p class="upper_first_letter">※{'label_kana'|lang}</p>
					</td>
				</tr>
				<!-- name romaji -->
				<tr>
					<th>
						<label for="" class=" control-label">{'label_user_name'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
					<td>
						{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
						<input type="text" class="check txt_width90" name="last_name" id="last_name" value="{set_value(last_name,$user_info_edit['last_name'])}" maxlength="64"/>
						<input type="hidden"  id="h-last_name" value="{set_value(last_name,$user_info_edit['last_name'])}" />
						<input type="text" class="check txt_width90" name="first_name" id="first_name" value="{set_value(first_name,$user_info_edit['first_name'])}"  maxlength="64"/>
						<input type="hidden"  id="h-first_name" value="{set_value(first_name,$user_info_edit['first_name'])}" />
						<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
					</td>
					
				</tr>
			{else}
				<!-- name romaji -->
				<tr>
					<th>
						<label for="" class=" control-label">{'label_user_name_ja'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
					<td>
						{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
						<input type="text" class="check txt_width90" name="last_name" id="last_name" value="{set_value(last_name,$user_info_edit['last_name'])}" maxlength="64"/>
						<input type="hidden"  id="h-last_name" value="{set_value(last_name,$user_info_edit['last_name'])}" />
						<input type="text" class="check txt_width90" name="first_name" id="first_name" value="{set_value(first_name,$user_info_edit['first_name'])}"  maxlength="64"/>
						<input type="hidden"  id="h-first_name" value="{set_value(first_name,$user_info_edit['first_name'])}" />
						<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
					</td>
					
				</tr>
			{/if}
			
			<!-- sex -->
			<tr>
				<th>
					<label class="label_gender" for="gender">{'label_user_gender'|lang}</label><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
				<td>
					{form_error('gender','<p class="alert-error"> ※ ','</p>')}
					<select name="gender" id= "gender" class="check txt_width90">
					{foreach from=$gender_types item=item}
						{if $user_info_edit['gender'] eq $item.id}
							<option value="{$item.id}" selected = "true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
				<input type="hidden"  id="h-gender" value="{$user_info_edit['gender']}" />
				</td>
				
			</tr>
			<!-- birthday -->
			<tr>
				<th>
					<label for="label_birthday" class=" control-label">{'label_user_birthday'|lang}</label><br>
					<span>※{'label_notpublished_in_profile'|lang}</span>
				</th>
				<td>{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
					
					{$year = date(Y)}
					{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
					{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
					<select name="txt_year" id="txt_year" class="check txt_width90">
						{while $year >= $min_year_cbx}
							<option value="{$year}">{$year}</option>
							{$year = $year -1}
						{/while}
					</select>

					{$count =1}
					<select name="txt_month" id="txt_month" class="check txt_width90">
						{while $count <=  12 }
							<option value="{$count}">{$count}</option>
							{$count = $count + 1}
						{/while}
					</select>
					<select name="txt_day" id="txt_day" class="check txt_width90">
					</select>
					<p class="upper_first_letter alert-error">※{'label_user_birthday_guidance'|lang}</p>
					<input type="hidden" id="h-txt_year" value="" >
					<input type="hidden" id="h-txt_month" value="" >
					<input type="hidden" id="h-txt_day" value="1" >
					<input type="hidden" id="h-day" value="1" >
					<input type="hidden" name="birthday" id="txt_birthday" value="{$user_info_edit['birthday']}">
				</td>
		 	</tr>
		
			<tr>
				<th>
					<label  for="label_qualification">{'label_user_qualifications_job'|lang}</label><br>
					<span>({'label_user_qualifications_etc'|lang})</span><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
				<td>
					<select name ="qualification_id" id="qualification" class="check" >
					{foreach from=$list_qualification item = item}
						{if $user_info_edit['qualification_id'] eq $item['id']}
							<option value="{$item['id']}" selected="true">{$item['name']}</option>
						{else}
							<option value="{$item['id']}" >{$item['name']}</option>
						{/if}
					{/foreach}
					</select>
					<input type="hidden" id="h-qualification" value="{$user_info_edit['qualification_id']}">
					<input type="hidden" name="qualification" id="txt_qualification" value="">
				</td>
			</tr>
			
			<!-- organization -->
			<tr>
				<th>
					<label for="organization" class="control-label">{'label_user_organization'|lang}</label><br>
					<span>({'label_user_organization_etc'|lang})</span><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
				<td>
					{form_error('organization','<p class="alert-error"> ※ ','</p>')}
					<input type="text" class="check" name="organization" id="organization" size="30" value="{$user_info_edit['organization']}" maxlength="255"/>
					<input type="hidden"  id="h-organization" value="{$user_info_edit['organization']}" />
					
				</td>
				
			</tr>
			<!-- department -->
			<tr>
				<th>
					<label for="department" class="control-label">{'label_user_department'|lang}</label><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
				<td>
					<input type="text" class="check" name="department" id="department" size="30" value="{$user_info_edit['department']}" maxlength="255"/>
					<input type="hidden"  id="h-department" value="{$user_info_edit['department']}" />
				</td>
			</tr>
			<!-- position -->
			<tr>
				<th>
					<label for="position" class="control-label">{'label_user_position'|lang}</label><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
				<td>
					<input type="text" class="check" name="position" id="position" size="30" value="{$user_info_edit['position']}" maxlength="128"/>
					<input type="hidden"  id="h-position" value="{$user_info_edit['position']}" />
				</td>
			</tr>
			<!-- phone -->
			<tr>
				<th>
					<label for="phone" class=" control-label">{'label_user_phone_number'|lang}</label><br>
					<span>※{'label_notpublished_in_profile'|lang}</span>
				</th>
				<td>
					{form_error('phone_number','<p class="alert-error"> ※ ','</p>')}
					<input type="tel" class="check  number" name="phone1" id="txt_phone1" size="4" value="" maxlength="3"> -
					<input type="tel" min="0" class="check  number" name="phone2" id="txt_phone2" size="4" value="" maxlength="4"> -
					<input type="tel" min="0" class="check  number" name="phone3" id="txt_phone3" size="4" value="" maxlength="4">
					<input type="hidden" id="h-txt_phone1" value="" />
					<input type="hidden" id="h-txt_phone2" value="" />
					<input type="hidden" id="h-txt_phone3" value="" />
					<input type="hidden" name="phone_number" value="{$user_info_edit['phone_number']|h}" id="txt_phone" />
				</td>
			</tr>
			<!-- domain -->
			<tr>
				<th><label for="domain" class="control-label">{'label_user_domain'|lang}</label></th>
				<td>
				<div class="clearfix">
					<input type="text" class="check " name="domain" id="domain" size="30" value="{$user_info_edit['domain']|h}" maxlength="255"/>
					<input type="hidden" id="h-domain" value="{$user_info_edit['domain']|h}" />
					{if $user_info_edit['domain_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="domain_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="domain_flag" /></a>
					{/if}
				</div>
					<input type="hidden" class="domain_flag" name="domain_flag" value="{$user_info_edit['domain_flag']|h}" />
					<input type="hidden" id="h-domain_flag" value="{$user_info_edit['domain_flag']|h}" />
				</td>
			</tr>
			<!-- specialist -->
			<tr>
				<th><label for="specialist" class="control-label">{'label_user_specialist'|lang}</label></th>
				<td>
					<input type="text" class="check" name="specialist" id="specialist" size="30" value="{$user_info_edit['specialist']|h}" maxlength="255"/>
					<input type="hidden"  id="h-specialist" value="{$user_info_edit['specialist']|h}" />
					{if $user_info_edit['specialist_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="specialist_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="specialist_flag" /></a>
					{/if}
					<input type="hidden" class="specialist_flag" name = "specialist_flag" value="{$user_info_edit['specialist_flag']|h}" >
					<input type="hidden" id="h-specialist_flag" value="{$user_info_edit['specialist_flag']|h}" />
					
				</td>
			</tr>
			<!-- history -->
			<tr>
				<th><label for="history" class="control-label">{'label_user_history'|lang}</label></th>
				<td>
					<textarea name="history" id ="history" class="check">{$user_info_edit['history']|h}</textarea>
					<textarea id="h-history" style="display:none;">{$user_info_edit['history']|h}</textarea>
					{if $user_info_edit['history_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="history_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="history_flag" /></a>
					{/if}
					<input type="hidden" class="history_flag" name="history_flag" value="{$user_info_edit['history_flag']|h}" >
					<input type="hidden" id="h-history_flag" value="{$user_info_edit['history_flag']|h}" >
				</td>
			</tr>
			<!-- university -->
			<tr>
				<th><label for="university" class="control-label">{'label_user_university'|lang}</label></th>
				<td>
					<input type="text" class="check" name="university" id="university" size="30" value="{$user_info_edit['university']|h}" maxlength="255"/>
					<input type="hidden" id="h-university" value="{$user_info_edit['university']|h}" />
					{if $user_info_edit['university_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="university_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="university_flag" /></a>
					{/if}
					<input type="hidden" class="university_flag" name="university_flag" value="{$user_info_edit['university_flag']|h}" >
					<input type="hidden" id="h-university_flag" value="{$user_info_edit['university_flag']|h}" />
				</td>
			</tr>
			
			<!-- scholar -->
			<tr>
				<th><label for="scholar" class="control-label">{'label_user_scholar'|lang}</label></th>
				<td>
					<textarea name="scholar" id="scholar" class="check">{$user_info_edit['scholar']|h}</textarea>
					<textarea id="h-scholar" style="display:none;">{$user_info_edit['scholar']|h}</textarea>
					{if $user_info_edit['scholar_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="scholar_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="scholar_flag" /></a>
					{/if}
					<input type="hidden" class="scholar_flag" name="scholar_flag"  value="{$user_info_edit['scholar_flag']|h}" >
					<input type="hidden" id="h-scholar_flag" value="{$user_info_edit['scholar_flag']|h}" />
				</td>
			</tr>
			
			<!-- author -->
			<tr>
				<th><label for="author" class="control-label">{'label_user_author'|lang}</label></th>
				<td>
					<textarea name="author" id="author" class="check">{$user_info_edit['author']|h}</textarea>
					<textarea id="h-author" style="display:none;">{$user_info_edit['author']|h}</textarea>
					{if $user_info_edit['author_flag'] eq $list_profile_status['1']['id']}
							<a class="right"><input type="checkbox" class="toggle" id="author_flag" checked/></a>
					{else}
							<a class="right"><input type="checkbox" class="toggle" id="author_flag" /></a>
					{/if}
					<input type="hidden" class="author_flag" name="author_flag"  value="{$user_info_edit['author_flag']|h}" >
					<input type="hidden" id="h-author_flag" value="{$user_info_edit['author_flag']|h}" />
				</td>
			<tr>
			<!-- society -->
			<tr>
				<th><label for="society" class="control-label">{'label_user_society'|lang}</label></th>
				<td>
					<textarea name="society" name="society" id="society" class="check">{$user_info_edit['society']|h}</textarea>
					<textarea id="h-society" style="display:none;">{$user_info_edit['society']|h}</textarea>
					{if $user_info_edit['society_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="society_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="society_flag" /></a>
					{/if}
					<input type="hidden" class="society_flag" name="society_flag" value="{$user_info_edit['society_flag']|h}" >
					<input type="hidden" id="h-society_flag" value="{$user_info_edit['society_flag']|h}" />
				</td>
			</tr>
			<!-- hobby -->
			<tr>
				<th><label for="hobby" class="control-label">{'label_user_hobby'|lang}</label></th>
				<td>
					<textarea name="hobby" id="hobby" class="check">{$user_info_edit['hobby']|h}</textarea>
					<textarea id="h-hobby" style="display:none;">{$user_info_edit['hobby']|h}</textarea>
					{if $user_info_edit['hobby_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="hobby_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="hobby_flag" /></a>
					{/if}
					<input type="hidden" class="hobby_flag" name="hobby_flag" value="{$user_info_edit['hobby_flag']|h}" >
					<input type="hidden" id="h-hobby_flag" value="{$user_info_edit['hobby_flag']|h}" />
				</td>
			</tr>
			<!-- message -->
			<tr>
				<th><label for="message" class="control-label">{'label_user_message'|lang}</label></th>
				<td>
					<textarea name="message" id="message" class="check">{$user_info_edit['message']|h}</textarea>
					<textarea id="h-message" style="display:none;">{$user_info_edit['message']|h}</textarea>
					{if $user_info_edit['message_flag'] eq $list_profile_status['1']['id']}
						<a class="right"><input type="checkbox" class="toggle" id="message_flag" checked/></a>
					{else}
						<a class="right"><input type="checkbox" class="toggle" id="message_flag" /></a>
					{/if}
					<input type="hidden" class="message_flag" name="message_flag" value="{$user_info_edit['message_flag']|h}" >
					<input type="hidden" id="h-message_flag" value="{$user_info_edit['message_flag']|h}" />
				</td>
			</tr>
			<!-- laguage -->
			<tr>
				<th>{'label_language'|lang}</th>
				<td>
					<select name="user_language" id="language" class="check">
						{foreach from=$languages item=item}
							{if isset($user_info_edit['language']) && $item.id eq $user_info_edit['language']}
								<option selected="selected" value="{$item['id']|h}">{$item['label']|lang}</option>
							{else}
								<option value="{$item['id']|h}">{$item['label']|lang}</option>
							{/if}
						{/foreach}
					</select>
					<input type="hidden" id="h-language" value="{$user_info_edit['language']}">
				</td>
			</tr>
		</table>
	{''|form_close}
	<style>
		p {
			margin:3px;
		}
		.txt{
			width:182px;
		}
		.txt_width60{
			width:49px !important;
		}
		
		.txt_width90{
			width:87px;
		}

		a:hover {
			cursor: pointer;
		}
		
		.hide {
			display : none;
		}
		
		.link {
			text-decoration: underline;
			cursor: pointer;
		}
		.right{
			float: right;
		}
		.mem_input textarea {
			width: 70%;
		}
	</style>
	{literal}
	<script language="Javascript">
		var ENABLE_EDIT = false;
		var isSAVE_EDIT_click = false;
		var isCANCELclick = false;
		$(document).ready(function(){
			var selected_year ;
			var selected_month ;
			var date =$('#txt_birthday').val();
			var phone_number = $('#txt_phone').val();
			
			
			var list = [];
			var is_public = '{/literal}{$list_profile_status[1]["id"]}{literal}';
			var is_private = '{/literal}{$list_profile_status[2]["id"]}{literal}';

			$(".toggle").toggleSwitch({
				onLabel:"{/literal}{'label_public'|lang}{literal}",
				offLabel:"{/literal}{'label_private'|lang}{literal}"
			});
			$('.TinyTools').on('click', function(){
				var this_checkbox = $(this).children('input[type= checkbox]');
				var id = this_checkbox.attr('id');
				var h_id = '#h-'+id;
				if (this_checkbox.is(':checked')) {
					$('.'+id).val(is_public);
				} else {
					$('.'+id).val(is_private);
				}
				if($(h_id).val() != $('.'+id).val()){
					if (!ENABLE_EDIT) {
						ENABLE_EDIT = true;
						$('.btn_save_edit').removeAttr('disabled');
						$('.btn_save_edit').removeClass('btn-disabled');
					}
					var index = list.indexOf(id);
					if (index < 0) {
						list.push(id);
					};
				} else {
					if (ENABLE_EDIT) {
						var index = list.indexOf(id);
						if (index > -1) {
							list.splice(index, 1);
						}
						if(list.length == 0){
							ENABLE_EDIT = false;
							$('.btn_save_edit').attr( "disabled", "disabled" );
							$('.btn_save_edit').addClass('btn-disabled');
						};
					};
				};
			});
			
			$('.check').on('blur, change',function(){
				var id = $(this).attr('id');
				var h_id = '#h-'+id;
				if($(h_id).val() != $(this).val()){
					if (!ENABLE_EDIT) {
						ENABLE_EDIT = true;
						$('.btn_save_edit').removeAttr('disabled');
						$('.btn_save_edit').removeClass('btn-disabled');
					}
					var index = list.indexOf(id);
					if (index < 0) {
						list.push(id);
					};
				} else {
					if (ENABLE_EDIT) {
						var index = list.indexOf(id);
						if (index > -1) {
							list.splice(index, 1);
						}
						if(list.length == 0){
							ENABLE_EDIT = false;
							$('.btn_save_edit').attr( "disabled", "disabled" );
							$('.btn_save_edit').addClass('btn-disabled');
						};
					};
				};
			});

			$('.number').keypress(function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 ) {
					return true;
				}
				return false;
			});

			if(date != '' && date != undefined){
				set_date(date);
			}
			if(phone_number !='' && phone_number != undefined){
				set_phone_number(phone_number);
			}

			$('#txt_year').change(function(){
				selected_year =  $( "select#txt_year option:selected" ).val();
				selected_month = $( "select#txt_month option:selected" ).val();
				if (selected_month >= 1 && selected_month <= 12) {
					get_day_in_month(selected_year,selected_month);
				}
				return ;
				
			});
			$('#txt_month').change(function(){
				selected_month = $( "select#txt_month option:selected" ).val();
				selected_year =  $( "select#txt_year option:selected" ).val();

				if (selected_year != 0) {
					get_day_in_month(selected_year,selected_month);
				}
				return ;
				
				get_day_in_month(selected_year,selected_month);
			});

			$('#txt_day').change(function(){
				$('#h-day').val ($( "select#txt_day option:selected" ).val());
			});

			
			$('.btn_save_edit').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					isSAVE_EDIT_click = true;
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
					return;
				}
				var number1 = $('#txt_phone1').val();
				var number2 = $('#txt_phone2').val();
				var number3 = $('#txt_phone3').val();
				if (number1 == "" && number2 == "" && number3 == "") {
					$('#txt_phone').val("");
				} else {
					$('#txt_phone').val(number1 +'-'+number2 +'-'+number3);
				}
				
				var year,month,day;
				year = parseInt($("select#txt_year option:selected" ).val());
				month = parseInt($("select#txt_month option:selected" ).val());
				day = parseInt($("select#txt_day option:selected" ).val());

				$('#txt_qualification').val($("select#qualification option:selected" ).text());
				
				if( year != 0 && month != 0 && day != 0){
					$('#txt_birthday').val(year +'-'+ month +'-'+ day);
				}
				isSAVE_EDIT_click = true;
				$('#form_main').submit();
			});
		});

		function update() {
			var urlAction = "{/literal}{$fixed_base_url}user/update{literal}";
			var cct = $("input[name=csrf_token]").val();
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct},
				dataType :'html',
				beforeSend: function() { $('#processing').removeClass('hide'); },
				success : function(data){
					$('#processing').addClass('hide');
					try {
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#dialog_p').addClass('hide');
						$('#dialog_p').empty().append(obj.message);
						$('#btn_save_yes').addClass('hide');
						$('#btn_no').addClass('hide');
						if (obj.is_change_email) {
							$('#btn_emailchanged_yes').removeClass('hide');
							$('#dialog_email_changed').removeClass('hide');
						} else {
							$('#btn_alert_yes').removeClass('hide');
							$('#dialog_p').removeClass('hide');
						};
						$(".modalOpen").click();
					}
					catch(ex){
						window.location.replace("{/literal}{$fixed_base_url}user/edit{literal}");
					}
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				}
			});
		}

		function get_day_in_month(selected_year,selected_month){
			if($('select#txt_day option').eq(0) == true){
				return;
			}
			if(selected_year == undefined){
				$( "select#txt_year option:selected" ).each(function() {
					 selected_year = $(this).val();
				});
			}
			if(selected_month == undefined){
				$( "select#txt_month option:selected" ).each(function() {
					selected_month = $(this).val();
				});
			}
			 var day_in_month = new Date(selected_year,selected_month,0).getDate();

			 $("select#txt_day option").remove();
			 for(var index =1 ; index <= day_in_month ; index ++){
				 $("select#txt_day").append('<option value='+index+'>'+ index +'</option>') ;
			 }
			 var hidden_day = parseInt($('#h-day').val());
			 if(hidden_day <= day_in_month){
				 $("select#txt_day option").eq(hidden_day-1).attr('selected',true);
			 } else {
				 $("select#txt_day option").eq(0).attr('selected',true);
			 }
			 
		}

		function set_date(date){
			var data = date.split('-');
			var year= parseInt(data[0]);
			var month =parseInt(data[1]);
			var day = parseInt(data[2]);
			
			get_day_in_month(year,month);
			
			$('select#txt_year option[value ='+year+']').attr('selected',true);
			$('select#txt_month option[value ='+month+']').attr('selected',true);
			$('select#txt_day option[value ='+day+']').attr('selected',true);
			$('#h-txt_day').val(day);
			$('#h-day').val(day);
			$('#h-txt_month').val(month);
			$('#h-txt_year').val(year);

		}

		function set_phone_number(phone_number){
			var data = phone_number.split('-');
			var num1= data[0];
			var num2 =data[1];
			var num3 = data[2];
			
			$('#txt_phone1').val(num1);
			$('#txt_phone2').val(num2);
			$('#txt_phone3').val(num3);

			$('#h-txt_phone1').val(num1);
			$('#h-txt_phone2').val(num2);
			$('#h-txt_phone3').val(num3);
		};
	</script>
	{/literal}
</div>