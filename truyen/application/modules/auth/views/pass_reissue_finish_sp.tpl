{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_pass_reissue'|lang}</h2>
		<div class="static_wrap pd10">
			{'label_pass_reissue_finish_sp'|lang}
		</div>
		<div class="btn ">
			<a href="{$fixed_base_url}login" class="com">{'label_link_top'|lang}</a>
		</div>
	</div>
{/block}