{* Extend our master_group template *}
{extends file="master_group.tpl"}

{* This block is defined in the master_group.tpl template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master_group.tpl template *}
{block name=body}

{if isset($group_user_status)}
	<div class="btn">
		{if ($group_user_status == $smarty.const.STATUS_GROUP_PUBLIC)}
			<a id = "join_group" href="#">{'label_join_group'|lang}</a>
		{else if ($group_user_status ==  $smarty.const.STATUS_GROUP_USER_PENDING_INVITATION)}
			<a id = "accept" href="#">{'label_invitee_accept'|lang}</a>
		{else if ($group_user_status ==  $smarty.const.STATUS_GROUP_USER_OWNER_APPROVE)}
			<p> {'label_waiting_owner_approval_font'|lang} </p>
		{/if}
	</div>
	
	<a href="" class="modalOpen" id="btn-dialog"></a>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox" style="text-align: center">
					<div class="ctinner_m">
						<p id="dialog_p"></p>
						<p class="ct m20">
							<a id="btn_yes" class="modalClose" style="cursor:pointer;">
								<img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png" alt="{'label_yes'|lang}">
							</a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
	
	<div id="processing" class="hide" >
		<div class="processing_mark"></div>
		<div class="show"><img src="{$fixed_base_url}assets/img/btn_progress.gif" ></div>
	</div>
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

{include file='master_post_processer.tpl'}

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
	<input type="hidden" id="hdn_invite_user" name="hdn_invite_user"/>
	{literal}
	<script type="text/javascript">
		$('#btn_edit_member').click(function() {
			$('#btn_edit_member').remove();
			$('.owner').remove();
			$('#button_edit_user').removeAttr("style");
			
			$('.chk').each(function(index,item){
				$(this).removeClass().addClass('ok');
				$('.ok a').removeClass();
				$('.ok a').removeAttr('href');
				$(this).children().attr('onclick','approve_user(' + $(this).attr('data-rel') +');');
			});
			$('.init').each(function(index,item){
				$(this).removeClass().addClass('del');
				$('.del a').removeClass();
				$('.del a').removeAttr('href');
				$(this).children().attr('onclick','delete_user(' + $(this).attr('data-rel') +');');
			});
		});
		
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
		}
		
		var user_approve="";
		function approve_user(user_id){
			if (user_approve == '') {
				user_approve = user_id;
			} else {
				user_approve = user_approve+',' + user_id;
			}
			$('#id'+ user_id).remove();
		}
		
		$('.btn_cancel').click(function(){
			location.reload();
		});
		
		$('.btn_finish').click(function(){
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
		});
	</script>
	{/literal}
	{/if} {* ($master_group_owned) *}
{/if} {* isset($group_user_status) *}
{/block}