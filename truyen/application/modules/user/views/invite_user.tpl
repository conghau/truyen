{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap2">
		<h2 class="ttl2">{'label_post_invite_title'|lang}</h2>
		<p class="mb10">{'label_post_invite'|lang}</p>

<div class="ctinner tround">
{'user/confirm_invite'|form_open:'id=form-main'}

		<table class="mem_input">
			<tr>
				<th>{'label_post_invite_email_address'|lang}</th>
				<td>
					<input type="text" maxlength="128" name="email" size="30" value="{(isset($invite['email']))?$invite['email']:''}"/>
					<p style="margin-top:5px"><span>※{'label_post_alphanumeric'|lang}</span></p>
					{form_error('email','<p style="color:red; margin-top:5px"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_post_invite_name'|lang}</th>
				<td>
					<input type="text" name="last_name_ja" maxlength="64" size="20" value="{(isset($invite['last_name']))?$invite['last_name']:''}">
					<input type="text" name="first_name_ja" maxlength="64" size="20" value="{(isset($invite['first_name']))?$invite['first_name']:''}">
					{form_error('last_name_ja','<p class="alert-error" style="margin-top:5px"> ※ ','</p>')}
					{form_error('first_name_ja','<p class="alert-error" style="margin-top:5px"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_post_content_title'|lang}</th>
			<td>
				{'label_post_content'|lang}
			</td>
			</tr>
		</table>
	<div class="mt20 ct">
	<a id="btn_confirm" name="btn_confirm" style="cursor:pointer;"><img src="{$fixed_base_url}assets/img/{$language}/btn_confirm.png"></a>
	</div>
	</div>
{''|form_close}
</div>
{block name=style}
<style>
	.alert-error{
			color:red;
	}
</style>
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
{/block}