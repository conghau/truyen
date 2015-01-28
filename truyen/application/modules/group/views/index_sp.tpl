{* Extend our master template *}
{extends file="master_group_sp.tpl"}

{* This block is defined in the master_group_sp.tpl template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master_group_sp.tpl template *}
{block name=body}

{if isset($group_user_status)}
	<div class="btn">
		{if ($group_user_status == $smarty.const.STATUS_GROUP_PUBLIC)}
			<a class="com" id="join_group" href="#">{'label_join_group'|lang}</a>
		{else if ($group_user_status ==  $smarty.const.STATUS_GROUP_USER_PENDING_INVITATION)}
			<a class="com" id="accept" href="#">{'label_invitee_accept'|lang}</a>
		{else if ($group_user_status ==  $smarty.const.STATUS_GROUP_USER_OWNER_APPROVE)}
			<a class="com">{'label_waiting_owner_approval_font'|lang}</a>
		{/if}
	</div>
	
	<a href="" class="modalOpen" id="btn-dialog"></a>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal" style="width:300px">
				<div class="ctbox" style="text-align: center">
					<div class="ctinner_m">
						<p id="dialog_p"></p>
						<p class="ct m20">
							<a id="btn_yes" class="modalClose" style="cursor:pointer;" href="">
								<img src="{$fixed_base_url}assets_sp/img/{$language}/modal_hai.png">
							</a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
	<style>
		.btn a{
			width : 85%;
		}
	</style>
	{literal}
	<script type="text/javascript">
	var group_id = {/literal}{$group_id}{literal};
	
	$('#join_group').click(function(event){
		event.preventDefault();
		show_dialog(1);
	});
	
	$('#accept').click(function(event){
		event.preventDefault();
		show_dialog(3);
	});

	$("#btn_yes").click(function(){
		$('.modalBase').removeClass("ready shown");
		location.reload();
	});

	function show_dialog(type){
		var cct = $.cookie('csrf_token');
		if(cct == undefined) {
			window.location.replace("{/literal}{$fixed_base_url}login{literal}");
			return;
		}
		var urlAction = "{/literal}{$fixed_base_url}group/join_group{literal}";
		if (cct != "") {
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct, group_id: group_id, type: type},
				dataType :'html',
				success : function(data){
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);
					$('#dialog_p').html(obj);
					$('#btn-dialog').click();
					$('#btn_yes').show();
					return false;
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				}
			});
		}
	}
	</script>
	{/literal}
{else}

{include file='master_post_processer_sp.tpl'}

{if $post_offset == 0 && !empty($keyword)}
<div class="ctinner tround">
<div style="padding:30px 0;text-align: center;font-size:15px;">{'label_search_result_not_found'|lang}</div>
</div>
{else if (($post_offset == 0) && (!isset($msg_no_exist)))}
<script>
$(function() {
	create_post();
});
</script>
{/if}

	{if ($master_group_owned)}
		{literal}
		<script type="text/javascript">
		var master_group_member='';
		var master_group_member_invite = '';
		var flag_edit = false;
			$(document).on('click', '#back_menu_modal, #cancel_edit', function(){
				var string = "";
				if (flag_edit == false) {
					string = string + '{/literal} {foreach $master_group_member as $member}';
					string = string + '{if $member['status'] == $smarty.const.STATUS_GROUP_USER_OWNER_APPROVE}';
					string = string + '<li id="id{$member['id']}" class="chk">';
					string = string + '<a onclick="approve_user({$member['id']})" class="mic{$member['category_id']}"">';
					string = string + '<span>{'label_button_approve'|lang}</span>';
					string = string + '{else if $member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE}';
					string = string + '<li id="id{$member['id']}">';
					string = string + '<a onclick="delete_user({$member['id']})" class="mic{$member['category_id']}"">';
					string = string + '<span>X</span>';
					string = string + '{/if}';
					string = string + '<p class="ellipsisline">';
					string = string + '{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}';
					string = string + '</p>';
					string = string + '</a>';
					string = string + '</li>';
					string = string + '{/foreach}{literal}';

					string = string + '{/literal} {foreach $master_group_member_invite as $member}';
					string = string + '<li id="id{$member['id']}" >';
					string = string + '<a onclick="delete_user({$member['id']})" class="mic{$member['category_id']}">';
					string = string + '<span>X</span>';
					string = string + '<p class="ellipsisline">';
					string = string + '{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}';
					string = string + '</p>';
					string = string + '</a>';
					string = string + '</li>';
					string = string + '{/foreach}{literal}';
					string = string + '<div id="invite_member"> </div>'
					$('.member_edit').html(string);
				} else {
					get_member();
				}
				$('#hdn_invite_user').val('');
			});

			function get_member(){
				var string = "";
				var string_edit = '';

				string = string + '<li>';
				string = string + '<a href="{/literal}{$fixed_base_url}user/{$master_group_owner['id']}/personal_profile" class="mic{$master_group_owner['category_id']}{literal}">';
				string = string + '<p class="ellipsisline">';
				string = string + '{/literal}{$master_group_owner['name']}&nbsp;&nbsp;{$master_group_owner['organization']}&nbsp;&nbsp;{$master_group_owner['position']}{literal}';
				string = string + '</p>';
				string = string + '</a>';
				string = string + '</li>';
				for (var index = 0; index < master_group_member.length; ++index) {
					var row = master_group_member[index];
					string = string + '<li>';
					string = string + '<a href="{$fixed_base_url}user/'+row['id']+'/personal_profile" class="mic' + row['category_id'] + '">';
					string = string + '<p class="ellipsisline">';
					string = string + row['name'] + '  ' + row['organization']+ '  ' + row['position'];
					string = string + '</p>';
					string = string + '</a>';
					string = string + '</li>';
				
					if (row['status'] == '{/literal}{$smarty.const.STATUS_GROUP_USER_OWNER_APPROVE}{literal}'){
						string_edit = string_edit + '<li class="chk" id="id' + row['id'] +'">';
						string_edit = string_edit + '<a onclick="approve_user(' + row['id']+')" class="mic' + row['category_id'] + '">';
						string_edit = string_edit + "<span>{/literal}{'label_button_approve'|lang}{literal}</span>";
					} else if (row['status'] == '{/literal}{$smarty.const.STATUS_GROUP_USER_ENABLE}{literal}'){
						string_edit = string_edit + '<li id="id'+ row['id']+'">';
						string_edit = string_edit + '<a onclick="delete_user('+row['id']+')" class="mic' + row['category_id'] + '">';
						string_edit = string_edit + '<span>X</span>';
					}
					string_edit = string_edit + '<p class="ellipsisline">';
					string_edit = string_edit + row['name'] + '  ' + row['organization']+ '  ' + row['position'];
					string_edit = string_edit + '</p>';
					string_edit = string_edit + '</a>';
					string_edit = string_edit + '</li>';
				}

				for (index = 0; index < master_group_member_invite.length; ++index) {
					var row = master_group_member_invite[index];
					string = string + '<li>';
					string = string + '<a href="{/literal}{$fixed_base_url}{literal}user/'+row['id']+'/personal_profile" class="mic' + row['category_id'] + '">';
					string = string + '<p class="ellipsisline">';
					string = string + row['name'] + '  ' + row['organization']+ '  ' + row['position'];
					string = string + '</p>';
					string = string + '</a>';
					string = string + '</li>';

					string_edit = string_edit + '<li id = "id' + row['id'] + '">';
					string_edit = string_edit + '<a onclick="delete_user('+row['id']+')" class="mic' + row['category_id'] + '">';
					string_edit = string_edit + '<span>X</span>';
					string_edit = string_edit + '<p class="ellipsisline">';
					string_edit = string_edit + row['name'] + '  ' + row['organization']+ '  ' + row['position'];
					string_edit = string_edit + '</p>';
					string_edit = string_edit + '</a>';
					string_edit = string_edit + '</li>';
				}
				$('.member').html(string);
				string_edit = string_edit + '<div id="invite_member"> </div>'
				$('.member_edit').html(string_edit);
			}
			function get_invite_member() {
				var url_get = "{/literal}{$fixed_base_url}group/invite_list/{$group_id}{literal}";
				$.ajax({
					type: 'GET',
					url:url_get,
					dataType:"html",
					cache:false,
					success:function(data){
						$("#mailtomodal").html(data);
					}
				});
			}

			function cancel_invite(user_id){
				var arr = $('#hdn_invite_user').val().split(",");
				var index = arr.indexOf(user_id.toString());

				if (index != -1) {
					arr.splice(index, 1);
					$('#id'+ user_id).remove();
				}
				$('#hdn_invite_user').val(arr.toString());
			}
			
			var user_delete="";
			function delete_user(user_id){
				if (user_delete == '') {
					user_delete = user_id;
				} else {
					user_delete = user_delete+',' + user_id;
				}
				$('#id'+ user_id).remove();
				$('#id'+ user_id).remove();
			}

			var user_approve="";
			function approve_user(user_id){
				if (user_approve == '') {
					user_approve = user_id;
				} else {
					user_approve = user_approve+',' + user_id;
				}
				$('#id'+ user_id).remove();
				$('#id'+ user_id).remove();
			}

			function update_group(){
				var cct = $.cookie('csrf_token');
				if(cct == undefined) {
					window.location.replace(fixed_base_url+"login");
					return;
				}
				
				var urlAction = fixed_base_url+"group/update_user";
				var user_insert = $('#hdn_invite_user').val();
				if (user_approve != "" || user_delete != "" || user_insert != "") {
					$.ajax({
						type: 'POST',
						url: urlAction,
						data: {csrf_token: cct, group_id: group_id, user_approve: user_approve, user_delete: user_delete, user_insert: user_insert},
						dataType :'html',
						beforeSend: function() { $('#processing').removeClass('hide'); },
						success : function(data){
							var obj = JSON && JSON.parse(data) || $.parseJSON(data);
							var string = '';
							if (obj.flag == 1) {
								if (obj.name_insert != '') {
									string = string + obj.name_insert;
									string = string + '<br/>';
								}
								if (obj.name_approve != '') {
									string = string + obj.name_approve;
									string = string + '<br/>';
								}
								if (obj.name_delete != '') {
									string = string + obj.name_delete;
									string = string + '<br/>';
								}
							} else {
								string = string + obj.message;
							}
							$("#dialog_p").html(string);
							$('#btn-dialog').click();
							$('#btn_yes').show();
							$('#btn_yes').attr('data-rel', group_id);
							$('#btn_no').hide();
							$('#processing').addClass('hide');
							return false;
						},
						error: function (data, status, e){
							window.location.replace("{/literal}{$fixed_base_url}login{literal}");
						}
					});
					user_approve = '';
					user_delete = '';
					$('#hdn_invite_user').val('');
				} else if(user_approve == "" && user_delete == "" && user_insert == ""){
					if ($(this).attr('tag') == 1) {
						 $('.modalBase').removeClass("ready shown");
					} else {
						location.reload();
					}
				}
			}
		</script>
		{/literal}	
	{/if} {* ($master_group_owned) *}
{/if} {* isset($group_user_status) *}
{/block}
