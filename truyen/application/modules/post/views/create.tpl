<!-- BEGIN -->
<div id="create_post_content">
{''|form_open:'id=form_create_post'}
	<div class="ctinner tround">
		<div class="entry_view">
			<div class="clearfix">
				<div class="enter_head">
					<p class="name">
						{'label_post_send_to'|lang} : 
						<a href="{$fixed_base_url}post/dest_list/{$smarty.const.MODAL_HIDE_BUTTON_INVITE}" class="mailtomodal link_button">{'label_post_select_dest'|lang}</a>
						<div id="dest">{(isset($post_edit_to))?$post_edit_to:''}</div>
						<div id="dest_error" style="color:red"></div>
					</p>
				</div><!--/enter_head-->
			</div><!--/clearfix-->
			<div class="th_edit">
				<textarea id="post_body_add" name="post_body" rows="5" cols="">{if (isset($body))}{$body|h}{/if}</textarea>
				<div id="post_error" style="color:red"></div>
			</div>
			<div class="clearfix upload_file"> 
				<div class="drop dropzone" id="dropbox">
					<div class="droplabel-top">
					{'label_post_drag_file'|lang}
					</div>
					<div class="droplabel-bottom">
					{'label_upload_notice'|lang}
					</div>
				</div>
				<div class="row fileupload-buttonbar">
					<div class="upload_right">
						<p>
							<span class="btn btn-primary fileinput-button">
							<span>{'label_post_select_file'|lang}...</span>
							<input type="file" id="upload_form" name="files[]" multiple>
							</span>
						</p>	
						{'label_post_file_retention_period'|lang}：
						<select name="expire_type" id="select">
							{foreach $expired_types as $item}
							<option value="{$item['id']}">{$item['label']|lang}</option>
							{/foreach}
						</select>
						<p class="mt20">
						<a><img id="btn_send" src="{$fixed_base_url}assets/img/{$language}/send.png" class="sendbtn"></a>
						</p>
					</div>
				</div>
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
			<div id="file_error" style="color:red"></div>
	        <!-- Uploaded files -->
	        <table role="presentation" class="table table-striped fileupload-list"><tbody class="files"></tbody></table>
		</div><!--/entry_view-->
	</div><!--/ctinner-->
	<input type="hidden" id="change_status" value="">
	<input type="hidden" id="hdn_dest_user" name="hdn_dest_user" value="{(isset($dest_user_id))?$dest_user_id:''}"/>
	<input type="hidden" id="hdn_dest_group" name="hdn_dest_group" value="{(isset($dest_group_id))?$dest_group_id:''}"/>
	<input type="hidden" id="hdn_dest_group_upload" name="hdn_dest_group_upload" value="{(isset($dest_group_id))?$dest_group_id:''}"/>
{''|form_close}
</div>

<!-- END -->
<div id="dialog-alert"></div>

<style>
	.no-close{
		display: none;
	}
	
	.btn_send_hover:hover {
		opacity: 0.7;
		cursor: pointer;
	}
</style>

{literal}
<script type="text/javascript">
	$("a.mailtomodal").pageslide({ direction: "left"});  
	$("#post_body_add").focus();
	var disable_button_send = false;
	var delete_upload_files = function() {
		$('.files').find('.delete').click();
	}
	function is_posting(){
		return (($.trim($("#post_body_add").val()) != "")
			|| ($(".files").children().length > 0)
			|| ($("#hdn_dest_user").val() != ""));
	}
	var intercept_page_move = function() {
        $(window).on('beforeunload', function() {
            if (is_posting()){
				return '{/literal}{'label_post_page_transition'|lang}{literal}';
            }
            return;
        });
    }

{/literal}
{include file="../../post/views/post_modal.tpl"}
{literal}

    $("#post_body_add,#select").on('change', intercept_page_move);
	$("#upload_form").on('change', intercept_page_move);
	$("a.mailtomodal").on('click', intercept_page_move);
	$("#btn_cancel").on('click', delete_upload_files);

	$("#btn_send").click(function() {
		if (disable_button_send) {
			return false;
		}
        $(window).off('beforeunload'); // 確認ボタンを出さない
		
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
				$('#dest_error').html('');
				$('#post_error').html('');
				$('#file_error').html('');
				if(obj.success === 'success') {
					$('#create_post_content').remove();
					if (typeof (group_id) !== 'undefined' && group_id !== '') {
						location.reload();
					} else {
						location.replace("{/literal}{$fixed_base_url}{literal}");
					}
				} else {
					if(typeof obj.body !== 'undefined') {
						$('#post_error').html(obj.body);
					}
					if (typeof obj.dest !== 'undefined'){
						$('#dest_error').text(obj.dest);
					}
					if (typeof obj.file !== 'undefined'){
						$('#file_error').text(obj.file);
					}
				}
			},
			error: function (data, status, e){
				window.location.replace(fixed_base_url + "login");
			}
		});
	});
	
	$("#sel_dest_invite").click(function() {
		$("#dialog-form").dialog( "open" );
		init_dialog();
	});

	function get_dest_name(user_id, group_id) {
		var str_user_id = user_id.join('_');
		if (str_user_id == '') {
			str_user_id = 0;
		}
		$.get("{/literal}{$fixed_base_url}post/get_dest_name/{literal}" + str_user_id + "/" + group_id, function (data) {
			var obj = JSON && JSON.parse(data) || $.parseJSON(data);
			$("#dest").html(obj);
		});
	}
	
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
			context: $('#form_create_post')[0],
		}).always(function () {
			intercept_page_move(); // 編集状態にする
			$(this).removeClass('fileupload-processing');
			$('.fileupload-list').show();
		}).done(function (result) {
			try {
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
			} catch (e) {
				confirm_reload();
			}
		});
	});
</script>
<style type="text/css">
{literal}
.upfile_part {
	width: 520px;
}
{/literal}
</style>
{/literal}