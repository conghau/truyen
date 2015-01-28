{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{block name=stylesheet}
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/fileupload.css">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload.css">
<link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="{$fixed_base_url}assets/css/upload/jquery.fileupload-ui-noscript.css"></noscript>
<link rel="stylesheet" href="{$fixed_base_url}assets/css/bootstrap.fileupload.css">
{/block}


{* This block is defined in the master.php template *}
{block name=body}
<div class="clearfix">
	<p class="kanfa"><a href="#"><img src="{$fixed_base_url}assets/img/btn_canfa.png" alt="新規のコンサルト/カンファ送信を作成"></a></p>
	<div class="per_area">
	<div class="clearfix">
			<div class="zan">送信可能残量</div>
			<div class="graf">
				<div class="grafwh">
				<div class="bar" style="width:50%"></div>
				</div>
			</div>
			<div class="percent"><strong>0.7G</strong>使用中/あと<strong>1.3G</strong>送れます</div>
	</div>
	</div>	
</div>


<div class="ctbox">



<!--新規スレッド投稿／コピー（個人ダッシュボード）【B-7】-->
{$fixed_base_url|cat:'sample/upload_file'|form_open:' enctype="multipart/form-data" id="fileupload" autocomplete=off'}

<div class="ctinner tround">
	<div class="entry_view">
		<div class="clearfix">
		<div class="enter_head">
			<p class="name">To : <input type="submit" value="宛先を選ぶ"></p>
		</div><!--/enter_head-->
					
		</div><!--/clearfix-->
		<div class="th_edit">
			<textarea name=""></textarea>
		</div>
		<div class="clearfix">
		<div class="drop dropzone" id="dropbox">
		ファイルをここにドラッグ
		</div>
        <div class="row fileupload-buttonbar">
		<div class="upload_right">
		<p>
<img type="submit" src="{$fixed_base_url}assets/img/btn_upload.png" alt="ファイルをアップロード" class="start">
		</p>
		ファイルの保存期間：<select name="select" id="select"><option>72時間</option></select>
		<p class=" mt20"><a href="#"><img src="{$fixed_base_url}assets/img/send.png"></a></p>
		</div>	
		</div>
		</div>
        <div class="row fileupload-buttonbar">
           <div class="col-lg-7">
                <span class="btn btn-primary fileinput-button">
                    <span>ファイルを選択する...</span>
                    <input type="file" name="files[]" multiple>
                </span>


                <button type="reset" class="btn btn-warning cancel">
                    <span>ファイル選択解除</span>
                </button>

                <button type="button" class="btn btn-danger delete">
                    <span>ファイル削除</span>
                </button>

                <input type="checkbox" class="toggle">

                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
			</div>

            <!-- アップロード進捗表示 -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- プログレスバー -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- アップロード進捗詳細 -->
                <div class="progress-extended">&nbsp;</div>
            </div>
		</div>
        <!-- アップロード/登録ファイル対象一覧 -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>

	</div><!--/entry_view-->
</div><!--/ctinner-->
{''|form_close}
<!--/新規スレッド投稿／コピー（個人ダッシュボード）【B-7】-->



<!--作成済みスレッドの編集【B-8】-->
<div class="ctinner tround">
	<div class="entry_view">
		<div class="clearfix">
		<div class="enter_head">
			<p class="name">To : 右田市連携会議</p>
		</div><!--/enter_head-->
					<p class="complete"><a href="#"><img src="{$fixed_base_url}assets/img/complete_s.png"></a></p>
		</div><!--/clearfix-->
		<div class="th_edit">
			<textarea name="">80歳男性の症例です。胸部大動脈瘤の疑い、2年前よりxxxxxを服用、xxxxx既往歴あり、南国大学病院にて一度は○○を試みるも○○○で閉胸。LAVA適用の可否判断と、サイジングをお願いします。</textarea>
			
			<div class="alignR">ファイルの保持期間を72時間延長する<input type="checkbox" name="" value=""></div>
		</div>
	</div><!--/entry_view-->
</div><!--/ctinner-->
<!--/作成済みスレッドの編集【B-8】-->


<!--1件-->
<div class="ctinner tround">
	<div class="entry_view">
		<div class="clearfix">
		<div class="enter_head">
			<div class="clearfix">
			<p class="name">From : 山田太郎</p>
			<p class="date">6月20日 10:34</p>
			</div><!--/clearfix-->
			<p class="to">To : 右田市連携会議　既読</p><span class="pop1">10</span>
		</div><!--/enter_head-->
		<div class="entry_btn">
			<ul>
			<li><a href="#"><img src="{$fixed_base_url}assets/img/btn_entry_edit.png"></a></li>
			<li><a href="#"><img src="{$fixed_base_url}assets/img/btn_entry_delete.png"></a></li>
			<li><a href="#"><img src="{$fixed_base_url}assets/img/btn_entry_copy.png"></a></li>
			</ul>
		</div><!--/entry_btn-->
		</div><!--/clearfix-->
		<div class="entry_body">
			<p>80歳男性の症例です。胸部大動脈瘤の疑い、2年前よりxxxxxを服用、xxxxx既往歴あり、南国大学病院にて一度は○○を試みるも○○○で閉胸。LAVA適用の可否判断と、サイジングをお願いします。</p>
		</div><!--/entry_body-->
		<div class="file_wrap clearfix">
			<ul class="file_part">
			<li class="jpg"><a href="#">aaaa.jpg</a><p>300KB</p></li>
			<li class="jpg"><a href="#">aaaa.jpg</a><p>300KB</p></li>
			<li class="jpg"><a href="#">aaaa.jpg</a><p>300KB</p></li>
			<li class="jpg"><a href="#">aaaa.jpg</a><p>300KB</p></li>
			</ul>
			<ul class="file_btn">
			<li><a href="#"><img src="{$fixed_base_url}assets/img/btn_file_dl.png" alt="一括ダウンロード"></a></li>
			<li><a  href="filemodal.html" class="file_modal"><img src="{$fixed_base_url}assets/img/btn_file_list.png" alt="ファイル一覧"></a></li>
			<li><a href="preview.html?TB_iframe" class="thickbox"><img src="{$fixed_base_url}assets/img/btn_file_preview.png" alt="プレビュー"></a></li>
			<li class="nokori alert">ファイル削除まで残り<span>2時間</span></li>
			</ul>
		</div><!--/file_wrap-->
		<div class="file_action clearfix">
			<a href="#">コメント(3)を表示する</a>
		</div><!--/file_action-->
		
		<div class="comment_wrap">
			<div class="comment_header">
			<div class="clearfix">
			<p class="name">From : 大川花子</p>
			<p class="date">6月20日 10:34</p>
			</div><!--/clearfix-->
			</div>
			<p>検討しましたが、ｘｘｘｘの理由でｘｘｘｘｘです。残念ながら、当方での対応は難しいです。<br>
			念のため、○○○○先生にも伺ってみましたが、ｘｘｘｘとのことでした。<br>
			その鑑別について先生がメモにして後日送って下さることになりました。<br>
			</p>
		</div><!--/comment_wrap-->

		<div class="comment_wrap">
			<div class="comment_header">
			<div class="clearfix">
			<p class="name">From : 山田太郎</p>
			<p class="date">6月20日 10:34</p>
			</div><!--/clearfix-->
			</div>
			<p>ご検討を有難うございました。○○○○先生のご見解は大変興味あります。後日で良いので、ぜひ詳しく教えて下さい。
			</p>
		</div><!--/comment_wrap-->
		

<!--投稿済みコメントの編集【B-9】-->
		<div class="comment_wrap pt20">
		<div class="th_edit">
			<textarea name="">ご検討を有難うございました。○○○○先生のご見解は大変興味あります。後日で良いので、ぜひ詳しく教えて下さい。</textarea>
		</div>
		<div class="alignR"><a href="#"><img src="{$fixed_base_url}assets/img/com_send.png"></a></div>
		</div>
<!--/投稿済みコメントの編集【B-9】-->	
	
	</div><!--/entry_view-->
</div><!--/ctinner-->
<!--/1件-->


</div><!--/ctbox-->

{literal}
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
			<ul class="upfile_part">
            <li class="jpg"><p class="name">{%=file.name%}</p></li>
			</ul>
            <strong class="error text-danger"></strong>
        </td>
        <td>
        </td>
        <td>
            <p class="size">処理中...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <span>アップロード</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>キャンセル</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
			<ul class="upfile_part">
            <li class="jpg"><p class="name">{%=file.name%}</p></li>
			</ul>
            {% if (file.error) { %}
                <div><span class="label label-danger">エラー</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>削除</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>キャンセル</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
{/literal}

<script src="{$fixed_base_url}assets/js/upload/tmpl.min.js"></script>
<script src="{$fixed_base_url}assets/js/upload/load-image.min.js"></script>
<script src="{$fixed_base_url}assets/js/upload/canvas-to-blob.min.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.iframe-transport.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-process.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-image.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-audio.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-video.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-validate.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-ui.js"></script>
<script src="{$fixed_base_url}assets/js/upload/jquery.fileupload-dropzone.js"></script>

<script type="text/javascript">
{literal}
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '{/literal}{$fixed_base_url}{literal}sample/upload_file',
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
			dropZone: $('#dropbox'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
    }

});
{/literal}
</script>
{/block}
