<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/custom.css"/>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.inview.min.js"></script>
<meta charset="UTF-8">
<title></title>
</head>
<body class="sc_bg">
{if !$auth}
	{literal}
	<script type="text/javascript">
		window.parent.location.reload();
	</script>
	{/literal}
{else}
	<!--お知らせ-->
	<div id="info_modal">
		<h2>{'label_notice'|lang}</h2>
		<div class="modal_inner">
			<ul class="info_list">
				{foreach $notices as $notice}
				<li id="{$notice['id']}" class="{$notice['status']}"><a target="_top" href="{$notice['link']}">{$notice['message']}</a></li>
				{/foreach}
				<li class="update" style="display:none"><a href="#">{'label_updating'|lang}</a></li>
			</ul>
		</div>
		<p class="ct">
			<a href="javascript:parent.$.pageslide.close()" _target="_parent"><img src="{$fixed_base_url}assets/img/{$language}/modal_close.png" alt="{'label_close'|lang}"></a>
		</p>
	</div>
	<!--/お知らせ-->
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
		var el_notice_updating = $(".update");
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
						 		var html = "<li id='" + notice.id + "' class='"+ notice.status +"'><a target='_top' href='" + notice.link + "'>" + notice.message + "</a></li>";
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
			var max_height = window.innerHeight - 120;
			$(".info_list").css("max-height", max_height);
		};

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
</body>
</html>
