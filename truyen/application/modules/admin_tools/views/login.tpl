<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>{'label_login'|lang}</title>
<link rel="stylesheet" href="{$fixed_base_url}assets-admin/css/style.css"/>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery.cookie.js"></script>
	<style>
		.ctwrap {
			width: 500px;
			margin:0 auto;
		}
		.mainarea {
			width: 500px;
		}
		.ttl {
			text-align: center;
		}
		.alert-error {
			color:red;
		}
		input[type=text],input[type=password]{
			width: 200px;
		}
		li {
			display: table-cell;
			vertical-align: middle;
			min-width: 100px;
		}
		ul {
			margin-left: 10px;
		}
		label {
			margin-right: 20px;
		}
	</style>
</head>

<body>
	<div class="header">
		<div class="ctwrap clearfix">
			<h1 class="logo"><a href="{$fixed_base_url}admin_tools"><img src="{$fixed_base_url}assets-admin/img/logo.png"></a></h1>
		</div>
	</div>
	<div class="ctwrap cleafix">
		<div class="mainarea">
			<div class="ctbox">
				<div class="ttl"><h2>{'label_login'|lang}</h2></div>
			</div>
			<div class="ctinner">
				{$ssl_base_url|cat:'admin_tools/login'|form_open:'id=form-main'}
					<ul class="form clearfix">
						{* ''|validation_errors *}
						{'admin_id'|form_error:'<label class="alert-error">※':'</label>'}
					</ul>
					<br/>
					<ul class="form clearfix">
						<li><label>{'label_login_id'|lang}</label> </li>
						<li>
							<input type="{if ($device_info->get_category() == 'iPhone' or $device_info->get_category() == 'iPod')}email{else}text{/if}" name="admin_id" value="{$admin_id|h}" />
						</li>
					</ul>
					
					<ul class="form clearfix">
						<li><label>{'label_password'|lang} </label> </li>
						<li><input type="password" name="password" value="{$password|h}" /></li>
					</ul>
					<ul class="form clearfix">
						<li>
							<input type="checkbox" id="login_flag" name="login_flag" value="1"{if $login_flag == 1} checked="checked"{/if}>
							<label>{'label_keep_login'|lang}</label>
						</li>
					</ul>
					<p class="ct">
						<input id="btn-login" type="image" src="{$fixed_base_url}assets-admin/img/btn_login.png" style="border: none" />
						<input type="submit" id="login" name="authadmin" style="display:none">
					</p>
				{''|form_close}
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$('document').ready(function(){
			$('#btn-login').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{$ssl_base_url}admin_tools/login");
					return false;
				}
				$('#login').click();
				return false;
			});
		});
		
	</script>
</body>
</html>
