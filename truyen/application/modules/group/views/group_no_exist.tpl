{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_wrap" style="width:740px">
		{'label_group_no_exist'|lang}
	</div>
{/block}