{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
<div class="col-md-12 column">
	<div class="media well">
		<a href="#" class="pull-left"><img src="http://lorempixel.com/64/64/"
			class="media-object" alt='' /></a>
		<div class="media-body">
			<h4 class="media-heading">Nested media heading</h4>
			Cras sit amet nibh libero, in gravida nulla. Nulla vel metus
			scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in
			vulputate at, tempus viverra turpis.
			<div class="media">
				<a href="#" class="pull-left"><img
					src="http://lorempixel.com/64/64/" class="media-object" alt='' /></a>
				<div class="media-body">
					<h4 class="media-heading">Nested media heading</h4>
					Cras sit amet nibh libero, in gravida nulla. Nulla vel metus
					scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum
					in vulputate at, tempus viverra turpis.
				</div>
			</div>
		</div>
	</div>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Product</th>
				<th>Payment Taken</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>TB - Monthly</td>
				<td>01/04/2012</td>
				<td>Default</td>
			</tr>
			<tr class="active">
				<td>1</td>
				<td>TB - Monthly</td>
				<td>01/04/2012</td>
				<td>Approved</td>
			</tr>
			<tr class="success">
				<td>2</td>
				<td>TB - Monthly</td>
				<td>02/04/2012</td>
				<td>Declined</td>
			</tr>
			<tr class="warning">
				<td>3</td>
				<td>TB - Monthly</td>
				<td>03/04/2012</td>
				<td>Pending</td>
			</tr>
			<tr class="danger">
				<td>4</td>
				<td>TB - Monthly</td>
				<td>04/04/2012</td>
				<td>Call in to confirm</td>
			</tr>
		</tbody>
	</table>
	<ul class="pagination">
		<li><a href="#">Prev</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">Next</a></li>
	</ul>
</div>
{/block}
