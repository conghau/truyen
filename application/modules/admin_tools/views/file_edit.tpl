{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
{'admin_tools/file/'|cat:$file.upload_id|cat:'/confirm_edit'|form_open:'id=form-main'}
	<div class="ctbox">
		<div class="ttl"><h2>{'label_file_management'|lang} > {$file.upload_id|h}</h2></div>
		<table id="box_table" class="box_table_h">
			<tr>
				<th>{'label_upload_id_srch'|lang}</th>
				<td>{$file.upload_id|h}</td>
			</tr
			<tr>
				<th>{'label_upload_user_id'|lang}</th>
				<td>{$file.user_id|h}
					<a href="{$fixed_base_url}admin_tools/user/{$file.user_id}/edit"><img src="{$fixed_base_url}assets-admin/img/user_detail.png" class="fR"></a>
				</td>
			</tr>
			<tr>
				<th>{'label_upload_post_id'|lang}</th>
				<td>{$file.post_id|h}
					<a href="{$fixed_base_url}admin_tools/post/{$file.post_id}/edit"><img src="{$fixed_base_url}assets-admin/img/thread_detail.png" class="fR"></a></td>
			</tr>
			<tr>
				<th>{'label_upload_expired_type'|lang}</th>
				<td>
				<select id="expired_type" name="expired_type">
					{foreach $expired_types as $item}
						{if $item['id'] eq $file.expired_type}
							<option value="{$item['id']}" selected="true">{$item['label']|lang}</option>
						{else}
							<option value="{$item['id']}" >{$item['label']|lang}</option>
						{/if}
					{/foreach}
				</select>
				</td>
			</tr>
			<tr>
				<th>{'label_upload_file_name'|lang}</th>
				<td>
					<p>{$file.file_name|h}</p>
				 	<a id="btn-download" href="{$fixed_base_url}admin_tools/download/{$file['file_id']}"><img src="{$fixed_base_url}assets-admin/img/file_download.png" class="fR"></a>
				 </td>
			</tr>
			<tr>
				<th>{'label_upload_file_type_edit'|lang}</th>
				<td>
					{foreach $file_types as $item}
						{if $item['id'] eq $file.file_type}
							{$item['label']|lang}
						{/if}
					{/foreach}
				</td>
			</tr>
			<tr>
				<th>{'label_upload_file_size_edit'|lang}</th>
				<td>{$file.file_size|file_size_format:0:'B'}</td>
			</tr>
			<tr>
				<th>{'label_upload_created_at'|lang}</th>
				<td>
					{$file.year_created_date|cat:('label_year'|lang)}{$file.month_created_date}{'label_month'|lang}{$file.day_created_date}{'label_day'|lang}
					{$file.hour_created_date}:{$file.min_created_date}
				</td>
			</tr>
			<tr id="expired">
				<th>{'label_upload_expired_at'|lang}</th>
				<td>
					<!-- select box year -->
					<select id="year" name="year_expired_date">
						{for $foo=$cur_year to $cur_year+10}
							{if $foo eq $file.year_expired_date}
								<option value="{$foo}" selected="true">{$foo}</option>
							{else}
								<option value="{$foo}">{$foo}</option>
							{/if}
						{/for}
					</select>
					{'label_year'|lang}
					
					<!-- select box month -->
					<select id="month" name="month_expired_date">
						{for $foo=1 to 12}
							{if $foo eq $file.month_expired_date}
								<option value="{str_pad($foo,2,'0',0)}" selected="true">{str_pad($foo,2,"0",0)}</option>
							{else}
								<option value="{str_pad($foo,2,'0',0)}">{str_pad($foo,2,"0",0)}</option>
							{/if}
						{/for}
					</select>
					{'label_month'|lang}
					
					<!-- select box day -->
					<select id="day" name="day_expired_date">
						{for $foo=1 to 31}
							{if $foo eq $file.day_expired_date}
								<option value="{str_pad($foo,2,'0',0)}" selected="true">{str_pad($foo,2,"0",0)}</option>
							{else}
								<option value="{str_pad($foo,2,'0',0)}">{str_pad($foo,2,"0",0)}</option>
							{/if}
						{/for}
					</select>
					{'label_day'|lang}
					
					<!-- select box hour -->
					<select id="hour" name="hour_expired_date">
						{for $foo=0 to 23}
							{if $foo eq $file.hour_expired_date}
								<option value="{str_pad($foo,2,'0',0)}" selected="true">{str_pad($foo,2,"0",0)}</option>
							{else}
								<option value="{str_pad($foo,2,'0',0)}">{str_pad($foo,2,"0",0)}</option>
							{/if}
						{/for}
					</select>
					:
					<!-- select box minute -->
					<select id="min" name="min_expired_date">
						{for $foo=0 to 59}
							{if $foo eq $file.min_expired_date}
								<option value="{str_pad($foo,2,'0',0)}" selected="true">{str_pad($foo,2,"0",0)}</option>
							{else}
								<option value="{str_pad($foo,2,'0',0)}">{str_pad($foo,2,"0",0)}</option>
							{/if}
						{/for}
					</select>
					<input type="hidden" id="txt_expired_date" name="expired_date">
					{form_error('expired_date','<p class="alert-error"> ※ ','</p>')}
				</td>
			</tr>
			<tr>
				<th>{'label_status'|lang}</th>
				<td>
					<select name="status">
					{foreach from=$status_types item = item}
						{if $file.status eq $item.id}
							<option value="{$item.id}" selected="true">{$item.label|lang}</option>
						{else}
							<option value="{$item.id}">{$item.label|lang}</option>
						{/if}
					{/foreach}
				</select>
				</td>
			</tr>
		</table>
		<div class="ct mtb20">
			<a href="" id="btn_edit"><img src="{$fixed_base_url}assets-admin/img/chk.png" ></a>
		</div>
	</div> <!-- ctbox -->
	<input type="hidden" name="upload_id" value="{$file.upload_id}">
	<input type="hidden" name="user_id" value="{$file.user_id}">
	<input type="hidden" name="post_id" value="{$file.post_id}">
	<input type="hidden" name="file_name" value="{$file.file_name}">
	<input type="hidden" name="file_type" value="{$file.file_type}">
	<input type="hidden" name="file_size" value="{$file.file_size}">
	<input type="hidden" name="year_created_date" value="{$file.year_created_date}">
	<input type="hidden" name="month_created_date" value="{$file.month_created_date}">
	<input type="hidden" name="day_created_date" value="{$file.day_created_date}">
	<input type="hidden" name="hour_created_date" value="{$file.hour_created_date}">
	<input type="hidden" name="min_created_date" value="{$file.min_created_date}">
	<a href="" class="modalOpen" id="btn-dialog"></a>
{''|form_close}
<div class="modalBase">
	<div class="modalMask"></div>
	<div class="modalWrap">
		<div class="modal">
			<div class="ctbox">
				<div class="ctinner_m">
					<p id="dialog_p"></p>
					<p class="ct m20">
						<a id="btn_ok" href="{$fixed_base_url}admin_tools/file/{$file.upload_id}/edit"><img src="{$fixed_base_url}assets-admin/img/ok.png"></a>
					</p>
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
	select {
		margin-right:0;
	}
	#expired {
		display: none;
	}
	.alert-error {
		color:red;
	}
</style>
{/block}

{block name=javascript}
{literal}
	<script language="javascript">
		$(document).ready(function() {
			var expired_type = $('#expired_type').val();
			if (expired_type == -1 || expired_type == 3 || expired_type == 365) {
				$('#expired').hide();
			} else {
				$('#expired').show();
			}
			
			var error = "{/literal}{$download_error}{literal}";
			if (error == "1") {
				$("#dialog_p").append("{/literal}{'L-A-0034-E'|lang}{literal}");
				$('#btn-dialog').click();
			}
			
			$('#month, #year').change(function () {
				setDay();
			});
			$('#btn_edit').click(function(){
				var cct_cookie = $.cookie('csrf_token');
				if(cct_cookie == undefined) {
					window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
					return false;
				}
				var year = $('#year').val();
				var month = $('#month').val();
				var day = $('#day').val();
				var hour = $('#hour').val();
				var min = $('#min').val();
				$('#txt_expired_date').val(year + '-' + month + '-' + day + ' '+ hour + ':' + min);
				$('#form-main').submit();
				return false;
			});

			$('#expired_type').change(function(){
				var expired_type = $('#expired_type').val();
				if (expired_type == -1 || expired_type == 3 || expired_type == 365) {
					$('#expired').hide();
				} else {
					$('#expired').show();
				}
			});
		});
			
		function daysInMonth(month,year) {
			var dd = new Date(year, month, 0);
			return dd.getDate();
		};
		function setDayDrop(dyear, dmonth, dday){
			var year =  parseInt(dyear.val());
			var month = parseInt(dmonth.val());
			var day = parseInt(dday.val());
			var countDay = daysInMonth(month,year);
			if (day > countDay) day=1;
			dday.empty();
			for (var i=1; i <= countDay; i++){
				if (i == day)
					dday.append("<option value='"+i+"' selected ='true' >"+i+"</option>");
				else
					dday.append("<option value='"+i+"'>"+i+"</option>");
			}
		};
		function setDay() {
			var year = $('#year');
			var month = $('#month');
			var day = $('#day');
			setDayDrop(year, month, day);
		};
	</script>
{/literal}
{/block}