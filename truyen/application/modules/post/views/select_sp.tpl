{if isset($auth) and !$auth}
{literal}
	<script type="text/javascript">
		window.parent.location.reload();
	</script>
	{/literal}
{else}
<div class="in">
	<div class="clearfix">
		<p><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow_l.png" width="13%" class="close"></p>
		<h2>{'label_post_can_be_sent'|lang}</h2>
	</div>
	<div class="tab_side clearfix">
		<ul>
			<li><a href="javascript:disp('tab1','tab2','tab3');" onclick="">{'label_post_personal'|lang}</a></li>
			{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
			<li><a href="javascript:disp('tab2','tab1','tab3');" onclick="">{'label_post_group'|lang}</a></li>
			<li><a href="javascript:disp('tab3','tab1','tab2');" onclick="">{'label_post_history'|lang}</a></li>	
			{/if}
		</ul>
	</div>
	<div class="btn ">
	{if $smarty.const.MODAL_HIDE_SETTING == $hide_item}
		<a onclick="sel_dest()">{'label_personal_add_blacklist'|lang}</a>
	{else}
		<a onclick="sel_dest()">{'label_post_button_select_dest'|lang}</a>
		{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
			<a id="btn_invite_user_sel" class="btn_invite_user_sel" onclick="sel_invite_user()">{'label_post_button_invite_new'|lang}</a>
		{/if}
	{/if}
	</div>
	<div id="tab1">
		<div class="tabwrap clearfix">
			<!-- 選択時はclass="sel"-->
			<div class="alpha">
				<a id="A" onclick="scroll('A')">A</a>
				<a id="B" onclick="scroll('B')">B</a>
				<a id="C" onclick="scroll('C')">C</a>
				<a id="D" onclick="scroll('D')">D</a>
				<a id="E" onclick="scroll('E')">E</a>
				<a id="F" onclick="scroll('F')">F</a>
				<a id="G" onclick="scroll('G')">G</a>
				<a id="H" onclick="scroll('H')">H</a>
				<a id="I" onclick="scroll('I')">I</a>
				<a id="J" onclick="scroll('J')">J</a>
				<a id="K" onclick="scroll('K')">K</a>
				<a id="L" onclick="scroll('L')">L</a>
				<a id="M" onclick="scroll('M')">M</a>
				<a id="N" onclick="scroll('N')">N</a>
				<a id="O" onclick="scroll('O')">O</a>
				<a id="P" onclick="scroll('P')">P</a>
				<a id="Q" onclick="scroll('Q')">Q</a>
				<a id="R" onclick="scroll('R')">R</a>
				<a id="S" onclick="scroll('S')">S</a>
				<a id="T" onclick="scroll('T')">T</a>
				<a id="U" onclick="scroll('U')">U</a>
				<a id="V" onclick="scroll('V')">V</a>
				<a id="W" onclick="scroll('W')">W</a>
				<a id="X" onclick="scroll('X')">X</a>
				<a id="Y" onclick="scroll('Y')">Y</a>
				<a id="Z" onclick="scroll('Z')">Z</a>
			</div>
			<div class="per_list content">
				<ul class="clearfix">
				{foreach $alpha as $a}
				<li class="al" id="scroll_{$a}">{$a}</li>
				{if isset($users[$a])}
					{foreach $users[$a] as $user}
					<li class="mic{$user->category_id}" onclick="sel_user_group(this)">
						<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$user->id}"/></p>
						<p class="name text-word-break">{$user->user_name}</p>
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
			<div class="gl_list content">
				<ul class="clearfix">
				{foreach $groups as $group}
				<li class="mic6" onclick="sel_user_group(this)">
					<p class="chkbox"><input type="checkbox" name="chk_group[]" value="{$group->id}"/></p>
					<p class="name text-word-break">{$group->name}</p>
				</li>
				{/foreach}
				</ul>
			</div>
		</div>
	</div>
	<div id="tab3" style="display:none;">
		<div class="tabwrap clearfix">
			<div class="arc_list content">
				<ul class="clearfix">
				{foreach $forwards as $forward}
					{if $forward->user_type == $type_user}
					<li class="mic{$user->category_id}" onclick="sel_user_group(this)">
						<p class="chkbox"><input type="checkbox" name="chk_user[]" value="{$forward->send_id}"/></p>
						<p class="name text-word-break">{$forward->user_name}</p>
						<p class="pf text-word-break">{$forward->organization}{$forward->position}</p>
					</li>
					{else}
					<li class="mic6" onclick="sel_user_group(this)">
						<p class="chkbox"><input type="checkbox" name="chk_group[]" value="{$forward->send_id}"/></p>
						<p class="name text-word-break">{$forward->group_name}</p>
					</li>
					{/if}
				{/foreach}
				</ul>
			</div>

		</div>

	</div>
	<div class="btn ">
	{if $smarty.const.MODAL_HIDE_SETTING == $hide_item}
		<a onclick="sel_dest()">{'label_personal_add_blacklist'|lang}</a>
	{else}
		{if $smarty.const.MODAL_HIDE_BUTTON_SEND != $hide_item}
			<a onclick="sel_dest()">{'label_post_button_select_dest'|lang}</a>
		{/if}
		{if $smarty.const.MODAL_HIDE_SETTING != $hide_item}
			<a id="btn_invite_user_sel" class="btn_invite_user_sel" onclick="sel_invite_user()">{'label_post_button_invite_new'|lang}</a>
		{/if}
	{/if}
	</div>
</div>
<input type="hidden" id="max_user_send" value="{$smarty.const.MAX_USER_SEND}"/>
<input type="hidden" id="max_group_send" value="{$smarty.const.MAX_GROUP_SEND}"/>
<input type="hidden" id="msg_sel_group_error" value="{$msg_group_error}"/>
<input type="hidden" id="msg_sel_user_error" value="{$msg_user_error}"/>
{literal}
<script type="text/javascript">
$(document).ready(function(){
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

	var select_group_id = $("#hdn_dest_group", top.document).val();
	if(typeof select_group_id !== 'undefined' && select_group_id !== '') {
		$('input[name="chk_group[]"]').each(function () {
			if($(this).val() === select_group_id) {
				$(this).prop('checked', true);
				$(this).parent().parent().addClass('sel');
			}
		});
	}
});

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
		$('#btn_yes').hide();
		$('#btn_no').hide();
		$('#btn_ok').show();
	} else if (arr_user.length > max_user) {
		$("#dialog_p").html($("#msg_sel_user_error").val());
		$('#btn-dialog').click();
		$('#btn_yes').hide();
		$('#btn_no').hide();
		$('#btn_ok').show();
	} else {
		if ($('#create_post_content').length == 0) {
			window.create_post_sync();
		}

		$("#hdn_dest_user").val(arr_user);
		$("#hdn_dest_group").val(arr_group);
		$("#flag").val(1);
		get_dest_name(arr_user, arr_group);
		$(".close").click();
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

function onlyUnique(value, index, self) { 
	return self.indexOf(value) === index;
}

function sel_invite_user() {
	window.open('{/literal}{$fixed_base_url}user/invite{literal}', '_parent');
}
</script>
{/literal}
{/if}
	