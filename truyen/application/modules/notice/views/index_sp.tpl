{if !$auth}
{literal}
<script type="text/javascript">
	window.location.reload();
</script>
{/literal}
{else}
<div id="notice_loaded" style="display:none"></div>
<div class="in">
	<p><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow_l.png" width="13%" class="close"></p>
	<h2>{'label_notice'|lang}</h2>
	<div class="modal_inner">
		<ul class="info_list">
			{foreach $notices as $notice}
			<li id="{$notice['id']}" class="{$notice['status']}"><a href="{$notice['link']}">{$notice['message']}</a></li>
			{/foreach}
			<li class="update" style="display:none"><a href="#">{'label_updating'|lang}</a></li>
		</ul>
	</div>
</div>
<style>
	.info_list {
		overflow: auto;
		height: auto;
	}
	.info_list li.unread {
		font-weight: bold;
	}
</style>
{literal}
<script type="text/javascript">
	var notice_offset = {/literal}{count($notices)}{literal};
	var notice_nomore = false;
	var notice_loading = false;
	var el_notice_list = $(".info_list");
	var el_notice_updating = $(".info_list .update");
	var preloadHeight = $(window).height();
	$(window).resize(updateNoticeListSize);
	updateNoticeListSize();	

	el_notice_list.scroll(function(){
		if (el_notice_list.scrollTop() + el_notice_list.height() > el_notice_list.prop('scrollHeight') - preloadHeight) {
			loadNotices();
		}
	});

	function loadNotices() {
		if (!notice_loading && !notice_nomore) {
			el_notice_updating.show();
			notice_loading = true;
			$.ajax({
				type: 'GET',
				url: '{/literal}{$fixed_base_url}notice/get{literal}',
				data: {offset: notice_offset},
				dataType: "json",
			 	success: function(data) {
				 	if (data.length > 0) {
				 		$.each(data, function(i, notice) {
				 			var html = "<li id='" + notice.id + "' class='"+ notice.status +"'><a href='" + notice.link + "'>" + notice.message + "</a></li>";
					 		el_notice_updating.before(html);
				 		});
				 		notice_offset += data.length;

				 		if (el_notice_list.height() < parseInt(el_notice_list.css("max-height"))
						 		|| el_notice_list.height() > el_notice_list.prop('scrollHeight') - preloadHeight) {
				 			notice_loading = false;
				 			loadNotices();
				 			return;
				 		}
				 	}
				 	else {
				 		notice_nomore = true;
				 	}
				 	el_notice_updating.hide();
			 		notice_loading = false;
			 	},
			 	error: function(){
			 		window.parent.location.reload();
			 	}
			});
		}
	};

	function updateNoticeListSize() {
		var max_height = window.innerHeight - $(".info_list").position().top - 2 * $(".info_list").position().left;
		$(".info_list").css("max-height", max_height);
	}

		$(document).on('click','.info_list li.unread', function(event) {
			$(this).removeClass('unread').addClass('read');
		});

		$(document).on('inview','.info_list li.unread', function(event, isInView, visiblePartX, visiblePartY) {
			if (visiblePartY == 'bottom' || visiblePartY == 'both' ){
				if(!$(this).data('read')){
					$(this).data('read', true);
					$.get("{/literal}{$fixed_base_url}{literal}notice/set_read/" + this.id, function (data) {});
				}
			}
		});

</script>
{/literal}
{/if} {* $auth *}