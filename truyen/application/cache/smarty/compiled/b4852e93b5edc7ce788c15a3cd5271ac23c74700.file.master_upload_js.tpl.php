<?php /* Smarty version Smarty-3.1.7, created on 2015-01-28 13:20:47
         compiled from "application/modules/layout/views/master_upload_js.tpl" */ ?>
<?php /*%%SmartyHeaderCode:39721990554c87fbf03a381-67707780%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b4852e93b5edc7ce788c15a3cd5271ac23c74700' => 
    array (
      0 => 'application/modules/layout/views/master_upload_js.tpl',
      1 => 1422416863,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39721990554c87fbf03a381-67707780',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fixed_base_url' => 0,
    'device_info' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_54c87fbf0be85',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54c87fbf0be85')) {function content_54c87fbf0be85($_smarty_tpl) {?><script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/load-image.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/canvas-to-blob.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-process.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-image.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-audio.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-video.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-validate.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-ui.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['fixed_base_url']->value;?>
assets/js/upload/jquery.fileupload-dropzone.js"></script>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td width="70%">
			<ul class="upfile_part">
            <li class="{%=file.ext_type%}">
            <p class="name dot">{%=file.name%}</p>
            <span class="size"><?php echo lang('label_processing');?>
</span>
            </li>
			</ul>
            <strong class="error text-danger"></strong>
        </td>
<?php if (!$_smarty_tpl->tpl_vars['device_info']->value->is_smartphone()){?>
        <td width="20%">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td width="10%">

            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <span><?php echo lang('label_button_upload');?>
</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <span><?php echo lang('label_button_cancel');?>
</span>
                </button>
            {% } %}
<?php }else{ ?>
        <td width="30%">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% if (!i && !o.options.autoUpload) { %}
                <a href="#" class="start" disabled>
                    <span><?php echo lang('label_button_upload');?>
</span>
                </a>
            {% } %}
            {% if (!i) { %}
                <a href="#" class="cancel">
                    <span><?php echo lang('label_button_cancel');?>
</span>
                </a>
            {% } %}
<?php }?>
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
                <div><span class="label label-danger"><?php echo lang('label_error');?>
</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td width="30%">
<?php if (!$_smarty_tpl->tpl_vars['device_info']->value->is_smartphone()){?>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <span><?php echo lang('label_button_delete');?>
</span>
                </button>
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <span><?php echo lang('label_button_cancel');?>
</span>
                </button>
            {% } %}
<?php }else{ ?>
            {% if (file.deleteUrl) { %}
                <a href="#" class="delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <span><?php echo lang('label_button_delete');?>
</span>
                </a>
            {% } else { %}
				&nbsp;
                <a href="#" class="cancel">
                    <span><?php echo lang('label_button_cancel');?>
</span>
                </a>
            {% } %}
<?php }?>
        </td>
    </tr>
{% } %}
</script>

<?php }} ?>