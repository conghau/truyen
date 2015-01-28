{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
<div class="static_all_wrap">
	<h2 class="ttl">{'label_user_active_recomend_complete'|lang}</h2>
	<div class="static_wrap pd10">
		{'label_user_msg_active_recommend_complete'|lang}
	</div>
	<div class="btn">
		<a href="{$fixed_base_url}user/edit">{'label_login'|lang}</a>
	</div>
</div>
{/block}