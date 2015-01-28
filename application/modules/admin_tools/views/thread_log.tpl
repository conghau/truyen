{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/threadlog'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_thread_log_management'|lang}</h2></div>
		<h3>{'label_button_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><label>{'label_post_id'|lang}</label>
				<input type="text" id="id" class="number" name="id" value="{(isset($id))?$id:''}"/>
				</li>
				<li>
					<label>{'label_post_user_id'|lang}</label>
					<input type="text" name="user_id" class="number" id = "user_id" value="{(isset($user_id))?$user_id:''}"/>
				</li>
				<li>{'label_post_posted_by_name'|lang}
					<input type="text" name="last_name_ja" id="last_name_ja" value="{(isset($last_name_ja))?$last_name_ja:''}"/>
					<input type="text" name="first_name_ja" id="first_name_ja" value="{(isset($first_name_ja))?$first_name_ja:''}"/>
				</li>
			</ul>
			<ul class="form clearfix">
				<li><label>{'label_created_date'|lang}</label>
					<input type="text" name="from_date" id="from_date" value="{(isset($from_date))?$from_date:''}"/> 〜
					<input type="text" name="to_date" id="to_date" value="{(isset($to_date))?$to_date:''}"/>
				</li>
				<li><label>{'label_post_public_status'|lang}</label>
					<select id="status" name="status">
						<option value=""></option>
						{foreach $status_types as $s}
						<option value="{$s.id}" {(isset($status) && $status=={$s.id})?'selected':''}>{$s.label|lang}</option>
						{/foreach}
					</select>
				</li>
			</ul>
			{form_error('id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('user_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('from_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('to_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<div class="alignR">
				<a id="btn_search" href=""><img src="{$fixed_base_url}assets-admin/img/search.png" alt="{'label_button_search'|lang}"></a>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<div class="fr">
			{if isset($total_records) && $total_records > 0}
				<li style="float: right">{'label_number_record_per_page'|lang}
					<select name="per_page" id="txt_per_page" style="width:75px">
						{foreach $pagination_per_page_option as $row}
							{if $per_page eq $row}
								<option value="{$row}" selected="true">{$row}{'label_item'|lang}</option>
							{else}
								<option value="{$row}">{$row}{'label_item'|lang}</option>
							{/if}
						{/foreach}
					</select>
				</li>
			{/if}
		</div>
		<div class="hit fl">
			{if isset($total_records) && $total_records > 0}
				<p>{$total_records}{'label_item'|lang}</p>
			{else}
				<p>{'label_no_data'|lang}</p>
			{/if}
		</div>	
	</div>
{if $total_pages > 0}
	<table class="box_table">
		<tr>
			<th width="10%">{'label_post_id'|lang}</th>
			<th width="40%">{'label_post_body'|lang}</th>
			<th width="20%">{'label_post_user_name'|lang}</th>
			<th width="10%">{'label_status'|lang}</th>
			<th width="10%">{'label_activity_link_detail'|lang}</th>
		</tr>
		{foreach $posts as $post}
		<tr>
			<td>{$post->id}</td>
			<td>{$post->body}</td>
			<td>{$post->last_name_ja|h}{$post->first_name_ja|h}</td>
			<td>{$post->status|lang}</td>
			<td><a href="{$fixed_base_url}admin_tools/threadlog/{$post->id}/detail">
					<img src="{$fixed_base_url}assets-admin/img/detail.png" alt="{'label_activity_link_detail'|lang}">
			</a></td>
		</tr>
		{/foreach}
	</table>
	<div class="page clearfix">{$links}</div>
{/if}
{''|form_close}
{/block}
{block name=stylesheet}
	<style>
		.alert-error{
			color:red;
		}
		table {
			table-layout: fixed;
		}
		table td {
			word-wrap:break-word;
		}
	</style>
{/block}
{block name=javascript}
{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			$("#from_date").datepicker({showButtonPanel: true});
			$("#to_date").datepicker({showButtonPanel: true});
			$('#txt_per_page').change(function(){
				$('#btn_search').click();
			});
			
			$('#btn_search').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return;
				}
				$('#form-main').submit();
				return false;
			});

			$('.number').keypress(function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode >= 48 && charCode <= 57)|| charCode ==8 || charCode ==9 || charCode == 45) {
					return true;
				}
				return false;
			});
		});
		
	</script>
{/literal}
{/block}
