{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/comment/'|cat:$comment['id']|cat:'/update'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_thread_comment_management'|lang} > {$comment['parent_id']} > {$comment['id']|h} </h2></div>
		<table class="box_table_h" style="table-layout:fixed">
		<tbody>
			<tr>
				<th>{'label_post_id'|lang}</th>
				<td>
					{$comment['parent_id']}
				</td>
			</tr>
			<tr>
				<th>{'label_post_body'|lang}</th>
				<td class="text-word-break">{$comment['body']}</td>
			</tr>
			<tr>
				<th>{'label_post_user_id'|lang}</th>
				<td>
					<div>
						<div style="float:left">{$comment['user_id']}</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>{'label_post_destination'|lang}</th>
				<td>
					{($comment['user'] !== '')?(('label_post_user_send_id'|lang)|cat:(': ')|cat:($comment['user'])|cat:'<br/>'):''}
					{($comment['group'] !== '')?(('label_post_group_id'|lang)|cat:(': ')|cat:($comment['group'])):''}
				</td>
			</tr>
			<tr>
				<th>{'label_post_created_at'|lang}</th>
				<td>{$comment['created_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H:i"}</td>
			</tr>
			<tr>
				<th>{'label_status'|lang}</th>
				<td>{$comment['status']|lang}</td>
			</tr>
		</tbody>
		</table>
		<input type="hidden" name="user_id" value="{$comment['user_id']|h}"/>
		<input type="hidden" name="user_dest" value="{$comment['user']|h}"/>
		<input type="hidden" name="group_dest" value="{$comment['group_dest']|h}"/>
		<div id="dialog-alert"><p></p></div>
		<div class="ct mtb10">
			<a href="" name="btn_cancel" id="btn_cancel" ><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="{'label_button_cancel'|lang}"></a>
			<a href="" name="btn_confirm" id="btn_confirm"><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="{'label_button_confirml'|lang}"></a>
			<a href="" class="modalOpen" id="btn-dialog"></a>
		</div>
	</div>
{''|form_close}
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p"></p>
					<p class="ct m20"><a href="{$fixed_base_url}admin_tools/post"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
{/block}

{block name=javascript}
{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		$("#btn_cancel").click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}

			$("#form-main").attr("action", "{/literal}{$fixed_base_url}admin_tools/comment/{$comment['id']|h}/edit{literal}");
			$("#form-main").submit();
			return false;
		});
	
		$("#btn_confirm").click(function() {
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}

			var urlAction = "{/literal}{$fixed_base_url}admin_tools/comment/{$comment['id']|h}/update{literal}";
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
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				}
			});
			return false;
		});
	});
	</script>
{/literal}
{/block}