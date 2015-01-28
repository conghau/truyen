{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
<div class="static_all_wrap">
	{if !isset($msg_error)}
		<h2 class="ttl">{'label_user_active_general_complete'|lang}</h2>
		<div class="static_wrap pd10">
			{'label_user_msg_active_general_complete'|lang}
		</div>
	{else}
		<h2 class="ttl">{$msg_error}</h2>
	{/if}
		<div class="btn"><a href="{$fixed_base_url}login">{'label_link_top'|lang}</a></div>
</div>
{/block}