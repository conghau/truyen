{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body1}
	{if !isset($msg_error)}
		<h2 class="ttl">{'label_user_active_general_complete'|lang}</h2>
		<div class="static_wrap">
			{'label_user_msg_active_general_complete'|lang}
		</div>
	{else}
		<h2 class="ttl">{$msg_error}</h2>
	{/if}
{/block}