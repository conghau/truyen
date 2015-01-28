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
	<div id="user_modal">
		<div class="tab_side clearfix">
		<ul>
			<li><a href="javascript:disp('tab1','tab2');" onclick="">{'label_post_personal'|lang}</a></li>
			<li><a href="javascript:disp('tab2','tab1');" onclick="">{'label_post_history'|lang}</a></li>
		</ul>
		</div>	
		<div id="tab1">
			<div class="ct ">
				<p>
					<a href="#" onclick="sel_dest()">
						<img src="{$fixed_base_url}assets/img/{$language}/btn_seluser_invite.png">
					</a>
				</p>
			</div>
			<div class="tabwrap clearfix">
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
									<p class="name text-word-break" style="width: 150px">{$user->name}</p>
									<p class="pf text-word-break">{$user->organization} {$user->position}</p>
								</li>
								<li class="clearfix"></li>
							{/foreach}
						{/if}
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
	
		<div id="tab2" style="display:none;">
			<div class="ct ">
				<p>
					<a href="#" onclick="sel_dest()">
						<img src="{$fixed_base_url}assets/img/{$language}/btn_seluser_invite.png">
					</a> 
				</p>
			</div>
			<div class="tabwrap clearfix">
				<div class="arc_list content">
					<ul class="clearfix">
						{foreach $history as $user}
						<li class="mic{$user->category_id}">
							<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$user->id}"/></p>
							<p class="name text-word-break" style="width: 150px">{$user->name}</p>
							<p class="pf text-word-break">{$user->organization} {$user->position}</p>
						</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
	</div>
<style>
	a {
	cursor: pointer;
	text-decoration: none;
}
</style>
{literal}
<script src="{/literal}{$fixed_base_url}{literal}assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
function disp(mon,dis1)
{
	$("#"+mon).show();
	$("#"+dis1).hide();
}
function recheck_dest(){
	$("li[class^=mic]").removeClass("sel");
	
	$('input[name="chk_user[]"]').prop('checked', false);
	var select_user_id = $("#hdn_invite_user", top.document).val();
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
	$("#per_list").height($(window).height() - 150);
	$("#tab2").height($(window).height() - 150);
	
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
	
	$(document).on("click",".per_list li, .arc_list li",function(e){

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
			}
		}
	});
});

//click button on popup
function sel_dest() {
	var user = $('input[name="chk_user[]"]:checked').map(function(){
	return $(this).val();
	});
	var arr_user = user.get();
	arr_user = jQuery.unique(arr_user);

	$("#hdn_invite_user", top.document).val(arr_user);
	$("#flag", top.document).val(1);
	get_invite_name(arr_user);
	$('body', top.document).click();
	var scroll_pos=(0);
	$('html, body', top.document).animate({scrollTop:(scroll_pos)}, '2000');
}

function get_invite_name(user_id) {
	var str_user_id = user_id.join('_');
	if (str_user_id == '') {
		str_user_id = 0;
	}

	$.get("{/literal}{$fixed_base_url}group/invite_name/{literal}" + str_user_id, function (data) {
		var string = '';
		var obj = JSON && JSON.parse(data) || $.parseJSON(data);
		var id = '';

		if(typeof $("#group_user_invite", top.document).val() === 'undefined') {
			id = 'invite_member';
			if (obj.length > 0) {
				$.each(obj, function(i, val) {
					string = string + '<li class="del gron" id = "id'+ val['id'] +'">';
					string = string + '<a onclick="cancel_invite('+ val['id'] +')">';
					string = string + '<p class="ellipsisline">';
					string = string + val['name'];
					string = string + '  ' + val['organization'] + ' ' + val['position'];
					string = string + '</p>';
					string = string + '</a>';
					string = string +'</li>';
				});
			}
		} else {
			id = 'group_user_invite';
			if (obj.length > 0) {
				$.each(obj, function(i, val) {
					string = string + '<li class="clearfix">';
					string = string + '<p class="name">';
					string = string + val['name']; 
					string = string + '</p>';
					string = string + '<p class="at">';
					string = string + val['organization'] + ' ';
					string = string + val['position'];
					string = string + '</p>';
					string = string + '<p class="atx">';
					string = string + '</p>';
					string = string + '</li>';
				});
			}
		}
		$("#"+id, top.document).html(string);
	});
	
}

function scroll(alpha) {
	$('.content').mCustomScrollbar("scrollTo", '#scroll_' + alpha);
	$('.alpha a').each(function () {
		$(this).removeClass();
	});
	$('#' + alpha).addClass('sel');
}
</script>
{/literal}
{/if}
</body>
</html>