<?php /* Smarty version Smarty-3.1.7, created on 2015-02-10 14:54:42
         compiled from "application\modules\story\views\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1043954da0da2b092b7-74521215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3610228cf30e5bff9fc14a9fee42bc6ff065f368' => 
    array (
      0 => 'application\\modules\\story\\views\\index.tpl',
      1 => 1422695260,
      2 => 'file',
    ),
    'a0ab728a6eeee0ed4cc41167342cccbe97394c71' => 
    array (
      0 => 'application/modules/layout/views\\master.tpl',
      1 => 1422722415,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1043954da0da2b092b7-74521215',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fixed_base_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_54da0da2d1ded',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54da0da2d1ded')) {function content_54da0da2d1ded($_smarty_tpl) {?><!DOCTYPE html>
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
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<!-- <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/css/style.css" /> -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
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
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Project name</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Dashboard</a></li>
					<li><a href="#">Settings</a></li>
					<li><a href="#">Profile</a></li>
					<li><a href="#">Help</a></li>
				</ul>
				<form class="navbar-form navbar-right">
					<input type="text" class="form-control" placeholder="Search...">
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
	</nav>
	<hr>
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
								
<?php  $_smarty_tpl->tpl_vars['story'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['story']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list_story']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['story']->key => $_smarty_tpl->tpl_vars['story']->value){
$_smarty_tpl->tpl_vars['story']->_loop = true;
?>
<div class="row">
	<br>
	<div class="col-md-2 col-sm-3 text-center">
		<a class="story-img" href="#"><img
			src="http://10.190.201.207/lar/public/imgs/news/40/banner.png"
			style="width: 100px; height: 100px" class="img-circle"></a>
	</div>
	<div class="col-md-10 col-sm-9">
		<h3><?php echo $_smarty_tpl->tpl_vars['story']->value->title;?>
</h3>
		<div class="row">
			<div class="col-xs-9">
				<div><?php echo $_smarty_tpl->tpl_vars['story']->value->introduce;?>
</div>
				<p class="lead">
					<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
story/detail/<?php echo $_smarty_tpl->tpl_vars['story']->value->id;?>
_<?php echo url_friendly($_smarty_tpl->tpl_vars['story']->value->title);?>
.html"
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
		<br> <br>
	</div>
</div>
<hr>
<?php } ?>
<div class="page clearfix"><?php echo $_smarty_tpl->tpl_vars['links']->value;?>
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