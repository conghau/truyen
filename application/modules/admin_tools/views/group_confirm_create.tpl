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
		<div class="ttl"><h2>{'label_group_management'|lang}</h2></div>
		<table class="box_table_h" style="table-layout: fixed;">
			<tbody>
			<tr>
				<th>{'label_group_name'|lang}</th>
				<td class="text-word-break"> <p>{$group_info['name']|h}</p></td>
			</tr>
			<tr>
				<th>{'label_group_summary'|lang}</th>
				<td class="text-word-break"> <p>{$group_info['summary']|h}</p></td>
			</tr>
			<tr>
				<th>{'label_group_public_status'|lang}</th>
				<td>{$group_info['lbl_public_status']|lang}</td>
			</tr>
			<tr>
				<th>{'label_group_user'|lang}</th>
				<td id="group_users">
				{if isset($lst_user_add_info)}
					{foreach $lst_user_add_info as $group_user}
						<ul class="block_group">
							<li><p style="width:120px">
								{$group_user->last_name_ja}
								{$group_user->first_name_ja}
							</li>
							<li>
								{$group_user->organization}
								<br>
								{$group_user->position}
							</li>
						</ul>
					{/foreach}
					{/if}
				</td>
			</tr>
			<tr>
				<th>{'label_group_owner_id'|lang}</th>
				<td>{$group_info['group_owner']|h}</td>
			</tr>
			<tr>
				<th><p>{'label_status'|lang}</p></th>
				<td>{$group_info['lbl_status_type']|lang}</td>
			</tr>
			</tbody>
		</table>

		<div class="ct mtb10">
			<a href="" id="btnBack" name="btnBack" ><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="{'label_button_cancel'|lang}"></a>
			<a href="" id="btnCreate" name="btnCreate"><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="{'label_button_create'|lang}"></a>
			<a href="" class="modalOpen" id="btn-dialog"></a>
		</div>
{''|form_close}
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
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
		$(document).ready (function () {
			$("#btnBack").click(function () {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$("#form-main").attr("action", "{/literal}{$fixed_base_url}admin_tools/group/create{literal}");
				$("#form-main").attr("method", "post");
				$("#form-main").submit();
				return false;
			});
			$("#btnCreate").click(function (){
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/group/store{literal}";
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