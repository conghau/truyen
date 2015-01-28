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

<body>
	<div class="header">
		<div class="ctwrap clearfix">
			<h1 class="logo"><a href="{$fixed_base_url}"><img src="{$fixed_base_url}assets/img/{$language}/logo.png"></a></h1>
		</div>
	</div>
	<h2 class="ttl">{'label_pass_reissue'|lang}</h2>
	<div class="static_wrap">
		{'label_pass_reissue_text'|lang}
		<div class="perror">
			{'pass_reissue_finish'|form_open}
				<p class="tl">{'label_pass_reissue_email_address'|lang}</p>
				<p><input type="text" name="txt_email" value="{$txt_email|h}" class="ml"></p>
				{'txt_email'|form_error:'<p class="red">※':'</p>'}</p>
				<p class="tl">{'label_pass_reissue_birthday'|lang}</p>
				<p>
					<input type="text" name="txt_year" value="{$txt_year|h}" class="w130"> {'label_year'|lang} 
					<input type="text" name="txt_month" value="{$txt_month|h}" class="w130"> {'label_month'|lang} 
					<input type="text" name="txt_day" value="{$txt_day|h}" class="w130"> {'label_day'|lang} 
				</p>
				{'txt_year'|form_error:'<p class="red">※':'</p>'}</p>
				{'txt_month'|form_error:'<p class="red">※':'</p>'}</p>
				{'txt_day'|form_error:'<p class="red">※':'</p>'}</p>
				{if isset($error)}
					<p class="red">{$error}</p>
				{/if}
				<input type="submit" class="btn" value="{'label_pass_reissue'|lang}">
			{''|form_close}
		</div>
	</div>
</body>
</html>