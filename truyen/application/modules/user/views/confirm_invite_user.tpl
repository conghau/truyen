{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap2">
		<h2 class="ttl2">{'label_post_confirm_invite_title'|lang}</h2>

		<p class="mb10">{'label_post_confirm_invite'|lang}</p>
<div class="ctinner tround">
{'user/confirm_invite'|form_open:'id=form-main'}
		<table class="mem_input">
			<tr>
				<th>{'label_post_invite_email_address'|lang}</th>
				<td class="text-word-break">{$invite['email']}</td>
			</tr>
			<tr>
				<th class="text-word-break">{'label_post_invite_name'|lang}</th>
				<td class="text-word-break">{$invite['last_name']}　{$invite['first_name']}</td>
			</tr>
		</table>
		<div class="mt20 ct">
		<a style="cursor: pointer;" id="btn_cancel"><img src="{$fixed_base_url}assets/img/{$language}/btn_pageback.png"></a>
		<a style="cursor: pointer;" id="btn_invite"><img src="{$fixed_base_url}assets/img/{$language}/btn_invite_complete.png"></a>
		<a href="" class="modalOpen" id="btn-dialog"></a>
		</div>
{''|form_close}
</div>

		<div class="modalBase">
			<div class="modalMask"></div>
			<div class="modalWrap">
				<div class="modal">
				<div class="ctbox">
					<div class="ttl"><h2>{'label_post_send_complete'|lang}</h2></div>
					<div class="ctinner">
						<p id="dialog_p" style="text-align: left">
						</p>
						<p class="ct m20"><a style="cursor: pointer;" onclick="javascript:window.location.replace('{$fixed_base_url}user')"><img src="{$fixed_base_url}assets/img/{$language}/ok.png"></a></p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
				</div>
			</div>
		</div>
	</div>
{literal}
<script type="text/javascript">
	$("#btn_cancel").click(function() {
		$("#form-main").attr("action", "{/literal}{$fixed_base_url}user/invite{literal}");
		$("#form-main").submit();
	});

	$("#btn_invite").click(function() {
		var urlAction = "{/literal}{$fixed_base_url}user/send_mail_invite{literal}";
		var cct = $("input[name=csrf_token]").val();
		$.ajax({
			type: 'POST',
			url: urlAction,
			data: {csrf_token: cct},
			dataType :'html',
			success : function(data){
				var obj = JSON && JSON.parse(data) || $.parseJSON(data);
				$('#dialog_p').append(obj);
				$('#btn-dialog').click();
			},
			error: function (data, status, e){
				window.location.replace("{/literal}{$fixed_base_url}login{literal}");
			}
		});
	});
</script>
{/literal}
{/block}