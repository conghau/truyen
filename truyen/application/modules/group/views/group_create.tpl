{extends file='master.tpl'}
{block name=title}
	{$title}
{/block}
{block name=body}
	<h2 class="ttl2">{'label_new_group'|lang}</h2>
<div class="ctinner tround">
	{'group/confirm_create'|form_open:'id=form-main'}
		<table class="mem_input">
			<tr>
				<th>{'label_group_name_edit'|lang}<font color="red"> ※</font></th>
				<td>
					<input type="text" id="name" name="name" value="{$name|h}""
					maxlength="255" size="30" style="margin-bottom:2px"/>
					{form_error('name','<div class="alert-error"> ※ ','</div>')}
				</td>
			</tr>
			<tr>
				<th>{'label_group_summary'|lang}</th>
				<td>
					<textarea id="summary" name="summary">{$summary|h}</textarea>
				</td>
			</tr>
			<tr>
				<th>{'label_group_public_status_edit'|lang}</th>
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
					<div class="alignR">
						<a href="{$fixed_base_url}group/invite_list/0" class="user_modal">
							<img src="{$fixed_base_url}assets/img/{$language}/add_invite_user.png" alt="{'label_group_add_invitees'|lang}">
						</a>
					<div>
					<input type="hidden" id="hdn_invite_user" name="hdn_invite_user" value="{$hdn_invite_user}"/>
				</td>
			</tr>
		</table>

		<p class="mt10 ct">
			<a id="btn-submit" style="cursor: pointer;">
				<img src="{$fixed_base_url}assets/img/{$language}/chk.png" alt="{'label_button_confirm'|lang}">
			</a>
		</p>
	{''|form_close}
</div>
<style>
	.no-close{
		display: none;
	}
	th{
		width:130px!important;
	}
</style>
{literal}
<script src="{/literal}{$fixed_base_url}/assets/js/jquery.pageslide.min.js{literal}"></script>
<script type="text/javascript">
	$("a.user_modal").pageslide({ direction: "left"});
	$(document).ready(function(){
		$('#btn-submit').click(function(){
			$('#form-main').submit();
			return false;
		});
	});
</script>
{/literal}
{/block}