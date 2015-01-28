{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body1}
<h2 class="ttl">{'label_user_active_recomend_complete'|lang}</h2>
<div class="static_wrap">
	{'label_user_msg_active_recommend_complete'|lang}
	<div class="ct mt20">
		<a href="{$fixed_base_url}user/edit"><img src="{$fixed_base_url}assets/img/{$language}/btn_to_login.png"></a>
	</div>
</div>
{/block}