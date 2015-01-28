{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_pass_reissue'|lang}</h2>
		<div class="static_wrap pd10">
			{'label_pass_reissue_text'|lang}
			<br>
			<br>
			<div class="perror">
				{'pass_reissue_finish'|form_open:'id=form-main'}
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
					<p class="red">{$error}</p>
					<div class="btn ">
						<a id = "pass_reissue" href="#" class="com">{'label_pass_reissue'|lang}</a>
					</div>
				{''|form_close}
			</div>
		</div>
		<div class="btn ">
			<a href="{$fixed_base_url}login" class="com">{'label_link_top'|lang}</a>
		</div>  
	</div>
{/block}
{block name=javascript}
	{literal}
	<script language="Javascript">
		$('document').ready(function(){
			$('#pass_reissue').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$ssl_base_url}login{literal}");
					return false;
				}
				
				$('#form-main').submit();
				return false;
			});
		});
	</script>
	{/literal}
{/block}