{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/group/'|cat:$group_id|cat:'/confirm_edit'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_management'|lang} > {$group_id} </h2></div>
		<div class="ct mtb10">
			<a href="{$fixed_base_url}admin_tools/post/{$group_id}/group">
				<img src="{$fixed_base_url}assets-admin/img/thread_group.png" alt="{'label_group_list_thread'|lang}">
			</a>
		</div>
		<table class="box_table_h">
			<input type="hidden" value="" id="group_id" name="group_id"/>
			<tbody>
			<tr>
				<th>{'label_group_id'|lang}<font color="red"> ※</font></th>
				<td width="" style="padding:5px;">
					{$group_id|h}
				</td>
			</tr>
			<tr>
				<th>{'label_group_name_edit'|lang}<font color="red"> ※</font></th>
				<td>
					<input type="text" id="name" name="name" value="{$name|h}""
					maxlength="255" size="30"/>
					{form_error('name','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_group_summary'|lang}</th>
				<td >
					<textarea style="max-width:423px; min-width:423px; max-height:166px; min-height:166px; resize: none;" 
					id="summary" name="summary">{$summary|h}</textarea>
				</td>
			</tr>
			<tr>
				<th>{'label_group_public_status_edit'|lang}</th>
				<td>
					<select id="public_status" name="public_status">
						{foreach from=$lst_public_status item=statusPublic}
							{if isset($public_status) && $public_status eq $statusPublic.id}
								<option selected="selected" value="{$statusPublic.id}">{$statusPublic.label|lang}</option>
							{else}
								<option value="{$statusPublic.id}">{$statusPublic.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<th>{'label_group_user'|lang}</th>
				<td id="group_users">
					{if isset($lst_user_info)}
						<ul class="nglist">
							{foreach $lst_user_info as $group_user}
								<li class="clearfix">
									<p class="name">
										{$group_user->last_name_ja}
										{$group_user->first_name_ja}
									</p>
									<p class="at">
										{$group_user->organization}<br/>
										{$group_user->position}
									</p>
									<p class="atx">
										{if $group_user->status == 3}
											{'label_waiting_invitee_accept'|lang}
										{else if $group_user->status == 4}
											{'label_waiting_owner_approval'|lang}
										{/if}
									</p>
								</li>
							{/foreach}
						</ul>
					{/if}
					<p>
					{'label_group_add_user'|lang}:
					</p>
					<textarea rows="5" cols="30" id="group_users_add" name="group_users_add">{$group_users_add}</textarea>
					{if isset($error) && $error|@count > 0}
						<div style="color:red;">※ {$error}</div>
					{/if}
				</td>
			</tr>
			<tr>
				<th>{'label_group_owner_id'|lang}</th>
				<td >
					<div style="width:100%; height:20px">
						<div style="float:left">
							<label id="group_owner" name="group_owner">{$group_owner}</label>
							<input type="hidden" id="group_owner" name="group_owner" value="{$group_owner}">
						</div>
						<div style="float:right">
							{if isset($group_owner) && $group_owner != ''}
							<a href="{$fixed_base_url}admin_tools/user/{$group_owner}/edit">
								<img src="{$fixed_base_url}assets-admin/img/user_detail.png" alt="{'label_user_detail'|lang}">
							</a>
							{/if}
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>{'label_group_created_at'|lang}</th>
				<td>
					<label>{$created_at|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</label>
					<input type="hidden" id="created_at" name="created_at" value="{$created_at}">
				</td>
			</tr>
			<tr>
				<th>{'label_status'|lang}</th>
				<td >
					<select id="status_type" name="status_type" style="min-width:153px;">
						{foreach from=$status_types item=status}
							{if isset($status_type) && $status_type eq $status.id}
								<option selected="selected" value="{$status.id}">{$status.label|lang}</option>
							{else}
								<option value="{$status.id}">{$status.label|lang}</option>
							{/if}
						{/foreach}
					</select>
				</td>
			</tr>
			</tbody>
		</table>

		<div class="ct mtb10">
			<a id="btnEdit" href="" ><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
			<a id="btnDelete" href=""><img src="{$fixed_base_url}assets-admin/img/delete.png" alt="{'label_button_delete'|lang}"></a>
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
						<p id="dialog_p">{'label_group_msg_delete_confirm'|lang}</p>
						<p class="ct m20">
							<a id="btn_no" class="modalClose">
								<img src="{$fixed_base_url}assets-admin/img/modal_iie.png" alt="{'label_no'|lang}">
							</a>
							<a id="btn_yes" class="modalClose">
								<img src="{$fixed_base_url}assets-admin/img/modal_hai.png" alt="{'label_yes'|lang}">
							</a>
							<a id="btn_ok" href="{$fixed_base_url}admin_tools/group">
								<img src="{$fixed_base_url}assets-admin/img/ok.png">
							</a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/block}

{block name=stylesheet}

<style>
	a{
		cursor:pointer;
	}
	.alert-error {
		color:red;
	}
	textarea {
		resize:none;
	}
</style>
{/block}

{block name=javascript}
{literal}
<script language="Javascript">
	$(document).ready(function() {
		$('#group_id').val('{/literal}{$group_id}{literal}');
		$('#btnEdit').click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return;
			}
			$('#form-main').submit();
			return false;
		});

		$( "#btnDelete" ).click(function() {
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
			delete_group();
			return false;
		});
	});

	function delete_group() {
		var urlAction = "{/literal}{$fixed_base_url}admin_tools/group/{$group_id}/delete{literal}";
		var cct = $("input[name=csrf_token]").val();
		$.ajax({
			type: 'POST',
			url: urlAction,
			data: {csrf_token: cct},
			dataType :'html',
			success : function(data){
				var obj = JSON && JSON.parse(data) || $.parseJSON(data);
				$('#dialog_p').empty();
				$('#dialog_p').append(obj[0].message);
				$('#btn-dialog').click();
			},
			error: function (data, status, e){
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
			}
		});
	}
</script>
{/literal}
{/block}