{extends file='master_group_sp.tpl'}
{block name=title}
	{$title}
{/block}
{block name=body}
{''|form_open:'id=form-main'}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_edit_group_confirm'|lang}</h2>
		<div class="static_wrap">
			<table class="inq" style="table-layout: fixed;">
				<tr>
					<th>{'label_group_name_edit'|lang}</th>
				</tr>
				<tr>
					<td><p>{$group_info['name']|h}</p></td>
				</tr>
				<tr>
					<th>{'label_group_summary'|lang}</th>
				</tr>
				<tr>
					<td><p>{$group_info['summary']|h|nl2br}</p></td>
				</tr>
				<tr>
					<th> {'label_group_public_status_edit'|lang} </th>
				</tr>
				<tr>
					<td>{$lst_public_status[$group_info['public_status']].label|lang}</td>
				</tr>
				<tr>
					<th>{'label_group_user'|lang}</th>
				</tr>
				<tr>
					<td>
						<ul class="nglist">
							{if isset($lst_user_info)}
								{foreach $lst_user_info as $group_user}
									<li class="clearfix">
										<p class="name">
											{$group_user->name}
										</p>
										<p class="at">
											{$group_user->organization}
											{$group_user->position}
										</p>
									</li>
								{/foreach}
							{/if}
							{if isset($lst_user_add_info)}
								{foreach $lst_user_add_info as $group_user_add}
									<li class="clearfix">
										<p class="name">
											{$group_user_add->name}
										</p>
										<p class="at">
											{$group_user_add->organization}
											{$group_user_add->position}
										</p>
									</li>
								{/foreach}
							{/if}
						</ul>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="btn">
		<a id="btnUpdate"> {'label_button_finish'|lang} </a>
	</div>
	<a href="" class="modalOpen" id="btn-dialog"></a>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ttl"><h2>{'label_button_finish'|lang}</h2></div>
					<div class="ctinner">
						<p id="dialog_p"></p>
						<p class="ct m20">
							<a href="{$fixed_base_url}group/{$group_info['group_id']}">
								<img src="{$fixed_base_url}assets/img/{$language}/ok.png">
							</a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
	<div id="processing" class="hide" >
		<div class="processing_mark"></div>
		<div class="show"><img src="{$fixed_base_url}assets/img/ajax_processing.gif" width="80px" height="80px"></div>
	</div>
<style>
	td{
		word-wrap: break-word;
	}
</style>
	{''|form_close}
{literal}
<script language="Javascript">
$(document).ready(function(){
	$("#btnUpdate").click(function(){
		var urlAction = "{/literal}{$fixed_base_url}group/{$group_info['group_id']}/update{literal}";
		var cct = $("input[name=csrf_token]").val();
		$.ajax({
			type: 'POST',
			url: urlAction,
			data: {csrf_token: cct},
			dataType :'html',
			beforeSend: function() { $('#processing').removeClass('hide'); },
			success : function(data){
				var obj = JSON && JSON.parse(data) || $.parseJSON(data);
				$('#dialog_p').append(obj);
				$('#btn-dialog').click();
				$('#processing').addClass('hide');
			},
			error: function (data, status, e){
				window.location.replace("{/literal}{$fixed_base_url}login{literal}");
			}
		});
		return false;
	});
});
</script>
{/literal}
{/block}