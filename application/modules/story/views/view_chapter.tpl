{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
<div class="col-md-12 column">
	<div>
		<select>
			{foreach $lstChapter as $item}
				<option>{$item->chapter}</option>
			{/foreach}
		</select>
	</div>
	<div class="media well">
		<div class="media-body">
			<!--{$chapter_content->content}-->
		</div>
	</div>
</div>
{/block}