{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{include file='master_post_processer_sp.tpl'}

{if $post_offset == 0 && !empty($keyword)}
<div class="ctinner tround">
<div style="padding:30px 0;text-align: center;font-size:15px;">{'label_search_result_not_found'|lang}</div>
</div>
{else if (($post_offset == 0) && (!isset($msg_no_exist)))}
<script>
$(function() {
	create_post();
});
</script>
{/if}

{/block}
