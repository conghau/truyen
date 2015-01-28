{extends file='master_group_sp.tpl'}
{block name=title}
	{$title}
{/block}
{block name=body}
	{$fixed_base_url|cat:'group/'|cat:$group_id|h|cat:'/confirm_edit'|form_open:'id=form-main'}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_edit_group'|lang}</h2>
		<div class="static_wrap">
			<table class="inq">
				<tr>
					<th>{'label_group_name_edit'|lang}<font color="red"> ※</font></th>
				</tr>
				<tr>
					<td>
						<input type="text" id="name" name="name" value="{$name|h}""
						maxlength="255" size="30" style="margin-bottom:2px"/>
						{form_error('name','<div class="alert-error"> ※ ','</div>')}
					</td>
				</tr>
				<tr>
					<th>{'label_group_summary'|lang}</th>
				</tr>
				<tr>
					<td>
						<textarea id="summary" name="summary">{$summary|h}</textarea>
					</td>
				</tr>
				<tr>
					<th>{'label_group_public_status_edit'|lang}</th>
				</tr>
				<tr>
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
				</tr>
				<tr>
					<td>
						{if isset($error) && $error|@count > 0}
							<div style="color:red;">※ {$error}</div>
						{/if}
						<ul class="nglist">
							{if isset($lst_user_info)}
								{foreach $lst_user_info as $group_user}
									<li class="clearfix">
										<p class="name">
											{$group_user->name}
										</p>
										<p class="at">
											{$group_user->organization}
										</p>
										<p class="atx">
											{if $group_user->status == 3}
												{'label_waiting_invitee_accept_font'|lang}
											{else if $group_user->status == 4}
												{'label_waiting_owner_approval_owner'|lang}
											{/if}
										</p>
									</li>
								{/foreach}
							{/if}
						</ul>
						<ul class="nglist" id="group_user_invite">
							{if isset($lst_user_add_info)}
							{foreach $lst_user_add_info as $group_user_add}
								<li class="clearfix">
									<p class="name">
										{$group_user_add->name}
									</p>
									<p class="at">
										{$group_user_add->organization}
									</p>
									<p class="atx">
									</p>
								</li>
							{/foreach}
						{/if}
						</ul>
						<div class="btn2">
							<a href="#mailtomodal" class="mailtomodal r2l" onclick="get_member()">{'label_group_add_invitees'|lang}</a>
							<input type="hidden" id="hdn_invite_user" name="hdn_invite_user" value="{$hdn_invite_user}"/>
						<div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="btn">
			<a id="btnEdit"> {'label_button_confirm'|lang} </a>
	</div>

	<div class="btn">
		<a id="btnDelete" style="background-color: #BE5050"> {'label_button_delete'|lang} </a>
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
							<a id="btn_no" class="modalClose">
								<img src="{$fixed_base_url}assets/img/{$language}/modal_iie.png" alt="{'label_no'|lang}">
							</a>
							<a id="btn_yes" class="modalClose">
								<img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png" alt="{'label_yes'|lang}">
							</a>
							<a id="btn_ok" href="{$fixed_base_url}user">
								<img src="{$fixed_base_url}assets/img/{$language}/ok.png">
							</a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
	{''|form_close}
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		$('#btnEdit').click(function(){
			$('#form-main').submit();
		});

		$( "#btnDelete" ).click(function() {
			$('#dialog_p').html("{/literal}{'label_group_msg_delete_confirm'|lang}{literal}");
			$('#btn-dialog').click();
			$('#btn_ok').hide();
		});

		$('#btn_yes').click(function(){
			var urlAction = "{/literal}{$fixed_base_url}group/{$group_id}/delete{literal}";
			var cct = $("input[name=csrf_token]").val();
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct},
				dataType :'html',
				success : function(data){
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);
					$("#dialog_p").html(obj);
					$('#btn-dialog').click();
					$('#btn_ok').show();
					$('#btn_yes').hide();
					$('#btn_no').hide();
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				}
			});
		});
	});

	function get_member() {
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
</script>
{/literal}
{/block}