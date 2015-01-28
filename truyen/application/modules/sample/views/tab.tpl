{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{block name=stylesheet}
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/fileupload.css">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload.css">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-ui-noscript.css"></noscript>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/bootstrap.fileupload.css">
{/block}


{* This block is defined in the master.php template *}
{block name=body}
<ul class="navwrap">
	<li>
		<ul class="subnavwrap clearfix">
			<li class="set_tab">
				<a class="current tabs" href="#tab1">
					<span>Edit Member Info</span>
				</a>
			</li>
			<li class="set_tab">
				<a class="tabs" href="#tab2">
					<span>Setting</span>
				</a>
			</li>	
		</ul>
 	</li>
</ul>
<div id="tab1" class="isTab">	
	<div class="set_tabinner">
		<div class="alignR">
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>
あああ<br>

		</div>
	</div>
</div>
<div id="tab2" class="isTab" style="display: none;">
	<div class="set_tabinner">
		<div class="alignR">
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
いいいい<br>
		</div>
	</div>
</div>
<script src="{$fixed_base_url}assets/js/tab.js"></script>
{/block}
