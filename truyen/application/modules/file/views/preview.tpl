<html lang="ja" id="previewHtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/preview.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-basic.css">
<meta charset="UTF-8">
<title></title>
</head>
<body style="background-color:#000;">
{if !$auth}
	{literal}
	<script type="text/javascript">
		window.parent.location.reload();
	</script>
	{/literal}
{else}

<div style="position:relative;width:770px;color:#fff;background-color: #000;margin:0 auto;padding:20px 0px 0px 30px;">
{if $post->upload_status == 0}
{'label_upload_no_upload_file'|lang}
{else if $post->upload_status == 1}
<div class="preview_processing" data-post-id="{$post_id}">
{'label_upload_processing'|lang}
</div>

{else if $post->upload_status == 2 && $post->process_count == 0}
<div class="preview_processing" data-post-id="{$post_id}">
{'label_upload_processing'|lang}
</div>

{else if $post->upload_status == 2}
<div class="preview_processing" data-post-id="{$post_id}" data-post-count="{$post->process_count}">
{'label_upload_post_processing'|lang|sprintf:{$progress_count}:{$post->process_count}}
				<!-- Progress Bar -->
				<div class="fileupload-progress fade in">
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{$progress_count * 100 / $post->process_count}">
						<div class="progress-bar progress-bar-success" style="width:{$progress_count * 100 / $post->process_count}%; height: 20px;"></div>
					</div>
				</div>
</div>

{else if $post->upload_status == 5 && $file_count == 0}
{'label_upload_no_preview_file'|lang}
{else if $post->upload_status == 5}
	<div class="clearfix">
		<p class="slide_current"></p>
		<div class="slide_main">
		</div>
		<div class="slide_cover">
			<div class="slide_cont">
				<ul class="slide_list" data-cache-num="{$cacheNum}" data-paging-num="{$pagingNum}">
					{foreach $file_list as $file}
					<li>
						<a href="javascript:;">
							<p>{$file['number']}</p>
							<div data-slide-index="{$file['index']}" 
								data-path-thumb="{$file['thumb']}" 
								data-path-main="{$file['main']}" 
								data-path-movie="{$file['movie']}" 
								data-path-download="{$file['down']}" >
							</div>
						</a>
					</li>
					{/foreach}
				</ul>
				<div class="slide_wrap">
					<a class="slide_back" href="javascript:;">＜</a>
					<ul class="slide_list">
						<li><a href="javascript:;"><p></p><div></div></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="clearfix">
		<div class="pre_ui">
			<a class="slide_prev" data-move="-1" href="javascript:;"></a>
			<a class="slide_next" data-move="1" href="javascript:;"></a>
			<a class="slide_prev move_p" data-move="-{$moveNum}" href="javascript:;"></a>
			<a class="slide_next move_n" data-move="{$moveNum}" href="javascript:;"></a>
	
			<a class="slide_toggle_play" href="javascript:;"><span>PLAY OR STOP</span></a>
			<a class="btn_download" target="_top" href="javascript:;"><span>{'label_button_preview_download'|lang}</span></a>
		</div>

		<div class="direct">
			<label for="slide_num">{'label_file_slide_number'|lang}</label>
			<input id="slide_num" type="text" size="5">
			<input id="move_slide" type="button" value="{'label_file_slide_move'|lang}">
		</div>
	</div>
{/if}
</div>

<script type="text/javascript" src="{$fixed_base_url}assets/js/potlite.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/preview.js"></script>

{/if} {* $auth *}
</body>
</html>