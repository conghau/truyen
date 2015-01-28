{* Extend our master template *}
{extends file="master.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{block name=javascript}
<script type="text/javascript" src="{base_url()}assets/js/jquery.inview.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
	var checker = new Array();
    $('.entrybox').on('inview', function(event, isInView, visiblePartX, visiblePartY) {
           if (visiblePartY == 'bottom' || visiblePartY == 'both' ){
				if (checker[$(this).attr('post-id')]) {
					return;
				}
                  //要素のしたが見えるようになったときに実行する処理
				console.log("fire event" + $(this).attr('post-id'));
				checker[$(this).attr('post-id')] = true;
            }
    });
</script>
{/literal}
{/block}

{* This block is defined in the master.php template *}
{block name=body}




<!--1件-->
<div class="ctinner tround">
	<div class="entry_view entrybox" id="post-234" post-id="234">
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
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_edit.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_delete.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_copy.png"></a></li>
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
			</ul>
			<ul class="file_btn">
			<li><a href="#"><img src="{base_url()}assets/img/btn_file_dl.png" alt="一括ダウンロード"></a></li>
			<li><a href="#file_modal" class="file_modal"><img src="{base_url()}assets/img/btn_file_list.png" alt="ファイル一覧"></a></li>
			<li><a href="#TB_inline?inlineId=preview" class="thickbox"><img src="{base_url()}assets/img/btn_file_preview.png" alt="プレビュー"></a></li>
			<li class="nokori">ファイル削除まで残り<span>10日</span></li>
			</ul>
		</div><!--/file_wrap-->
		<div class="file_action clearfix">
			<a href="#">コメント(3)を表示する</a>
			<a href="#">コメントを送る</a>
		</div><!--/file_action-->
	</div><!--/entry_view-->
</div><!--/ctinner-->


<!--1件-->
<div class="ctinner tround">
	<div class="entry_view entrybox" id="post-5678" post-id="5678">
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
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_edit.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_delete.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_copy.png"></a></li>
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
			</ul>
			<ul class="file_btn">
			<li><a href="#"><img src="{base_url()}assets/img/btn_file_dl.png" alt="一括ダウンロード"></a></li>
			<li><a  href="#file_modal" class="file_modal"><img src="{base_url()}assets/img/btn_file_list.png" alt="ファイル一覧"></a></li>
			<li><a href="#TB_inline?inlineId=preview" class="thickbox"><img src="{base_url()}assets/img/btn_file_preview.png" alt="プレビュー"></a></li>
			<li class="nokori alert">ファイル削除まで残り<span>2時間</span></li>
			</ul>
		</div><!--/file_wrap-->
		<div class="file_action clearfix">
			<a href="#">コメント(3)を表示する</a>
			<a href="#">コメントを送る</a>
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
		<div class="clearfix">
		<div class="enter_head ">
			<p class="name">From : 山田太郎</p>
			<p class="date">6月20日 10:34</p>
		</div><!--/enter_head-->
					<p class="complete"><a href="#"><img src="{base_url()}assets/img/complete_s.png"></a></p>
		</div><!--/clearfix-->
		<div class="th_edit">
			<textarea name="">ご検討を有難うございました。○○○○先生のご見解は大変興味あります。後日で良いので、ぜひ詳しく教えて下さい。</textarea>
		</div>
		</div>
<!--/投稿済みコメントの編集【B-9】-->	
	
	</div><!--/entry_view-->
</div><!--/ctinner-->
<!--/1件-->

<!--1件-->
<div class="ctinner tround">
	<div class="entry_view entrybox" id="post-1234" post-id="1234">
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
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_edit.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_delete.png"></a></li>
			<li><a href="#"><img src="{base_url()}assets/img/btn_entry_copy.png"></a></li>
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
			</ul>
			<ul class="file_btn">
			<li><a href="#"><img src="{base_url()}assets/img/btn_file_dl.png" alt="一括ダウンロード"></a></li>
			<li><a href="#file_modal" class="file_modal"><img src="{base_url()}assets/img/btn_file_list.png" alt="ファイル一覧"></a></li>
			<li><a href="#TB_inline?inlineId=preview" class="thickbox"><img src="{base_url()}assets/img/btn_file_preview.png" alt="プレビュー"></a></li>
			<li class="nokori">ファイル削除まで残り<span>10日</span></li>
			</ul>
		</div><!--/file_wrap-->
		<div class="file_action clearfix">
			<a href="#">コメント(3)を表示する</a>
			<a href="#">コメントを送る</a>
		</div><!--/file_action-->
	</div><!--/entry_view-->
</div><!--/ctinner-->

{/block}
