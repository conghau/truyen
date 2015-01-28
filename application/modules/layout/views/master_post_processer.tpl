<a href="" class="modalOpen" id="btn-dialog"></a>
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p" style="overflow-y: auto; max-height: 500px;"></p>
					<p class="ct m20">
						<a id="btn_no" class="modalClose" href=""><img src="{$fixed_base_url}assets/img/{$language}/modal_iie.png"></a>
						<a id="btn_yes" class="modalClose" href=""><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
					</p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
<div id="processing" class="hide" >
	<div class="processing_mark"></div>
	<div class="show"><img src="{$fixed_base_url}assets/img/btn_progress.gif" ></div>
</div>
<input type="hidden" id="message_error" value="{$message_error}"/>
<input type="hidden" id="msg_confirm_del_comment" value="{$msg_confirm_del_comment}"/>
<input type="hidden" id="msg_confirm_del_thread" value="{$msg_confirm_del_thread}"/>
<input type="hidden" id="send_to_id" value="{if isset($send_to_id)}{$send_to_id}{/if}"/>


<div class="clearfix">
	<p class="kanfa"><a onclick="create_post()" id="create_post"><img src="{$fixed_base_url}assets/img/{$language}/btn_canfa.png" alt="{'label_user_create_new_thread'|lang}"></a></p>
	<div class="per_area">
		<div class="clearfix">
			<div class="zan">{'label_upload_freespace'|lang}</div>
			<div class="graf">
				<div class="grafwh">
					<div class="bar" style="width:{round($user_used/$user_size,2)*100}%"></div>
				</div>
			</div>
			<div class="percent">
				{sprintf('label_upload_size_user'|lang,$user_used,$user_size-$user_used)}
			</div>
		</div>
	</div>
</div>

<div style="display:none" id="post_create"></div>
<div class="ctbox">
{include file='master_post_layout.tpl'}
</div>
<div id="list_post_bottom"></div>

<style>
	.enter_head {
		width: 500px;
	}
	textarea {
		resize:none;
	}
	a {
		cursor: pointer;
		text-decoration: none;
	}
</style>

<script src="{$fixed_base_url}assets/js/post.js"></script>

<script>
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
		var send_to_id = $("#send_to_id").val();
		if(send_to_id != '') {
			create_post_send_to(send_to_id);
		}
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
				url: fixed_base_url + {/literal}{if isset($group_id)}'group/' + group_id + '/file/' + $(this).attr('data-post-id'){else}'user/file/' + $(this).attr('data-post-id'){/if}{literal},
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
				url: fixed_base_url + 'file/processing/' + $(this).attr('data-post-id'),
				success: function(json){
					if (json.upload_status == 5) {
						iframe.prevObject.get(0).contentDocument.location.reload(true);
					} else {
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
		var suffix = this.id.replace(/^.*?_/, "");
		$('#show_' + suffix).hide();
		$('#hide_' + suffix).show();
		$("div[name='display_comment_" + suffix + "']").remove();
		get_comment_list(suffix);
		$('#list_comment_' + suffix).show();
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

	$(document).on('click','.btn-beforedownload',function(){
		var THIS = $(this);
		if (THIS.hasClass("disabled")) {
			return false;
		}
		
		THIS.children().attr('src', '{/literal}{$fixed_base_url}{literal}assets/img/btn_progress.gif');
		var csrf_token = THIS.parent().find("input[name='csrf_token']").val();
		$.ajax({
			type: 'POST',
			url: $(this).attr('href'),
			data: {csrf_token: csrf_token},
			beforeSend: function() { THIS.addClass('disabled');},
			success: function(data){
				THIS.removeAttr("style");
				THIS.addClass('hide');
				THIS.parent().children('.btn-batchdownload').removeClass('hide');
				THIS.children().attr('src', '{/literal}{$fixed_base_url}assets/img/{$language}{literal}/btn_file_dl.png');
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
