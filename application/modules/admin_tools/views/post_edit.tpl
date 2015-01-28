{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/post/'|cat:$post['id']|h|cat:'/confirm_edit'|form_open:'id=form_main'}
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
				<td>
					<textarea id="body" name="body" rows="10" cols="50">{$post['body']|h}</textarea>
				</td>
			</tr>
			<tr>
				<th>{'label_post_user_id'|lang}</th>
				<td>
					{$post['user_id']}
					<a href="{$fixed_base_url}admin_tools/user/{$post['user_id']}/edit">
						<img src="{$fixed_base_url}assets-admin/img/user_detail.png" atl="{'label_post_user_detail'|lang}" class="fR">
					</a>
				</td>
			</tr>
			<tr>
				<th>{'label_post_attached_file'|lang}</th>
				<td>
					{if ($post['attach_file']>0)}
						{'label_post_attached_file_yes'|lang}
						<a href="{$fixed_base_url}admin_tools/file/{$post['id']}/post">	
							<img src="{$fixed_base_url}assets-admin/img/file_list.png" atl="{'label_post_file_list'|lang}" class="fR">
						</a>
					{else}
						{'label_post_attached_file_no'|lang}
					{/if}
				</td>
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
					<select id="status" name="status">
						{foreach $status_types as $s}
						<option value="{$s.id|h}" {(isset($post['status']) && $post['status']=={$s.id|h})?'selected':''}>{$s.label|lang}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			{$i = 1}
			{foreach $post['comments'] as $comment}
			<tr>
				<th>{'label_post_comment'|lang} {$i}</th>
				<td>
					<div class="text-word-break">{$comment['body']}</div>
					<br/>
					<div>
						<div style="float: left;">
							{'label_post_id'|lang} : {$comment['id']}<br/>
							{'label_post_user_send_id'|lang} : {$comment['user_id']}<br/>
							{'label_post_date_time'|lang} : {$comment['created_at']|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang} H:i"}<br/>
							{'label_status'|lang} : {$comment['status']|lang}<br/>
						</div>
						<div style="float: right">
							<br/><br/><br/>
							<a style="margin-right: 10px" href="{$fixed_base_url}admin_tools/comment/{$comment['id']}/edit">
								<img src="{$fixed_base_url}assets-admin/img/edit_list.png" alt="{'label_button_update'|lang}" class="fR">
							</a>
						</div>
					</div>
				</td>
			</tr>
			{$i = $i + 1}
			{/foreach}
		</tbody>
		</table>
		<input type="hidden" name="created_at" value="{$post['created_at']|h}"/>
		<input type="hidden" name="user_id" value="{$post['user_id']|h}"/>
		<input type="hidden" name="user_dest" value="{$post['user']|h}"/>
		<input type="hidden" name="group_dest" value="{$post['group']|h}"/>
		<input type="hidden" name="attach_file" value="{$post['attach_file']|h}"/>
		<div class="ct mtb10">
			<a name="btn_update" href="" id="btn_update"><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
			<a name="btn_delete" href="" id="btn_delete"><img src="{$fixed_base_url}assets-admin/img/delete.png" alt="{'label_button_delete'|lang}"></a>
		</div>
		<a href="" class="modalOpen" id="btn-dialog"></a>
	</div>
{''|form_close}
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal" style="width:300px">
				<div class="ctbox" style="text-align: left">
					<div class="ctinner_m">
						<p id="dialog_p">{$msg_delete_confirm}</p>
						<p class="ct m20">
							<a id="btn_no" class="modalClose" href=""><img src="{$fixed_base_url}assets-admin/img/modal_iie.png"></a>
							<a id="btn_yes" class="modalClose" href=""><img src="{$fixed_base_url}assets-admin/img/modal_hai.png"></a>
							<a id="btn_ok" href="{$fixed_base_url}admin_tools/post"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/block}

{block name=stylesheet}
	<style>
		textarea {
			resize:none;
		}
	</style>
{/block}

{block name=javascript}
	{literal}
		<script language="Javascript">
			$(document).ready(function(){
				$('#btn_update').click(function(){
					var cct_cookie = $.cookie('csrf_token');
					if(cct_cookie == undefined) {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
						return false;
					}
					$('#form_main').submit();
					return false;
				});
	
				$( "#btn_delete" ).click(function() {
					$('#btn-dialog').click();
					$('#btn_ok').hide();
					return false;
				});
				
				$("#btn_yes").click(function() {
					$('#btn_ok').show();
					$('#btn_yes').hide();
					$('#btn_no').hide();
					delete_id();
					return false;
				});
			});

			function delete_id() {
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return false;
				}
				
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/post/{$post['id']|h}/delete{literal}";
				var cct = $("input[name=csrf_token]").val();
				$.ajax({
					type: 'POST',
					url: urlAction,
					data: {csrf_token: cct},
					dataType :'html',
					success : function(data){
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#dialog_p').empty();
						$('#dialog_p').append(obj);
						$('#btn-dialog').click();
					},
					error: function (data, status, e){
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					}
				});
			}
		</script>
	{/literal}
{/block}