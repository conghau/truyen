{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=javascript}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
$(function() {
    $('#redirect_top').click(function() {
        $(window.location).attr('href', '{/literal}{$base_url}{literal}');
    });
});
{/literal}
</script>
{/block}

{* This block is defined in the master.php template *}
{block name=body}
<h2><span>エラー</span></h2>
<div>
	{$message}
</div>
<p>{$date}</p>
<input class="one_button_gray" type="button" id="redirect_top" value="戻る">
{/block}