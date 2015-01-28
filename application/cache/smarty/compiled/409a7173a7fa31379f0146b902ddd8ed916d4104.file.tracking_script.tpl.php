<?php /* Smarty version Smarty-3.1.7, created on 2015-01-28 13:20:47
         compiled from "/var/www/html/truyen/application/modules/layout/views/tracking_script.tpl" */ ?>
<?php /*%%SmartyHeaderCode:206773349754c87fbf0c77e0-63777369%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '409a7173a7fa31379f0146b902ddd8ed916d4104' => 
    array (
      0 => '/var/www/html/truyen/application/modules/layout/views/tracking_script.tpl',
      1 => 1422416863,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '206773349754c87fbf0c77e0-63777369',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_54c87fbf0cec0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54c87fbf0cec0')) {function content_54c87fbf0cec0($_smarty_tpl) {?><!-- tracking script -->
<?php if ($_SERVER['SERVER_NAME']=='qlifebox.jp'){?>

<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-28283317-20', 'auto');
ga('require', 'displayfeatures');
ga('send', 'pageview');

</script>

<?php }?>
<?php }} ?>