{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
<div style="width :150px;text-overflow: ellipsis">block is defined in the master.php templateblock is defined in the master.php templateblock is defined in the master.php templateblock is defined in the master.php template</div>
{foreach $list_story as $story}
	<div class="row">
	<br>
	<div class="col-md-2 col-sm-3 text-center">
		<a class="story-img" href="#"><img
			src="http://10.190.201.207/lar/public/imgs/news/40/banner.png"
			style="width: 100px; height: 100px" class="img-circle"></a>
	</div>
	<div class="col-md-10 col-sm-9">
		<h3>{$story->title}</h3>
		<div class="row">
			<div class="col-xs-9">
				<div>{$story->introduce}</div>
				<p class="lead">
					<a href="{$base_url}story/detail/{$story->id}"
						class="btn btn-default">Read More</a>
				</p>
				<p class="pull-right">
					<span class="label label-default">keyword</span> <span
						class="label label-default">tag</span> <span
						class="label label-default">post</span>
				</p>
				<ul class="list-inline">
					<li><a href="#">2 Days Ago</a></li>
					<li><a href="#"><i class="fa fa-comment"></i> 4 Comments</a></li>
					<li><a href="#"><i class="fa fa-share-alt"></i> 34 Shares</a></li>
				</ul>
			</div>
			<div class="col-xs-3"></div>
		</div>
		<br>
		<br>
	</div>
</div>
<hr>
{/foreach}


<ul class="pagination">
	<li><a href="#">Prev</a></li>
	<li><a href="#">1</a></li>
	<li><a href="#">2</a></li>
	<li><a href="#">3</a></li>
	<li><a href="#">4</a></li>
	<li><a href="#">5</a></li>
	<li><a href="#">Next</a></li>
</ul>

{/block}
