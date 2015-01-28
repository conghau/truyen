<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=0">
<title>{block name=title}{/block}</title>

<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/css/style.css"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/css/custom.css"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/css/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/js/thickbox.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/css/tinytools.toggleswitch.css">
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets_sp/css/preview.css"/>
{include file="master_upload_css_sp.tpl"}

<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/modal.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/post.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/jquery.cookie.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/tinytools.toggleswitch.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/jquery.inview.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets_sp/js/linedot.js"></script>
{include file="master_upload_js.tpl"}

{block name=stylesheet}{/block}
</head>

<body>
<div id="content_area">
	<div class="header">
		{if !isset($no_had_left)}
			<!-- header -->
			<div class="ctwrap clearfix">
				<div class="menu"><a href="#menumodal" class="menumodal">MENU</a></div>
				<div class="home"><a href="{$fixed_base_url}">HOME</a></div>
				<h1><a href="{$fixed_base_url}"><img src="{$fixed_base_url}assets_sp/img/logo.png" class="logo"></a></h1>
				<div class="mailto"><a onclick="get_dest()" href="#mailtomodal" class="mailtomodal r2l">{'label_post_can_be_sent'|lang}</a>
				<span>({$num = ((isset($master_notice_num))?$master_notice_num:'')}{$num})</span>
				</div>
				<div class="info"><a onclick="get_notice()" href="#info_modal" class="info_modal r2l"></a></div>
		{$fixed_base_url|form_open:'id=form_search_area'}
				<div class="search_area" id="search_area"><input type="text" name="keyword" value="{$keyword|h}" placeholder="{'label_placeholder_search'|lang}" maxlength="64"><input type="image" src="{$fixed_base_url}assets_sp/img/{$language}/btn_search.png" value=" {'label_button_search'|lang} " class="searchbtn"></div>
		{''|form_close}
			</div>
		{else}
			<div class="ctwrap clearfix">
				<h1><a href="{$fixed_base_url}"><img src="{$fixed_base_url}assets_sp/img/logo.png" class="logo"></a></h1>
			</div>
		{/if}
	</div>
{block name=body}
<div style="display:none" id="post_create"></div>
<div class="ctinner tround">
	<div class="static_wrap pd10">
		{'label_group_no_exist'|lang}
	</div>
</div>
{/block}
</div>

{if !isset($controller) || $controller != 'login'}
<div id="info_modal" style="display: none;">
	<div class="in">
		<p><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow_l.png" width="13%" class="close"></p>
		<h2>{'label_notice'|lang}</h2>
		<div class="modal_inner">
			<ul class="info_list">
				<li class="update"><a href="#">{'label_updating'|lang}</a></li>
			</ul>
		</div>
	</div>
</div>
<div id="mailtomodal"></div>
<div id="file_modal"></div>
<div id="menumodal">
{if isset($user)}
	<div class="in">
		<p class="alignR"><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow.png" width="13%" class="close"></p>
		<!--利用者管理-->
		<div class="sidecate">
			<div class="top"><h2>{'label_user_user_data'|lang}</h2></div>
			<div class="sideinner">
				<div class="sideprofarea">
					<p class="name mic{$master_category_id}">{(isset($master_user_name)) ? $master_user_name : ''}</p>
					<p class="prof">{(isset($master_user_department)) ? $master_user_department : ''}</p>
					<ul class="btns2 clearfix">
						{$master_id = ((isset($master_user_id)) ? $master_user_id : '')}
						<li><a href="{$fixed_base_url}user/edit">{'label_user_link_edit_info'|lang}</a></li>
						<li><a href="{$fixed_base_url}logout" id="btn-logout">{'label_logout'|lang}</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--投稿管理-->
		<div class="sidecate">
			<div class="top"><h2>{'label_upload_freespace'|lang}</h2></div>
			<div class="sideinner">
				<div class="percent">{sprintf('label_upload_size_user'|lang,$user_used,$user_size-$user_used)}</div>
				<div class="graf">
					<div class="grafwh">
						<div class="bar" style="width:{if $user_size > 0}{round($user_used/$user_size,2)*100}{/if}%"></div>
					</div>
				</div>
			</div>
		</div>
		<!--ログ/集計管理-->
		<div class="sidecate">
			<div class="top"><h2>{'label_group_joined'|lang}</h2></div>
			<ul class="groupmenu scroll">
				{$master_group_joined = ((isset($master_group_joined))?$master_group_joined:'')}
				{if $master_group_joined neq ''}
				{foreach $master_group_joined as $item}
					<li><a href="{$fixed_base_url}group/{$item->id}" class="mic6">{$item->name}</a></li>
				{foreachelse}
					<li class="none">{'label_none'|lang}</li>
				{/foreach}
				{else}
					<li class="none">{'label_none'|lang}</li>
				{/if}
			</ul>
		</div>
		<!--ログ/集計管理-->
		<div class="sidecate">
			<div class="top"><h2>{'label_group_can_join'|lang}</h2></div>
			<ul class="groupmenu scroll">
				{$master_group_can_join = ((isset($master_group_can_join))?$master_group_can_join:'')}
				{if $master_group_can_join neq ''}
				{foreach $master_group_can_join as $item}
				<li><a href="{$fixed_base_url}group/{$item->id}" class="mic5">{$item->name} {sprintf('label_group_member'|lang,$item->total_member)}</a></li>
				{/foreach}
				{/if}
			</ul>
		</div>
		<div class="btn">
			<p><a href="{$fixed_base_url}group/create">{'label_group_create'|lang}</a></p>
		</div>
{/if}
<!-- footer -->
<div class="foot">
	<ul>
		<li><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a></li>
		<li><a href="{$fixed_base_url}other/term" >{'label_footer_terms'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a></li>
		<li><a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a></li>
	</ul>
	<p>© 2014 QLife, Inc. </p>
</div>

	</div>
</div>
{literal}
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets_sp/js/thickbox.js{literal}"></script>
	<script type="text/javascript">
		var fixed_base_url = "{/literal}{$fixed_base_url}{literal}";
		window.$body = $(document.body);
		//window.iscrollIns = null;
		// iSCrollが必要かの判別 AndroidかつWebViewコンポーネントがChromeでないもの
		window.needIscroll = /android/.test(navigator.userAgent.toLowerCase()) && !(/chrome/.test(navigator.userAgent.toLowerCase()));

		function get_dest() {
			var url_get = "{/literal}{$fixed_base_url}post/dest_list/{$smarty.const.MODAL_HIDE_BUTTON_SEND}{literal}";
			$.ajax({
				type: 'GET',
				url:url_get,
				dataType:"html",
				cache:false,
				success:function(data){
					$("#mailtomodal").html(data);
				}
			});
		}
		
		var notice_loading = false;
		function get_notice() {
			if (!$("#notice_loaded").length && !notice_loading){
				notice_loading = true;
				var url_get = "{/literal}{$fixed_base_url}notice{literal}";
				$.ajax({
					type: 'GET',
					url: url_get,
					cache: false,
					success: function(data){
						$("#info_modal").html(data);
						notice_loading = false;
					},
					error: function(){window.location.reload();},
				});
			}
		}

		if (!$(".modalOpen").length){
			$("body").append('<div class="modalOpen"></div>'
			+'<div class="modalBase">'
			+	'<div class="modalMask"></div>'
			+	'<div class="modalWrap">'
			+		'<div class="modal">'
			+			'<div class="ctbox"></div>'
			+		'</div>'
			+	'</div>'
			+'</div>');
		} else if (!$("body.modalOpen").length) {
			$(".modalOpen").detach().appendTo("body");
			$(".modalBase").detach().appendTo("body");
		}

		$(".modal .ctbox").append(
			'<div class="ctinner_m" style="display:none" id="logout-confirm-dialog">'
			+'<p style="margin-bottom:10px;">{/literal}{(isset($msg_confirm_logout))?$msg_confirm_logout :""}{literal}</p>'
			+'<p class="ct m20">'
				+ '<a onclick="modal_logout_clicked(false)"><img src="{/literal}{$fixed_base_url}assets_sp/img/{$language}/modal_iie.png{literal}"/></a>'
				+ '<a onclick="modal_logout_clicked(true)"><img src="{/literal}{$fixed_base_url}assets_sp/img/{$language}/modal_hai.png{literal}"/></a>'
			+'</p>'
			+'</div>');
			

		$("#btn-logout").click(confirm_logout);
		function confirm_logout(event) {
			event.preventDefault();
			$(".ctbox .ctinner_m").hide();
			$(".ctbox .ttl").hide();
			$(".ctbox .ctinner").hide();
			$("#logout-confirm-dialog").show();
			$('.modalOpen').click();
		}

		function modal_logout_clicked(confirm){
			$('.modalBase').removeClass("ready shown");
			$(".ctbox .ctinner_m").show();
			$(".ctbox .ttl").show();
			$(".ctbox .ctinner").show();
			$("#logout-confirm-dialog").hide();

			if (confirm){
				window.location.replace("{/literal}{$fixed_base_url}logout{literal}");
			}
		}

		function sel_user_group(item) {
			var li = $(item);
			var input = li.find("input");
			if( input.length != 0 ){
				input.get(0).checked = !input.get(0).checked;
				li.toggleClass("sel",input.get(0).checked);

				if (input.get(0).name == 'chk_user[]') {
					$('input[name="chk_user[]"]').each(function () {
						if (input.get(0).value === $(this).val()) {
							$(this).prop('checked', input.get(0).checked);
							if (input.get(0).checked) {
								$(this).parent().parent().addClass('sel');
							} else {
								$(this).parent().parent().removeClass('sel');
							}
						}
					});
				} else {
					$('input[name="chk_group[]"]').each(function () {
						if (input.get(0).value === $(this).val()) {
							$(this).prop('checked', input.get(0).checked);
							if (input.get(0).checked) {
								$(this).parent().parent().addClass('sel');
							} else {
								$(this).parent().parent().removeClass('sel');
							}
						}
					});
				}
			}
		}
	</script>
{/literal}
{/if}
{include file="../../layout/views/tracking_script.tpl"}
{block name=javascript}{/block}

<div id="preview_wrap" >
<div id="prein">
</div>
</div>
<p id="page-top"><a href="#top"><img src="{$fixed_base_url}assets_sp/img/japanese/pagetop.png" ></a></p>
</body>
</html>