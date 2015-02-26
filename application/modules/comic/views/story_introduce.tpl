{* Extend our master template *} {extends file="master.tpl"} {* This
block is defined in the master.php template *} {block name=title}
{$title} {/block} {* This block is defined in the master.php template *}
{block name=body}
<div class="col-md-12 column">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<div class="item_truyen_box" style="text-align:center">
				<div class="img">
					<a itemprop="url" href="#"><img itemprop="image" src="{$story_detail->avatar}" class="img-rounded img-responsive"></a>
				</div>
				<div id="wrap-rating" class="rating" xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
					<h3 style="font-size: 12px; line-height: 18px;" property="v:itemreviewed">{$story_detail->title}</h3>
					<div id="truyen-rating" style="margin: 0px auto; height: 20px; width: 115px; overflow: hidden; z-index: 1; position: relative;" class="basic" data-average="0.2" data-id="1"><div class="jRatingColor" style="width: 4.6px;"></div><div class="jRatingAverage" style="width: 0px; top: -20px;"></div><div class="jStar" style="width: 115px; height: 20px; top: -40px; background: url(http://manga24h.com/public/images/stars.png) repeat-x;"></div></div>

					   				<span rel="v:rating">
						   				<p id="rating-point" typeof="v:Rating">
											<span property="v:average">0.2</span> /
											<span property="v:best">5</span>
										</p><p><span property="v:count">32951</span> lượt đánh giá</p>
	         							<p></p>

	         						</span>
					<p style="display: none; margin-top: 3px;" id="rate_message"></p>



				</div>

				<div class="btn-group-vertical">

					<a class="btn btn-info btn-sm follow-story" role="button">Theo Dõi Manga  Này  </a>


				</div>

			</div><!-- End item_truyen_box -->
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<h1 class="name" itemprop="name">{$story_detail->title}</h1>

			<div class="item_truyen_detail" style="background-color:#fafafa; border-radius:5px; margin-top:10px">


				<ul class="nav nav-tabs" id="truyen_detail">
					<li class="active"><a href="#truyen_thongtin" data-toggle="tab">Thông Tin</a></li>

					<li><a href="#truyen_tags" data-toggle="tab"> Tags</a></li>


				</ul>

				<div class="tab-content truyen_thongtin_tab" style="background:#fff">
					<div class="tab-pane fade in active" id="truyen_thongtin">
						<table class="table" id="truyen_detail_metadata">
							<tbody><tr>
								<td class="theloai" width="120" align="left">Thể Loại:</td>
								<td class="theloai">
									{$story_detail->type}
								</td>
							</tr>
							<tr>
								<td class="theloai" width="120" align="left">Tác giả :</td>
								<td class="theloai">{$story_detail->author}</td>
							</tr>
							<tr>
								<td class="tinhtrang" align="left">Tình Trạng:</td>
								<td class="tinhtrang"><span><a href="http://manga24h.com/status/truyen-dang-cap-nhat.html.html">{$story_detail->state}</a></span></td>
							</tr>
							<tr>
								<td class="tinhtrang" align="left">Nguồn</td>
								<td class="nguon"><span>{$story_detail->source}</span></td>
							</tr>
							<tr>
								<td class="view" align="left">Lượt Xem:</td>
								<td class="view"><span class="badge">{$story_detail->view}</span></td>
							</tr>
							<tr>
								<td class="view" align="left">Ngày Đăng:</td>
								<td class="view"><span class="badge">5 năm trước</span></td>
							</tr>

							</tbody></table>
					</div>



					<div class="tab-pane fade" id="truyen_tags">
						<a href="http://manga24h.com/tag/doc-truyen-tranh.html">doc truyen tranh</a> ,<a href="http://manga24h.com/tag/truyen-tranh-online.html">truyen tranh online</a> ,<a href="http://manga24h.com/tag/truyen-tieng-viet.html">truyen tieng viet</a> ,<a href="http://manga24h.com/tag/truyen-tranh.html">truyen tranh</a> ,<a href="http://manga24h.com/tag/truyen-tranh-hay.html">truyen tranh hay</a> ,<a href="http://manga24h.com/tag/truyen-tranh-vui.html">truyen tranh vui</a> ,<a href="http://manga24h.com/tag/truyen-tranh-18-.html">truyen tranh 18+</a> ,<a href="http://manga24h.com/tag/truyen-tranh-nhat-ban.html">truyen tranh nhat ban</a> ,<a href="http://manga24h.com/tag/truyen-tranh-han-quoc.html">truyen tranh han quoc</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online.html">doc truyen tranh online</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh.html">doc truyen tranh</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-tinh-cam.html">doc truyen tranh tinh cam</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-tieng-viet.html">doc truyen tranh online tieng viet</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-nhanh.html">doc truyen tranh online nhanh</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-hay.html">doc truyen tranh online hay</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-android.html">doc truyen tranh online android</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-iphone.html">doc truyen tranh online iphone</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-ipad.html">doc truyen tranh online ipad</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-16-.html">doc truyen tranh online 16+</a> ,<a href="http://manga24h.com/tag/doc-truyen-tranh-online-18-.html">doc truyen tranh online 18+</a> ,<a href="http://manga24h.com/tag/truyen-tranh-online-nhat-ban.html">truyen tranh online nhat ban</a> ,<a href="http://manga24h.com/tag/truyen-tranh-online-han-quoc.html">truyen tranh online han quoc</a>
					</div>


				</div> <!-- End tab-content -->

				<div class="noidung" itemprop="description">
					{$story_detail->intro}
				</div>


			</div><!-- End truyen detail-->
		</div>
	</div>
	<div class="well">
		<select class="form-control" onchange="location.href=this.value">
			<option disabled>Chon nhanh chap</option>
			{foreach $lst_chapter as $item}
				<option value="{$base_url}comic/{$item->id}_{$story_detail->id}/{url_friendly($story_detail->title)}_{url_friendly($item->name)}.html">{$item->name}</option>
			{/foreach}
		</select>
	</div>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Chapter</th>
				<th>Ngày Đăng </th>
				<th>Luợt Xem </th>
			</tr>
		</thead>
		<tbody>
			{foreach $lst_chapter as $item}
			<tr>
				<td>1</td>
				<td><a href="{$base_url}comic/{$item->id}_{$story_detail->id}/{url_friendly($story_detail->title)}_{url_friendly($item->name)}.html">{$item->name}
					</a>
				</td>
				<td>01/04/2012</td>
				<td>12456</td>
			</tr>
			{/foreach}
		</tbody>
	</table>

</div>
	<ul id="pagination-demo" class="pagination pagination-sm"></ul>
{/block}
{block name=javascript}
{literal}
	<script>
		$(document).ready(function(){

			$('#pagination-demo').twbsPagination({
				totalPages: {/literal}{$total_chapter}{literal},
				startPage: 1,
				visiblePages: 5,
				href: false,
				hrefVariable: '{{number}}',
				first: 'First',
				prev: 'Previous',
				next: 'Next',
				last: 'Last',
				loop: false,
				onPageClick: function (event, page) {
					$('#page-content').text('Page ' + page);
				},
				paginationClass: 'pagination',
				nextClass: 'next',
				prevClass: 'prev',
				lastClass: 'last',
				firstClass: 'first',
				pageClass: 'page',
				activeClass: 'active',
				disabledClass: 'disabled'
			});

//			rateDisable = false;
//			if($.cookie('ranime_68') == '1'){
//
//				rateDisable = true;
//			}
//
//			$(".basic").jRating({
//				bigStarsPath : 'http://manga24h.com/public/images/stars.png', // path of the icon stars.png
//				smallStarsPath : 'http://manga24h.com/public/images/small.png', // path of the icon small.png
//				type:"big",
//				length : 5,
//				decimalLength : 1,
//				isDisabled: rateDisable,
//				step: true,
//				nbRates : 3,
//				rateMax : 5,
//				showRateInfo:false,
//				phpPath : 'http://manga24h.com/index.php?module=ajax&act=ajax&rating=1&act=anime&id=68',
//
//				onSuccess : function(data){
//
//					if(data){
//						if(data.type == 'success'){
//							classMsgRating = 'rate_success';
//							$.cookie('ranime_68', '1', { expires: 30 });
//						}else if(data.type == 'error'){
//							classMsgRating = 'rate_error';
//						}
//
//						$('#rate_message').html('<p class="'+classMsgRating+'">'+data.message+'</p>');
//						$('#rate_message').slideDown('normal').delay(3000).slideUp('normal');
//					}
//
//				},
//
//
//			});
		})
	</script>
{/literal}
{/block}
