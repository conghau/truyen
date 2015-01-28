{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}

<div class="row">
	<div class="col-md-4">
		<div class="thumbnail">
			<img alt="300x200" src="http://lorempixel.com/600/200/people">
			<div class="caption">
				<h3>Thumbnail label</h3>
				<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.
					Donec id elit non mi porta gravida at eget metus. Nullam id dolor
					id nibh ultricies vehicula ut id elit.</p>
				<p>
					<a class="btn btn-primary" href="#">Action</a> <a class="btn"
						href="#">Action</a>
				</p>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="thumbnail">
			<img alt="300x200" src="http://lorempixel.com/600/200/city">
			<div class="caption">
				<h3>Thumbnail label</h3>
				<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.
					Donec id elit non mi porta gravida at eget metus. Nullam id dolor
					id nibh ultricies vehicula ut id elit.</p>
				<p>
					<a class="btn btn-primary" href="#">Action</a> <a class="btn"
						href="#">Action</a>
				</p>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="thumbnail">
			<img alt="300x200" src="http://lorempixel.com/600/200/sports">
			<div class="caption">
				<h3>Thumbnail label</h3>
				<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.
					Donec id elit non mi porta gravida at eget metus. Nullam id dolor
					id nibh ultricies vehicula ut id elit.</p>
				<p>
					<a class="btn btn-primary" href="#">Action</a> <a class="btn"
						href="#">Action</a>
				</p>
			</div>
		</div>
	</div>
</div>
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
