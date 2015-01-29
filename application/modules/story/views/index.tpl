{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
{foreach $list_author as $key => $rset}
	<p>{$rset->tacgia}</p>
{/foreach}
<div class="row">
	<br>
	<div class="col-md-2 col-sm-3 text-center">
		<a class="story-img" href="#"><img
			src="http://10.190.201.207/lar/public/imgs/news/40/banner.png"
			style="width: 100px; height: 100px" class="img-circle"></a>
	</div>
	<div class="col-md-10 col-sm-9">
		<h3>VAGRANT PUSH</h3>
		<div class="row">
			<div class="col-xs-9">
				<p>As of version 1.7, Vagrant is capable of deploying or "pushing"
					application code in the same directory as your Vagrantfile to a
					remote such as an FTP server or HashiCorp's Atlas. Pushes are
					defined in an application's Vagrantfile and are invoked using th</p>
				<p class="lead">
					<a href="http://10.190.201.207/lar/public/home/news/40"
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
<div class="row">
	<br>
	<div class="col-md-2 col-sm-3 text-center">
		<a class="story-img" href="#"><img
			src="http://10.190.201.207/lar/public/imgs/news/39/header_logo_002.png"
			style="width: 100px; height: 100px" class="img-circle"></a>
	</div>
	<div class="col-md-10 col-sm-9">
		<h3>リバップ会員サービス申込</h3>
		<div class="row">
			<div class="col-xs-9">
				<p>「リバップ会員サービス」TOPページにて会員資格および入会方法を必ずご確認の上、お申し込みください。
					リバップ会員サービスお申し込みの方には、入会に必要な書類を郵送させていただきます。</p>
				<p class="lead">
					<a href="http://10.190.201.207/lar/public/home/news/39"
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
