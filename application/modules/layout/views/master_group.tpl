<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{block name=title}{/block}</title>

<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets/css/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets/js/thickbox.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets/js/jquery.pageslide.css" />
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.css"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets/css/tinytools.toggleswitch.css">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/custom.css"/>
{include file="master_upload_css.tpl"}

<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.ui.datepicker-ja.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/modal.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.cookie.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.inview.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/tinytools.toggleswitch.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/linedot.js"></script>
{include file="master_upload_js.tpl"}
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
{literal}
$(function () {   
  $('.kidoku').hover(
    function() {$(this).find('.arrow_box').show();}, 
    function() {$(this).find('.arrow_box').hide();}
  );
});
{/literal}
</script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/column.js"></script>

{block name=stylesheet}{/block}
</head>

<body>
<div class="header">
	<div class="ctwrap clearfix">
		<h1 class="logo"><a href="{$fixed_base_url}"><img src="{$fixed_base_url}assets/img/{$language}/logo.png"></a></h1>
		<div class="home"><a href="{$fixed_base_url}">Home</a></div>
		{if isset($user->id)}
			<div class="mailto">
				<a href="{$fixed_base_url}post/dest_list/{$smarty.const.MODAL_HIDE_BUTTON_SEND}" class="mailtomodal">
					{'label_post_can_be_sent'|lang}
				</a>
			</div>
			<div class="info">
				<a href="{$fixed_base_url}notice" class="info_modal">
					{$num = ((isset($master_notice_num))?$master_notice_num:'')}
					{sprintf('label_link_notice'|lang,$num)}
				</a>
			</div>
		{'group/'|cat:$master_group_id|form_open:'id=form_search_area'}
				<div class="search_area" id="search_area"><input type="text" name="keyword" value="{$keyword|h}" placeholder="{'label_placeholder_search'|lang}" maxlength="64"><input type="image" src="{$fixed_base_url}assets/img/{$language}/btn_search.png" value=" {'label_button_search'|lang} " class="searchbtn"></div>
		{''|form_close}
		{/if}
	</div>
</div>
<div class="ctwrap clearfix">
	<div class="side">
		<div class="sidecate">
			<div class="top"><h2><img src="{$fixed_base_url}assets/img/{$language}/side_ttl_groupdata.jpg"></h2></div>
			<div class="sideinner">
				<div class="sideprofarea">
					<p class="name mic6 text-word-break">{$master_group_name}</p>
					<p class="prof text-word-break">{$master_group_summary}</p>
					{if ($master_group_owned && isset($process_edit) == FALSE)}
					<div class="alignR">
						<a href="{$fixed_base_url}group/{$master_group_id}/edit"><img src="{$fixed_base_url}assets/img/{$language}/edit.png"></a>
					</div>
					{/if}
				</div>
			</div>
		</div>

		<!--group related-->
		<div class="sidecate">
			<div class="top"><h2><img src="{$fixed_base_url}assets/img/{$language}/side_ttl_groupmembers.jpg"></h2></div>
			{if $master_group_owned && isset($process_edit) == FALSE}
				<div class="groupmenlist">
			{else}
				<div class="groupmenu">
			{/if}
				<ul>
					{if isset($master_group_owner) }
					<li class="owner">
						<a href="{$fixed_base_url}user/{$master_group_owner['id']}/personal_profile" class="mic{$master_group_owner['category_id']}">
							<p class="ellipsisline">
								{$master_group_owner['name']}&nbsp;&nbsp;{$master_group_owner['organization']}&nbsp;&nbsp;{$master_group_owner['position']}
							</p>
						</a>
					</li>
					{/if}
				{foreach $master_group_member as $member}
					{if ($member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE) || ($master_group_owned)}
						{if $member['status'] == $smarty.const.STATUS_GROUP_USER_OWNER_APPROVE}
						<li class="chk" id="id{$member['id']}" data-rel="{$member['id']}">
						{else if $member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE}
						<li class="init" id="id{$member['id']}" data-rel="{$member['id']}">
						{/if}
							<a href="{$fixed_base_url}user/{$member['id']}/personal_profile" class="mic{$member['category_id']}">
								<p class="ellipsisline">
									{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
								</p>
							</a>
						</li>
					{/if}
				{/foreach}
				{foreach $master_group_member_invite as $member}
					{if ($member['status'] == $smarty.const.STATUS_GROUP_USER_ENABLE) || ($master_group_owned)}
					<li class="init" id="id{$member['id']}" data-rel="{$member['id']}">
						<a href="{$fixed_base_url}user/{$member['id']}/personal_profile" class="mic{$member['category_id']}">
							<p class="ellipsisline">
								{$member['name']}&nbsp;&nbsp;{$member['organization']}&nbsp;&nbsp;{$member['position']}
							</p>
						</a>
					</li>
					{/if}
				{/foreach}
				{if ($master_group_owned && isset($process_edit) == FALSE)}
					<div id="invite_member"></div>
				{/if}
				</ul>
				{if ($master_group_owned && isset($process_edit) == FALSE)}
					<div class="btn_group_menu_margin">
						<a id="btn_edit_member"><img src="{$fixed_base_url}assets/img/{$language}/member_edit.png" alt="{'label_group_menbers_edit'|lang}"></a>
					</div>
					<div style="display: none;" id="button_edit_user">
						<div class="ct">
							<p>
								<a class="user_modal" href="{$fixed_base_url}group/invite_list/{$group_id}">
									<img src="{$fixed_base_url}assets/img/{$language}/mem_gr_invite.png" alt="{'label_invite_member'|lang}">
								</a>
							</p>
						</div>
						<div class="ct mt10">
							<a class="btn_cancel">
								<img src="{$fixed_base_url}assets/img/{$language}/btn_back_cancel2.png" alt="{'label_button_cancel'|lang}">
							</a>
							<a class="btn_finish">
								<img src="{$fixed_base_url}assets/img/{$language}/member_complete.png" alt="{'label_button_finish'|lang}">
							</a>
						</div>
					</div>
				{/if}
			</div>
		</div>
		{if $master_group_joined}
		<div class="sidecate-menu ct">
		{''|form_open:'id=form_unsubscribe'}
			<p><a onclick="confirm_unsubscribe_group()"><img src="{$fixed_base_url}assets/img/{$language}/group_out.png"></a></p>
		{''|form_close}
		</div>
		{/if}

		<div class="sidecate">
		<ul class="sidemenu">
		<li class="smenutop"><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a></li>
		<li><a href="{$fixed_base_url}other/term" >{'label_footer_terms'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a></li>
		<li><a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a></li>
		</ul>
		</div>
		<p class="ct copy">© 2014 QLife, Inc.</p> 

	</div>
	<div class="mainarea">
		{block name=body}{/block}
	</div>
</div>
{literal}
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets/js/jquery.pageslide.js{literal}"></script>
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets/js/adjustIframeWidth.js{literal}"></script>
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets/js/thickbox.js{literal}"></script>
	<script type="text/javascript">
  	var isClickFinish = false;
  	var isClickCancel = false;
  	var intercept_page_move = function() {
  	$(window).on('beforeunload', function() {
  		if ((isClickFinish == false)
  		&& (isClickCancel == false)
  				) {
  					return '{/literal}{'label_group_page_transition'|lang}{literal}';
  				}
  				return ;
  	});
  	}
  	$(".btn_finish").click(function(){
  		isClickFinish = true;
  	});
  	$(".btn_cancel").on('click',function(){
  		isClickCancel = true;
  	});
  	$("#btn_edit_member").on('click', intercept_page_move);
	
$(function() {
		$("a.user_modal").pageslide({ direction: "left"});
		$("a.file_modal").pageslide({ direction: "left"});
		$("a.info_modal").pageslide({ direction: "left"});
		$("a.mailtomodal").pageslide({ direction: "left"}); 
		var doc_height =$(window).height() - $('div.header').height();
		if ($('div.wrapper').height() < doc_height){
			$('div.wrapper').css('min-height',doc_height -150);
		}
});
	</script>
	
{/literal}

{if $master_group_joined}
	{literal}
	<script type="text/javascript">
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
		$.getScript("{/literal}{$fixed_base_url}{literal}assets/js/modal.js");
	}

	$(".modal .ctbox").append(
		'<div class="ctinner_m" style="display:none" id="unsubscribe-confirm-dialog">'
		+'<p style="margin-bottom:10px;">{/literal}{$msg_confirm_unsubscribe}{literal}</p>'
		+'<p class="ct m20">'
			+ '<a onclick="modal_unsubscribe_clicked(false)"><img src="{/literal}{$fixed_base_url}assets/img/{$language}/modal_iie.png{literal}"/></a>'
			+ '<a onclick="modal_unsubscribe_clicked(true)"><img src="{/literal}{$fixed_base_url}assets/img/{$language}/modal_hai.png{literal}"/></a>'
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
</div>
</body>
</html>
