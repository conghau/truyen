{* Extend our master template *}
{extends file="master_admin.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{block name=body}
	{''|form_open:'id=form-main enctype=multipart/form-data'}
		<div class="ctbox">
			<div class="ttl"><h2>{$title}</h2></div>
				<div class="ctinner tround">
				{'label_upload_format_guidance'|lang}
				(<a href="#" id="format_toggle">{'label_format_example'|lang}</a>)
				<br>
				<div id ="table-scroll" class="format" style="display: none;margin-top: 5px">
					<table class="box_table">
						<col width="20%">
						<col width="40%">
						<col width="40%">
						<tr>
							<th>{'label_format_column_number'|lang}</th>
							<th>{'label_format_column_name'|lang}</th>
							<th>{'label_format_description'|lang}</th>
						</tr>
					</table>
					<table class="box_table">
						<col width="20%">
						<col width="40%">
						<col width="40%">
						{foreach $tsv_format as $key => $rset}
							<tr>
								<td>{($key + 1)}</td>
								<td>{'column'|array_selector:$rset|h}</td>
								<td>{'description'|array_selector:$rset|h}</td>
							</tr>
						{/foreach}
					</table>
				</div>
				<br>
				<div style="float:left">
					<input type="file" name="upload" id="upload" style="width:200px" accept=".tsv" />
				</div>
				<div>
					<input type="image" id="btn-upload" class="btn-disabled" disabled
					style="border: none; padding-top:2px" src="{$fixed_base_url}assets-admin/img/upload.png" />
				</div>
				
				<div class="mtb10"><img src="{$fixed_base_url}assets-admin/img/hr.png" width="100%"></div>
				<div class="alignR">
					<div style="float:left; margin-left: 70px;padding-top: 3px;">{'label_type_export_tsv'|lang}
						<select name="type_tsv" id="type_tsv" style="width:200px">
							{foreach from=$format_tsv item=item}
									<option value="{$item['id']}">{$item['label']|lang}</option>
							{/foreach}
						</select>
					</div>
					<a id = "btn_export_all">
						<img src="{$fixed_base_url}assets-admin/img/dl_all.png">
					</a>
				</div>
			</div><!--/ctinner-->
		</div><!--/ctbox-->
			
		{if isset($case)}
			<div class="upload_chk">
				{if $case == "upload_confirm"}
					{if isset($msg_error) && $msg_error != ""}
						<h4>{$msg_error}</h4>
					{else}
						<h4> {'label_user_upload_results'|lang} </h4>
						<p> {$msg_result}</p>
						{if isset($error_details)}
							<p style="white-space:pre;">{$error_details}</p>
						{elseif $flag_error == true}
							<a href="{$fixed_base_url}admin_tools/qualification/download_file_error">{'label_button_download'|lang}</a>
						{elseif $flag_error == false}
							{'label_user_upload'|lang}
							<div class="chk_data">
								{'label_user_upload_new'|lang} <span>{$type_row['new']} {'label_item'|lang} </span> <br>
								{'label_user_upload_update'|lang} <span>{$type_row['update']} {'label_item'|lang} </span> <br>
								{'label_user_upload_delete'|lang} <span>{$type_row['delete']} {'label_item'|lang} </span>
							</div>
						{/if}
					{/if}
				{elseif $case == "upload_done"}
					{if isset($message) && $message != ""}
						<h4>{$message}</h4>
					{/if}
				{/if}
			</div>
			{if $case == "upload_confirm" && $flag_error == false}
				<div class="ct mtb10">
					<input type="image" id="btn_confirm" style="border: none; padding-top:2px"
					src="{$fixed_base_url}assets-admin/img/btn_upload_exec.png" />
				</div>
			{/if}
		{else}
				<table class="box_table">
					<col width="5%">
					<col width="45%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
					<tr>
						<th>{'label_qualification_id'|lang}</th>
						<th>{'label_qualification_name'|lang}</th>
						<th>{'label_qualification_category_id'|lang}</th>
						<th>{'label_qualification_position'|lang}</th>
						<th>{'label_qualification_status'|lang}</th>
					</tr>
					{for $i=0 to (count($qualifications['id'])-1)}
						<tr>
							<td>{$qualifications['id'][$i]}</td>
							<td class="text-word-break">{$qualifications['name'][$i]}</td>
							<td>{$qualifications['category_id'][$i]}</td>
							<td>{$qualifications['position'][$i]}</td>
							<td class="ct">
								{foreach from=$status_types item=item}
									{if $qualifications['status'][$i] eq $item.id}
										<label>{$item.label|lang}</label>
									{/if}
								{/foreach}
							</td>
						</tr>
					{/for}	
				</table>
			<div class="ct mtb20">
				<a href="#" id ="btn-cancel"><img src="{$fixed_base_url}assets-admin/img/cancel.png" alt="キャンセル"></a>　
				<a href="#" id ="btn-update"><img src="{$fixed_base_url}assets-admin/img/complete.png" alt="完了"></a>
				<a href="" class="modalOpen" id="btn-dialog"></a>
			</div>
		{/if}			
	{''|form_close}
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ctinner_m">
						<p id="dialog_p"></p>
						<p class="ct m20"><a href="{$fixed_base_url}admin_tools/qualification">
						<img src="{$fixed_base_url}assets-admin/img/ok.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>
{/block}

{block name=stylesheet}
	<style>
		a {
			cursor:pointer;
		}
	</style>
{/block}

{block name=javascript}
	{literal}
		<script type="text/javascript">
			$(document).ready(function(){
				$("#btn-cancel").click(function(){
					var urlAction = "{/literal}{$fixed_base_url}admin_tools/qualification{literal}";
					$("#form-main").attr('action',urlAction);
					$("#form-main").submit();
					return false;
				});
				
				$("#btn-update").click(function(){
					var urlAction = "{/literal}{$fixed_base_url}admin_tools/qualification/update{literal}";
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
						}
					});
					return false;
				});

				$("#btn-upload").click(function() {
					var cct_cookie = $.cookie('csrf_token');
					if(cct_cookie == undefined) {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
						return;
					}

					var urlAction = "{/literal}{$fixed_base_url}admin_tools/qualification/import_confirm{literal}";
					$("#form-main").attr('action',urlAction);
					$("#form-main").submit();
				});
				
				$("#btn_confirm").click(function() {
					var cct_cookie = $.cookie('csrf_token');
					if(cct_cookie == undefined) {
						window.location.replace("{/literal}{$fixed_base_url}admin_tools/login{literal}");
						return;
					}

					var urlAction = "{/literal}{$fixed_base_url}admin_tools/qualification/import_done{literal}";
					$("#form-main").attr('action',urlAction);
					$("#form-main").submit();
				});

				$("#upload").change(function(){
					if($(this).val() == ''){
						$('#btn-upload').attr("disabled",'disabled');
						$('#btn-upload').addClass('btn-disabled');
					} else {
						$('#btn-upload').removeAttr('disabled');
						$('#btn-upload').removeClass('btn-disabled');
					}
				});

				$("#btn_export_all").click(function(){
					var href="{/literal}{$fixed_base_url}{literal}admin_tools/qualification/export_all?encoding="+$('#type_tsv').val(); 
					$("#btn_export_all").attr('href', href);
				});

				$("#format_toggle").click(function () {
					$("div.format").toggle();
				});
			});	
		</script>

	{/literal}
{/block}