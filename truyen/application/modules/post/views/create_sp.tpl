<div id="create_post_content">
{''|form_open:'id=form_create_post'}
	<div class="ctinner clearfix">
		<div class="entry_view">
			<div class="clearfix">
			<div class="enter_head">
				<p class="name">{'label_post_send_to'|lang} :
					<a href="#mailtomodal" class="mailtomodal link_button r2l" onclick="get_dest_create()">{'label_post_select_dest'|lang}</a>
					<span style="margin-top: 5px" id="dest">{(isset($post_edit_to))?$post_edit_to:''}</span>
				</p>
				<div id="dest_error" style="color:red"></div>
			</div><!--/enter_head-->
			</div><!--/clearfix-->
			<div class="th_edit">
				<textarea id="post_body_add" name="post_body" rows="5" cols="" style="width:100%; resize: none">{if (isset($body))}{$body|h}{/if}</textarea>
				<div id="post_error" style="color:red"></div>
			</div>
			<div class="clearfix">
			<span class="btn-upload">
				<span>{'label_post_select_file'|lang}...</span>
			</span>
			<span class="fileinput-button">
				<input type="file" id="upload_form" name="files[]" multiple>
			</span>
			</div>
			<div>
				{'label_post_file_retention_period'|lang}：
				<select name="expire_type" id="select">
					{foreach $expired_types as $item}
					<option value="{$item['id']}">{$item['label']|lang}</option>
					{/foreach}
				</select>
			</div>
			
			<div class="btn">
				<a href="#" id="btn_send" name="button_send" class="sendbtn">{'label_post_button_send'|lang}</a>
			</div>
			<div class="row fileupload-buttonbar">
				<div class="mt20">
					<!-- The global file processing state -->
					<span class="fileupload-process"></span>
				</div>
	
				<!-- Upload Progress -->
				<div class="fileupload-progress fade">
					<!-- Progress Bar -->
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar progress-bar-success" style="width:0%;"></div>
					</div>
					<!-- Upload detail -->
					<div class="progress-extended">&nbsp;</div>
				</div>
			</div>
	        <!-- Uploaded files -->
	        <table role="presentation" class="table table-striped fileupload-list"><tbody class="files"></tbody></table>

		</div><!--/entry_view-->
	</div><!--/ctinner-->
	<input type="hidden" id="change_status" value="">
	<input type="hidden" id="hdn_dest_user" name="hdn_dest_user" value="{(isset($dest_user_id))?$dest_user_id:''}"/>
	<input type="hidden" id="hdn_dest_group" name="hdn_dest_group" value="{(isset($dest_group_id))?$dest_group_id:''}"/>
	<input type="hidden" id="hdn_dest_group_upload" name="hdn_dest_group_upload" value="{(isset($dest_group_id))?$dest_group_id:''}"/>
{''|form_close}
{literal}
<script type="text/javascript">
$("#post_body_add").focus();
var disable_button_send = false;

{/literal}
{include file="../../post/views/post_modal_sp.tpl"}
{literal}

function get_dest_create() {
	var url_get = "{/literal}{$fixed_base_url}post/dest_list/{$smarty.const.MODAL_HIDE_BUTTON_INVITE}{literal}";
	$.ajax({
		type: 'GET',
		url:url_get,
		dataType:"html",
		cache:false,
		success:function(data){
			$("#mailtomodal").html(data);
		}
	});
}

$(".btn-upload").click(function() {
	$('#upload_form').click();
});

$("#btn_send").click(function() {
	if (disable_button_send) {
		return false;
	}
	var post_from;
	if (typeof (group_id) !== 'undefined' && group_id !== '') {
		post_from = "{/literal}{$smarty.const.TYPE_GROUP}{literal}";
	} else {
		post_from = "{/literal}{$smarty.const.TYPE_USER}{literal}";
	}

	var urlAction = "{/literal}{$fixed_base_url}post/store/{literal}" + post_from;
	var form = $("#form_create_post").serializeArray();
	$.ajax({
		type: 'POST',
		url: urlAction,
		data: form,
		dataType :'html',
		beforeSend: function() { $('#processing').removeClass('hide'); },
		success : function(data){
			$('#processing').addClass('hide');
			var obj;
			try {
				obj = JSON && JSON.parse(data) || $.parseJSON(data);
				if (obj.error) {
					confirm_reload();
				}
			} catch (e) {
				confirm_reload();
			}
			$('#post_error').html('');
			$('#dest_error').text('');
			if(obj.success === 'success') {
				$('#create_post_content').remove();
				if (typeof (group_id) !== 'undefined' && group_id !== '') {
					location.reload();
				} else {
					location.replace("{/literal}{$fixed_base_url}{literal}");
				}
			} else if(typeof obj.body !== 'undefined'){
				$('#post_error').html(obj.body);
			} else if(typeof obj.dest !== 'undefined') {
				$('#dest_error').text(obj.dest);
			}
		},
		error: function (data, status, e){
			location.replace("{/literal}{$fixed_base_url}login{literal}");
		}
	});
});

	// UPLOAD 用スクリプト
	$(function () {
		'use strict';
		$('.fileupload-list').hide();

		// Initialize the jQuery File Upload widget:
		$('#form_create_post').fileupload({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			autoUpload: true,
            limitMultiFileUploads: 20,
            limitMultiFileUploadSize: 1073741824,
            limitMultiFileUploadSizeOverhead: 512,
            sequentialUploads: false,
            url: '{/literal}{$fixed_base_url}{literal}file/upload{/literal}/{(isset($dest_group_id))?$dest_group_id:''}{literal}',
            messages: {
                unknownError: '{/literal}{'label_upload_unknown_error'|lang}{literal}'  
            },	
		});

		// Enable iframe cross-domain access via redirect option:
		$('#form_create_post').fileupload(
			'option',
			'redirect',
			window.location.href.replace(
			/\/[^\/]*$/,
			'/cors/result.html?%s'
			)
		);

		// Load existing files:
		$('#form_create_post').addClass('fileupload-processing');

		$.ajax({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			url: $('#form_create_post').fileupload('option', 'url'),
			dropZone: $('#dropbox'),
			dataType: 'json',
			context: $('#form_create_post')[0]
		}).always(function () {
			$(this).removeClass('fileupload-processing');
			$('.fileupload-list').show();
		}).done(function (result) {
			$(this).fileupload('option', 'done')
//			.call(this, $.Event('done'), {result: result});
//			delete_upload_files(); // 初期のみ削除
			// 削除処理は result から読み込んで処理
            var files = result.files, filesLength = files.length;
			var result = "";
	        for (var i = 0; i < filesLength; i++) {
				var data = {
					url: files[i].deleteUrl,
					type: files[i].deleteType
				}
                $.ajax(data).done().fail();
			}
		});
	});
</script>
{/literal}
</div>
<style type="text/css">
{literal}
.upfile_part {
	width: 200px;
}
{/literal}
</style>
