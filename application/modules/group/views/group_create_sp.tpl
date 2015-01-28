{extends file='master_sp.tpl'}
{block name=title}
	{$title}
{/block}
{block name=body}
	{$fixed_base_url|cat:'group/confirm_create'|form_open:'id=form-main'}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_new_group'|lang}</h2>
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
								{if isset($public_status)}
									{if $public_status eq $statusPublic.id}
										<option selected="selected" value="{$statusPublic.id}">{$statusPublic.label|lang}</option>
									{else}
										<option value="{$statusPublic.id}">{$statusPublic.label|lang}</option>
									{/if}
								{else}
									<option selected="selected" value="{$statusPublic.id}">{$statusPublic.label|lang}</option>
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
						<ul class="nglist" id = "group_user_invite">
							{if isset($lst_user_add_info)}
								{foreach $lst_user_add_info as $group_user}
									<li class="clearfix">
										<p class="name">
											{$group_user->name}
										</p>
										<p class="at">
											{$group_user->organization}
											{$group_user->position}
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
							</a>
						<div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="btn ">
		<a id="btn-submit" style="cursor: pointer;">
			{'label_button_confirm'|lang}
		</a>
	</div>
	<a href="" class="modalOpen" id="btn-dialog"></a>
	{''|form_close}
{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			$('#btn-submit').click(function(){
				$('#form-main').submit();
				return false;
			});
		});
	
		function get_member() {
			var url_get = "{/literal}{$fixed_base_url}group/invite_list/0{literal}";
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