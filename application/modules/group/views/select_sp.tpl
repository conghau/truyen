{if isset($auth) and !$auth}
{literal}
	<script type="text/javascript">
		window.parent.location.reload();
	</script>
	{/literal}
{else}
	<div class="in">
		<div class="clearfix">
			<p><a id="btn_back_invite"><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow.png" width="13%" class="close"></a></p>
		</div>
		
		<div class="tab_side clearfix">
			<ul>
				<li><a href="javascript:disp('tab1','tab2');" onclick="">{'label_post_personal'|lang}</a></li>
				<li><a href="javascript:disp('tab2','tab1');" onclick="">{'label_post_history'|lang}</a></li>
			</ul>
		</div>
		<div class="btn">
			<a id="btn_finish_invite_top" onclick="sel_invite_user()">
				<div class="close" style="width:100%">{'label_group_button_select_dest'|lang}</div>
			</a>
		</div>
		<div id="tab1">
			<div class="tabwrap clearfix">
				<!-- 選択時はclass="sel"-->
				<div class="alpha">
					<a id="A" onclick="scroll('A')">A</a><a id="B" onclick="scroll('B')">B</a><a id="C" onclick="scroll('C')">C</a><a id="D" onclick="scroll('D')">D</a>
					<a id="E" onclick="scroll('E')">E</a><a id="F" onclick="scroll('F')">F</a><a id="G" onclick="scroll('G')">G</a><a id="H" onclick="scroll('H')">H</a>
					<a id="I" onclick="scroll('I')">I</a><a id="J" onclick="scroll('J')">J</a><a id="K" onclick="scroll('K')">K</a><a id="L" onclick="scroll('L')">L</a>
					<a id="M" onclick="scroll('M')">M</a><a id="N" onclick="scroll('N')">N</a><a id="O" onclick="scroll('O')">O</a><a id="P" onclick="scroll('P')">P</a>
					<a id="Q" onclick="scroll('Q')">Q</a><a id="R" onclick="scroll('R')">R</a><a id="S" onclick="scroll('S')">S</a><a id="T" onclick="scroll('T')">T</a>
					<a id="U" onclick="scroll('U')">U</a><a id="V" onclick="scroll('V')">V</a><a id="W" onclick="scroll('W')">W</a><a id="X" onclick="scroll('X')">X</a>
					<a id="Y" onclick="scroll('Y')">Y</a><a id="Z" onclick="scroll('Z')">Z</a>
				</div>
				<div class="per_list content">
					<ul class="clearfix">
					{foreach $alpha as $a}
					<li class="al" id="scroll_{$a}">{$a}</li>
					{if isset($users[$a])}
						{foreach $users[$a] as $user}
						<li class="mic{$user->category_id}" onclick="sel_user_group(this)">
							<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$user->id}"/></p>
							<p class="name text-word-break">{$user->name}</p>
							<p class="pf text-word-break">{$user->organization}{$user->position}</p>
						</li>
						{/foreach}
					{/if}
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
		<div id="tab2" style="display:none;">
			<div class="tabwrap clearfix">
				<div class="arc_list content">
					<ul class="clearfix">
						{foreach $history as $user}
							<li class="mic{$user->category_id}" onclick="sel_user_group(this)">
								<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$user->id}"/></p>
								<p class="name text-word-break" style="width: 150px">{$user->name}</p>
								<p class="pf text-word-break">{$user->organization} {$user->position}</p>
							</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
		<div class="btn">
			<a id="btn_finish_invite_bottom" onclick="sel_invite_user()">
				<div class="close" style="width:100%">{'label_group_button_select_dest'|lang}</div>
			</a>
		</div>
	</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	if(typeof $("#groupmembermodal", top.document).val() !== 'undefined') {
		$('#btn_back_invite').attr('href', '#groupmembermodal');
		$('#btn_back_invite').addClass('return');
		$('#btn_finish_invite_top').attr('href', '#groupmembermodal');
		$('#btn_finish_invite_top').addClass('return');
		$('#btn_finish_invite_bottom').attr('href', '#groupmembermodal');
		$('#btn_finish_invite_bottom').addClass('return');
	}
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
});
function disp(mon,dis1,dis2)
{
	$("#"+mon).show();
	$("#"+dis1).hide();
}

function scroll(alpha) {
	$("body").animate({
		scrollTop: $('#scroll_' + alpha).offset().top
	}, 1000);

	$('.alpha a').each(function () {
		$(this).removeClass();
	});
	$('#' + alpha).addClass('sel');
}

//click button on popup
function sel_invite_user() {
	var user = $('input[name="chk_user[]"]:checked').map(function(){
	return $(this).val();
	});
	var arr_user = user.get();
	arr_user = jQuery.unique(arr_user);
	$("#hdn_invite_user", top.document).val(arr_user);
	$("#flag", top.document).val(1);
	get_invite_name(arr_user);
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
			id = '#invite_member';
			if (obj.length > 0) {
				$.each(obj, function(i, val) {
					string = string + '<li id = "id'+ val['id'] +'" class="gron">';
					string = string + '<a onclick="cancel_invite('+ val['id'] +')" class="mic' + val['category_id'] + '">';
					string = string + '<span>X</span>';
					string = string + '<p class="ellipsisline">';
					string = string + val['name'];
					string = string + '  ' + val['organization'] + ' ' + val['position'];
					string = string + '</p>';
					string = string + '</a>';
					string = string +'</li>';
				});
			}
		} else {
			id = '#group_user_invite';
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
		$(id, top.document).html(string);
	});
}
</script>
{/literal}
{/if}