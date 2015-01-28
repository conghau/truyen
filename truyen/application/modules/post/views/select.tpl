<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/custom.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets/js/jquery.mCustomScrollbar.css"/>
<script type="text/javascript" src="{$fixed_base_url}assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/modal.js"></script>
<meta charset="UTF-8">
<title></title>
</head>
<body class="sc_bg">
{if isset($auth) and !$auth}
	{literal}
	<script type="text/javascript">
		window.parent.location.reload();
	</script>
	{/literal}
{else}
	<div id="mailtomodal">
		<h2>{'label_post_can_be_sent'|lang}</h2>
		<div class="tab_side clearfix">
			<li><a href="javascript:disp('tab1','tab2','tab3');" onclick="">{'label_post_personal'|lang}</a></li>
			{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
			<li><a href="javascript:disp('tab2','tab1','tab3');" onclick="">{'label_post_group'|lang}</a></li>
			<li><a href="javascript:disp('tab3','tab1','tab2');" onclick="">{'label_post_history'|lang}</a></li>
			{/if}
		</div>	
		<div class="ct ">
			<p>
				{if $smarty.const.MODAL_HIDE_SETTING == $hide_item}
					<a href="#" class="btn_send" onclick="sel_dest()"><img src="{$fixed_base_url}assets/img/{$language}/btn_send2.png"></a> 
				{else}
					<a href="#" class="btn_send" onclick="sel_dest()"><img src="{$fixed_base_url}assets/img/{$language}/btn_send1.png"></a> 
					{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
					<a href="#" class="btn_invite_user_sel" onclick="sel_invite_user()"><img src="{$fixed_base_url}assets/img/{$language}/btn_invite.png"></a>
					{/if}
				{/if}
				
			</p>
		</div>
		<div id="tab1">
			<div class="tabwrap clearfix">
			<!-- 選択時はclass="sel"-->
				<div class="alpha">
					<a id="N" onclick="scroll('N')">N</a><a id="O" onclick="scroll('O')">O</a><a id="P" onclick="scroll('P')">P</a><a id="Q" onclick="scroll('Q')">Q</a>
					<a id="R" onclick="scroll('R')">R</a><a id="S" onclick="scroll('S')">S</a><a id="T" onclick="scroll('T')">T</a><a id="U" onclick="scroll('U')">U</a>
					<a id="V" onclick="scroll('V')">V</a><a id="W" onclick="scroll('W')">W</a><a id="X" onclick="scroll('X')">X</a><a id="Y" onclick="scroll('Y')">Y</a>
					<a id="Z" onclick="scroll('Z')">Z</a>
				</div>
				<div class="alpha">
					<a id="A" onclick="scroll('A')">A</a><a id="B" onclick="scroll('B')">B</a><a id="C" onclick="scroll('C')">C</a><a id="D" onclick="scroll('D')">D</a>
					<a id="E" onclick="scroll('E')">E</a><a id="F" onclick="scroll('F')">F</a><a id="G" onclick="scroll('G')">G</a><a id="H" onclick="scroll('H')">H</a>
					<a id="I" onclick="scroll('I')">I</a><a id="J" onclick="scroll('J')">J</a><a id="K" onclick="scroll('K')">K</a><a id="L" onclick="scroll('L')">L</a>
					<a id="M" onclick="scroll('M')">M</a>
				</div>
				<div class="per_list content">
					<ul class="clearfix">
						{foreach $alpha as $a}
							<li class="al" id="scroll_{$a}">{$a}</li>
							{if isset($users[$a])}
								{foreach $users[$a] as $user}
									<li class="mic{$user->category_id}">
										<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$user->id}"/></p>
										<p class="name text-word-break" style="width: 150px">{$user->user_name}</p>
										<p class="pf">{$user->organization}{$user->position}</p>
									</li>
									<li class="clearfix"></li>
								{/foreach}
							{/if}
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
		{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
		<div id="tab2" style="display:none;">
			<div class="tabwrap clearfix">
				<div class="gl_list content">
					<ul class="clearfix">
					{foreach $groups as $group}
						<li class="mic6">
							<p class="chkbox">
								<input type="checkbox" name="chk_group[]" value="{$group->id}"/>
							</p>
							<p class="name text-word-break">{$group->name}</p>
						</li>
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
		{/if}
		{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
		<div id="tab3" style="display:none;">
			<div class="tabwrap clearfix">
				<div class="arc_list content">
					<ul class="clearfix">
					{foreach $forwards as $forward}
						{if $forward->user_type == $type_user}
						<li class="mic{$forward->category_id}">
							<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$forward->send_id}"></p>
							<p class="name text-word-break">{$forward->user_name}</p>
							<p class="pf" style="width:160px">{$forward->organization}{$forward->position}</p>
						</li>
						{else}
						<li class=mic6>
							<p class="chkbox">
								<input type="checkbox" name="chk_group[]" value="{$forward->send_id}"/>
							</p>
							<p class="name text-word-break">{$forward->group_name}</p>
						</li>
						{/if}
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
		{/if}
	</div>
	<input type="hidden" id="max_user_send" value="{$smarty.const.MAX_USER_SEND}"/>
	<input type="hidden" id="max_group_send" value="{$smarty.const.MAX_GROUP_SEND}"/>
	<input type="hidden" id="msg_sel_group_error" value="{$msg_group_error}"/>
	<input type="hidden" id="msg_sel_user_error" value="{$msg_user_error}"/>
	<input type="hidden" id="hide_item" value="{$hide_item}"/>
	<a href="" class="modalOpen" id="btn-dialog"></a>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap" style="width: 30%; margin: auto 40px">
			<div class="modal" style="width:300px; margin: auto 0px">
				<div class="ctbox" style="text-align: center">
					<div class="ctinner_m">
						<p id="dialog_p"></p>
						<p class="ct m20">
							<a id="btn_ok" class="modalClose" href=""><img src="{$fixed_base_url}assets/img/{$language}/ok.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
<style>
a {
	cursor: pointer;
	text-decoration: none;
}
.group_icon {
	padding-left: 32px;
	width: 90%;
	background: url(../img/icons/group1.png) no-repeat 5px;
	background-size: 24px;
	display: inline-block;
	text-align: left;
	height: 32px;
}

.tab_side li {
	margin: 10px 0;
	width:32%;
	float:left;
	margin-right:4px;
}
.tab_side li a{
	text-align: center;
	color:#fff;
	display: block;
	line-height: 20px;
	padding:5px;
	background-color: #666;
	border-radius: 4px;
	text-decoration: none;
}
</style>
{literal}
<script src="{/literal}{$fixed_base_url}{literal}assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
function recheck_dest(){
	$("li[class^=mic]").removeClass("sel");
	
	$('input[name="chk_user[]"]').prop('checked', false);
	var select_user_id = $("#hdn_dest_user", top.document).val();
	if(typeof select_user_id !== 'undefined' && select_user_id !== '') {
		var arr_user_id = select_user_id.split(",");
		$.each( arr_user_id, function( key, value ) {
			$('input[name="chk_user[]"]').each(function () {
				if($(this).val() === value) {
					$(this).prop('checked', true);
					$(this).parent().parent().addClass('sel');
				}
			});
			
		});
	}
	
	$('input[name="chk_group[]"]').prop('checked', false);
	var select_group_id = $("#hdn_dest_group", top.document).val();
	if(typeof select_group_id !== 'undefined' && select_group_id !== '') {
		$('input[name="chk_group[]"]').each(function () {
			if($(this).val() === select_group_id) {
				$(this).prop('checked', true);
				$(this).parent().parent().addClass('sel');
			}
		});
	}
}

$(document).ready(function(){
	recheck_dest();

	$(window).load(function(){
		$(".content").mCustomScrollbar();
		$(".disable-destroy a").click(function(e){
			e.preventDefault();
			var $this=$(this),
				rel=$this.attr("rel"),
				el=$(".content"),
				output=$("#info > p code");
			switch(rel){
				case "toggle-disable":
				case "toggle-disable-no-reset":
					if(el.hasClass("mCS_disabled")){
						el.mCustomScrollbar("update");
						output.text("$(\".content\").mCustomScrollbar(\"update\");");
					}else{
						var reset=rel==="toggle-disable-no-reset" ? false : true;
						el.mCustomScrollbar("disable",reset);
						if(reset){
							output.text("$(\".content\").mCustomScrollbar(\"disable\",true);");
						}else{
							output.text("$(\".content\").mCustomScrollbar(\"disable\");");
						}
					}
					break;
				case "toggle-destroy":
					if(el.hasClass("mCS_destroyed")){
						el.mCustomScrollbar();
						output.text("$(\".content\").mCustomScrollbar();");
					}else{
						el.mCustomScrollbar("destroy");
						output.text("$(\".content\").mCustomScrollbar(\"destroy\");");
					}
					break;
			}
		});
	});
	
	$(document).on("click",".per_list li, .gl_list li, .arc_list li",function(e){
		var li    = $(e.currentTarget);
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
	});
});

function disp(mon,dis1,dis2)
{
	$("#"+mon).show();
	$("#"+dis1).hide();
	$("#"+dis2).hide();
}

function scroll(alpha) {
	$('.content').mCustomScrollbar("scrollTo", '#scroll_' + alpha);
	$('.alpha a').each(function () {
		$(this).removeClass();
	});
	$('#' + alpha).addClass('sel');
}

function onlyUnique(value, index, self) { 
	return self.indexOf(value) === index;
}

//click button on popup
function sel_dest() {
	var user = $('input[name="chk_user[]"]:checked').map(function(){
	return $(this).val();
	});
	var group = $('input[name="chk_group[]"]:checked').map(function(){
		return $(this).val();
	});
	var arr_user = user.get();
	arr_user = arr_user.filter( onlyUnique );
	var arr_group = group.get();
	arr_group = arr_group.filter( onlyUnique );
	var max_user = Number($("#max_user_send").val());
	var max_group = Number($("#max_group_send").val());

	if (arr_group.length > max_group) {
		$("#dialog_p").html($("#msg_sel_group_error").val());
		$('#btn-dialog').click();
	} else if (arr_user.length > max_user) {
		$("#dialog_p").html($("#msg_sel_user_error").val());
		$('#btn-dialog').click();
	} else {
		if ($('#create_post_content', top.document).length == 0) {
			top.window.create_post_sync();
		}

		$("#hdn_dest_user", top.document).val(arr_user);
		$("#hdn_dest_group", top.document).val(arr_group);
		$("#flag", top.document).val(1);
		get_dest_name(arr_user, arr_group);
		$('body', top.document).click();
		var scroll_pos=(0);
		$('html, body', top.document).animate({scrollTop:(scroll_pos)}, '2000');
	}
}

function get_dest_name(user_id, group_id) {
	var str_user_id = user_id.join('_');
	if (str_user_id == '') {
		str_user_id = 0;
	}
	$.get("{/literal}{$fixed_base_url}post/get_dest_name/{literal}" + str_user_id + "/" + group_id, function (data) {
		var obj = JSON && JSON.parse(data) || $.parseJSON(data);
		$("#dest", top.document).html(obj);
		$('#dest_error', top.document).text('');
	});
}

function sel_invite_user() {
	window.open('{/literal}{$fixed_base_url}user/invite{literal}', '_parent');
}
</script>
{/literal}
{/if}
</body>
</html>