{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/file/'|cat:$file.upload_id|cat:'/update'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_file_management'|lang} > {$file.upload_id|h}</h2></div>
		<table class="box_table_h" >
			<tr>
				<th>{'label_upload_id_srch'|lang}</th>
				<td>{$file.upload_id}</td>
			</tr>
			<tr>
				<th> {'label_upload_user_id'|lang} </th>
				<td> {$file.user_id}</td>
			</tr>
			<tr>
				<th> {'label_upload_post_id'|lang}</th>
				<td> {$file.post_id} </td>
			</tr>
			<tr>
				<th> {'label_upload_expired_type'|lang} </th>
				<td>
					{foreach $expired_types as $item}
						{if $item.id eq $file.expired_type}
							{$item.label|lang}
						{/if}
					{/foreach}
				</td>
			</tr>
			<tr>
				<th> {'label_upload_file_name'|lang}</th>
				<td> {$file.file_name} </td>
			</tr>
			<tr>
				<th> {'label_upload_file_type_edit'|lang}</th>
				<td>
					{foreach $file_types as $item}
						{if $item.id eq $file.file_type}
							{$item.label|lang}
						{/if}
					{/foreach}
				</td>
			</tr>
			<tr>
				<th> {'label_upload_file_size_edit'|lang}</th>
				<td> {$file.file_size|file_size_format:0:'B'}</td>
			</tr>
			<tr>
				<th> {'label_upload_created_at'|lang}</th>
				<td>
					{$file.year_created_date|cat:('label_year'|lang)}{$file.month_created_date}{'label_month'|lang}{$file.day_created_date}{'label_day'|lang}
					{$file.hour_created_date}:{$file.min_created_date}
				</td>
			</tr>
			<tr id="expired">
				<th> {'label_upload_expired_at'|lang}</th>
				<td>
					{$file.year_expired_date}{'label_year'|lang}{$file.month_expired_date}{'label_month'|lang}{$file.day_expired_date}{'label_day'|lang}
					{$file.hour_expired_date}:{$file.min_expired_date}
				</td>
			</tr>
			<tr>
				<th> {'label_status'|lang}</th>
				<td> 
					<label>{$status_types[$file.status].label|lang}</label>
				</td>
			</tr>
		</table>
		<div class="ct mtb20">
			<a href="" id="btn-cancel"><img src="{$fixed_base_url}assets-admin/img/cancel.png" ></a>
			<a href="" id="btn-update"><img src="{$fixed_base_url}assets-admin/img/complete.png" ></a>
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
					<p class="ct m20"><a href="{$fixed_base_url}admin_tools/file"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a></p>
				</div><!--/ctinner-->
			</div><!--/ctbox-->
		</div>
	</div>
</div>
{/block}

{block name=stylesheet}
	<style>
		table {
			table-layout:fixed;
			font-size: inherit;
		}
		table td {
			overflow: hidden;
			word-wrap:break-word;
		}
	</style>
{/block}
{block name=javascript}
	{literal}
	<script language="Javascript">
		$(document).ready(function(){
			var expired_type = "{/literal}{$file['expired_type']}{literal}";
			if (expired_type == -1 || expired_type == 3 || expired_type == 365 ) {
				$('#expired').hide();
			}
			$("#btn-cancel").click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return false;
				}
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/file/{$file['upload_id']}/edit{literal}";
				$("#form-main").attr('action',urlAction);
				$("#form-main").submit();
				return false;
			});
			
			$("#btn-update").click(function(){
				var urlAction = "{/literal}{$fixed_base_url}admin_tools/file/{$file['upload_id']}/update{literal}";
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
						return false;
					}
				});
				return false;
			});
		});
	</script>
	{/literal}
{/block}