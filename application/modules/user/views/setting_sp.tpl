
<div class="static_wrap">
{$fixed_base_url|cat:'setting/save'|form_open:'class=form-horizontal id=form-main-setting method=POST'}
		<table class="inq">
			<tr>
				<th>{'label_setting_language'|lang}</th>
			</tr>
			<tr>
				<td>
					<select name="language" id="language" class="check_change">
						{foreach from=$languages item=item}
							{if isset($user_info_edit['language']) && $item.id eq $user_info_edit['language']}
								<option selected="selected" id="language_{$item['id']}" value="{$item['id']|h}">{$item['label']|lang}</option>
							{else}
								<option value="{$item['id']|h}">{$item['label']|lang}</option>
							{/if}
						{/foreach}
						<input type="hidden" value="$user_info_edit['language']" id="h_language">
					</select>
				</td>
			</tr>
			<tr>
				<th>{'label_setting_sent_mail'|lang}<br/><span>{'label_setting_sent_mail_body'|lang}</span></th>
			</tr>
			<tr>
				<td>
					<ul class="nglist">
						{foreach $data_setting['black_list_user'] as $user}
							<li class="clearfix"><p class="name">{$user['name']}</p>
								<p class="at">{$user['organization']|cat:' '|cat:$user['position']}</p>
								<p class="ngx delete_user" id="black_user_{$user['id']}" data-rel="{$user['id']}" onclick="delete_user({$user['id']})">X</p>
							</li>
						{/foreach}
					</ul>
					<div class="btn "><a href="#" class="mailtomodal r2l" onclick="get_dest_create()" >{'label_btn_add'|lang}</a></div>
				</td>
			</tr>
			<tr>
				<th>{'label_setting_join_group'|lang}</th>
			</tr>
			<tr>
				<td>
					<ul class="group_list">
						{foreach $data_setting['list_group'] as $group }
							<li class="clearfix"><p class="name">{$group->name}</p><p class="ngx delete_group" data-rel="{$group->id}">X</p></li>
						{/foreach}
					</ul>
				</td>
			</tr>
			<tr>
				<th>{'label_setting_mail_announcement'|lang}</th>
			</tr>
			<tr>
				<td>
					<table width="100%" cellspacing="0" cellpadding="0" class="">
					{foreach from=$config_setting item=item}
					<tr>
						<td width="80%">{$item['label']|lang}</td>
						<td class="alignR">
						{if isset($data_setting['list_config'][$item['id']]) && $data_setting['list_config'][$item['id']] eq $data_setting['check']}
							<td class="alignR"><input type="checkbox" name="setting_mail[]" class="setting_mail margin_right" id="mail_{$item['id']}" value="{$item['id']}" checked></td>
							<input type="hidden" value="1" id="h_mail_{$item['id']}">
						{else}
							<td class="alignR"><input type="checkbox" name="setting_mail[]" class="setting_mail margin_right" id="mail_{$item['id']}" value="{$item['id']}"></td>
							<input type="hidden" value="0" id="h_mail_{$item['id']}">
						{/if}
					</tr>
					{/foreach}
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="user_list" id="txt_user_list" value="">
		<input type="hidden" name="group_list" id="txt_group_list" value="">
		<input type="hidden" name="hdn_dest_user" id="hdn_dest_user"  value="">
		<input type="hidden" name="flag" id="flag" value="">
{''|form_close}
<style>
	.delete_user{
		cursor:pointer;
	}
	.delete_group{
		cursor:pointer;
	}
	.hide {
		display : none;
	}
	.margin_right {
		margin-right: 0px;
	}
	table{
		table-layout: fixed;
	}
	table td {
		word-wrap:break-word;
	}
</style>
<script language="Javascript">
	var fixed_base_url = "{$fixed_base_url}";
</script>
<script src="{$fixed_base_url}assets/js/post.js"></script>
{literal}
<script language="Javascript">
		var userlist = [];
		var grouplist = [];
		var users_temp = [];
		var list_language = [];
		var list_config_mail = [];
		var ENABLE = false;
		var flagUser = false;
		var flagMail = false;
		var isSAVEclick = false;
		var isCANCELclick = false;
		var ENABLE_LANG = false;
		$(document).ready(function(){
			init_list_user();
			init_list_group();
			init_setting_mail();
			$(".btn_save_setting").click(function(){
				isSAVEclick = true;
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
					return;
				}
				if ($(this).hasClass("disabled")) {
					return false;
				}
				$("#open_withdraw_dialog").hide();
				$("#logout-confirm-dialog").hide();
				$('#btn_alert_yes').addClass('hide');
				$('#btn_yes').removeClass('hide');
				$('#btn_no').removeClass('hide');
				$('#dialog_p').html('');
				var msg_save_confirm = '{/literal}{$data_setting["msg_save_confirm"]}{literal}';
				$('#dialog_p').append(msg_save_confirm);
				$(".modalOpen").click();
			});
			
			setInterval(function() {
				if($('#flag').val() != 0) {
					getUserInfo();
				}
				$('#flag').val(0);
			}, 1000);
			
			$('.delete_group').click(function(){
				var id = parseInt($(this).attr('data-rel'));
				if(id) {
					remove_group(id);	
				}
				$(this).parent().remove();
				enable_btn_save();
				ENABLE = true;
			});

			$('.check_change').change(function(){
				if(ENABLE == false ) {
					var id = $('#h_language').val();
					if ($(this).val() == id) {
						var index = list_language.indexOf(id);
						
						if (index > -1) {
							list_language.splice(index, 1);
						}
						if(list_language.length == 0){
							ENABLE_LANG = false;
							disable_btn_save();
						}
					} else {
						if (!ENABLE_LANG) {
							ENABLE_LANG = true;
							enable_btn_save();
						}
						var index = list_language.indexOf(id);
						if (index < 0) {
							list_language.push(id);
						}
					}
				}
			});
			
			$('.setting_mail').click(function(){
				if(ENABLE == false ) {
					var id = $(this).attr('id');
						if($(this).is(':checked') == $('#h_'+id).val()) {
							var index = list_config_mail.indexOf(id);
							if (index > -1) {
								list_config_mail.splice(index, 1);
							}
							if(list_config_mail.length == 0){
								flagMail = false;
								disable_btn_save();
							}
						} else {
							if (!flagMail) {
								flagMail = true;
								enable_btn_save();
							}
							var index = list_config_mail.indexOf(id);
							if (index < 0) {
								list_config_mail.push(id);
							}
					}
				}
			});
			
		});
			function save() {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
					return;
				}
				var urlAction = "{/literal}{$fixed_base_url}setting/save{literal}";
				get_list_user_update();
				$('#txt_user_list').val(userlist);
				$('#txt_group_list').val(grouplist);
				var form = $("#form-main-setting").serializeArray();
				$.ajax({
					type: 'POST',
					url: urlAction,
					data: form,
					dataType :'html',
					beforeSend: function() { $('#processing').removeClass('hide'); },
					success : function(data){
						$('#processing').addClass('hide');
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#btn_no').addClass('hide');
						$('#btn_save_yes').addClass('hide');
						$('#btn_alert_yes').removeClass('hide');
						$('#dialog_p').empty().append(obj.message);
						$(".modalOpen").click();
					},
					error: function (data, status, e){
						window.location.replace("{/literal}{$fixed_base_url}login{literal}");
					}
				});
			}
		
		function get_dest_create() {
			$.get("{/literal}{$fixed_base_url}post/dest_list/{$smarty.const.MODAL_HIDE_SETTING}{literal}", function (data) {
				$("#mailtomodal").html(data);
				$("#mailtomodal").show();
			});
		}
		
		function remove_display_user() {
			for(var i = 0; i < users_temp.length;i++) {
					$("#black_user_" + parseInt(users_temp[i])).parent().remove();
			}
			users_temp.length=0;
			enable_btn_save();
		}
		
		function getUserInfo(){
			var urlAction = "{/literal}{$fixed_base_url}setting/get_user_by_id{literal}";
			remove_display_user();
			$.ajax({
				type: 'GET',
				url: urlAction,
				data: {user_add: $('#hdn_dest_user').val()},
				dataType :'html',
				beforeSend: function() { $('#processing').removeClass('hide'); },
				success : function(data){
					$('#processing').addClass('hide');
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);
					for (var i=0 ; i< obj.length ; i++) {
						var index = userlist.indexOf(obj[i].id);
						if (index == -1) {
							add_user(obj[i].id);
							var string ='<li class="clearfix"><p class="name">'+obj[i].name+
							'</p><p class="at">'+obj[i].organization+' '+obj[i].position+'</p><p class="ngx delete_user" onclick="delete_user('+obj[i].id+')" id="black_user_'
							+parseInt(obj[i].id)+'" data-rel="'+obj[i].id+'">X</p></li>';
							$('.nglist').append(string);
						}
					}
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				}
			});
		}

		function get_list_user_update() {
			for(var i=0 ; i < users_temp.length ; i++) {
				userlist.push(users_temp[i]);
			}
		}
		
		function init_list_user() {
				var user = '{/literal}{$data_setting["black_list_user_id"]}{literal}';
				var obj = JSON && JSON.parse(user) || $.parseJSON(user);
				for(var i=0 ; i < obj.length ; i++) {
					userlist.push(obj[i].id);
				}
		}

		function init_list_group() {
			var group = '{/literal}{$data_setting["list_group_id"]}{literal}';
			var obj = JSON && JSON.parse(group) || $.parseJSON(group);
			for(var i=0 ; i < obj.length ; i++) {
				grouplist.push(obj[i].id);
			}
		}
		
		function add_user(user_id) {
			users_temp.push(user_id);
			flagUser=true;
		}	

		function remove_user(user_id) {
			var index = userlist.indexOf(user_id);
			if(index != -1){
				userlist.splice(index,1);
				enable_btn_save();
				ENABLE = true;
				return ;
			}
			var index = users_temp.indexOf(user_id);
			if(index != -1){
				users_temp.splice(index,1);
			}
			
			if (check_change_list_user()==true && ENABLE==false) {
				disable_btn_save();
			}
			$('#hdn_dest_user').val(users_temp);
		}

		function check_change_list_user() {
			var list_user_temp =[];
			for(var i=0 ;i<users_temp.length;i++ ){
				list_user_temp.push(users_temp[i]);
			}
			for(var i=0 ;i<userlist.length;i++ ){
				list_user_temp.push(userlist[i]);
			}
			return compare_array(list_user_temp,userlist);
		}

		function compare_array(arr1,arr2) {
			flagUser = true;
			if(arr1.length != arr2.length) {
				return false;
			}
			for(var i=0;i<arr1.length;i++) {
				var index = arr2.indexOf(arr1[i]);
				if(index == -1) {
					return false;
				}
			}
			flagUser = false;
			return true;
		}
		
		function remove_group(group_id) {
			var index = grouplist.indexOf(group_id);
			if(index != -1){
				grouplist.splice(index,1);
			}
		}	

		function enable_btn_save() {
			$('.btn_save_setting').removeClass('disabled');
		}
		
		function disable_btn_save() {
			if(ENABLE_LANG == false && flagMail==false && flagUser ==false && ENABLE==false){
				$('.btn_save_setting').addClass('disabled');
			}
		}

		function delete_user(id) {
				if(id) {
					remove_user(id);
				}
				$('#black_user_'+id).parent().remove();
		}

		function init_setting_mail() {
			var mails = '{/literal}{$data_setting["check_enable_mail"]}{literal}';
			var obj = JSON && JSON.parse(mails) || $.parseJSON(mails);
			for(var i= 0 ; i < obj.length ; i++) {
				$("#mail_"+obj[i]).attr("disabled", "disabled");
				$("#mail_"+obj[i]).attr("checked", "checked");
			}
		}

		function deleteUser() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				return;
			}
			var user_Id =  '{/literal}{$data_setting["user_id"]}{literal}';
			var urlAction = "{/literal}{$fixed_base_url}setting/delete_user{literal}";
			$.ajax({
				type: 'GET',
				url: urlAction,
				data: {userId: user_Id},
				dataType :'html',
				success : function(data){
					$("#logout-confirm-dialog").hide();
					window.location.replace("{/literal}{$fixed_base_url}{literal}setting/withdraw_finish?lang={/literal}{$language}{literal}");
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				}
			});
		}
		
	</script>
{/literal}
</div><!--/ctwrap-->
