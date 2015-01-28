{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/comment/'|cat:$comment['id']|h|cat:'/confirm_edit'|form_open:'id=form_main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_thread_comment_management'|lang} > {$comment['parent_id']} > {$comment['id']|h} </h2></div>
		<table class="box_table_h">
		<tbody>
			<tr>
				<th>{'label_post_id'|lang}</th>
				<td>
					{$comment['parent_id']}
				</td>
			</tr>
			<tr>
				<th>{'label_post_body'|lang}</th>
				<td>
					<textarea id="body" name="body" rows="7" cols="59">{$comment['body']|h}</textarea>
				</td>
			</tr>
			<tr>
				<th>{'label_post_user_id'|lang}</th>
				<td>
					<div>
						<div style="float:left">{$comment['user_id']}</div>
						<div style="float:right">
							<a href="{$fixed_base_url}admin_tools/user/{$comment['user_id']}/edit"><img src="{$fixed_base_url}assets-admin/img/user_detail.png" alt="{'label_post_user_detail'|lang}"></a>
						</div>
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
				<td>
					<select id="status" name="status">
						{foreach $status_types as $s}
						<option value="{$s.id|h}" {(isset($comment['status']) && $comment['status']=={$s.id|h})?'selected':''}>{$s.label|lang}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</tbody>
		</table>
		<input type="hidden" name="user_id" value="{$comment['user_id']|h}"/>
		<input type="hidden" name="user_dest" value="{$comment['user']|h}"/>
		<input type="hidden" name="group_dest" value="{$comment['group']|h}"/>
		<input type="hidden" name="created_at" value="{$comment['created_at']|h}"/>
		<input type="hidden" name="parent_id" value="{$comment['parent_id']|h}"/>
		<div class="ct mtb10">
			<a href="" name="btn_update" id="btn_update"><img src="{$fixed_base_url}assets-admin/img/chk.png" alt="{'label_button_confirm'|lang}"></a>
			<a href="" name="btn_delete" id="btn_delete"><img src="{$fixed_base_url}assets-admin/img/delete.png" alt="{'label_button_delete'|lang}"></a>
		</div>
		<a href="" class="modalOpen" id="btn-dialog"></a>
	</div>
{''|form_close}
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal" style="width:300px; text-align: center">
				<div class="ctbox">
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
<script type="text/javascript">
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

		var urlAction = "{/literal}{$fixed_base_url}admin_tools/comment/{$comment['id']}/delete{literal}";
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