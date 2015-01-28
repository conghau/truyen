{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{''|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_management'|lang} > {$group_info['group_id']|h} </h2></div>
		<table class="box_table_h" style="table-layout: fixed;">
			<tbody>
			<tr>
				<th>{'label_group_id'|lang}</th>
				<td class="text-word-break"> <p>{$group_info['group_id']|h}</p></td>
			</tr>
			<tr>
				<th>{'label_group_name'|lang}</th>
				<td class="text-word-break"> <p>{$group_info['name']|h}</p></td>
			</tr>
			<tr>
				<th>{'label_group_summary'|lang}</th>
				<td>{$group_info['summary']|h|nl2br}</td>
			</tr>
			<tr>
				<th>{'label_group_public_status'|lang}</th>
				<td>{$group_info['lbl_public_status']|lang}</td>
			</tr>
			<tr>
				<th>{'label_group_user'|lang}</th>
				<td>
					{if isset($lst_user_info)}
						<ul class="nglist">
							{foreach $lst_user_info as $group_user}
								<li class="clearfix">
									<p class="name">
										{$group_user->last_name_ja}
										{$group_user->first_name_ja}
									</p>
									<p class="at">
										{$group_user->organization}<br>
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
					{if isset($lst_user_add_info)}
						{'label_group_add_user'|lang}:
						<ul class="nglist">
							{foreach $lst_user_add_info as $group_user_add}
								<li class="clearfix">
									<p class="name">
										{$group_user_add->last_name_ja}
										{$group_user_add->first_name_ja}
									</p>
									<p class="at">
										{$group_user_add->organization}<br/>
										{$group_user_add->position}
									</p>
								</li>
							{/foreach}
						</ul>
					{/if}
				</td>
			</tr>
			<tr>
				<th>{'label_group_owner_id'|lang}</th>
				<td width="">
					<label id="group_owner" name="group_owner">{$group_info['group_owner']}</label>
				</td>
			</tr>
			<tr>
				<th>{'label_group_created_at'|lang}</th>
				<td width="">
					<label id="created_at" name="created_at">{$group_info['created_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}"}</label>
				</td>
			</tr>
			<tr>
				<th><p>{'label_status'|lang}</p></th>
				<td width="">{$group_info['lbl_status_type']|lang}</td>
			</tr>
			</tbody>
		</table>

		<div class="ct mtb10">
			<a href="" id="btnBack" ><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="{'label_button_cancel'|lang}"></a>
			<a href="" id="btnUpdate" ><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="value="{'label_button_update'|lang}"></a>
			<a href="" class="modalOpen" id="btn-dialog"></a>
		</div>
	</div>
{''|form_close}
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner">
					<p id="dialog_p"></p>
					<p class="ct m20"><a href="{$fixed_base_url}admin_tools/group"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
<div id="processing" class="hide" >
	<div class="processing_mark"></div>
	<div class="show"><img src="{$fixed_base_url}assets-admin/img/btn_progress.gif" ></div>
</div>
{/block}

{block name=stylesheet}
<style>
	.block_group{
		background-color:#F2EDF1; 
	}
	.block_group {
		margin-bottom: 10px;
	}
	.block_group li{
		padding: 10px;
		display: table-cell;
		vertical-align: middle;
	}
	td{
		word-wrap: break-word;
	}
</style>
{/block}

{block name=javascript}
{literal}
	<script language="Javascript">
		$(document).ready(function(){	
			$("#btnBack").click(function() {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$("#form-main").attr("action", "{/literal}{$fixed_base_url}admin_tools/group/{$group_info['group_id']}/edit{literal}");
				$("#form-main").submit();
				return false;
			});
			$("#btnUpdate").click(function(){
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/group/{$group_info['group_id']}/update{literal}";
				var cct = $("input[name=csrf_token]").val();
				$.ajax({
					type: 'POST',
					url: urlAction,
					data: {csrf_token: cct},
					dataType :'html',
					beforeSend: function() { $('#processing').removeClass('hide'); },
					success : function(data){
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#dialog_p').append(obj[0].message);
						$('#btn-dialog').click();
						$('#processing').addClass('hide');
					},
					error: function (data, status, e){
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					}
				});
				return false;
			});
		});
	</script>
{/literal}
{/block}