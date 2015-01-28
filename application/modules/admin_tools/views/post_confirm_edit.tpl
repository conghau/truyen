{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{''|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_thread_comment_management'|lang} > {$post['id']} </h2></div>
		<table class="box_table_h" style="table-layout:fixed">
			<tbody>
			<tr>
				<th>{'label_post_id'|lang}</th>
				<td>
					{$post['id']}
				</td>
			</tr>
			<tr>
				<th>{'label_post_body'|lang}</th>
				<td class="text-word-break">
					{$post['body']}
				</td>
			</tr>
			<tr>
				<th>{'label_post_user_id'|lang}</th>
				<td>{$post['user_id']}</td>
			</tr>
			<tr>
				<th>{'label_post_attached_file'|lang}</th>
				<td>{($post['attach_file']>0)?('label_post_attached_file_yes'|lang):('label_post_attached_file_no'|lang)}</td>
			</tr>
			<tr>
				<th>{'label_post_destination'|lang}</th>
				<td>
					{($post['user'] !== '')?(('label_post_user_send_id'|lang)|cat:(': ')|cat:($post['user'])|cat:'<br/>'):''}
					{($post['group'] !== '')?(('label_post_group_id'|lang)|cat:(': ')|cat:($post['group'])):''}
				</td>
			</tr>
			<tr>
				<th>{'label_post_created_at'|lang}</th>
				<td>{$post['created_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H:i"}</td>
			</tr>
			<tr>
				<th>{'label_status'|lang}</th>
				<td>
					{$post['status']|lang}
				</td>
			</tr>
			{$i = 1}
			{foreach $post['comments'] as $comment}
			<tr>
				<th>{'label_post_comment'|lang} {$i}</th>
				<td>
					<div class="text-word-break">
						{$comment['body']}
					</div>
					<br/>
					<div>
						<div style="float: left;">
							{'label_post_id'|lang}:{$comment['id']}<br/>
							{'label_post_user_send_id'|lang}:{$comment['user_id']}<br/>
							{'label_post_date_time'|lang}:{$comment['created_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H:i"}<br/>
							{'label_status'|lang} : {$comment['status']|lang}<br/>
						</div>
					</div>
					<br/><br/><br/><br/>
					<div>
						{if $comment['delete']}
							<div style="text-align:right; color:red">
								{'label_post_comment_delete_notice'|lang}
							</div>
						{/if}
					</div>
				</td>
			</tr>
			{$i = $i +1}
			{/foreach}
			</tbody>
		</table>
		<div class="ct mtb10">
			<a href="" name="btn_cancel" id="btn_cancel"><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="{'label_button_cancel'|lang}"></a>
			<a href="" name="btn_confirm" id="btn_confirm"><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="{'label_button_confirm'|lang}"></a>
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

{block name=stylesheet}
	<style>
		.no-close .ui-dialog-titlebar-close {
			display: none;
		}
	</style>
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
				$("#form-main").attr("action", "{/literal}{$fixed_base_url}admin_tools/post/{$post['id']|h}/edit{literal}");
				$("#form-main").submit();
				return false;
			});
		
			$("#btn_confirm").click(function() {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return false;
				}
				
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/post/{$post['id']|h}/update{literal}";
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