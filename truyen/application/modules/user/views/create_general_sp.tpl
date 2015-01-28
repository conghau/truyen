{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
<div class="static_all_wrap">
	{if isset($user)}
		<h2 class="ttl">{sprintf(('label_user_error_had_login'|lang),$user->login_id,$user->login_id)}</h2>
	{else}
	<h2 class="ttl">{'label_user_regist'|lang}</h2>
	<p class="mb10">{'label_user_regist_introduce'|lang}</p>
	<div class="static_wrap">
	{$fixed_base_url|cat:'user/confirm_create'|form_open:'enctype=multipart/form-data class=form-horizontal id=form_main autocomplete =off'}
		<table class="inq">
			<!-- email -->
			<tr>
				<th><label class=" control-label">{'label_user_email'|lang}</label></th>
			</tr>
			<tr>
				<td>
					{form_error('email','<p class="alert-error"> ※ ','</p>')}
					<input type="email" size ="30" name="email" value="{$email|h}" placeholder="{'label_user_email'|lang}" maxlength="128">
					<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
				</td>
			</tr>
			<!-- LoginId -->
			<tr>
				<th><label>{'label_user_login_id'|lang} </label></th>
			</tr>
			<tr>
				<td>
					{form_error('login_id','<p class="alert-error"> ※ ','</p>')}
					<input type="text" name="login_id" id="login_id" size ="30" value="{$login_id|h}" placeholder="{'label_user_login_id'|lang}" autocomplete ="off" maxlength="64">
					<p class="upper_first_letter">※{'label_user_login_id_guidance'|lang}</p>
				</td>
			</tr>
			<!--password -->
			<tr>
				<th>
					<label>{'label_user_password'|lang}</label>
				</th>
			</tr>
			<tr>
				<td>
					{form_error('password','<p class="alert-error"> ※ ','</p>')}
					<input type="password" name="hpassword" style="display: none" autocomplete ="off">
					<input type="password" size ="30" name="password" value="{$password|h}" placeholder="{'label_user_password'|lang}" autocomplete ="off" maxlength="255">
					<p class="upper_first_letter">※{'label_user_password_guidance'|lang}</p>
				</td>
			</tr>
			<!-- re-password -->
			<tr>
				<th>
					<label>{'label_user_re_password'|lang}</label>
				</th>
			</tr>
			<tr>
				<td>
					{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
					<input type="password" name="re_password" id="re_password" size ="30" value="{$re_password|h}" placeholder="{'label_user_re_password'|lang}" maxlength="255">
					<p class="upper_first_letter">※{'label_user_password_guidance'|lang}</p>
				</td>
			</tr>
			{if $language eq 'japanese'}
				<!-- name ja -->
				<tr>
					<th>
						<label class=" control-label">{'label_user_name_ja'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
				</tr>
				<tr>
					<td>
						{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
						<input type="text" size="11" name="last_name_ja" value="{$last_name_ja|h}" placeholder="{'label_placeholder_last_name_ja'|lang}" maxlength="64">
						<input type="text" size="11" name="first_name_ja" value="{$first_name_ja|h}" placeholder="{'label_placeholder_first_name_ja'|lang}" maxlength="64">
						<p class="upper_first_letter">※{'label_kana'|lang}</p>
					</td>
				</tr>
				<!-- name romaji -->
				<tr>
					<th>
						<label class=" control-label">{'label_user_name'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
				</tr>
				<tr>
					<td>
						{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
						<input type="text" size="11" name="last_name" value="{$last_name|h}" placeholder="{'label_placeholder_last_name'|lang}" maxlength="64">
						<input type="text" size="11" value="{$first_name|h}" name="first_name" placeholder="{'label_placeholder_first_name'|lang}" maxlength="64">
						<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
					</td>
					
				</tr>
			{else}
				<!-- name romaji -->
				<tr>
					<th>
						<label class=" control-label">{'label_user_name_ja'|lang}</label><br>
						<span>※{'label_published_in_profile'|lang}</span>
					</th>
				</tr>
				<tr>
					<td>
						{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
						<input type="text" size="11" name="last_name" value="{$last_name|h}" placeholder="{'label_placeholder_last_name'|lang}" maxlength="64">
						<input type="text" size="11" value="{$first_name|h}" name="first_name" placeholder="{'label_placeholder_first_name'|lang}" maxlength="64">
						<p class="upper_first_letter">※{sprintf(('label_alpha_numbeic'|lang),'')}</p>
					</td>
					
				</tr>
			{/if}
			<!-- sex -->
			<tr>
				<th>
					<label class="label_gender" for="gender">{'label_user_gender'|lang}</label>
					<br />
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
			</tr>
			<tr>
				<td>
					{form_error('gender','<p class="alert-error"> ※ ','</p>')}
					<select name="gender" class="txt_width90 check_selected">
					<option selected="true" value="0" disabled>{'label_user_gender'|lang}</option>
					{foreach from=$gender_types item=item}
						{if isset($gender) and $gender eq $item.id}
							<option value="{$item.id}" selected = "true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
				</td>
			</tr>
			<!-- birthday -->
			<tr>
				<th>
					<label class=" control-label">{'label_user_birthday'|lang}</label>
				</th>
			</tr>
			<tr>
				<td>
					{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
					{$year = date(Y)}
					{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
					{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
					<select name="txt_year" id="txt_year" class="txt_width90" style="margin-right : 0">
						<option disabled value="0">{'label_placeholder_year'|lang}</option>
						{while $year >= $min_year_cbx}
							<option value="{$year}"{if $txt_year == $year} selected{/if}>{$year}</option>
							{$year = $year -1}
						{/while}
					</select>

					{$count =1}
					<select name="txt_month" id="txt_month" class="txt_width90" style="margin-right : 0">
						<option disabled value="0">{'label_placeholder_month'|lang}</option>
						{while $count <=  12 }
							<option value="{$count}"{if $txt_month == $count} selected{/if}>{$count}</option>
							{$count = $count + 1}
						{/while}
					</select>
					<select name="txt_day" id="txt_day" class="txt_width90" style="margin-right : 0">
						<option disabled value="0">{'label_placeholder_day'|lang}</option>
					</select>
					<p class="upper_first_letter alert-error">※{'label_user_birthday_guidance'|lang}</p>
					
					<input type="hidden" id="h_txt_day" value="{if $txt_day}{$txt_day}{else}1{/if}">
					<input type="hidden" name="birthday" id="txt_birthday" value="{(isset($birthday))?$birthday:''}">
				</td>
		 	</tr>
		
			<tr>
				<th>
					<label class="" for="label_qualification">{'label_user_qualifications_job'|lang}</label><br>
					<span>({'label_user_qualifications_etc'|lang})</span><br>
					<span>※{'label_published_in_profile'|lang}</span>
				</th>
			</tr>
			<tr>
				<td>
					{form_error('qualification_id','<p class="alert-error"> ※ ','</p>')}
					<select name ="qualification_id" id="qualification" class="check_selected">
						<option value="0" selected="true" disabled>{'label_please_select'|lang}</option>
						{foreach from=$list_qualification item = item}
							{if $qualification_id eq $item['id']}
								<option value="{$item['id']}" selected="true">{$item['name']}</option>
							{else}
								<option value="{$item['id']}">{$item['name']}</option>
							{/if}
						{/foreach}
					</select>
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
			</tr>
			<tr>
				<td>
					{form_error('organization','<p class="alert-error"> ※ ','</p>')}
					<input type="text" size ="30" name="organization" value="{$organization|h}" placeholder="{'label_user_organization'|lang}" maxlength="255">
				</td>
				
			</tr>
			<!-- author -->
			<tr>
				<th><label for="auth_method" class="control-label">{'label_please_select'|lang}</label></th>
			</tr>
			<tr>
				<td>
					{form_error('auth_method','<p class="alert-error"> ※ ','</p>')}
					<select name="auth_method" id="cbx_auth_method" class="check_selected" >
						<option value="0" selected="true" disabled>{'label_user_auth_method'|lang}</option>
					{foreach from=$list_auth_method item=item}
						{if isset($auth_method) and $auth_method eq $item.id}
							<option value="{$item.id}" selected="true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select><br>
					<p>※{'label_auth_message'|lang}</p>
				</td>
			</tr>
			<tr class="auth auth_{$list_auth_method['auth_image']['id']} hide" id="auth_{$list_auth_method['auth_image']['id']}">
				<th><label>{'label_user_auth_by_image'|lang}</label></th>
			</tr>
			<tr class="auth auth_{$list_auth_method['auth_image']['id']} hide">
				<td>
					{if isset($msg_upload)}
						{$msg_upload}
					{/if}
					<input type="file" class ="clear" name="auth_method_image" id ="auth_method_image" accept="image/*"/>
					
				</td>
			</tr>
			<tr class="auth auth_{$list_auth_method['auth_info']['id']} hide" id="auth_{$list_auth_method['auth_info']['id']}">
				<th><label>{'label_user_auth_by_info'|lang}</label></th>
			</tr>
			<tr class="auth auth_{$list_auth_method['auth_info']['id']} hide">
				<td>
					<p>{'label_user_confirm_organization'|lang}<p>
					{form_error('confirm_organization','<p class="alert-error"> ※ ','</p>')}
					<input type="text" size="30" class="clear" name="confirm_organization" value="{(isset($confirm_organization))?$confirm_organization:''}" maxlength="255"/><br>
					<br>
					<p>{'label_user_confirm_phone_number'|lang}</p>
					{form_error('confirm_phone_number','<p class="alert-error"> ※ ','</p>')}
					<input type="text" class="clear txt_width60 number" id="txt_phone1" value="" maxlength="3"> -
					<input type="text" class="clear txt_width60 number" id="txt_phone2" value="" maxlength="4"> -
					<input type="text" class="clear txt_width60 number" id="txt_phone3" value="" maxlength="4">
					<input type="hidden" name="confirm_phone_number" value="{(isset($confirm_phone_number))?$confirm_phone_number:''}" id="txt_phone" maxlength="20">
					<br><p>※{'label_user_auth_by_info_introduce'|lang}</p>
					
				</td>
			</tr>
			<!-- language -->
			<tr>
				<th>{'label_language'|lang}</th>
			</tr>
			<tr>
				<td>
					<select name="user_language">
						{foreach from=$languages item=item}
							<option{if $item.id eq $user_language} selected="selected"{/if} value="{$item['id']|h}">{{$item['label']}|lang}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
	{''|form_close}
	</div>
	<div class="mt20">
		<input type ="checkbox" id="chk_agree">{'label_agree'|lang}
	</div>
	<div class="btn">
		<a id="btn_confirm" class="disabled" href="#">{'label_button_confirm'|lang}</a>
	</div>
	{/if}
</div>
{/block}
{block name=stylesheet}
	<style>
		.btn .disabled {
			color: #999;
		}
		p {
			margin:3px;
		}
		.txt_width90{
			width:87px;
		}
		.txt_width60{
			width:55px !important;
		}

		select{
			width:158px;
		}
		
		.alert-error{
			color:red;
		}
		
		.hide {
			display : none;
		}
		
		p.center {
			text-align: center;
			width: 67%;
		}
		
		p.upper_first_letter:first-letter {
			text-transform:uppercase;
		}
	</style>
{/block}
{block name=javascript}
	{literal}
	<script language="Javascript">
		var selected_year = '{/literal}{$txt_year}{literal}';
		var selected_month = '{/literal}{$txt_month}{literal}';
		var selected_day = '{/literal}{$txt_day}{literal}';
		$(document).ready(function(){
			var selected_year;
			var selected_month ;
			var date =$('#txt_birthday').val();
			var auth_method = "{/literal}{(isset($auth_method))?$auth_method : ""}{literal}";
			var phone_number = $('#txt_phone').val();
			if(date != '' && date != 0 && date != undefined){
				set_date(date);
			}

			if (auth_method != '' && auth_method != undefined) {
				$('.auth_'+auth_method).removeClass('hide');
			}

			if(phone_number !='' && phone_number != undefined){
				set_phone_number(phone_number);
			}
			$('.number').keypress(function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 ) {
					return true;
				}
				return false;
			});

			var change_date = function(){
				selected_month = $( "select#txt_month option:selected" ).val();
				selected_year =  $( "select#txt_year option:selected" ).val();

				if (selected_year != 0) {
					get_day_in_month(selected_year,selected_month);
				}
				return ;
			};
			$('#txt_year').change(change_date);
			$('#txt_month').change(change_date);
			change_date();

			$('#txt_day').change(function(){
				$('#h_txt_day').val ($( "select#txt_day option:selected" ).val());
			});

			$('#cbx_auth_method').change(function(){
				var value_select = $( "select#cbx_auth_method option:selected" ).val();
				$('.clear').val(''); //clear value 
				$('.auth').addClass('hide');
				$('.auth_'+value_select).removeClass('hide');
			});

			$('#chk_agree').click(function(){
				if ($('#chk_agree').is(':checked')) {
					$('#btn_confirm').removeClass('disabled');

				} else {
					$('#btn_confirm').addClass('disabled');

				}
			});

			$('#btn_confirm').click(function(){
				if ($(this).hasClass("disabled")) {
					return false;
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

				if( year != 0 && month != 0 && day != 0){
					$('#txt_birthday').val(year +'-'+ month +'-'+ day);
				} else {
					$('#txt_birthday').val(0);
				}

				$('select.check_selected option:selected').each(function(index){
					if($(this).val() == 0){
						$(this).removeAttr('disabled');
					}
				});
				$('#txt_qualification').val($("select#qualification option:selected" ).text());
				$('#form_main').submit();
			});

		});
		
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
				if (index == selected_day) {
					 $("select#txt_day").append('<option selected value='+index+'>'+ index +'</option>') ;
				} else {
					 $("select#txt_day").append('<option value='+index+'>'+ index +'</option>') ;
				}
			 }
			 var hidden_day = parseInt($('#h_txt_day').val());
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
			
			$('select#txt_year').val(year);
			$('select#txt_month').val(month);
			$('select#txt_day').val(day);
			$('#h_txt_day').val(day);

			get_day_in_month(year,month);
		}

		function set_phone_number(phone_number){
			var data = phone_number.split('-');
			var num1= data[0];
			var num2 =data[1];
			var num3 = data[2];
			
			$('#txt_phone1').val(num1);
			$('#txt_phone2').val(num2);
			$('#txt_phone3').val(num3);
		}
	</script>
	{/literal}
{/block}