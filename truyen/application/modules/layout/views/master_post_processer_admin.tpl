
<div id="processing" class="hide" >
	<div class="processing_mark"></div>
	<div class="show"><img src="{$fixed_base_url}assets-admin/img/btn_progress.gif" ></div>
</div>

{include file='master_post_layout_admin.tpl'}
<div id="list_post_bottom"></div>

<style>
	.enter_head {
		width: 500px;
	}
	a {
		cursor: pointer;
		text-decoration: none;
	}
</style>
<script src="{$fixed_base_url}assets-admin/js/jquery.pageslide.min.js"></script>
<script type="text/javascript">
	var fixed_base_url = "{$fixed_base_url}";

{if isset($csrf_hidden)}
	var csrf_hidden = '{$csrf_hidden}';
{/if}
{if isset($post_offset) && $post_offset >= 0 }
	var post_offset = {$post_offset};
	var loading_post = false;
	var post_query_url = "{$post_query_url}";
	var preload_height = $(window).height() * 2;

{literal}
	check_preload_post();

	function countPost(html){
		var count = (html.match(/\<div class\=\"ctinner tround\"\>/g) || []).length;
		return count;
	}

	function loadNextPosts(){
		if (loading_post || post_offset < 0)
			return;
		loading_post = true;

		$.ajax({
			type: 'POST',
			url: post_query_url + '/' + post_offset,
			data: {csrf_token: csrf_hidden{/literal}{if isset($keyword) && $keyword != ''}{literal}, keyword: '{/literal}{$keyword|urlencode}{literal}'{/literal}{/if}{literal}},
			success: function(html){
				$("#list_post_bottom").before(html);
				updateFileRemainTime();
				jQuery(".dot").LineDot();
				jQuery(".dot").removeClass("dot");
				$("a.file_modal").pageslide({ direction: "left"});
				loading_post = false;
				var count = countPost(html);
				if (count > 0) {
					post_offset += count;
					check_preload_post();
				} else {
					post_offset = -1;
				}
			},
			error: function(){
				window.parent.location.reload();
			}
		});
	}

	function check_preload_post(){
		var list_bottom = $("#list_post_bottom").offset().top;
		var scroll_bottom = $(window).scrollTop() + $(window).height();

		if (list_bottom < scroll_bottom + preload_height){
			loadNextPosts();
		}
	}
	
	$(window).scroll(check_preload_post);
	$(window).resize(function(){
		preload_height = $(window).height() * 2;
		check_preload_post();
	});

{/literal}
{/if}
{literal}

	$(document).ready(function(){
		updateFileRemainTime()
		setInterval(updateFileRemainTime, 60000);
		setInterval(updateFileUploadStatus, 12000);
		setInterval(updatePreviewStatus, 5000);
		$("a.file_modal").pageslide({ direction: "left"});
	});

	function updateFileRemainTime(){
		$(".uploads_expired").each(function(){
			if ($(this).children("input").val() == '') { /* Unlimited time */
				$(this).html('');
				$(this).removeClass();
				return;
			}
			var exp_time = new Date($(this).children("input").val());
			var remain_time = exp_time - new Date();
			var label;
			if (remain_time < 0) {
				remain_time = 0;
			}
			remain_time /= 60 * 60 * 1000; /* Hours */
			if (remain_time < 72) $(this).addClass("alert");
			if (remain_time >= 24) {
				remain_time /= 24; /* Days */
				remain_time = Math.floor(remain_time);
				label = "{/literal}{'label_user_thread_upload_days'|lang}{literal}";
			} else {
				if (remain_time >= 1) { /* Hours */
					remain_time = Math.floor(remain_time);
					label = "{/literal}{'label_user_thread_upload_hours'|lang}{literal}";
				} else{ /* Minutes */
					remain_time = Math.floor(remain_time * 60);  
					label = "{/literal}{'label_user_thread_upload_minutes'|lang}{literal}";
				}
			}
			$(this).children(".remain_time").html(remain_time + label);
		});
	}

	function updateFileUploadStatus(){
		$(".upload_processing").each(function(){
			var target = $(this);
			$.ajax({
				type: 'GET',
				url: fixed_base_url + {/literal}'admin_tools/post/' + $(this).attr('data-post-id') + '/file'{literal},
				success: function(html){
					if (target.html() != html) {
						target.html(html);
						target.removeClass("upload_processing");
						updateFileRemainTime();
						jQuery(".dot").LineDot();
						jQuery(".dot").removeClass("dot");
					}
				},
				error: function(){
					window.parent.location.reload();
				}
			});
		});
	}

	function updatePreviewStatus(){
		var iframe = $('iframe:first').contents();
		$(".preview_processing", iframe).each(function(){
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: fixed_base_url + 'file/processing/' + $(this).attr('data-post-id') + "/admin",
				success: function(json){
					if (json.upload_status == 5) {
						iframe.prevObject.get(0).contentDocument.location.reload(true);
					} else {
						$('#file_progress', iframe).html(json.progress_count);
						$('#file_progress', iframe).html(json.progress_count);
					}
				},
				error: function(){
					window.parent.location.reload();
				}
			});
		});
	}


	$(document).on('click','[id^="show_"]', function() {
		var post_id = this.id.replace(/^.*?_/, "");
		$('#show_' + post_id).hide();
		$('#hide_' + post_id).show();
		$("div[name='display_comment_" + post_id + "']").remove();

		$.get(fixed_base_url + "admin_tools/" + post_id + "/comment_list", function (data) {
			$("#list_comment_" + post_id).html(data);
		}).fail(function() {
			window.location.replace(fixed_base_url + "admin_tools/login");
		});

		$('#list_comment_' + post_id).show();
	});
	
	$(document).on('click','[id^="hide_"]', function() {
		var suffix = this.id.replace(/^.*?_/, "");
		$('#list_comment_' + suffix).hide();
		$('#show_' + suffix).show();
		$('#hide_' + suffix).hide();
		$("div[name='display_comment_" + suffix + "']").remove();
	});

	$(document).on('hover', '.kidoku', function(e) {
		if ($(this).find(".pop1").html() > 0){
			if (e.type == "mouseenter") {
				$(this).find('.arrow_box').show();
			} else {
				$(this).find('.arrow_box').hide();
			}
		}
	});

	$(document).on('click','.btn-beforedownload',function(){
		var THIS = $(this);
		if (THIS.hasClass("disabled")) {
			return false;
		}
		
		THIS.children().attr('src', '{/literal}{$fixed_base_url}{literal}assets-admin/img/btn_progress.gif');
		$.ajax({
			type: 'POST',
			url: $(this).attr('href'),
			data: {csrf_token: csrf_hidden},
			beforeSend: function() {
				THIS.addClass('disabled');
				THIS.attr('style','cursor: default')
			},
			success: function(data){
				THIS.removeClass('disabled');
				THIS.removeattr('style');
				THIS.addClass('hide');
				THIS.parent().children('.btn-batchdownload').removeClass('hide');
				THIS.children().attr('src', '{/literal}{$fixed_base_url}{literal}assets-admin/img/btn_file_dl.png');
			},
			error: function(data,status, e){
				window.parent.location.reload();
				return false;
			}
		});
		return false;
	});

	$(document).on('click','.btn-batchdownload',function(){
		var THIS = $(this);
		THIS.addClass('hide');
		THIS.parent().children('.btn-beforedownload').removeClass('hide');
	});
</script>
{/literal}
