{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body1}
<h2 class="ttl">{'label_term'|lang}</h2>
<div class="static_wrap">
<h3 class="aligntop">{'label_term_title'|lang}</h3>
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
{block name="stylesheet"}
<style>
	h3.aligntop{
		margin-top:0px;
	}
</style>
{/block}



