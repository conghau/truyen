{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/group/confirm_create'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_group_management'|lang}</h2></div>
		<input type="hidden" value="" id="group_id" name="group_id"/>
		<table class="box_table_h">
			<tbody>
			<tr>
				<th>{'label_group_name'|lang}<font color="red"> ※</font></th>
				<td>
					<input type="text" id="name" name="name" value="{$name|h}"
					maxlength="255" size="30"/>
					{form_error('name','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_group_summary'|lang}</th>
				<td>
					<textarea style="max-width:423px; min-width:423px; max-height:166px; min-height:166px; resize: none;" 
					id="summary" name="summary">{$summary|h}</textarea>
				</td>
			</tr>
			<tr>
				<th>{'label_group_public_status'|lang}</th>
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
				<th>{'label_group_owner_id'|lang}<font color="red"> ※</font></th>
				<td >
					<input type="text" id="group_owner" name="group_owner" 
						maxlength="11" size="11" value="{$group_owner|h}">
					{form_error('group_owner','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_status'|lang}</td>
				<td >
					<select id="status_type" name="status_type">
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
			<a id="btn-submit" href=""><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
		</div>
	</div>
{''|form_close}
{/block}

{block name=stylesheet}
<style>
	textarea {
		resize: none;
	}
	.alert-error {
		color: red;
	}
</style>
{/block}

{block name=javascript}
{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			$('#btn-submit').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$('#form-main').submit();
				return false;
			});
		});
	</script>
{/literal}
{/block}