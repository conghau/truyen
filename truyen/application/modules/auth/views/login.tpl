<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{block name=title}{$title}{/block}</title>

<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/custom.css"/>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.cookie.js"></script>
<style>

</style>
</head>

<body class="login">
	<div class="loginnner">
		<div class="clearfix">
			<div class="loginlogo">
				<p><img src="{$fixed_base_url}assets/img/{$language}/login_logo.png"></p>
				<p>{'label_login_introduce'|lang}</p>
				<p><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a>
			</div>
		<div class=wrapper>
			<div class="loginbox">
			{$ssl_base_url|cat:'login'|form_open:'id=form-main'}
				{'user_id'|form_error:'<p class="error" style="text-align:left">※':'</p>'}
				<p><input type="{if ($device_info->get_category() == 'iPhone' or $device_info->get_category() == 'iPod')}email{else}text{/if}"  class="w100" name="user_id" value="{$user_id|h}" placeholder="{'label_login_id'|lang}"/></p>
				<p><input type="password" name="password" value="{$password|h}" class="w100" placeholder="{'label_password'|lang}"></p>
				<p class="forget"><a href="{$fixed_base_url}pass_reissue">{'label_forgot_password'|lang}</a></p>
				<p><a href="" id="btn_sub"><img src="{$fixed_base_url}assets/img/{$language}/login_btn1.png" alt="{'label_login'|lang}"></a></p>
				<p><a href="{$fixed_base_url}user/create"><img src="{$fixed_base_url}assets/img/{$language}/login_btn2.png" alt="{'label_button_create_new'|lang}"></a></p>
				<input class="button" type="submit" id="btn_submit" name="auth" value="{'label_login'|lang}" style="display:none"/>
			{''|form_close}
			</div>
		</div>
		</div>
	</div>

	<div class="bt0">
		<div class="footer">
			<div class="linkarea">
				<a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a> |
				<a href="{$fixed_base_url}other/term">{'label_footer_terms'|lang}</a> |
				<a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a> |
				<a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a> |
				<a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a>
			</div>
			<p class="ct">© 2014 QLife, Inc.</p> 
		</div>
	</div>

	<script type="text/javascript">

		$('document').ready(function(){
			$('#btn_sub').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{$ssl_base_url}login");
					return false;
				}
				$('#btn_submit').click();
				return false;
			});
		});
	</script>
{include file="../../layout/views/tracking_script.tpl"}
</body>
</html>