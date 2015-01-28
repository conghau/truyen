<?php /* Smarty version Smarty-3.1.7, created on 2015-01-28 16:57:22
         compiled from "application/modules/story/views/story_introduce.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12067588554c8b2825a4f66-22329238%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba42ef2c24dcb91b33ed7215094cf94d3742c959' => 
    array (
      0 => 'application/modules/story/views/story_introduce.tpl',
      1 => 1422437521,
      2 => 'file',
    ),
    'df981e7cc2f22ff9f2a23a1e75c768f459381a3b' => 
    array (
      0 => 'application/modules/layout/views/master.tpl',
      1 => 1422438604,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12067588554c8b2825a4f66-22329238',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fixed_base_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_54c8b2825e969',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54c8b2825e969')) {function content_54c8b2825e969($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>
	<?php echo $_smarty_tpl->tpl_vars['title']->value;?>

</title>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/css/style.css" />
<link rel="stylesheet"
	href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet"
	href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/bootstrap/css/style.css" />
<link rel="stylesheet"
	href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/bootstrap/css/bootstrap-theme.min.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<link rel="stylesheet"
	href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/bootstrap/js/bootstrap.min.js" />
<script type="text/javascript"
	href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/bootstrap/js/scripts.js"></script>
<!-- <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/css/bootstrap.min.css"/> -->
<!-- <!-- <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/css/kendo.metro.css"/> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.common-bootstrap.min.css" rel="stylesheet" /> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.bootstrap.min.css" rel="stylesheet" /> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.metro.min.css" rel="stylesheet"> -->

<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/jszip.min.js"></script> -->
<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/kendo.all.min.js"></script> -->
<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/kendo.timezones.min.js"></script> -->



</head>

<body>
	<header>
		<div class="container" class="row">
			<h1 class="col-sm-3 hidden-sm">Kendo UI &hearts; Bootstrap</h1>
			<h1 class="col-sm-3 visible-sm">
				Kendo UI &hearts;<br /> Bootstrap
			</h1>

			<button id="configure" class="visible-xs">
				<span class="glyphicon glyphicon-align-justify"></span>
			</button>

			<div id="configurator-wrap" class="col-sm-9 hidden-xs">
				<div id="configurator" class="row">
					<label class="col-sm-4">
						<div class="description">Dimensions</div> <select id="dimensions"></select>
					</label> <label class="col-sm-4">
						<div class="description">Theme</div> <select id="theme"></select>
					</label> <label class="col-sm-4">
						<div class="description">Font-size</div> <select id="font-size"></select>
					</label>
				</div>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="row clearfix">
			<div class="col-md-12 column">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#"><?php echo lang('label_home');?>
</a></li>
					<li><a href="#">Profile</a></li>
					<li class="disabled"><a href="#">Messages</a></li>
					<li class="dropdown pull-right"><a href="#" data-toggle="dropdown"
						class="dropdown-toggle">Dropdown<strong class="caret"></strong></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul></li>
				</ul>
				<nav class="navbar navbar-default" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse"
							data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span><span
								class="icon-bar"></span><span class="icon-bar"></span><span
								class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">Brand</a>
					</div>

					<div class="collapse navbar-collapse"
						id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active"><a href="#">Link</a></li>
							<li><a href="#">Link</a></li>
							<li class="dropdown"><a href="#" class="dropdown-toggle"
								data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>
								<ul class="dropdown-menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a></li>
									<li class="divider"></li>
									<li><a href="#">One more separated link</a></li>
								</ul></li>
						</ul>
						<form class="navbar-form navbar-left" role="search">
							<div class="form-group">
								<input type="text" class="form-control">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="#">Link</a></li>
							<li class="dropdown"><a href="#" class="dropdown-toggle"
								data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>
								<ul class="dropdown-menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a></li>
								</ul></li>
						</ul>
					</div>

				</nav>
				<div class="row clearfix">
					<div class="col-md-2 column">
						<div class="row clearfix">
							<div class="col-md-12 column"></div>
						</div>
						<div class="row clearfix">
							<div class="col-md-12 column"></div>
						</div>
						<a id="modal-550052" href="#modal-container-550052" role="button"
							class="btn" data-toggle="modal">Launch demo modal</a>

						<div class="modal fade in" id="modal-container-550052"
							role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"
											aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Modal title</h4>
									</div>
									<div class="modal-body">...</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default"
											data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary">Save changes</button>
									</div>
								</div>

							</div>

						</div>

					</div>
					<div class="col-md-8 column">
						<div class="row clearfix">
							<div class="col-md-12 column">
								<ul class="breadcrumb">
									<li><a href="#">Home</a> <span class="divider">/</span></li>
									<li><a href="#">Library</a> <span class="divider">/</span></li>
									<li class="active">Data</li>
								</ul>
								
<div class="col-md-12 column">
					<div class="media well">
						 <a href="#" class="pull-left"><img src="http://lorempixel.com/64/64/" class="media-object" alt='' /></a>
						<div class="media-body">
							<h4 class="media-heading">
								Nested media heading
							</h4> Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
							<div class="media">
								 <a href="#" class="pull-left"><img src="http://lorempixel.com/64/64/" class="media-object" alt='' /></a>
								<div class="media-body">
									<h4 class="media-heading">
										Nested media heading
									</h4> Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
								</div>
							</div>
						</div>
					</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>
									#
								</th>
								<th>
									Product
								</th>
								<th>
									Payment Taken
								</th>
								<th>
									Status
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									1
								</td>
								<td>
									TB - Monthly
								</td>
								<td>
									01/04/2012
								</td>
								<td>
									Default
								</td>
							</tr>
							<tr class="active">
								<td>
									1
								</td>
								<td>
									TB - Monthly
								</td>
								<td>
									01/04/2012
								</td>
								<td>
									Approved
								</td>
							</tr>
							<tr class="success">
								<td>
									2
								</td>
								<td>
									TB - Monthly
								</td>
								<td>
									02/04/2012
								</td>
								<td>
									Declined
								</td>
							</tr>
							<tr class="warning">
								<td>
									3
								</td>
								<td>
									TB - Monthly
								</td>
								<td>
									03/04/2012
								</td>
								<td>
									Pending
								</td>
							</tr>
							<tr class="danger">
								<td>
									4
								</td>
								<td>
									TB - Monthly
								</td>
								<td>
									04/04/2012
								</td>
								<td>
									Call in to confirm
								</td>
							</tr>
						</tbody>
					</table>
					<ul class="pagination">
						<li>
							<a href="#">Prev</a>
						</li>
						<li>
							<a href="#">1</a>
						</li>
						<li>
							<a href="#">2</a>
						</li>
						<li>
							<a href="#">3</a>
						</li>
						<li>
							<a href="#">4</a>
						</li>
						<li>
							<a href="#">5</a>
						</li>
						<li>
							<a href="#">Next</a>
						</li>
					</ul>
				</div>



								<div class="row clearfix">
									<div class="col-md-12 column">
										<address>
											<strong>Twitter, Inc.</strong><br> 795 Folsom Ave, Suite 600<br>
											San Francisco, CA 94107<br> <abbr title="Phone">P:</abbr>
											(123) 456-7890
										</address>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2 column">
						<div class="row clearfix">
							<div class="col-md-12 column"></div>
						</div>
						<div class="row clearfix">
							<div class="col-md-12 column"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	

</body>
</html>
<?php }} ?>