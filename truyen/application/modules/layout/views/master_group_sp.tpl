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
		{'group/'|cat:$master_group_id|form_open:'id=form_search_area'}
				<div class="search_area" id="search_area"><input type="text" name="keyword" value="{$keyword|h}" placeholder="{'label_placeholder_search'|lang}" maxlength="64"><input type="image" src="{$fixed_base_url}assets_sp/img/{$language}/btn_search.png" value=" {'label_button_search'|lang} " class="searchbtn"></div>
		{''|form_close}
			</div>
		{/if}
	</div>

	<h2 class="group_push mb20 gr_h2_border mic6"><span class="text-word-break">{$master_group_name}</span></h2>
	{block name=body}{/block}
	<!-- footer -->
	<div class="foot">
		<ul>
			<li><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a></li>
			<li><a href="{$fixed_base_url}other/term">{'label_footer_terms'|lang}</a></li>
			<li><a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a></li>
			<li><a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a></li>
			<li><a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang} </a></li>
		</ul>
		<p>© 2014 QLife, Inc. </p>
	</div>
</div>
<div id="menumodal">
	<div class="in">
		<p class="alignR"><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow.png" width="13%" class="close"></p>
		<!--グループデータ-->
		<div class="sidecate">
			<div class="top"><h2>{'label_group_group_data'|lang}</h2></div>
			<div class="sideinner">
				<h2 class="group_push_n mic6 text-word-break"><span>{$master_group_name}</span></h2>
				<div class="sideprofarea">
					<p class="prof mt10 text-word-break">{$master_group_summary}</p>
					{if ($master_group_owned && isset($process_edit) == FALSE)}
						<ul class="btn clearfix">
							<li><a href="{$fixed_base_url}group/{$master_group_id}/edit">{'label_link_edit'|lang}</a></li>
						</ul>
					{/if}
				</div>
			</div>
		</div>
		
		<!--参加メンバー-->
		<div class="sidecate">
			<div class="top"><h2>{'label_group_members'|lang}</h2></div>
			<div class="gr_list">
				<ul class="member">
					{if isset($master_group_owner) }
					<li>
						<a href="{$fixed_base_url}user/{$master_group_owner['id']}/personal_profile" class="mic{$master_group_owner['category_id']}">
							<p class="ellipsisline">
								{$master_group_owner['name']}&nbsp;&nbsp;{$master_group_owner['organization']}&nbsp;&nbsp;{$master_group_owner['position']}
							</p>
						</a>
					</li>
					{/if}
				{foreach $master_group_member as $member}
					{if ($member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE) || ($master_group_owned)}
						<li>
							<a href="{$fixed_base_url}user/{$member['id']}/personal_profile" class=" mic{$member['category_id']}">
								<p class="ellipsisline">
									{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
								</p>
							</a>
						</li>
					{/if}
				{/foreach}
				{foreach $master_group_member_invite as $member}
					{if ($member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE) || ($master_group_owned)}
						<li>
							<a href="{$fixed_base_url}user/{$member['id']}/personal_profile" class="mic{$member['category_id']}">
								<p class="ellipsisline">
									{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
								</p>
							</a>
						</li>
					{/if}
				{/foreach}
				</ul>
			</div>
			{if ($master_group_owned && isset($process_edit) == FALSE)}
				<div class="listbottom">
					<p class="btn3"><a href="#groupmembermodal" class="groupmembermodal">{'label_group_menbers_edit'|lang}</a></p>
				</div>
			{/if}
		</div>

		{if $master_group_joined}
		<div class="btn">
		{''|form_open:'id=form_unsubscribe'}
			<a onclick="confirm_unsubscribe_group()">
				{'label_group_unsubscribe'|lang}
			</a>
		{''|form_close}
		</div>
		{/if}
	</div>
</div>
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
<!--参加メンバー-->
{if ($master_group_owned && isset($process_edit) == FALSE)}
<div id="groupmembermodal">
	<div class="in">
		<div class="modalhead">
			<a class="minibtn" onclick="update_group()">{'label_button_finish'|lang}</a>
			<a href="#menumodal" class="return" id="cancel_edit"><p class="close">{'label_button_cancel'|lang}</p></a>
		</div>
		<p><a href="#menumodal" class="return" id="back_menu_modal"><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow.png" width="13%" id="return_menu_modal" class="close"></a></p>
		<h2>{'label_group_members'|lang}</h2>
		<div class="gr_list">
			<input type="hidden" id="hdn_invite_user" name="hdn_invite_user"/>
			<ul class="member_edit">
				{foreach $master_group_member as $member}
					{if $member['status'] == $smarty.const.STATUS_GROUP_USER_OWNER_APPROVE}
						<li id="id{$member['id']}" class="chk">
						<a onclick="approve_user({$member['id']})" class="mic{$member['category_id']}">
							<span>{'label_button_approve'|lang}</span>
					{else if $member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE}
						<li id="id{$member['id']}">
						<a onclick="delete_user({$member['id']})" class="mic{$member['category_id']}">
							<span>X</span>
					{/if}
							<p class="ellipsisline">
								{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
							</p>
						</a>
					</li>
				{/foreach}
				{foreach $master_group_member_invite as $member}
					<li id="id{$member['id']}" >
						<a onclick="delete_user({$member['id']})" class="mic{$member['category_id']}">
							<span>X</span>
							<p class="ellipsisline">
								{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
							</p>
						</a>
					</li>
				{/foreach}
				<div id ="invite_member">
				</div>
			</ul>
		</div>
		<div class="btn ">
			<a href="#mailtomodal" class="mailtomodal r2l" onclick="get_invite_member()">{'label_invite_members'|lang}</a>
		</div>
	</div>
</div>
{/if}
<div id="mailtomodal"></div>
<div id="file_modal">
	<div class="in">
		<p><img src="{$fixed_base_url}assets_sp/img/backarrow.png" width="13%" class="close"></p>
		<h2>{'label_attach_list'|lang}</h2>
		<div class="modal_inner"></div>
	</div>
</div>
{literal}
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets_sp/js/thickbox.js{literal}"></script>
	<script type="text/javascript">
	window.$body = $(document.body);
	// iSCrollが必要かの判別 AndroidかつWebViewコンポーネントがChromeでないもの
	window.needIscroll = /android/.test(navigator.userAgent.toLowerCase()) && !(/chrome/.test(navigator.userAgent.toLowerCase()));

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
		$.getScript("{/literal}{$fixed_base_url}{literal}assets_sp/js/modal.js");
	} else if (!$("body.modalOpen").length) {
		$(".modalOpen").detach().appendTo("body");
		$(".modalBase").detach().appendTo("body");
	}

	if (!$("#processing").length){
		$("body").append('<div id="processing" class="hide">'
			+	'<div class="processing_mark"></div>'
			+	'<div class="show">'
			+		'<img src="{/literal}{$fixed_base_url}{literal}assets/img/ajax_processing.gif" width="80px" height="80px">'
			+	'</div>'
			+'</div>');
	} else if (!$("body#processing").length) {
		$("#processing").detach().appendTo("body");
	}
	
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
</script>
{/literal}
{if $master_group_joined}
	{literal}
	<script type="text/javascript">
	$(".modal .ctbox").append(
		'<div class="ctinner_m" style="display:none" id="unsubscribe-confirm-dialog">'
		+'<p style="margin-bottom:10px;">{/literal}{$msg_confirm_unsubscribe}{literal}</p>'
		+'<p class="ct m20">'
			+ '<a onclick="modal_unsubscribe_clicked(false)"><img src="{/literal}{$fixed_base_url}assets_sp/img/{$language}/modal_iie.png{literal}"/></a>'
			+ '<a onclick="modal_unsubscribe_clicked(true)"><img src="{/literal}{$fixed_base_url}assets_sp/img/{$language}/modal_hai.png{literal}"/></a>'
		+'</p>'
		+'</div>');
	
	
	function confirm_unsubscribe_group() {
		$(".ctinner_m").hide();
		$("#unsubscribe-confirm-dialog").show();
		
		$('.modalOpen').click();
	}

	
	function modal_unsubscribe_clicked(confirm){
		$('.modalBase').removeClass("ready shown");
		
		$(".ctinner_m").show();
		$("#unsubscribe-confirm-dialog").hide();
	
		if (confirm){
			var urlAction = "{/literal}{$fixed_base_url}group/leave{literal}";
			var group_id = "{/literal}{$master_group_id}{literal}";
			var token = $("#form_unsubscribe input[name=csrf_token]").val();
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: token, group_id: group_id},
				success : function(successful){
					if (successful){
						window.location.replace("{/literal}{$fixed_base_url}user{literal}");
					} else {
						window.location.reload();
					}
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}{literal}");
				}
			});
		}
	}
	</script>
	{/literal}
{/if} {* ($master_group_joined) *}
{include file="../../layout/views/tracking_script.tpl"}
{block name=javascript}{/block}

<div id="preview_wrap" >
<div id="prein">
</div>
</div>
<p id="page-top"><a href="#top"><img src="{$fixed_base_url}assets_sp/img/japanese/pagetop.png" ></a></p>
</body>
</html>
