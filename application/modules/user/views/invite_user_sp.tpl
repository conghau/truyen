{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'user/confirm_invite'|form_open:'id=form-main'}
<div class="static_all_wrap">
<h2 class="ttl">{'label_post_invite_title'|lang}</h2>
<p>{'label_post_invite'|lang}</p>
<div class="static_wrap">
<table class="inq">
	<tr>
		<th>{'label_post_invite_email_address'|lang}</th>
	</tr>
	<tr>
		<td>
			<input type="text" name="email" maxlength="128" style="width:87%" value="{(isset($invite['email']))?$invite['email']:''}">
			<p class="att" style="margin-top:5px">※{'label_post_alphanumeric'|lang}</p>
			{form_error('email','<p style="color:red; margin-top:5px"> ※ ','</p>')}
		</td>
	</tr>
	<tr>
		<th>{'label_post_invite_name'|lang}</th>
	</tr>
	<tr>
		<td>
			<input type="text" maxlength="64" name="last_name_ja" style="width:40%" value="{(isset($invite['last_name']))?$invite['last_name']:''}">
			<input type="text" maxlength="64" name="first_name_ja" style="width:40%" value="{(isset($invite['first_name']))?$invite['first_name']:''}">
			{form_error('last_name_ja','<p style="margin-top:5px; color: red"> ※ ','</p>')}
			{form_error('first_name_ja','<p style="margin-top:5px; color: red"> ※ ','</p>')}
		</td>
	</tr>
</table>
</div>
	<div class="btn ">
		<a id="btn_confirm" name="btn_confirm" class="com">{'label_button_confirm'|lang}</a>
	</div>
</div>
<div class="navtx">
	{'label_post_content'|lang}
</div>
{''|form_close}
<a href="" class="modalOpen" id="btn-dialog"></a>
{literal}
<script type="text/javascript">
	$("#btn_confirm").click(function() {
		var cct_cookie = $.cookie('csrf_token');
		if(cct_cookie == undefined) {
			window.location.replace("{/literal}{$fixed_base_url}login{literal}");
			return;
		}
		$("#form-main").submit();
	});
</script>
{/literal}
{/block}