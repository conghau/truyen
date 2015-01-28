{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}

<div class="static_all_wrap">

<h2 class="ttl">{'lable_security_title1_sp'|lang}</h2>

<div class="static_wrap pd10">

<h3>{'lable_security_main_sp'|lang}</h3>
{'lable_security_body_sp'|lang}
<h4>{'lable_security_title1_sub1_sp'|lang}</h4>
{'lable_security_title1_sub2_sp'|lang}<br>
{'lable_security_title1_sub2_body_sp'|lang}
<br>
{'lable_security_title1_sub3_sp'|lang}
{'lable_security_title1_sub3_body_sp'|lang}
<br>
{'lable_security_title1_sub4_sp'|lang}
{'lable_security_title1_sub4_body_sp'|lang}
<br>
<h3>{'lable_security_title2_sp'|lang}</h3>
{'lable_security_title2_body_sp'|lang}
<br>
<h3>{'lable_security_title3_sp'|lang}</h3>
{'lable_security_title3_body_sp'|lang}</h3>
<br>
{'lable_security_title3_body_end_sp'|lang}
</div>
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
<script type="text/javascript">
	$("link[href$='thickbox.css']").remove();
</script>
{/block}
