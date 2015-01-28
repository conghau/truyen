{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}



	{block name=body2}
<p>これはPCのページです。</p>

<h1>{'label_hello'|lang}</h1>

サンプル

<a href="./?lang=english">{'label_english'|lang}</a>
<a href="./?lang=japanese">{'label_japanese'|lang}</a>

<p>
デバイス種別：{if $is_mobile}モバイル{elseif $is_smartphone}スマホ{elseif $is_tablet}タブレット{else}PC{/if}<br>
機種情報：{$code|h}<br>
カテゴリ：{$category|h}<br>
</p>

{$user->id}:{$user->last_name} {$user->first_name}

<a href="{$ssl_base_url}login">ログイン</a>
<a href="{$ssl_base_url}logout">ログアウト</a>

<div id="inviewTest">Hello World</div>
<div id="message"></div>
</div>

{literal}
<script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/js/jquery.inview.js" type="text/javascript"></script>
<script type="text/javascript">
      $('#inviewTest').on('inview', function(event, isInView, visiblePartX, visiblePartY) {
            if (isInView) {
              console.log("a");
              //要素が見えたときに実行する処理
              $('#message').html("見えました");
            } else {
              //要素が見えなくなったときに実行する処理
              $('#message').html("見えなくなりました");
            }
    });
 </script>
{/literal}

{/block}
