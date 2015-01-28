{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body1}

<h2 class="ttl">{'lable_security_title1'|lang}</h2>

<div class="static_wrap">

<h3>{'lable_security_main'|lang}</h3>
{'lable_security_body'|lang}
<h4>{'lable_security_title1_sub1'|lang}</h4>

<div class="clearfix">
<p class="txt_l">{'lable_security_title1_sub2'|lang}</p>
<div class="txt_r">
{'lable_security_title1_sub2_body'|lang}
</div>
</div>
<br>
<div class="clearfix">
<p class="txt_l">{'lable_security_title1_sub3'|lang}</p>
<div class="txt_r">
{'lable_security_title1_sub3_body'|lang}
</div>
</div>
<br>
<div class="clearfix">
<p class="txt_l">{'lable_security_title1_sub4'|lang}</p>
<div class="txt_r">
{'lable_security_title1_sub4_body'|lang}
</div>
</div>

<h3>{'lable_security_title2'|lang}</h3>
{'lable_security_title2_body'|lang}
<h3>{'lable_security_title3'|lang}</h3>
{'lable_security_title3_body'|lang}
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

{/block}
{block name=javascript}
<script type="text/javascript">
	$("link[href$='thickbox.css']").remove();
</script>
{/block}



