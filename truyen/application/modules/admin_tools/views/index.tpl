{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}

{include file='master_post_processer_admin.tpl'}

{/block}
