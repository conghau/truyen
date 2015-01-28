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
	{$fixed_base_url|cat:'admin_tools/admin/confirm_create'|form_open:'role=form class=form-horizontal id=form_main autocomplete=off'}
		<div class="ctbox">
			<div class="ttl"><h2>{'label_admin_management'|lang}</h2></div>
			<table id="box_table" class="box_table_h">
				<tbody>
					<!-- email -->
					<tr>
						<th>{'label_admin_email'|lang}<a style="color:red">※</a></th>
						<td>
							<input type="email" maxlength="128" name="email" value="{(isset($email))?$email:''|h}">
							{form_error('email','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- Loginid -->
					<tr>
						<th>{'label_admin_login_id'|lang}<a style="color:red">※</a></th>
						<td>
							<input type="text" maxlength="64" id="login_id" name="login_id" value="{(isset($login_id))?$login_id:''|h}" autocomplete="off">
							{form_error('login_id','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- password -->
					<tr>
						<th>
							<label>{'label_admin_password'|lang}</label><a style="color:red">※</a>
						</th>
						<td>
							<input type="password" style="display:none" autocomplete ="off">
							<input type="password" name="password" id="password" maxlength="255" autocomplete ="off"/>
							{form_error('password','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- re-password -->
					<tr>
						<th>
							<label>{'label_admin_re_password'|lang}</label><a style="color:red">※</a>
						</th>
						<td>
							<input type="password" class="txt" name="re_password" id="re_password" maxlength="255"/>
							{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- first - last name ja -->
					<tr>
						<th>{'label_admin_fullname'|lang}<a style="color:red">※</a></th>
						<td>
							<input type="text" maxlength="64" value="{(isset($last_name_ja))?$last_name_ja:''|h}" name="last_name_ja">
							<input type="text" maxlength="64" value="{(isset($first_name_ja))?$first_name_ja:''|h}" name="first_name_ja">	
							{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
							{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- first -last name romaji -->
					<tr >
						<th >{'label_admin_fullname_ro'|lang}</th>
						<td>	
							<input type="text" maxlength="64" value="{(isset($last_name))?$last_name:''|h}" name="last_name">
							<input type="text" maxlength="64" value="{(isset($first_name))?$first_name:''|h}" name="first_name">
							{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
							{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- gender -->
					<tr>
						<th>{'label_admin_gender'|lang}</th>
						<td>
							<select name="gender" >
								{foreach from=$gender_types item=gender_item}
									{if isset($gender) && $gender_item.id eq $gender}
										<option selected="selected" value="{$gender_item.id|h}">{{$gender_item['label']}|lang}</option>						
									{else}
										<option value="{$gender_item.id|h}">{{$gender_item['label']}|lang}</option>
									{/if}
								{/foreach}
							</select>
						</td>
					</tr>
					<!-- birthday -->	
					<tr>
						<th><label class=" control-label">{'label_admin_birthday'|lang}</label></th>
						<td>
							{$year = date(Y)}
							{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
							{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
							<select name="txt_year" id="txt_year" class="txt_width90">
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
							<select name="txt_day" id="txt_day" >
							</select>
							<input type="hidden" id="h_txt_day" value="1">
							<input type="hidden" name="birthday" id="txt_birthday" value="{(isset($birthday))?$birthday:''}">
							{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
						</td>
					 </tr>	
					<!-- organization -->
					<tr>
						<th>{'label_admin_organization'|lang}</th>
						<td>
							<input type="text" maxlength="255" name="organization" value="{(isset($organization))?$organization:''|h}">
							{form_error('organization','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- department -->
					<tr>
						<th>{'label_admin_department'|lang}</th>
						<td >
							<input type="text" maxlength="255" name="department" value="{(isset($department))?$department:''|h}">
							{form_error('department','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- position -->
					<tr>
						<th>{'label_admin_position'|lang}</th>
						<td >
							<input type="text" maxlength="128" name="position" value="{(isset($position))?$position:''|h}">
							{form_error('position','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- phone_number -->
					<tr>
						<th>{'label_admin_phone_number'|lang}</th>
						<td >
							<input type="text" maxlength="15" class="number" name="phone_number" value="{(isset($phone_number))?$phone_number:''|h}" >
							{form_error('phone_number','<p class="alert-error"> ※ ','</p>')}
						</td>
					</tr>
					<!-- info -->
					<tr>
						<th>{'label_admin_txtinfo'|lang}</th>
						<td >
							<textarea name="info">{(isset($info))?$info:''|h}</textarea>
						</td>
					</tr>
					<!-- language -->
					<tr>
						<th>{'label_language'|lang}</th>
						<td>
							<select name="language">
								{foreach from=$languages item=item}
									{if isset($admin_language) && $item.id eq $admin_language}
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
						<th>{'label_admin_status'|lang}</th>
						<td>
							<select name="status">
								{foreach from=$status_types item=status_item}
									{if isset($status) && $status_item.id eq $status}
										<option selected="selected" value="{$status_item.id|h}">{{$status_item['label']}|lang}</option>
									{else}
										<option value="{$status_item.id|h}">{{$status_item['label']}|lang}</option>
									{/if}
								{/foreach}
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="ct mtb20">
				<a href="" id="btn_confirm"><img src="{$fixed_base_url}assets-admin/img/chk.png" ></a>
			</div>
		</div> <!-- ctbox --> 
	{''|form_close}
	{literal}
	<script language="Javascript">
		$(document).ready(function(){
			var selected_year;
			var selected_month;
			var date =$('#txt_birthday').val();		
			get_day_in_month(selected_year,selected_month);
	
			$('.number').keypress(function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 || charCode == 45) {
					return true;
				}
				return false;
			});
			
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
		
			if(date != ''){
				set_date(date);
			}
			
			$('#btn_confirm').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				var year,month,day;
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
			get_day_in_month(year,month);
			$('select#txt_year option[value ='+year+']').attr('selected',true);
			$('select#txt_month option[value ='+month+']').attr('selected',true);
			$('select#txt_day option[value ='+day+']').attr('selected',true);
		}
	</script>
	{/literal}
{/if}
{/block}

{block name=stylesheet}
<style>
	.alert-error {
		color: red;
	}
	textarea {
		border: 1px solid #cccccc;
		border-radius: 10px;
		width: 433px;
		height: 166px;
		padding: 5px;
		resize: none;
	}
</style>
{/block}
