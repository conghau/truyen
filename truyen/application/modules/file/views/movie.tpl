<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title></title>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
</head>
<body>
<div class="video">
<div id="videoframe">
<video id="player" poster="{$fixed_base_url}file/image/{$file_id}/thumbnail_l" preload="none" controls width="100%">
<source src='{$fixed_base_url}file/image/{$file_id}/movie'>
</video>
</div>
</div>
{literal}
<style>
body {
	margin: 0;
	background-color: black;
	color: #fff;

	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-o-box-sizing: border-box;
	-ms-box-sizing: border-box;
    
	transition: 0.1s linear;
	-webkit-transition: 0.1s linear;
	-moz-transition: 0.1s linear;
	-ms-transition: 0.1s linear;
	-o-transition: 0.1s linear;

}
#videoframe {
{/literal}{if !$device_info->is_smartphone()} 
	position: absolute;
	top: 10%;
	left: -100%;
	right: -100%;
	margin: auto;
	max-width: 800px;
	max-height: 800px;
{/if}{literal}
}

#player {
}

</style>
<script type="text/javascript">
var mimetype = '{/literal}{$file_type}{literal}';
window.onload = function() {
	if (!HTMLVideoElement) {
		$('#videoframe').html('{/literal}{'label_preview_not_supported_browser'|lang}{literal}');
	}
	var video = document.createElement("video");
	if (video.canPlayType(mimetype) === ""){
		$('#videoframe').html('{/literal}{'label_preview_not_supported_play'|lang}{literal}');
	}
	$('#videoframe').attr('preload', 'auto');
};
</script>
{/literal}
  </body>
</html>
