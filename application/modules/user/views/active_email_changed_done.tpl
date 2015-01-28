{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{if !isset($user)}
	{block name=body1}
		{if isset($msg_error)}
			<h2 class="ttl">{$msg_error}</h2>
		{/if}
	{/block}
{else}
	{block name=body}
		{if !isset($msg_error)}
			<h2 class="ttl" style="width: inherit;">{'label_user_change_email'|lang}</h2>
			<div class="ctinner tround">
				<h3 style="color: #85B200;">{'label_user_msg_change_email_complete'|lang}</h3>
			</div>
		{else}
			<h2 class="ttl" style="width: inherit;">{$msg_error}</h2>
		{/if}
	{/block}
{/if}