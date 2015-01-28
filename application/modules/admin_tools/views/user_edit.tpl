{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/user/'|cat:$user_info_edit['id']|cat:'/confirm_edit'|form_open:'role=form class=form-horizontal id=form_main autocomplete=off'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_user_manager'|lang} > {$user_info_edit['id']} </h2></div>
		<div class="ct mtb10">
			<a href="{$fixed_base_url}admin_tools/group/{$user_info_edit['id']}/user"><img src="{$fixed_base_url}assets-admin/img/group.png" alt="{'label_user_link_to_group'|lang}"></a>
			<a href="{$fixed_base_url}admin_tools/post/{$user_info_edit['id']}/user"><img src="{$fixed_base_url}assets-admin/img/thread.png" alt="{'label_user_link_to_thread'|lang}"></a>
		</div>
		<table class="box_table_h">
		<tbody>
			<tr>
				<th><label for="label_id" >{'label_user_user_id'|lang} </label><a style="color:red">※</a></th>
				<td><label>{$user_info_edit['id']}</label></td>
			</tr>
			<!-- email -->
			<tr>
				<th><label name ="label_email" >{'label_user_email'|lang}</label><a style="color:red">※</a></th>
				<td><input type="email"name="email" value="{set_value(email,$user_info_edit['email'])}" maxlength="128">
				{form_error('email','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th><label for="label_login_id">{'label_user_login_id'|lang} </label><a style="color:red">※</a></th>
				<td><input type="text" name="login_id" value="{set_value(login_id,$user_info_edit['login_id'])}" autocomplete ="off" maxlength="64">
				{form_error('login_id','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- password -->
			<tr>
				<th><label>{'label_user_password'|lang}</label><a style="color:red">※</h>
				</td>
				<td>
					<input type="password" style="display:none" autocomplete ="off">
					<input type="password" name="password" id="password" autocomplete ="off" maxlength="255">
					{form_error('password','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- re-password -->
			<tr>
				<th>
					<label>{'label_user_re_password'|lang}</label>
				</th>
				<td>
					<input type="password" name="re_password" id="re_password" maxlength="255">
					{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- name ja -->
			<tr>
				<th><label for="label_name_ja" >{'label_user_name_ja'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="text" name="last_name_ja" value="{set_value(last_name_ja,$user_info_edit['last_name_ja'])}" maxlength="64">
					<input type="text" value="{set_value(first_name_ja,$user_info_edit['first_name_ja'])}" name="first_name_ja" maxlength="64">
					{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
					{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- name romaji -->
			<tr>
				<th><label for="">{'label_user_name'|lang}</label><a style="color:red">※</a></th>
				<td>
					<input type="text" name="last_name" value="{set_value(last_name,$user_info_edit['last_name'])}" maxlength="64">
					<input type="text" value="{set_value(first_name,$user_info_edit['first_name'])}" name="first_name" maxlength="64">
					{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
					{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- sex -->
			<tr>
				<th><label class="label_gender" for="gender">{'label_user_gender'|lang}</label><a style="color:red">※</a></th>
				<td>
					<select name="gender">
					{foreach from=$gender_types item=item}
						{if $user_info_edit['gender'] eq $item.id}
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
				<th><label for="label_birthday">{'label_user_birthday'|lang}</label><a style="color:red">※</a></th>	
				<td>
					{$year = date(Y)}
					{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
					{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
					<select name="txt_year" id="txt_year">
						{while $year >= $min_year_cbx}
							<option value="{$year}">{$year}</option>
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
					<input type="hidden" name="birthday" id="txt_birthday" value="">
					{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
				</td>
		 	</tr>
		
			<tr>
				<th><label class="" for="label_qualification">{'label_user_qualifications_job'|lang}</label></th>
				<td>
					<select name ="qualification_id" id="qualification" >
					{foreach from=$list_qualification item = item}
						{if $user_info_edit['qualification_id'] eq $item['id']}
							<option value="{$item['id']}" selected="true">{$item['name']}</option>
						{else}
							<option value="{$item['id']}" >{$item['name']}</option>
						{/if}
					{/foreach}
					</select>
					<input type="hidden" name="qualification" id="txt_qualification" value="">
				</td>
			</tr>
			
			<!-- organization -->
			<tr>
				<th><label for="organization">{'label_user_organization'|lang}</label></th>
				<td>
					<input type="text" name="organization" value="{$user_info_edit['organization']}" maxlength="255"/>
					{form_error('organization','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- department -->
			<tr>
				<th><label for="department" >{'label_user_department'|lang}</label></th>
				<td>
					<input type="text" name="department" value="{$user_info_edit['department']}" maxlength="255"/>
					{form_error('department','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- position -->
			<tr>
				<th><label for="position" >{'label_user_position'|lang}</label></th>
				<td>
					<input type="text" name="position" value="{$user_info_edit['position']}" maxlength="128"/>
					{form_error('position','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- phone -->
			<tr>
				<th><label for="phone">{'label_user_phone_number'|lang}</label></th>
				<td>
					<input type="text" name="phone1" id="txt_phone1" value="" maxlength="3" class="txtWidth90 number"> -
					<input type="text" name="phone2" id="txt_phone2" value="" maxlength="4" class="txtWidth90 number"> -
					<input type="text" name="phone3" id="txt_phone3" value="" maxlength="4" class="txtWidth90 number">
					<input type="hidden" name="phone_number" value="{$user_info_edit['phone_number']|h}" id="txt_phone">
					{form_error('phone_number','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<!-- domain -->
			<tr>
				<th><label for="domain">{'label_user_domain'|lang}</label></th>
				<td>
					<input type="text" name="domain" value="{$user_info_edit['domain']|h}" maxlength="255">
					{form_error('domain','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			
			<!-- history -->
			<tr>
				<th><label for="history" >{'label_user_history'|lang}</label></th>
				<td><input type="text" name="history" value="{$user_info_edit['history']|h}" ></td>
			</tr>
			<!-- university -->
			<tr>
				<th><label for="university" >{'label_user_university'|lang}</label></th>
				<td><input type="text" name="university" value="{$user_info_edit['university']|h}" maxlength="255"></td>
			</tr>
			
			<!-- scholar -->
			<tr>
				<th><label for="scholar">{'label_user_scholar'|lang}</label></th>
				<td><textarea name="scholar">{$user_info_edit['scholar']|h}</textarea></td>
			</tr>
			
			<!-- author -->
			<tr>
				<th><label for="author" >{'label_user_author'|lang}</label></th>
				<td><textarea name="author">{$user_info_edit['author']|h}</textarea></td>
			<tr>
			<!-- society -->
			<tr>
				<th><label for="society">{'label_user_society'|lang}</label></th>
				<td><textarea name="society">{$user_info_edit['society']|h}</textarea></td>
			</tr>
			<!-- hobby -->
			<tr>
				<th><label for="hobby">{'label_user_hobby'|lang}</label></th>
				<td><textarea name="hobby">{$user_info_edit['hobby']|h}</textarea></td>
			</tr>
			
			<!-- message -->
			<tr>
				<th><label for="message">{'label_user_message'|lang}</label></th>
				<td><textarea name="message">{$user_info_edit['message']}</textarea></td>
			</tr>
			<!-- auth_method -->
			<tr>
				<th><label for="label_auth_method">{'label_user_auth_method'|lang} </label></th>
				<td>
					{$list_auth_method[$user_info_edit['auth_method']].label|lang}
				</td>
			</tr>
			<!-- company_code -->
			<tr>
				<th><label for="company_code">{'label_user_company_code'|lang}</label></th>
				<td>
					<input type="text" name="company_code" value="{$user_info_edit['company_code']}" maxlength="255">
					{form_error('company_code','<p class="alert-error"> ※ ','</p>')}
				</td>
				
			</tr>
			<!-- recommend_user_id -->
			<tr>
				<th><label for="label_recommend_user_id">{'label_user_recommend_user_id'|lang} </label></th>
				<td><label>{$user_info_edit['recommend_user_id']}</label>
			</tr>
			<!-- joined_at -->
			<tr>
				<th><label for="label_joined_at" >{'label_user_joined_at'|lang} </label></th>
				<td><label>{$user_info_edit['joined_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H{'label_hours'|lang}i{'label_minutes'|lang}"}</label>
			</tr>
			<!-- language -->
			<tr>
				<th>{'label_language'|lang}</th>
				<td>
					<select name="language">
						{foreach from=$languages item=item}
							{if isset($user_info_edit['language']) && $item.id eq $user_info_edit['language']}
								<option selected="selected" value="{$item['id']|h}">{$item['label']|lang}</option>
							{else}
								<option value="{$item['id']|h}">{$item['label']|lang}</option>
							{/if}
						{/foreach}
					</select>
				</td>
			</tr>
			<!-- status -->
			<tr>
				<th><label class="" >{'label_status'|lang}</label></th>
				<td>
					<select name="status" >
					{foreach from=$status_types item=item}
						{if $user_info_edit['status'] eq $item.id}
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
		<div class="ct mtb10">
			<a href="" id="btn_confirm"><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
			<a href="" id="btn-delete"><img src="{$fixed_base_url}assets-admin/img/delete.png" alt="{'label_button_delete'|lang}"></a>
		</div>
		<a href="" class="modalOpen" id="btn-dialog"></a>
	</div>
	{''|form_close}
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ctinner_m">
						<p id="dialog_p">{$msg_delete_confirm}</p>
						<p class="ct m20">
							<a id="btn_no" class="modalClose" href=""><img src="{$fixed_base_url}assets-admin/img/modal_iie.png"></a>
							<a id="btn_yes" class="modalClose" href=""><img src="{$fixed_base_url}assets-admin/img/modal_hai.png"></a>
							<a id="btn_ok" href="{$fixed_base_url}admin_tools/user"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/block}

{block name=stylesheet}
	<style>
		.alert-error{
			color:red;
		}
		.txtWidth90 {
			width: 90px;
		}
		textarea {
			border: 1px solid #cccccc;
			border-radius: 10px;
			width: 423px;
			height: 166px;
			padding: 5px;
			resize: none;
		}
	</style>
{/block}

{block name=javascript}
	{literal}
	<script language="Javascript">
	$(document).ready(function(){
		var selected_year;
		var selected_month;
		var date ="{/literal}{$user_info_edit['birthday']}{literal}";
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

		$( "#btn-delete" ).click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			$('#btn-dialog').click();
			$('#btn_ok').hide();
			return false;
		});
		
		$("#btn_yes").click(function() {
			$('#btn_ok').show();
			$('#btn_yes').hide();
			$('#btn_no').hide();
			delete_id();
			return false;
		});
	});
	function delete_id() {
		var urlAction = "{/literal}{$fixed_base_url}admin_tools/user/{$user_info_edit['id']}/delete{literal}";
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
				if (data.status == '417') {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/user{literal}");
					return;
				}
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
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