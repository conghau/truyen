{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
<p>これはスマフォのページです。</p>


サンプル

<p>
デバイス種別：{if $is_mobile}モバイル{elseif $is_smartphone}スマホ{elseif $is_tablet}タブレット{else}PC{/if}<br>
機種情報：{$code|h}<br>
カテゴリ：{$category|h}<br>
</p>

{/block}
