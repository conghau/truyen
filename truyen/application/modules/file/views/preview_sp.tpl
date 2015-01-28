{*　JS側で呼び出していたため、以下不要
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/potlite.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/preview.js"></script>
*}
<div id="previewHtml">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-basic.css">
{if !$auth}
	{literal}
	<script type="text/javascript">
		window.location.reload();
	</script>
	{/literal}
{else}

<div class="pe_inner" style="position:fixed;top:0;left:0;color:#fff;background-color: #000;height: 100%;width: 100%;z-index:9999;">
	<div class="clearfix">
		<a onclick="javascript:closePreview();" class="closebtn">×</a>

{if $post->upload_status == 0}
<div class="no_file">
{'label_upload_no_upload_file'|lang}
</div>
{else if $post->upload_status == 1}
<div class="no_file">
{'label_upload_processing_sp'|lang}
</div>
{else if $post->upload_status == 2 && $post->process_count == 0}
<div class="no_file">
{'label_upload_processing_sp'|lang}
</div>
{else if $post->upload_status == 2}
<div class="no_file">
<div class="preview_processing" data-post-id="{$post_id}" data-post-count="{$post->process_count}">
{'label_upload_post_processing_sp'|lang|sprintf:{$progress_count}:{$post->process_count}}

				<!-- Progress Bar -->
				<div class="fileupload-progress">
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar progress-bar-success" style="width:0%;"></div>
					</div>
				</div>
</div>	
</div>

{else if $post->upload_status == 5 && $file_count == 0}
<div class="no_file">
{'label_upload_no_preview_file'|lang}
</div>
{else if $post->upload_status == 5}
		<span class="slide_current"></span>

		<div class="pre_ui">
			<div class="clearfix">
				<div class="direct">
					<label for="slide_num">{'label_file_slide_number'|lang} </label>
					<input id="slide_num" type="text" size="5">
					<input id="move_slide" type="button" value="{'label_file_slide_move'|lang}">
				</div>
				<div class="thumb_ui clearfix">
					<a class="slide_prev" data-move="-{$moveNum}"  href="javascript:;">&lt;&lt;</a>
					<a class="slide_prev" data-move="-1"  href="javascript:;">＜</a>
					<a class="slide_toggle_play" href="javascript:;"><span>PLAY OR STOP</span></a>
					<a class="slide_next" data-move="1" href="javascript:;">＞</a>
					<a class="slide_next" data-move="{$moveNum}" href="javascript:;">&gt;&gt;</a>
					<a class="slide_toggle" href="javascript:;"><span>menu</span></a>
				</div>
			</div>
		</div>

		<div class="slide_main"></div>
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
{/if}
	</div>
</div>

{/if} {* $auth *}
</div>
