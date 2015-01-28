{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/user/confirm_create'|form_open:'role=form class=form-horizontal id=form_main autocomplete =off'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_user_manager'|lang}</h2></div>
		<table id="box_table" class="box_table_h">
		<tbody>
			
			<!-- email -->
			<tr>
				<th><label class=" control-label">{'label_user_email'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="email" class="txt" name="email" value="{(isset($email))?$email:''}" maxlength="128">
					{form_error('email','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th><label>{'label_user_login_id'|lang} </label><a style="color:red">※</a></th>
				<td>
					<input type="text" class="txt" name="login_id" id="login_id" maxlength="64" value="{(isset($login_id))?$login_id:''}" >
					{form_error('login_id','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- password -->
			<tr>
				<th><label>{'label_user_password'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="password" style="display:none" autocomplete ="off">
					<input type="password" class="txt" name="password" id="password" maxlength="255" autocomplete ="off">
					{form_error('password','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- re-password -->
			<tr>
				<th>
					<label>{'label_user_re_password'|lang}</label><a style="color:red">※</a>
				</th>
				<td>
					<input type="password" class="txt" name="re_password" id="re_password" maxlength="255"/>
					{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- name ja -->
			<tr>
				<th><label class=" control-label">{'label_user_name_ja'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="text" class="txt_width90" name="last_name_ja" value="{(isset($last_name_ja))?$last_name_ja:''}" maxlength="64" >
					<input type="text" class="txt_width90" value="{(isset($first_name_ja))?$first_name_ja:''}" name="first_name_ja" maxlength="64" >
					{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
					{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- name romaji -->
			<tr>
				<th><label class=" control-label">{'label_user_name'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="text" class="txt_width90" name="last_name" value="{(isset($last_name))?$last_name:''}" maxlength="64" >
					<input type="text" class="txt_width90" value="{(isset($first_name))?$first_name:''}" name="first_name" maxlength="64" >
					{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
					{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- gender -->
			<tr>
				<th><label class="label_gender" for="gender">{'label_user_gender'|lang}</label><a style="color:red">※</a></th>
				<td>
					<select name="gender" class="txt_width90">
					{foreach from=$gender_types item=item}
						{if $gender eq $item.id}
							<option value="{$item.id}" selected = "true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
					{form_error('gender','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- birthday -->
			<tr>
				<th><label class=" control-label">{'label_user_birthday'|lang}</label><a style="color:red">※</a></th>	
				<td>
					{$year = date(Y)}
					{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
					{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
					<select name="txt_year" id="txt_year">
						{while $year >= $min_year_cbx}
							{if $year eq $default_year }
								<option value="{$year}" selected>{$year}</option>
							{else}
								<option value="{$year}">{$year}</option>
							{/if}
							{$year = $year -1}
						{/while}
					</select>
					{$count =1}
					<select name="txt_month" id="txt_month" >
						{while $count <=  12 }
							<option value="{$count}">{$count}</option>
							{$count = $count + 1}
						{/while}
					</select>
					<select name="txt_day" id="txt_day">
					</select>
					
					<input type="hidden" id="h_txt_day" value="1">
					<input type="hidden" name="birthday" id="txt_birthday" value="{(isset($birthday))?$birthday:''}">
					{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
				</td>
		 	</tr>
		
			<tr>
				<th><label class="" for="label_qualification">{'label_user_qualifications_job'|lang}</label></th>
				<td>
					<select name ="qualification_id" id="qualification">
					{$index = 0}
					{if isset($qualification_id)}
						{foreach from=$list_qualification item = item}
							{if $qualification_id eq $item['id']}
								<option value="{$item['id']}" selected="true">{$item['name']}</option>
							{else}
								<option value="{$item['id']}">{$item['name']}</option>
							{/if}
						{/foreach}
					{else}
						{foreach from=$list_qualification item = item}
							{if $index eq 0}
								<option value="{$item['id']}" selected="true">{$item['name']}</option>
							{else}
								<option value="{$item['id']}">{$item['name']}</option>
							{/if}
							{$index = 1}
						{/foreach}
					{/if}
					
					</select>
					<input type="hidden" name="qualification" id="txt_qualification" value="">
				</td>
			</tr>
			
			<!-- organization -->
			<tr>
				<th><label for="organization" class="control-label">{'label_user_organization'|lang}</label></th>
				<td>
					<input type="text" class="txt" name="organization" value="{(isset($organization))?$organization:''}" maxlength="255" >
					{form_error('organization','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- department -->
			<tr>
				<th><label for="department" class="control-label">{'label_user_department'|lang}</label></th>
				<td>
					<input type="text" class="txt" name="department" value="{(isset($department))?$department:''}" maxlength="255" >
					{form_error('department','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- position -->
			<tr>
				<th><label for="position" class="control-label">{'label_user_position'|lang}</label></th>
				<td>
					<input type="text" class="txt" name="position" value="{(isset($position))?$position:''}" maxlength="128" >
					{form_error('position','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- phone -->
			<tr>
				<th><label for="phone" class=" control-label">{'label_user_phone_number'|lang}</label></th>
				<td>
					<input type="text" class="txt_width90 number" name="phone1" id="txt_phone1" value="" maxlength="3"> -
					<input type="text" class="txt_width90 number" name="phone2" id="txt_phone2" value="" maxlength="4"> -
					<input type="text" class="txt_width90 number" name="phone3" id="txt_phone3" value="" maxlength="4">
					<input type="hidden" name="phone_number" value="{(isset($phone_number))?$phone_number:''}" id="txt_phone" maxlength="20">
					{form_error('phone_number','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- domain -->
			<tr>
				<th><label for="domain" class="control-label">{'label_user_domain'|lang}</label></th>
				<td>
					<input type="text" class="txt" name="domain" value="{(isset($domain))?$domain:''}" maxlength="255" >
					{form_error('domain','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>

			<!-- history -->
			<tr>
				<th><label for="history" class="control-label">{'label_user_history'|lang}</label></th>
				<td><input type="text" class="txt" name="history" value="{(isset($history))?$history:''}" ></td>
			</tr>
			<!-- university -->
			<tr>
				<th><label for="university" class="control-label">{'label_user_university'|lang}</label></th>
				<td><input type="text" class="txt" name="university" value="{(isset($university))?$university:''}" maxlength="255" ></td>
			</tr>
			
			<!-- scholar -->
			<tr>
				<th><label for="scholar" class="control-label">{'label_user_scholar'|lang}</label></th>
				<td><textarea name="scholar">{(isset($scholar))?$scholar:''}</textarea></td>
				
			</tr>
			
			<!-- author -->
			<tr>
				<th><label for="author" class="control-label">{'label_user_author'|lang}</label></th>
				<td><textarea name="author">{(isset($author))?$author:''}</textarea></td>
			<tr>
			<!-- society -->
			<tr>
				<th><label for="society" class="control-label">{'label_user_society'|lang}</label></th>
				<td><textarea name="society">{(isset($society))?$society:''}</textarea></td>
				
			</tr>
			<!-- hobby -->
			<tr>
				<th><label for="hobby" class="control-label">{'label_user_hobby'|lang}</label></th>
				<td><textarea name="hobby">{(isset($hobby))?$hobby:''}</textarea></td>
			</tr>
			
			<!-- message -->
			<tr>
				<th><label for="message" class="control-label">{'label_user_message'|lang}</label></th>
				<td><textarea name="message">{(isset($message))?$message:''}</textarea></td>
			</tr>
			
			<!-- company_code -->
			<tr>
				<th><label for="company_code" class="control-label">{'label_user_company_code'|lang}</label></th>
				<td>
					<input type="text" class="txt" name="company_code" value="{(isset($company_code))?$company_code:''}" maxlength="255">
					{form_error('company_code','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- language -->
			<tr>
				<th>{'label_language'|lang}</th>
				<td>
					<select name="language">
						{foreach from=$languages item=item}
							{if isset($user_language) && $item.id eq $user_language}
								<option selected="selected" value="{$item['id']|h}">{{$item['label']}|lang}</option>
							{else}
								<option value="{$item['id']|h}">{{$item['label']}|lang}</option>
							{/if}
						{/foreach}
					</select>
				</td>
			</tr>
			<!-- status -->
			<tr>
				<th><label class="" for="label_status">{'label_status'|lang}</label></th>
				<td>
					<select name="status" class="txt_width120">
					{foreach from=$status_types item=item}
						{if $status eq $item.id}
							<option value="{$item.id}" selected="true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
					</select>
				</td>
				
			</tr>
		</tbody>
		</table>
	</div> <!-- ctbox -->
	<div class="ct mtb20">
		<a href="" id="btn_confirm"><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
	</div>
{''|form_close}
{/block}

{block name=stylesheet}
	<style>
		textarea {
			border: 1px solid #cccccc;
			border-radius: 10px;
			width: 423px;
			height: 166px;
			padding: 5px;
			resize: none;
		}
		.alert-error{
			color:red;
		}
		.txt_width90 {
			width: 90px;
		}
	</style>
{/block}	

{block name=javascript}
	{literal}
	<script language="Javascript">
		$(document).ready(function(){
			var selected_year ;
			var selected_month ;
			var date =$('#txt_birthday').val();
			var phone_number = $('#txt_phone').val();

			if(date != '' && date != undefined){
				set_date(date);
			} else {
				get_day_in_month(selected_year,selected_month);
			}

			if(phone_number !='' && phone_number != undefined){
				set_phone_number(phone_number);
			}

			$('#txt_year').change(function(){
				$( "select#txt_year option:selected" ).each(function() {
					selected_year = $(this).val();
				});
				get_day_in_month(selected_year,selected_month);
			});
			$('#txt_month').change(function(){
				$( "select#txt_month option:selected" ).each(function() {
					 selected_month = $(this).val();
				});
				get_day_in_month(selected_year,selected_month);
			});

			$('#txt_day').change(function(){
				$('#h_txt_day').val ($( "select#txt_day option:selected" ).val());
			});

			$('.number').keypress(function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 ) {
					return true;
				}
				return false;
			});

			$('#btn_confirm').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				var number1 = $('#txt_phone1').val();
				var number2 = $('#txt_phone2').val();
				var number3 = $('#txt_phone3').val();
				var year,month,day;
				if (number1 == "" && number2 == "" && number3 == "") {
					$('#txt_phone').val("");
				} else {
					$('#txt_phone').val(number1 +'-'+number2 +'-'+number3);
				}
				$('#txt_qualification').val($("select#qualification option:selected" ).text());
				year =$("select#txt_year option:selected" ).val();
				month =$("select#txt_month option:selected" ).val();
				day =$("select#txt_day option:selected" ).val();
				$('#txt_birthday').val(year +'-'+ month +'-'+ day);
				
				$('#form_main').submit();
				return false;
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
				 $("select#txt_day").append('<option value='+index+'>'+ index +'</option>') ;
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
			
			$('select#txt_year option[value ='+year+']').attr('selected',true);
			$('select#txt_month option[value ='+month+']').attr('selected',true);
			$('select#txt_day option[value ='+day+']').attr('selected',true);
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