{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
<div class="col-md-12 column">
	<div class="row" style="max-width:800px; margin:auto">
		<div class="col-md-4 col-xs-2" style="text-align:right">
			<button class="btn  btn-info" type="button" onclick="previouschap()"><i class="glyphicon glyphicon-chevron-left"></i></button>
		</div>
		<div class="col-md-4 col-xs-8">
			{$title = url_friendly($comic_title)}
			<select class="form-control" onchange="location.href=this.value">
				{foreach $lst_chapter as $item}
					{if $item->id eq $chapter_id}
						<option value="{$base_url}comic/{$item->id}_{$story_id}/{$title}_{url_friendly($item->name)}.html" selected>{$item->name}</option>
					{else}
						<option value="{$base_url}comic/{$item->id}_{$story_id}/{$title}_{url_friendly($item->name)}.html">{$item->name}</option>
					{/if}
				{/foreach}
			</select>
		</div>
		<div class="col-md-4 col-xs-2">
			<button class="btn  btn-info" type="button" onclick="previouschap()"><i class="glyphicon glyphicon-chevron-right"></i></button>
		</div>
	</div>
	{foreach $lst_image as $item}
		<div style="z-index:500;">
			<img src="{$item->link}" style="border:3px solid #fff; margin:auto;z-index:500" class="img-responsive">
		</div>
	{/foreach}
</div>
{/block}