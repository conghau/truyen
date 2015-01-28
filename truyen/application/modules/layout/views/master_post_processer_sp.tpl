<a class="modalOpen" id="btn-dialog"></a>
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal" style="width:300px">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p" style="overflow-y: auto; max-height: 300px;"></p>
					<p class="ct m20">
						<a id="btn_no" class="modalClose" href=""><img src="{$fixed_base_url}assets_sp/img/{$language}/modal_iie.png"></a>
						<a id="btn_yes" class="modalClose" href=""><img src="{$fixed_base_url}assets_sp/img/{$language}/modal_hai.png"></a>
						<a id="btn_ok" class="modalClose" style="display:none" href=""><img src="{$fixed_base_url}assets_sp/img/{$language}/ok.png"></a>
					</p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
<div id="processing" class="hide" >
	<div class="processing_mark"></div>
	<div class="show"><img src="{$fixed_base_url}assets/img/ajax_processing.gif" width="80px" height="80px"></div>
</div>
<input type="hidden" id="message_error" value="{$message_error}"/>
<input type="hidden" id="msg_confirm_del_comment" value="{$msg_confirm_del_comment}"/>
<input type="hidden" id="msg_confirm_del_thread" value="{$msg_confirm_del_thread}"/>
<input type="hidden" id="send_to_id" value="{if isset($send_to_id)}{$send_to_id}{/if}"/>

<div class="ctwrap clearfix">
	<div class="mainarea">
		<p class="mb10"><a onclick="create_post()" id="create_post"><img src="{$fixed_base_url}assets_sp/img/{$language}/btn_canfa.png" width="100%"></a></p>
		<div class="ctbox">
			<!--新規スレッド投稿／コピー（個人ダッシュボード）【B-7】-->
			<div style="display:none" id="post_create"></div>
			<!--/新規スレッド投稿／コピー（個人ダッシュボード）【B-7】-->
			
			{include file='master_post_layout_sp.tpl'}
			
			<div id="list_post_bottom"></div>
		</div><!--/ctbox-->
	</div><!--/mainarea-->
</div><!--/ctwrap-->

<script type="text/javascript">
	$('body').append('<div id="preview_wrap" ><div id="prein"></div></div>');
</script>


<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/post.js"></script>
<script type="text/javascript">
	var fixed_base_url = "{$fixed_base_url}";

{if isset($group_id)}
	var group_id = {$group_id};
{/if}
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
		var count = (html.match(/post_detail_/g) || []).length;
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

	function show_click(item) {
		var suffix = item.id.replace(/^.*?_/, "");
		$('#show_' + suffix).hide();
		$('#hide_' + suffix).show();
		$("div[name='display_comment_" + suffix + "']").remove();
		get_comment_list(suffix);
		$('#list_comment_' + suffix).show();
	}

	function hide_click(item) {
		var suffix = item.id.replace(/^.*?_/, "");
		$('#list_comment_' + suffix).hide();
		$('#show_' + suffix).show();
		$('#hide_' + suffix).hide();
		$("div[name='display_comment_" + suffix + "']").remove();
	}
	
	$(document).ready(function(){
		var send_to_id = $("#send_to_id").val();
		if(send_to_id != '') {
			create_post_send_to(send_to_id);
		}
		updateFileRemainTime();
		setInterval(updateFileRemainTime, 60000);
		setInterval(updateFileUploadStatus, 12000);
		setInterval(updatePreviewStatus, 5000);
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
				data: {csrf_token: csrf_hidden{/literal}{if isset($keyword) && $keyword != ''}{literal}, keyword: '{/literal}{$keyword|urlencode}{literal}'{/literal}{/if}{literal}},
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
//		var iframe = $('iframe:first').contents();
		$(".preview_processing").each(function(){
			var target_count = $(this).attr('data-post-count') ;
			var post_id = $(this).attr('data-post-id');
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: fixed_base_url + 'file/processing/' + post_id,
				success: function(json){
					if (json.upload_status == 5) {
						$.ajax({
							type: 'GET',
							url: fixed_base_url + 'file/preview/' + post_id,
							success: function(html){
								$('#previewHtml').html(html);
								initSlide();
							},
							error: function(){
								window.location.reload();
							}
						});
					} else {
		                var progress = Math.floor(json.progress_count / target_count * 100);
						$('#file_progress').html(json.progress_count);
                        $('.progress').attr('aria-valuenow', progress).children().first().css(
                                'width',
                                progress + '%'
                            );					}
				},
				error: function(){
					window.location.reload();
				}
			});
		});
	}


	var checker = new Array();
	$(document).on('inview', '.entrybox', function(event, isInView, visiblePartX, visiblePartY) {
		if (visiblePartY == 'bottom' || visiblePartY == 'both' ){
			if (checker[$(this).attr('post-id')]) {
				return;
			}
			//要素のしたが見えるようになったときに実行する処理
			checker[$(this).attr('post-id')] = true;

			if ($(this).attr('owner') === '0') {
				insert_view($(this).attr('post-id'));
			}
		}
	});

	$(document).on('click','.file_modal', function(e){
		var url_get = "{/literal}{$fixed_base_url}file/file_list/{literal}" + $(this).attr('data-rel');
		$.ajax({
			type: 'GET',
			url:url_get,
			dataType:"html",
			cache:false,
			success:function(data){
				$("#file_modal").html(data);
			}
		});
	});
</script>
{/literal}
