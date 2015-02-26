<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{block name=title}{/block}</title>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<!-- <link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css" /> -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="{$fixed_base_url}assets/bootstrap/js/scripts.js"></script>
<script src="{$fixed_base_url}assets/js/jquery.twbsPagination.js"></script>
<!-- <link rel="stylesheet" href="{$fixed_base_url}assets/css/bootstrap.min.css"/> -->
<!-- <!-- <link rel="stylesheet" href="{$fixed_base_url}assets/css/kendo.metro.css"/> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.common-bootstrap.min.css" rel="stylesheet" /> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.bootstrap.min.css" rel="stylesheet" /> -->
<!-- <link href="http://cdn.kendostatic.com/2014.3.1316/styles/kendo.metro.min.css" rel="stylesheet"> -->

<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/jszip.min.js"></script> -->
<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/kendo.all.min.js"></script> -->
<!--         <script src="http://cdn.kendostatic.com/2014.3.1316/js/kendo.timezones.min.js"></script> -->


{block name=stylesheet}{/block}
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
					<li class="active"><a href="#">{'label_home'|lang}</a></li>
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
				{*<nav class="navbar navbar-default" role="navigation">*}
					{*<div class="navbar-header">*}
						{*<button type="button" class="navbar-toggle" data-toggle="collapse"*}
							{*data-target="#bs-example-navbar-collapse-1">*}
							{*<span class="sr-only">Toggle navigation</span><span*}
								{*class="icon-bar"></span><span class="icon-bar"></span><span*}
								{*class="icon-bar"></span>*}
						{*</button>*}
						{*<a class="navbar-brand" href="#">Brand</a>*}
					{*</div>*}
					{*<div class="collapse navbar-collapse"*}
						{*id="bs-example-navbar-collapse-1">*}
						{*<ul class="nav navbar-nav">*}
							{*<li class="active"><a href="#">Link</a></li>*}
							{*<li><a href="#">Link</a></li>*}
							{*<li class="dropdown"><a href="#" class="dropdown-toggle"*}
								{*data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>*}
								{*<ul class="dropdown-menu">*}
									{*<li><a href="#">Action</a></li>*}
									{*<li><a href="#">Another action</a></li>*}
									{*<li><a href="#">Something else here</a></li>*}
									{*<li class="divider"></li>*}
									{*<li><a href="#">Separated link</a></li>*}
									{*<li class="divider"></li>*}
									{*<li><a href="#">One more separated link</a></li>*}
								{*</ul></li>*}
						{*</ul>*}
						{*<form class="navbar-form navbar-left" role="search">*}
							{*<div class="form-group">*}
								{*<input type="text" class="form-control">*}
							{*</div>*}
							{*<button type="submit" class="btn btn-default">Submit</button>*}
						{*</form>*}
						{*<ul class="nav navbar-nav navbar-right">*}
							{*<li><a href="#">Link</a></li>*}
							{*<li class="dropdown"><a href="#" class="dropdown-toggle"*}
								{*data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>*}
								{*<ul class="dropdown-menu">*}
									{*<li><a href="#">Action</a></li>*}
									{*<li><a href="#">Another action</a></li>*}
									{*<li><a href="#">Something else here</a></li>*}
									{*<li class="divider"></li>*}
									{*<li><a href="#">Separated link</a></li>*}
								{*</ul></li>*}
						{*</ul>*}
					{*</div>*}
				{*</nav>*}
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
								{block name=body}{/block}


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
	{block name=javascript}{/block}
</body>
</html>
