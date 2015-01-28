{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}

<div class="static_all_wrap">

<h2 class="ttl">{'label_term'|lang}</h2>

<div class="static_wrap pd10">

<h3>{'label_term_title'|lang}</h3>
<h4>{'label_term_article_1'|lang}</h4>
<span>{'label_term_article_1.1'|lang}</span>
<br>
<span>{'label_term_article_1.2'|lang}</span>
<br>
<h4>{'label_term_article_2'|lang}</h4>
<span>{'label_term_article_2.1'|lang}</span>
<br>
<span>{'label_term_article_2.2'|lang}</span>
<br>
<h4>{'label_term_article_3'|lang}</h4>
<span>{'label_term_article_3.1'|lang}</span>
<br>
<span>{'label_term_article_3.2'|lang}</span>
<br>
<span>{'label_term_article_3.3'|lang}</span>
<br>
<span>{'label_term_article_3.4'|lang}</span>
<br>
<h4>{'label_term_article_4'|lang}</h4>
<span>{'label_term_article_4.1'|lang}</span>
<br>
<span>{'label_term_article_4.2'|lang}</span>
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

<div></div>

{/block}

