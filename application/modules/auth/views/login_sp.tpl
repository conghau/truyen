{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap">
		<p class="mtb20"> {'label_title_login'|lang} </p>
		
		{$ssl_base_url|cat:'login'|form_open:'id=form-main'}
		<ul class="form clearfix">
			{'user_id'|form_error:'<p class="error">※':'</p>'}
		</ul>
		<p><input type="text" placeholder="{'label_login_id'|lang}" name="user_id" value="{$user_id|h}" class="w100"></p>
		<p><input type="password" placeholder="{'label_password'|lang}" name="password" value="{$password|h}" class="w100"></p>
	
		<div class="btn">
			<input class="button" type="submit" id="btn_submit" name="auth" value="{'label_login'|lang}" style="display:none"/>
			<a id="btn_sub" class="com w100btn">{'label_login'|lang}</a>
		</div>
		<div class="btn ">
			<a href="{$fixed_base_url}user/create" class="com w100btn">{'label_button_create_new'|lang}</a>
		</div> 
  		<p class="btn">
			<a href="{$fixed_base_url}pass_reissue" class="com w100btn">{'label_forgot_password'|lang}</a>
		</p>
		{''|form_close}
	</div>

<!-- footer -->
<div class="mt20">
<div class="foot">
	<ul>
		<li><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a></li>
		<li><a href="{$fixed_base_url}other/term" >{'label_footer_terms'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a></li>
		<li><a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a></li>
	</ul>
	<p>© 2014 QLife, Inc. </p>
</div>
</div>

{/block}
{block name=javascript}
	{literal}
	<script language="Javascript">
		$('document').ready(function(){
			$('#btn_sub').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$ssl_base_url}login{literal}");
					return false;
				}
				
				$('#btn_submit').click();
				return false;
			});
		});
	</script>
	{/literal}
{/block}