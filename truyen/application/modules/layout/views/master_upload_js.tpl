<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/tmpl.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/load-image.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/canvas-to-blob.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-process.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-image.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-audio.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-video.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-validate.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-ui.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets/js/upload/jquery.fileupload-dropzone.js"></script>

{literal}
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td width="70%">
			<ul class="upfile_part">
            <li class="{%=file.ext_type%}">
            <p class="name dot">{%=file.name%}</p>
            <span class="size">{/literal}{'label_processing'|lang}{literal}</span>
            </li>
			</ul>
            <strong class="error text-danger"></strong>
        </td>
{/literal}{if !$device_info->is_smartphone()}{literal}
        <td width="20%">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td width="10%">

            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <span>{/literal}{'label_button_upload'|lang}{literal}</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <span>{/literal}{'label_button_cancel'|lang}{literal}</span>
                </button>
            {% } %}
{/literal}{else}{literal}
        <td width="30%">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% if (!i && !o.options.autoUpload) { %}
                <a href="#" class="start" disabled>
                    <span>{/literal}{'label_button_upload'|lang}{literal}</span>
                </a>
            {% } %}
            {% if (!i) { %}
                <a href="#" class="cancel">
                    <span>{/literal}{'label_button_cancel'|lang}{literal}</span>
                </a>
            {% } %}
{/literal}{/if}{literal}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td width="70%">
			<ul class="upfile_part">
            <li class="{%=file.ext_type%}">
            <p class="name dot">{%=file.name%}</p>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
			</li>
			</ul>
            {% if (file.error) { %}
                <div><span class="label label-danger">{/literal}{'label_error'|lang}{literal}</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td width="30%">
{/literal}{if !$device_info->is_smartphone()}{literal}
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <span>{/literal}{'label_button_delete'|lang}{literal}</span>
                </button>
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <span>{/literal}{'label_button_cancel'|lang}{literal}</span>
                </button>
            {% } %}
{/literal}{else}{literal}
            {% if (file.deleteUrl) { %}
                <a href="#" class="delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <span>{/literal}{'label_button_delete'|lang}{literal}</span>
                </a>
            {% } else { %}
				&nbsp;
                <a href="#" class="cancel">
                    <span>{/literal}{'label_button_cancel'|lang}{literal}</span>
                </a>
            {% } %}
{/literal}{/if}{literal}
        </td>
    </tr>
{% } %}
</script>
{/literal}
