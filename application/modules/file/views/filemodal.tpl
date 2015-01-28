<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/custom.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.css"/>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/modal.js"></script>
<meta charset="UTF-8">
<title></title>
</head>
<body class="sc_bg">
	<div id="file_modal">
	{if !$auth}
		{literal}
		<script type="text/javascript">
			window.parent.location.reload();
		</script>
		{/literal}
	{else}
		<h2>{'label_attach_list'|lang}</h2>
		<div class="modal_inner content" style="height:600px">
		{if count($files) > 0}
			<table class="filelist" style="table-layout: fixed;">
				{foreach $files as $file}
				<tr>
					<td width = "45%" class="text-word-break"> <p> {$file['file_name']} </p> </td>
					<td width = "20%"> {$file['file_size']|file_size_format:0} </td>
					<td width = "35%" class="text-word-break ct">
						{if ($file['file_id'] == '0')}
							{'L-F-0034-E'|lang}
						{else}
							<a href="{$fixed_base_url}file/download_file_list/{$file['file_id']}">
								<img src="{$fixed_base_url}assets/img/{$language}/download.png">
							</a>
						{/if}
					</td>
				</tr>
				{/foreach}
			</table>
		{else}
			{'L-F-0034-E'|lang}
		{/if}
		</div>
	{/if}
	</div>
	<style>
		tr{
			line-height: 14px;
		}
	</style>
	{literal}
	<script src="{/literal}{$fixed_base_url}{literal}assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(window).load(function(){
			$(".content").mCustomScrollbar();
			$(".disable-destroy a").click(function(e){
				e.preventDefault();
				var $this=$(this),
					rel=$this.attr("rel"),
					el=$(".content"),
					output=$("#info > p code");
				switch(rel){
					case "toggle-disable":
					case "toggle-disable-no-reset":
						if(el.hasClass("mCS_disabled")){
							el.mCustomScrollbar("update");
							output.text("$(\".content\").mCustomScrollbar(\"update\");");
						}else{
							var reset=rel==="toggle-disable-no-reset" ? false : true;
							el.mCustomScrollbar("disable",reset);
							if(reset){
								output.text("$(\".content\").mCustomScrollbar(\"disable\",true);");
							}else{
								output.text("$(\".content\").mCustomScrollbar(\"disable\");");
							}
						}
						break;
					case "toggle-destroy":
						if(el.hasClass("mCS_destroyed")){
							el.mCustomScrollbar();
							output.text("$(\".content\").mCustomScrollbar();");
						}else{
							el.mCustomScrollbar("destroy");
							output.text("$(\".content\").mCustomScrollbar(\"destroy\");");
						}
						break;
				}
			});
		});
	});
	</script>
	{/literal}
</body>
</html>