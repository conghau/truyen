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
	{$fixed_base_url|cat:'admin_tools/admin/'|cat:$admin_info_edit['id']|cat:'/confirm_edit'|form_open:'role=form class=form-horizontal id=form_main autocomplete=off'}
	<div class="ctbox">
			<div class="ttl"><h2>{'label_admin_management'|lang} > {$admin_info_edit['id']}</h2></div>
			<table id="box_table" class="box_table_h">
			<tbody>
				<tr>
					<th>{'label_admin_id'|lang}<a style="color:red">※</a></th>
					<td><label name="id" value="{set_value(id,$admin_info_edit['id'])}">{$admin_info_edit['id']|h}</label> </td>
					<input type="hidden" name="id" value="{$admin_info_edit['id']|h}">
				</tr>
				<!-- email -->
				<tr>
					<th>{'label_admin_email'|lang}<a style="color:red">※</a></th>
					<td>
						<input type="email" maxlength="128" name="email" value="{set_value(email,$admin_info_edit['email'])}">
						{form_error('email','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- Loginid -->
				<tr> 
					<th>{'label_admin_login_id'|lang}<a style="color:red">※</a></th>
					<td>
						 <input type="text" maxlength="64" value="{set_value(login_id,$admin_info_edit['login_id'])}" name="login_id" autocomplete ="off">
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
						<input type="password" name="password" id="password" autocomplete ="off" maxlength="255">
						{form_error('password','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- re-password -->
				<tr>
					<th>
						<label>{'label_admin_re_password'|lang}</label><a style="color:red">※</a>
					</th>
					<td>
						<input type="password" name="re_password" id="re_password" maxlength="255">
						{form_error('re_password','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- first - last name ja -->
				<tr>
					<th>{'label_admin_fullname'|lang}<a style="color:red">※</a></th>
					<td>
						<input type="text" maxlength="64" value="{set_value(last_name_ja,$admin_info_edit['last_name_ja'])}" name="last_name_ja">
						<input type="text" maxlength="64" value="{set_value(first_name_ja,$admin_info_edit['first_name_ja'])}" name="first_name_ja">
						{form_error('last_name_ja','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name_ja','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- first- last name romaji -->
				<tr>
					<th>{'label_admin_fullname_ro'|lang}</th>
					<td>
						<input type="text" maxlength="64" value="{set_value(last_name,$admin_info_edit['last_name'])}" name="last_name">
						<input type="text" maxlength="64" value="{set_value(first_name,$admin_info_edit['first_name'])}" name="first_name">
						{form_error('last_name','<p class="alert-error"> ※ ','</p>')}
						{form_error('first_name','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- gender -->
				<tr>
					<th>{'label_admin_gender'|lang}</th>
					<td>
						<select name="gender">
							{foreach from=$gender_types item=gender_item}
								{if isset($admin_info_edit['gender']) && $gender_item.id eq $admin_info_edit['gender']}
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
					<th><label for="label_birthday" class=" control-label">{'label_admin_birthday'|lang}</label></th>	
					<td>
						{$year = date(Y)}
						{$default_year = $smarty.const.DEFAULT_YEAR_CBX}
						{$min_year_cbx = $smarty.const.MIN_YEAR_CBX}
						<select name="txt_year" id="txt_year" class="txt_width90">
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
						<input type="hidden" name="birthday" id="txt_birthday" value="{set_value(birthday,$admin_info_edit['birthday'])}">
						{form_error('birthday','<p class="alert-error"> ※ ','</p>')}
					</td>
				 </tr>	
				<!-- organization -->
				<tr>
					<th>{'label_admin_organization'|lang}</th>
					<td>
						<input type="text" maxlength="255" name="organization" value="{set_value(organization,$admin_info_edit['organization'])}">
					</td>
				</tr>
				<!-- department -->
				<tr>
					<th>{'label_admin_department'|lang}</th>
					<td>
						<input type="text" maxlength="255" name="department" value="{set_value(department,$admin_info_edit['department'])}">
						{form_error('department','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- position -->
				<tr>
					<th>{'label_admin_position'|lang}</th>
					<td>
						<input type="text" maxlength="128" name="position" value="{set_value(position,$admin_info_edit['position'])}">
						{form_error('position','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- phone_number -->
				<tr>
					<th>{'label_admin_phone_number'|lang}</th>
					<td>
						<input type="text" maxlength="15" class="number" name="phone_number" value="{set_value(phone_number,$admin_info_edit['phone_number'])}">
						{form_error('phone_number','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<!-- info -->
				<tr>
					<th>{'label_admin_txtinfo'|lang}</th>
					<td>
						<textarea name="info">{set_value(info,$admin_info_edit['info'])}</textarea>
					</td>
				</tr>
				<!-- laguage -->
				<tr>
					<th>{'label_language'|lang}</th>
					<td>
						<select name="language">
							{foreach from=$languages item=item}
								{if isset($admin_info_edit['language']) && $item.id eq $admin_info_edit['language']}
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
					<th>{'label_admin_status'|lang}</th>
					<td>
						<select name="status">
							{foreach from=$status_types item=status_item}
								{if isset($admin_info_edit['status']) && $status_item.id eq $admin_info_edit['status']}
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
			{if $current_id_login neq $admin_info_edit['id']}
				<a href="" id="btn-delete"><img src="{$fixed_base_url}assets-admin/img/delete.png"></a>
			{/if}
		</div>
		
		<a href="" class="modalOpen" id="btn-dialog"></a>
	</div> <!-- ctbox -->
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
							<a id="btn_ok" href="{$fixed_base_url}admin_tools/admin"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/if}
{/block}
{block name=stylesheet}
<style>
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
{block name=javascript}
{literal}
<script language="Javascript">
	$(document).ready(function(){
		var selected_year;
		var selected_month;
		var date ="{/literal}{$admin_info_edit['birthday']}{literal}";
	
		if(date != ''){
			set_date(date);
		} else {
			get_day_in_month(selected_year,selected_month);
		}

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
	
		$( "#btn-delete" ).click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
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
		var urlAction = "{/literal}{$fixed_base_url}admin_tools/admin/{$admin_info_edit['id']}/delete{literal}";
		var cct = $("input[name=csrf_token]").val();
		$.ajax({
			type: 'POST',
			url: urlAction,
			data: {csrf_token: cct},
			dataType :'html',
			success : function(data){
				var obj = JSON && JSON.parse(data) || $.parseJSON(data);
				$('#dialog_p').empty();
				$('#dialog_p').append(obj.message);
				$('#btn-dialog').click();
			},
			error: function (data, status, e){
				if (data.status == '417') {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/admin{literal}");
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
</script>
{/literal}

{/block}