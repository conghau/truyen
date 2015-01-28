{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
<div class="modalOpen"></div>
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p"  style="text-align: left">{$msg_save_confirm}</p>
					<p id="dialog_email_changed" class="hide" style="text-align">{(isset($msg_email_changed))?$msg_email_changed :''}</p>
					<p class="ct m20">
						<a id="btn_no" class=" modalClose" href="#"><img src="{$fixed_base_url}assets/img/{$language}/modal_iie.png"></a>
						<a id="btn_alert_yes" class="modalClose hide" href="#"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
						<a id="btn_emailchanged_yes" class="modalClose hide" href="#"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
						<a id="btn_save_yes" class="modalClose" href="#"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
					</p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
			<div class="ctinner_m" style="display:none;" id="open_withdraw_dialog">
				<p style="margin-bottom:10px;">{'label_user_withdraw'|lang}</p>
				<p class="ct m20">
					<a id="btn-cancel-withdraw"><img src="{$fixed_base_url}assets/img/{$language}/modal_iie.png"/></a>
					<a id="btn-withdraw"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"/></a>
				</p>
			</div>
		</div>
	</div>
</div>
<div id="processing" class="hide" >
	<div class="processing_mark"></div>
	<div class="show"><img src="{$fixed_base_url}assets/img/ajax_processing.gif" width="80px" height="80px"></div>
</div>
<ul class="navwrap">
	<li>
		<ul class="subnavwrap clearfix">
			<li class="set_tab">
				<a id="tab_edit" class="current tabs" href="#tab1">
					<span>{'label_user_title_edit'|lang}</span>
				</a>
			</li>
			<li class="set_tab">
				<a id="tab_setting" class="tabs" href="#tab2">
					<span>{'label_user_title_setting'|lang}</span>
				</a>
			</li>
		</ul>
 	</li>
</ul>
<div id="tab1" class="isTab">
	<div class="set_tabinner">
		{include file='./edit_sp.tpl'}
		<div class="btn"><a href="#" class="btn_cancel">{'label_button_cancel'|lang}</a></div>	
		<div class="btn"><a href="#" class="btn_save_edit inactive disabled">{'label_button_save'|lang}</a></div>
		<div class="btn"><a href="#" class="btn-open-withdraw">{'label_btn_mem_delete'|lang}</a></div>	
	</div>
</div>
<div id="tab2" class="isTab" style="display: none;">
	<div class="set_tabinner">
		{if isset($data_setting)}
			{include file='./setting_sp.tpl'}
		{/if}
		<div class="btn"><a href="#" class="btn_cancel">{'label_button_cancel'|lang}</a></div>	
		<div class="btn"><a href="#" class="btn_save_setting inactive disabled">{'label_button_save'|lang}</a></div>
		<div class="btn"><a href="#" class="btn-open-withdraw">{'label_btn_mem_delete'|lang}</a></div>	
	</div>
</div>
<script src="{$fixed_base_url}assets_sp/js/tab.js"></script>
{literal}
<script type="text/javascript">
	$(document).ready(function(){
		var id_current_tab = $('.current').attr('id');
		$('#btn_save_yes').click(function(){
			id_current_tab = $('.current').attr('id');
			if ('tab_edit' == id_current_tab){
				update();
			}
			if ('tab_setting' == id_current_tab) {
				save();
			}
		});
		$(".tabs").click(function(){
			id_current_tab = $('.current').attr('id');
		});
		$('#btn_alert_yes').click(function(){
			window.location.replace("{/literal}{$fixed_base_url}{literal}");
		});

		$('#btn_no').click(function(){
			ENABLE_EDIT = true;
			$('.btn_save_edit').removeClass('disabled');
		});

		$('#btn_emailchanged_yes').click(function(){
			$('#dialog_email_changed').addClass('hide');
			$('#btn_emailchanged_yes').addClass('hide');
			$('#btn_alert_yes').removeClass('hide');
			$('#dialog_p').removeClass('hide');
			
			$(".modalOpen").click();
		});


		$('.btn_cancel').click(function(){
			isCANCELclick = true;
			window.location.replace("{/literal}{$fixed_base_url}{literal}");
		});
		
		jQuery(window).on('beforeunload', function(e) {
			if ('tab_edit' == id_current_tab){
				if (ENABLE_EDIT == true && isSAVE_EDIT_click != true && isCANCELclick != true) {
					var message = '{/literal}{$msg_leave_confirm}{literal}';
					e.returnValue = message;
					return e.returnValue;
				};
			}
			if ('tab_setting' == id_current_tab) {
				if ((ENABLE_LANG == true || flagMail==true || flagUser == true || ENABLE== true) && isSAVEclick != true && isCANCELclick != true ) {
					var message = '{/literal}{$msg_leave_confirm}{literal}';
					e.returnValue = message;
					return e.returnValue;
				}
			}
		});

		$('.btn-open-withdraw').on('click', function() {open_withdraw_dialog();});
		function open_withdraw_dialog(event) {
			if (event) {
				event.preventDefault();
			}
			$(".ctbox .ctinner_m").hide();
			$(".ctbox .ttl").hide();
			$(".ctbox .ctinner").hide();
			$("#open_withdraw_dialog").show();
			$('.modalOpen').click();
		}

		$('#btn-withdraw').on('click', function(){
			$('.modalBase').removeClass("ready shown");
			$(".ctbox .ctinner_m").show();
			$(".ctbox .ttl").show();
			$(".ctbox .ctinner").show();
			$("#open_withdraw_dialog").hide();
			deleteUser();
		});

		$('#btn-cancel-withdraw').on('click', function(){
			$('.modalBase').removeClass("ready shown");
			$(".ctbox .ctinner_m").show();
			$(".ctbox .ttl").show();
			$(".ctbox .ctinner").show();
			$("#open_withdraw_dialog").hide();
		});

	});
</script>
{/literal}
{/block}