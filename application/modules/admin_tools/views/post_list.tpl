{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{$fixed_base_url|cat:'admin_tools/post'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_post_thread_comment_management'|lang}</h2></div>
		<h3>{'label_post_button_post_search'|lang}</h3>
		<div class="ctinner">
			<ul class="form clearfix">
				<li><p>
					<label class="" for="post_id">{'label_post_id'|lang}</label>
					<input type="text" id="post_id" class="number" name="post_id" value="{(isset($post_id))?$post_id:''}"/>	
				</p></li>
				<li><p>
					<label class="" for="group_id">{'label_post_group_dest_id'|lang}</label>
					<input type="text" name="group_id" class="number" id = "group_id" value="{(isset($group_id))?$group_id:''}"/>
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label class="" for="user_id">{'label_post_user_id'|lang}</label>
					<input type="text" name="user_id" class="number" id = "user_id" value="{(isset($user_id))?$user_id:''}"/>
				</p></li>
				<li><p>
					<label class="" for="fullname">{'label_post_posted_by_name'|lang}</label>
					<input type="text" name="last_name" id="last_name" value="{(isset($last_name))?$last_name:''}"/>
					<input type="text" name="first_name" id="first_name" value="{(isset($first_name))?$first_name:''}"/>
				</p></li>
			</ul>
			<ul class="form clearfix">
				<li><p>
					<label for="created_date">{'label_created_date'|lang}</label>
					<input type="text" name="from_date" id="from_date" style="width:80px" value="{(isset($from_date))?$from_date:''}"/> 〜
					<input type="text" name="to_date" id="to_date" style="width:80px" value="{(isset($to_date))?$to_date:''}"/>
				</p></li>
				<li><p>
					<label class="" for="status">{'label_post_public_status'|lang}</label>
					<select id="status" name="status">
						<option value=""></option>
						{foreach $status_types as $s}
						<option value="{$s.id|h}" {(isset($status) && $status=={$s.id|h})?'selected':''}>{$s.label|lang}</option>
						{/foreach}
					</select>
				</p></li>
			</ul>
			{form_error('post_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('group_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('user_id','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('from_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			{form_error('to_date','<ul><li class="alert-error"> ※ ','</li></ul>')}
			<p class="alignR"><a id="btn_search" href=""><img src="{$fixed_base_url}assets-admin/img/search.png"></a></p>
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->

	{if isset($is_has_data) and $is_has_data eq true }
	<div class="ctbox">
		<div class="ctinner tround">
			<div class="alignR">
				<div style="float:left; margin-left: 70px;padding-top: 3px;">{'label_type_export_tsv'|lang}
					<select name="type_tsv" id="type_tsv" style="width:200px">
						{foreach from=$format_tsv item=item}
								<option value="{$item['id']}">{$item['label']|lang}</option>
						{/foreach}
					</select>
				</div>
				<a id = "btn_export_all"><img src="{$fixed_base_url}assets-admin/img/dl_all.png"></a>　
				{if isset($total_pages) and $total_pages > 0 }
					<a id = "btn_export_search"><img src="{$fixed_base_url}assets-admin/img/dl_result.png"></a>
				{/if}
			</div>
		</div> <!-- ctinner -->
	</div> <!-- ctbox -->
	{/if}
	
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
			<p>
				{if $total_records > 0}
					{$total_records}{'label_item'|lang}
				{else}
					{'label_no_data'|lang}
				{/if}
			</p>
		</div>
		
	</div>
	{''|form_close}
	{if isset($total_records) && $total_records > 0 }
	<table class="box_table" style="table-layout:fixed">
		<tr>
			<th style="width:90px">{'label_post_id'|lang}</th>
			<th style="width:350px">{'label_post_body'|lang}</th>
			<th style="width:80px">{'label_post_user_id'|lang}</th>
			<th style="width:80px">{'label_status'|lang}</th>
			<th style="width:70px">{'label_link_edit'|lang}</th>
		</tr>
		{foreach $posts as $post}
		<tr>
			<td>{$post->id}</td>
			<td><div class="text-word-break">{$post->body}</div></td>
			<td>{$post->user_id}</td>
			<td style="text-align:center">{$post->status|lang}</td>
			<td style="text-align:center"><a href="{$fixed_base_url}admin_tools/post/{$post->id}/edit"><img src="{$fixed_base_url}assets-admin/img/edit.png"></a></td>
		</tr>
		{/foreach}
	</table>
	<div class="page clearfix">{$links}</div>
	{/if}
{/block}
{block name=stylesheet}
<style>
	.alert-error{
		color:red;
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
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			$('#form-main').submit();
		});
		$('#btn_search').click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
				return false;
			}
			$('#form-main').submit();
			return false;
		});

		$("#btn_export_all").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/post/export_all?encoding="+$('#type_tsv').val(); 
			$("#btn_export_all").attr('href', href);
		});

		$("#btn_export_search").click(function(){
			var href="{/literal}{$fixed_base_url}{literal}admin_tools/post/export_search?encoding="+$('#type_tsv').val(); 
			$("#btn_export_search").attr('href', href);
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