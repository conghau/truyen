<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

2015-01-31 03:39:57	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6281896
ベンチマーク
Loading Time: Base Classes	0.1751
Controller Execution Time ( Story / Index )	0.2370
Total Execution Time	0.4172
Total queries time	0.0674

データベース:db_truyen_chu
クエリ:2
0.0415	"DESCRIBE `storys`"
0.0259	"SELECT * FROM (`storys`)"
--------------------------------------------------------
2015-01-31 03:41:39	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	8061472
ベンチマーク
Loading Time: Base Classes	0.1752
Controller Execution Time ( Story / Index )	0.2993
Total Execution Time	0.4815
Total queries time	0.0122

データベース:db_truyen_chu
クエリ:2
0.0081	"DESCRIBE `storys`"
0.0041	"SELECT * FROM (`storys`)"
--------------------------------------------------------
2015-01-31 03:43:43	PROFILE	::1	
URI 文字列	story/detail/79
クラス/メソッド	story/view_story
メモリ使用量	5617840
ベンチマーク
Loading Time: Base Classes	0.2666
Controller Execution Time ( Story / View Story )	1.7897
Total Execution Time	2.0655
Total queries time	1.2351

データベース:db_truyen_chu
クエリ:4
0.0112	"DESCRIBE `storys`"
0.0008	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '79'"
0.0081	"DESCRIBE `story_details`"
1.2150	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '79'"
--------------------------------------------------------
2015-01-31 03:44:10	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6281584
ベンチマーク
Loading Time: Base Classes	0.2622
Controller Execution Time ( Story / Index )	0.2682
Total Execution Time	0.5391
Total queries time	0.0514

データベース:db_truyen_chu
クエリ:2
0.0091	"DESCRIBE `storys`"
0.0423	"SELECT * FROM (`storys`)"
--------------------------------------------------------
2015-01-31 03:58:35	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6283424
ベンチマーク
Loading Time: Base Classes	0.4236
Controller Execution Time ( Story / Index )	0.2152
Total Execution Time	0.6508
Total queries time	0.0098

データベース:db_truyen_chu
クエリ:2
0.0067	"DESCRIBE `storys`"
0.0031	"SELECT * FROM (`storys`)"
--------------------------------------------------------
2015-01-31 03:58:52	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6286688
ベンチマーク
Loading Time: Base Classes	0.3594
Controller Execution Time ( Story / Index )	0.1790
Total Execution Time	0.5459
Total queries time	0.0107

データベース:db_truyen_chu
クエリ:3
0.0072	"DESCRIBE `storys`"
0.0031	"SELECT * FROM (`storys`)"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
--------------------------------------------------------
2015-01-31 03:59:43	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6286688
ベンチマーク
Loading Time: Base Classes	0.3599
Controller Execution Time ( Story / Index )	0.3151
Total Execution Time	0.6879
Total queries time	0.0173

データベース:db_truyen_chu
クエリ:3
0.0114	"DESCRIBE `storys`"
0.0051	"SELECT * FROM (`storys`)"
0.0008	"SELECT count(id) As totalRecords FROM (`storys`)"
--------------------------------------------------------
2015-01-31 04:09:52	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	6328944
ベンチマーク
Loading Time: Base Classes	0.4469
Controller Execution Time ( Story / Index )	0.2216
Total Execution Time	0.6766
Total queries time	0.0117

データベース:db_truyen_chu
クエリ:3
0.0076	"DESCRIBE `storys`"
0.0036	"SELECT * FROM (`storys`)"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
--------------------------------------------------------
2015-01-31 04:13:19	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3899648
ベンチマーク
Loading Time: Base Classes	0.2038
Controller Execution Time ( Story / Index )	0.1687
Total Execution Time	0.3794
Total queries time	0.008

データベース:db_truyen_chu
クエリ:3
0.0071	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:14:40	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	5680480
ベンチマーク
Loading Time: Base Classes	0.4821
Controller Execution Time ( Story / Index )	0.4541
Total Execution Time	0.9519
Total queries time	0.0139

データベース:db_truyen_chu
クエリ:3
0.0122	"DESCRIBE `storys`"
0.0009	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0008	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:14:51	PROFILE	::1	
URI 文字列	story/paginate/10
クラス/メソッド	story/index
メモリ使用量	3904288
ベンチマーク
Loading Time: Base Classes	0.3372
Controller Execution Time ( Story / Index )	0.1697
Total Execution Time	0.5143
Total queries time	0.0068

データベース:db_truyen_chu
クエリ:3
0.0060	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0004	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:15:00	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3904768
ベンチマーク
Loading Time: Base Classes	0.6161
Controller Execution Time ( Story / Index )	0.2849
Total Execution Time	0.9134
Total queries time	0.0121

データベース:db_truyen_chu
クエリ:3
0.0107	"DESCRIBE `storys`"
0.0007	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0007	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:16:05	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3900264
ベンチマーク
Loading Time: Base Classes	0.2656
Controller Execution Time ( Story / Index )	0.1799
Total Execution Time	0.4535
Total queries time	0.0075

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:16:20	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3904288
ベンチマーク
Loading Time: Base Classes	0.4043
Controller Execution Time ( Story / Index )	0.1776
Total Execution Time	0.5898
Total queries time	0.0076

データベース:db_truyen_chu
クエリ:3
0.0066	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:18:20	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3906312
ベンチマーク
Loading Time: Base Classes	0.3668
Controller Execution Time ( Story / Index )	0.3201
Total Execution Time	0.7004
Total queries time	0.0161

データベース:db_truyen_chu
クエリ:3
0.0146	"DESCRIBE `storys`"
0.0007	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0008	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 20"
--------------------------------------------------------
2015-01-31 04:35:43	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3906352
ベンチマーク
Loading Time: Base Classes	0.2807
Controller Execution Time ( Story / Index )	0.1911
Total Execution Time	0.4804
Total queries time	0.0085

データベース:db_truyen_chu
クエリ:3
0.0076	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 20"
--------------------------------------------------------
2015-01-31 04:37:06	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3906656
ベンチマーク
Loading Time: Base Classes	0.3100
Controller Execution Time ( Story / Index )	0.2613
Total Execution Time	0.5808
Total queries time	0.0067

データベース:db_truyen_chu
クエリ:3
0.0058	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 20"
--------------------------------------------------------
2015-01-31 04:37:22	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3906384
ベンチマーク
Loading Time: Base Classes	0.5486
Controller Execution Time ( Story / Index )	0.1892
Total Execution Time	0.7471
Total queries time	0.0069

データベース:db_truyen_chu
クエリ:3
0.0059	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 20"
--------------------------------------------------------
2015-01-31 04:37:32	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/10
クラス/メソッド	story/index
メモリ使用量	3904848
ベンチマーク
Loading Time: Base Classes	0.5886
Controller Execution Time ( Story / Index )	0.2160
Total Execution Time	0.8253
Total queries time	0.0075

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:45:28	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3905360
ベンチマーク
Loading Time: Base Classes	0.2972
Controller Execution Time ( Story / Index )	0.1864
Total Execution Time	0.4918
Total queries time	0.0091

データベース:db_truyen_chu
クエリ:3
0.0080	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0006	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:47:01	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3905584
ベンチマーク
Loading Time: Base Classes	0.4057
Controller Execution Time ( Story / Index )	0.2256
Total Execution Time	0.6409
Total queries time	0.0083

データベース:db_truyen_chu
クエリ:3
0.0072	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0006	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:47:18	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/story/paginate/story/paginate/10
クラス/メソッド	story/index
メモリ使用量	3905776
ベンチマーク
Loading Time: Base Classes	0.3892
Controller Execution Time ( Story / Index )	0.1886
Total Execution Time	0.5865
Total queries time	0.0069

データベース:db_truyen_chu
クエリ:3
0.0061	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0004	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:47:27	PROFILE	::1	
URI 文字列	story/paginate/story/paginate/story/paginate/story/paginate/story/paginate/170
クラス/メソッド	story/index
メモリ使用量	3906224
ベンチマーク
Loading Time: Base Classes	0.3874
Controller Execution Time ( Story / Index )	0.1898
Total Execution Time	0.5856
Total queries time	0.008

データベース:db_truyen_chu
クエリ:3
0.0070	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:48:08	PROFILE	::1	
URI 文字列	story/paginate
クラス/メソッド	story/index
メモリ使用量	3903952
ベンチマーク
Loading Time: Base Classes	0.3853
Controller Execution Time ( Story / Index )	0.1895
Total Execution Time	0.5830
Total queries time	0.0081

データベース:db_truyen_chu
クエリ:3
0.0071	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:50:46	PROFILE	::1	
URI 文字列	story/paginate
クラス/メソッド	story/index
メモリ使用量	3904696
ベンチマーク
Loading Time: Base Classes	0.4394
Controller Execution Time ( Story / Index )	0.2043
Total Execution Time	0.6543
Total queries time	0.0074

データベース:db_truyen_chu
クエリ:3
0.0064	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:50:52	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	3904208
ベンチマーク
Loading Time: Base Classes	0.2289
Controller Execution Time ( Story / Index )	0.2276
Total Execution Time	0.4646
Total queries time	0.041

データベース:db_truyen_chu
クエリ:3
0.0398	"DESCRIBE `storys`"
0.0006	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0006	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:50:52	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	3904208
ベンチマーク
Loading Time: Base Classes	0.2381
Controller Execution Time ( Story / Index )	0.1908
Total Execution Time	0.4373
Total queries time	0.0042

データベース:db_truyen_chu
クエリ:3
0.0034	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0004	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:51:10	PROFILE	::1	
URI 文字列	story/paginate/10
クラス/メソッド	story/index
メモリ使用量	3906032
ベンチマーク
Loading Time: Base Classes	0.5234
Controller Execution Time ( Story / Index )	0.2056
Total Execution Time	0.7398
Total queries time	0.0069

データベース:db_truyen_chu
クエリ:3
0.0060	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 10"
--------------------------------------------------------
2015-01-31 04:51:17	PROFILE	::1	
URI 文字列	story/paginate/20
クラス/メソッド	story/index
メモリ使用量	3906560
ベンチマーク
Loading Time: Base Classes	0.4500
Controller Execution Time ( Story / Index )	0.1982
Total Execution Time	0.6567
Total queries time	0.0075

データベース:db_truyen_chu
クエリ:3
0.0066	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 20"
--------------------------------------------------------
2015-01-31 04:51:25	PROFILE	::1	
URI 文字列	story/paginate/170
クラス/メソッド	story/index
メモリ使用量	3908904
ベンチマーク
Loading Time: Base Classes	0.7017
Controller Execution Time ( Story / Index )	0.3094
Total Execution Time	1.0279
Total queries time	0.009

データベース:db_truyen_chu
クエリ:3
0.0075	"DESCRIBE `storys`"
0.0006	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0009	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 170"
--------------------------------------------------------
2015-01-31 04:51:41	PROFILE	::1	
URI 文字列	story/detail/182
クラス/メソッド	story/view_story
メモリ使用量	3871832
ベンチマーク
Loading Time: Base Classes	0.4339
Controller Execution Time ( Story / View Story )	0.5893
Total Execution Time	1.0336
Total queries time	0.3005

データベース:db_truyen_chu
クエリ:4
0.0064	"DESCRIBE `storys`"
0.0004	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '182'"
0.0060	"DESCRIBE `story_details`"
0.2877	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '182'"
--------------------------------------------------------
2015-01-31 04:51:45	PROFILE	::1	
URI 文字列	story/paginate/170
クラス/メソッド	story/index
メモリ使用量	3908904
ベンチマーク
Loading Time: Base Classes	0.4750
Controller Execution Time ( Story / Index )	0.5118
Total Execution Time	0.9957
Total queries time	0.2274

データベース:db_truyen_chu
クエリ:3
0.0072	"DESCRIBE `storys`"
0.1013	"SELECT count(id) As totalRecords FROM (`storys`)"
0.1189	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 170"
--------------------------------------------------------
2015-01-31 04:51:51	PROFILE	::1	
URI 文字列	story/paginate/130
クラス/メソッド	story/index
メモリ使用量	3914032
ベンチマーク
Loading Time: Base Classes	0.3908
Controller Execution Time ( Story / Index )	0.2074
Total Execution Time	0.6072
Total queries time	0.008

データベース:db_truyen_chu
クエリ:3
0.0064	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0011	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 130"
--------------------------------------------------------
2015-01-31 04:51:56	PROFILE	::1	
URI 文字列	story/paginate
クラス/メソッド	story/index
メモリ使用量	3904744
ベンチマーク
Loading Time: Base Classes	0.4285
Controller Execution Time ( Story / Index )	0.2466
Total Execution Time	0.6858
Total queries time	0.0082

データベース:db_truyen_chu
クエリ:3
0.0071	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0006	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 04:52:28	PROFILE	::1	
URI 文字列	story/paginate/40
クラス/メソッド	story/index
メモリ使用量	3902576
ベンチマーク
Loading Time: Base Classes	0.4589
Controller Execution Time ( Story / Index )	0.3527
Total Execution Time	0.8270
Total queries time	0.0117

データベース:db_truyen_chu
クエリ:3
0.0101	"DESCRIBE `storys`"
0.0008	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0008	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 40"
--------------------------------------------------------
2015-01-31 04:57:11	PROFILE	::1	
URI 文字列	story/paginate/60
クラス/メソッド	story/index
メモリ使用量	5681808
ベンチマーク
Loading Time: Base Classes	0.5483
Controller Execution Time ( Story / Index )	0.3146
Total Execution Time	0.8736
Total queries time	0.0086

データベース:db_truyen_chu
クエリ:3
0.0073	"DESCRIBE `storys`"
0.0006	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0007	"SELECT * FROM (`storys`) LIMIT 10 OFFSET 60"
--------------------------------------------------------
2015-01-31 05:00:39	PROFILE	::1	
URI 文字列	story/detail/71
クラス/メソッド	story/view_story
メモリ使用量	4060296
ベンチマーク
Loading Time: Base Classes	0.5346
Controller Execution Time ( Story / View Story )	0.9497
Total Execution Time	1.5010
Total queries time	0.5354

データベース:db_truyen_chu
クエリ:4
0.0101	"DESCRIBE `storys`"
0.0007	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '71'"
0.0081	"DESCRIBE `story_details`"
0.5165	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '71'"
--------------------------------------------------------
2015-01-31 05:00:46	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	3875824
ベンチマーク
Loading Time: Base Classes	0.3890
Controller Execution Time ( Story / View Chapter )	0.2719
Total Execution Time	0.6700
Total queries time	0.0466

データベース:db_truyen_chu
クエリ:2
0.0076	"DESCRIBE `story_details`"
0.0390	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:12:31	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	3875848
ベンチマーク
Loading Time: Base Classes	0.4223
Controller Execution Time ( Story / View Chapter )	0.1918
Total Execution Time	0.6233
Total queries time	0.007

データベース:db_truyen_chu
クエリ:2
0.0063	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:15:00	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5613832
ベンチマーク
Loading Time: Base Classes	0.4305
Controller Execution Time ( Story / View Chapter )	0.2585
Total Execution Time	0.7003
Total queries time	0.0076

データベース:db_truyen_chu
クエリ:2
0.0069	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:16:18	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5614624
ベンチマーク
Loading Time: Base Classes	0.3837
Controller Execution Time ( Story / View Chapter )	0.2465
Total Execution Time	0.6392
Total queries time	0.0069

データベース:db_truyen_chu
クエリ:2
0.0062	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:16:38	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5613960
ベンチマーク
Loading Time: Base Classes	0.4461
Controller Execution Time ( Story / View Chapter )	0.2452
Total Execution Time	0.7005
Total queries time	0.0068

データベース:db_truyen_chu
クエリ:2
0.0061	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:20:21	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618808
ベンチマーク
Loading Time: Base Classes	0.4045
Controller Execution Time ( Story / View Chapter )	0.2670
Total Execution Time	0.6842
Total queries time	0.0074

データベース:db_truyen_chu
クエリ:2
0.0067	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:22:54	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618840
ベンチマーク
Loading Time: Base Classes	0.2648
Controller Execution Time ( Story / View Chapter )	0.2804
Total Execution Time	0.5551
Total queries time	0.0068

データベース:db_truyen_chu
クエリ:2
0.0061	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:24:34	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	3878968
ベンチマーク
Loading Time: Base Classes	0.3936
Controller Execution Time ( Story / View Chapter )	0.2017
Total Execution Time	0.6051
Total queries time	0.0075

データベース:db_truyen_chu
クエリ:2
0.0067	"DESCRIBE `story_details`"
0.0008	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:25:26	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618160
ベンチマーク
Loading Time: Base Classes	0.4568
Controller Execution Time ( Story / View Chapter )	0.2568
Total Execution Time	0.7234
Total queries time	0.0082

データベース:db_truyen_chu
クエリ:2
0.0075	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:25:35	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618152
ベンチマーク
Loading Time: Base Classes	0.3638
Controller Execution Time ( Story / View Chapter )	0.2429
Total Execution Time	0.6163
Total queries time	0.007

データベース:db_truyen_chu
クエリ:2
0.0063	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:26:45	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5617824
ベンチマーク
Loading Time: Base Classes	0.2921
Controller Execution Time ( Story / View Chapter )	0.2919
Total Execution Time	0.5951
Total queries time	0.0075

データベース:db_truyen_chu
クエリ:2
0.0068	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:28:10	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5617224
ベンチマーク
Loading Time: Base Classes	0.3621
Controller Execution Time ( Story / View Chapter )	0.2717
Total Execution Time	0.6445
Total queries time	0.0071

データベース:db_truyen_chu
クエリ:2
0.0064	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:30:36	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5616072
ベンチマーク
Loading Time: Base Classes	0.3686
Controller Execution Time ( Story / View Chapter )	0.2887
Total Execution Time	0.6681
Total queries time	0.0074

データベース:db_truyen_chu
クエリ:2
0.0067	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:31:25	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618712
ベンチマーク
Loading Time: Base Classes	0.4175
Controller Execution Time ( Story / View Chapter )	0.2599
Total Execution Time	0.6873
Total queries time	0.007

データベース:db_truyen_chu
クエリ:2
0.0063	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:31:49	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618768
ベンチマーク
Loading Time: Base Classes	0.4724
Controller Execution Time ( Story / View Chapter )	0.2973
Total Execution Time	0.7887
Total queries time	0.0072

データベース:db_truyen_chu
クエリ:2
0.0065	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:32:06	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618744
ベンチマーク
Loading Time: Base Classes	0.4997
Controller Execution Time ( Story / View Chapter )	0.2609
Total Execution Time	0.7706
Total queries time	0.007

データベース:db_truyen_chu
クエリ:2
0.0062	"DESCRIBE `story_details`"
0.0008	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:32:19	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618680
ベンチマーク
Loading Time: Base Classes	0.5048
Controller Execution Time ( Story / View Chapter )	0.2677
Total Execution Time	0.7827
Total queries time	0.0072

データベース:db_truyen_chu
クエリ:2
0.0065	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:32:37	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5618680
ベンチマーク
Loading Time: Base Classes	0.5448
Controller Execution Time ( Story / View Chapter )	0.3081
Total Execution Time	0.8650
Total queries time	0.008

データベース:db_truyen_chu
クエリ:2
0.0072	"DESCRIBE `story_details`"
0.0008	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:35:38	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5616520
ベンチマーク
Loading Time: Base Classes	0.5593
Controller Execution Time ( Story / View Chapter )	0.3291
Total Execution Time	0.8986
Total queries time	0.0145

データベース:db_truyen_chu
クエリ:2
0.0135	"DESCRIBE `story_details`"
0.0010	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:36:10	PROFILE	::1	
URI 文字列	story/ac-thu-tieu-tu/chapter/1-957.html
クラス/メソッド	story/view_chapter
メモリ使用量	5613552
ベンチマーク
Loading Time: Base Classes	0.8068
Controller Execution Time ( Story / View Chapter )	0.3089
Total Execution Time	1.1307
Total queries time	0.0078

データベース:db_truyen_chu
クエリ:2
0.0070	"DESCRIBE `story_details`"
0.0008	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '957'"
--------------------------------------------------------
2015-01-31 05:36:22	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	5678376
ベンチマーク
Loading Time: Base Classes	0.5499
Controller Execution Time ( Story / Index )	0.3715
Total Execution Time	0.9369
Total queries time	0.0853

データベース:db_truyen_chu
クエリ:3
0.0060	"DESCRIBE `storys`"
0.0788	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 05:36:43	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	5763904
ベンチマーク
Loading Time: Base Classes	0.3763
Controller Execution Time ( Story / View Story )	0.6052
Total Execution Time	0.9928
Total queries time	0.2658

データベース:db_truyen_chu
クエリ:4
0.0060	"DESCRIBE `storys`"
0.0004	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0046	"DESCRIBE `story_details`"
0.2548	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 05:36:43	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	3981824
ベンチマーク
Loading Time: Base Classes	0.4015
Controller Execution Time ( Story / View Story )	0.8586
Total Execution Time	1.2714
Total queries time	0.019

データベース:db_truyen_chu
クエリ:4
0.0117	"DESCRIBE `storys`"
0.0005	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0048	"DESCRIBE `story_details`"
0.0020	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 05:36:53	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3851512
ベンチマーク
Loading Time: Base Classes	0.4571
Controller Execution Time ( Story / View Chapter )	0.3482
Total Execution Time	0.8184
Total queries time	0.0965

データベース:db_truyen_chu
クエリ:2
0.0098	"DESCRIBE `story_details`"
0.0867	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
--------------------------------------------------------
2015-01-31 09:40:59	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3850872
ベンチマーク
Loading Time: Base Classes	0.9826
Controller Execution Time ( Story / View Chapter )	0.4868
Total Execution Time	1.4880
Total queries time	0.009

データベース:db_truyen_chu
クエリ:2
0.0084	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
--------------------------------------------------------
2015-01-31 09:48:54	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	5681688
ベンチマーク
Loading Time: Base Classes	0.3043
Controller Execution Time ( Story / Index )	0.5408
Total Execution Time	0.8570
Total queries time	0.0089

データベース:db_truyen_chu
クエリ:3
0.0079	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 09:48:54	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	3902640
ベンチマーク
Loading Time: Base Classes	0.3139
Controller Execution Time ( Story / Index )	0.2387
Total Execution Time	0.5640
Total queries time	0.0044

データベース:db_truyen_chu
クエリ:3
0.0035	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 09:49:03	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	5763624
ベンチマーク
Loading Time: Base Classes	0.5545
Controller Execution Time ( Story / View Story )	0.8846
Total Execution Time	1.4570
Total queries time	0.4107

データベース:db_truyen_chu
クエリ:4
0.0093	"DESCRIBE `storys`"
0.0006	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0073	"DESCRIBE `story_details`"
0.3935	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 09:59:05	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3899128
ベンチマーク
Loading Time: Base Classes	0.8614
Controller Execution Time ( Story / Index )	1.0118
Total Execution Time	1.9144
Total queries time	0.0447

データベース:db_truyen_chu
クエリ:3
0.0141	"DESCRIBE `storys`"
0.0299	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0007	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 10:00:50	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3899104
ベンチマーク
Loading Time: Base Classes	0.4559
Controller Execution Time ( Story / Index )	0.2845
Total Execution Time	0.7546
Total queries time	0.0087

データベース:db_truyen_chu
クエリ:3
0.0079	"DESCRIBE `storys`"
0.0004	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0004	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 10:07:25	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	5680176
ベンチマーク
Loading Time: Base Classes	0.3881
Controller Execution Time ( Story / Index )	0.3580
Total Execution Time	0.7575
Total queries time	0.008

データベース:db_truyen_chu
クエリ:3
0.0070	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 10:07:42	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	5680200
ベンチマーク
Loading Time: Base Classes	0.3549
Controller Execution Time ( Story / Index )	0.3509
Total Execution Time	0.7185
Total queries time	0.0068

データベース:db_truyen_chu
クエリ:3
0.0058	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 10:08:10	PROFILE	::1	
URI 文字列	story/detail/1_bong-hong-cho-tinh-dau.html
クラス/メソッド	story/view_story
メモリ使用量	3981952
ベンチマーク
Loading Time: Base Classes	0.3554
Controller Execution Time ( Story / View Story )	0.5594
Total Execution Time	0.9287
Total queries time	0.2775

データベース:db_truyen_chu
クエリ:4
0.0073	"DESCRIBE `storys`"
0.0004	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0082	"DESCRIBE `story_details`"
0.2616	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 10:08:17	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3899912
ベンチマーク
Loading Time: Base Classes	0.3625
Controller Execution Time ( Story / Index )	0.2893
Total Execution Time	0.6641
Total queries time	0.008

データベース:db_truyen_chu
クエリ:3
0.0071	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0004	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 13:09:47	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	3898712
ベンチマーク
Loading Time: Base Classes	0.4465
Controller Execution Time ( Story / Index )	0.3399
Total Execution Time	0.7978
Total queries time	0.0951

データベース:db_truyen_chu
クエリ:3
0.0850	"DESCRIBE `storys`"
0.0096	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 13:12:33	PROFILE	::1	
URI 文字列	URI データはありません
クラス/メソッド	story/index
メモリ使用量	5680016
ベンチマーク
Loading Time: Base Classes	0.3863
Controller Execution Time ( Story / Index )	0.3404
Total Execution Time	0.7385
Total queries time	0.0389

データベース:db_truyen_chu
クエリ:3
0.0379	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 13:13:34	PROFILE	::1	
URI 文字列	story/detail/1_bong-hong-cho-tinh-dau.html
クラス/メソッド	story/view_story
メモリ使用量	5764232
ベンチマーク
Loading Time: Base Classes	0.4218
Controller Execution Time ( Story / View Story )	1.8389
Total Execution Time	2.2722
Total queries time	1.3937

データベース:db_truyen_chu
クエリ:4
0.0064	"DESCRIBE `storys`"
0.0005	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0694	"DESCRIBE `story_details`"
1.3174	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 16:49:33	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5588968
ベンチマーク
Loading Time: Base Classes	0.4251
Controller Execution Time ( Story / View Chapter )	0.3134
Total Execution Time	0.7506
Total queries time	0.0271

データベース:db_truyen_chu
クエリ:2
0.0078	"DESCRIBE `story_details`"
0.0193	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
--------------------------------------------------------
2015-01-31 17:09:52	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5590112
ベンチマーク
Loading Time: Base Classes	0.4761
Controller Execution Time ( Story / View Chapter )	0.2976
Total Execution Time	0.7857
Total queries time	0.0068

データベース:db_truyen_chu
クエリ:2
0.0062	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
--------------------------------------------------------
2015-01-31 17:11:51	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3922904
ベンチマーク
Loading Time: Base Classes	0.4207
Controller Execution Time ( Story / View Chapter )	1.7353
Total Execution Time	2.1693
Total queries time	1.4147

データベース:db_truyen_chu
クエリ:3
0.0063	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
1.4077	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:12:46	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5699928
ベンチマーク
Loading Time: Base Classes	0.4460
Controller Execution Time ( Story / View Chapter )	0.6228
Total Execution Time	1.0809
Total queries time	0.2293

データベース:db_truyen_chu
クエリ:3
0.0062	"DESCRIBE `story_details`"
0.0400	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.1831	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:12:58	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700200
ベンチマーク
Loading Time: Base Classes	0.5300
Controller Execution Time ( Story / View Chapter )	1.1565
Total Execution Time	1.6996
Total queries time	0.6477

データベース:db_truyen_chu
クエリ:3
0.0068	"DESCRIBE `story_details`"
0.0007	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.6402	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:13:38	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3952536
ベンチマーク
Loading Time: Base Classes	0.4282
Controller Execution Time ( Story / View Chapter )	0.4701
Total Execution Time	0.9107
Total queries time	0.2173

データベース:db_truyen_chu
クエリ:3
0.0068	"DESCRIBE `story_details`"
0.0058	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.2047	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:13:50	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3952536
ベンチマーク
Loading Time: Base Classes	0.4460
Controller Execution Time ( Story / View Chapter )	0.6630
Total Execution Time	1.1220
Total queries time	0.3968

データベース:db_truyen_chu
クエリ:3
0.0075	"DESCRIBE `story_details`"
0.0005	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.3888	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:15:01	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700136
ベンチマーク
Loading Time: Base Classes	0.4135
Controller Execution Time ( Story / View Chapter )	0.7999
Total Execution Time	1.2270
Total queries time	0.4055

データベース:db_truyen_chu
クエリ:3
0.0059	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.3990	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:15:17	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	3904064
ベンチマーク
Loading Time: Base Classes	0.3758
Controller Execution Time ( Story / Index )	0.2900
Total Execution Time	0.6808
Total queries time	0.05

データベース:db_truyen_chu
クエリ:3
0.0103	"DESCRIBE `storys`"
0.0391	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0006	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 17:15:17	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	3904096
ベンチマーク
Loading Time: Base Classes	0.3441
Controller Execution Time ( Story / Index )	0.3194
Total Execution Time	0.6758
Total queries time	0.0044

データベース:db_truyen_chu
クエリ:3
0.0034	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 17:15:33	PROFILE	::1	
URI 文字列	story
クラス/メソッド	story/index
メモリ使用量	5684128
ベンチマーク
Loading Time: Base Classes	0.4265
Controller Execution Time ( Story / Index )	0.3511
Total Execution Time	0.7903
Total queries time	0.0074

データベース:db_truyen_chu
クエリ:3
0.0064	"DESCRIBE `storys`"
0.0005	"SELECT count(id) As totalRecords FROM (`storys`)"
0.0005	"SELECT * FROM (`storys`) LIMIT 10"
--------------------------------------------------------
2015-01-31 17:15:45	PROFILE	::1	
URI 文字列	story/detail/1_bong-hong-cho-tinh-dau.html
クラス/メソッド	story/view_story
メモリ使用量	5764664
ベンチマーク
Loading Time: Base Classes	0.4972
Controller Execution Time ( Story / View Story )	0.9200
Total Execution Time	1.4311
Total queries time	0.45

データベース:db_truyen_chu
クエリ:4
0.0192	"DESCRIBE `storys`"
0.0004	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0049	"DESCRIBE `story_details`"
0.4255	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:15:51	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5699736
ベンチマーク
Loading Time: Base Classes	0.4716
Controller Execution Time ( Story / View Chapter )	0.7128
Total Execution Time	1.1968
Total queries time	0.2111

データベース:db_truyen_chu
クエリ:3
0.0060	"DESCRIBE `story_details`"
0.0328	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.1723	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:16:17	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3926792
ベンチマーク
Loading Time: Base Classes	0.4505
Controller Execution Time ( Story / View Chapter )	0.6196
Total Execution Time	1.0837
Total queries time	0.3375

データベース:db_truyen_chu
クエリ:4
0.0060	"DESCRIBE `story_details`"
0.0005	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.0003	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.3307	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:16:52	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5661144
ベンチマーク
Loading Time: Base Classes	0.4255
Controller Execution Time ( Story / View Chapter )	0.5364
Total Execution Time	0.9746
Total queries time	0.2127

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `story_details`"
0.0372	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.1690	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:17:35	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5701144
ベンチマーク
Loading Time: Base Classes	0.5124
Controller Execution Time ( Story / View Chapter )	0.8048
Total Execution Time	1.3317
Total queries time	0.3696

データベース:db_truyen_chu
クエリ:3
0.0062	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.3628	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:17:49	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700088
ベンチマーク
Loading Time: Base Classes	0.4421
Controller Execution Time ( Story / View Chapter )	0.8214
Total Execution Time	1.2767
Total queries time	0.3709

データベース:db_truyen_chu
クエリ:3
0.0058	"DESCRIBE `story_details`"
0.0063	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.3588	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:18:22	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	3946888
ベンチマーク
Loading Time: Base Classes	0.5398
Controller Execution Time ( Story / View Chapter )	0.8258
Total Execution Time	1.3789
Total queries time	0.4808

データベース:db_truyen_chu
クエリ:3
0.0062	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.4740	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:18:49	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700088
ベンチマーク
Loading Time: Base Classes	0.4690
Controller Execution Time ( Story / View Chapter )	0.5362
Total Execution Time	1.0179
Total queries time	0.2084

データベース:db_truyen_chu
クエリ:3
0.0063	"DESCRIBE `story_details`"
0.0330	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.1691	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:19:38	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700232
ベンチマーク
Loading Time: Base Classes	0.4381
Controller Execution Time ( Story / View Chapter )	0.8818
Total Execution Time	1.3336
Total queries time	0.4858

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.4787	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:19:48	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5700120
ベンチマーク
Loading Time: Base Classes	0.4752
Controller Execution Time ( Story / View Chapter )	0.6394
Total Execution Time	1.1276
Total queries time	0.2309

データベース:db_truyen_chu
クエリ:3
0.0357	"DESCRIBE `story_details`"
0.0227	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.1725	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:20:09	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	6437776
ベンチマーク
Loading Time: Base Classes	0.4152
Controller Execution Time ( Story / View Chapter )	1.1087
Total Execution Time	1.5378
Total queries time	0.656

データベース:db_truyen_chu
クエリ:3
0.0071	"DESCRIBE `story_details`"
0.0008	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.6481	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:20:58	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5189464
ベンチマーク
Loading Time: Base Classes	0.4565
Controller Execution Time ( Story / View Chapter )	7.9841
Total Execution Time	8.4536
Total queries time	6.819

データベース:db_truyen_chu
クエリ:4
0.0062	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
5.5483	"SELECT * FROM (`story_details`)"
1.2639	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:22:22	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/1-1.html
クラス/メソッド	story/view_chapter
メモリ使用量	5756728
ベンチマーク
Loading Time: Base Classes	0.3956
Controller Execution Time ( Story / View Chapter )	0.9000
Total Execution Time	1.3095
Total queries time	0.4591

データベース:db_truyen_chu
クエリ:3
0.0063	"DESCRIBE `story_details`"
0.0396	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '1'"
0.4132	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:27:01	PROFILE	::1	
URI 文字列	story/detail/1_bong-hong-cho-tinh-dau.html
クラス/メソッド	story/view_story
メモリ使用量	5764752
ベンチマーク
Loading Time: Base Classes	0.5017
Controller Execution Time ( Story / View Story )	0.6603
Total Execution Time	1.1792
Total queries time	0.2755

データベース:db_truyen_chu
クエリ:4
0.0065	"DESCRIBE `storys`"
0.0425	"SELECT `storys`.`title` FROM (`storys`) WHERE `storys`.`id` =  '1'"
0.0066	"DESCRIBE `story_details`"
0.2199	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:27:06	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3931096
ベンチマーク
Loading Time: Base Classes	0.4860
Controller Execution Time ( Story / View Chapter )	0.6514
Total Execution Time	1.1512
Total queries time	0.3482

データベース:db_truyen_chu
クエリ:3
0.0059	"DESCRIBE `story_details`"
0.0279	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3144	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:27:30	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3909064
ベンチマーク
Loading Time: Base Classes	0.7087
Controller Execution Time ( Story / View Chapter )	0.6825
Total Execution Time	1.4049
Total queries time	0.3347

データベース:db_truyen_chu
クエリ:3
0.0066	"DESCRIBE `story_details`"
0.0106	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3175	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:27:44	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5682976
ベンチマーク
Loading Time: Base Classes	0.5352
Controller Execution Time ( Story / View Chapter )	0.7635
Total Execution Time	1.3127
Total queries time	0.3239

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `story_details`"
0.0091	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3083	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:32:15	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5555504
ベンチマーク
Loading Time: Base Classes	0.4885
Controller Execution Time ( Story / View Chapter )	0.3508
Total Execution Time	0.8540
Total queries time	0.0274

データベース:db_truyen_chu
クエリ:2
0.0064	"DESCRIBE `story_details`"
0.0210	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
--------------------------------------------------------
2015-01-31 17:33:11	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5682952
ベンチマーク
Loading Time: Base Classes	0.5355
Controller Execution Time ( Story / View Chapter )	0.6696
Total Execution Time	1.2190
Total queries time	0.2207

データベース:db_truyen_chu
クエリ:3
0.0060	"DESCRIBE `story_details`"
0.0005	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.2142	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:33:33	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5682976
ベンチマーク
Loading Time: Base Classes	0.5697
Controller Execution Time ( Story / View Chapter )	0.8416
Total Execution Time	1.4251
Total queries time	0.3723

データベース:db_truyen_chu
クエリ:3
0.0066	"DESCRIBE `story_details`"
0.0151	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3506	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:33:54	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5643992
ベンチマーク
Loading Time: Base Classes	0.4557
Controller Execution Time ( Story / View Chapter )	0.7039
Total Execution Time	1.1749
Total queries time	0.2198

データベース:db_truyen_chu
クエリ:3
0.0064	"DESCRIBE `story_details`"
0.0292	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.1842	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:35:43	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5683016
ベンチマーク
Loading Time: Base Classes	0.4889
Controller Execution Time ( Story / View Chapter )	0.8204
Total Execution Time	1.3240
Total queries time	0.3206

データベース:db_truyen_chu
クエリ:3
0.0075	"DESCRIBE `story_details`"
0.0108	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3023	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:35:51	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5643864
ベンチマーク
Loading Time: Base Classes	0.6467
Controller Execution Time ( Story / View Chapter )	0.7211
Total Execution Time	1.3825
Total queries time	0.2444

データベース:db_truyen_chu
クエリ:3
0.0068	"DESCRIBE `story_details`"
0.0383	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.1993	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:36:34	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3905680
ベンチマーク
Loading Time: Base Classes	0.6905
Controller Execution Time ( Story / View Chapter )	0.7291
Total Execution Time	1.4376
Total queries time	0.3443

データベース:db_truyen_chu
クエリ:3
0.0065	"DESCRIBE `story_details`"
0.0010	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3368	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:41:37	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5634496
ベンチマーク
Loading Time: Base Classes	0.4227
Controller Execution Time ( Story / View Chapter )	0.6965
Total Execution Time	1.1365
Total queries time	0.27

データベース:db_truyen_chu
クエリ:3
0.0079	"DESCRIBE `story_details`"
0.0318	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.2303	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:43:05	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3897008
ベンチマーク
Loading Time: Base Classes	0.4807
Controller Execution Time ( Story / View Chapter )	0.6395
Total Execution Time	1.1368
Total queries time	0.3369

データベース:db_truyen_chu
クエリ:3
0.0084	"DESCRIBE `story_details`"
0.0213	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3072	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:44:19	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3811272
ベンチマーク
Loading Time: Base Classes	0.5353
Controller Execution Time ( Story / View Chapter )	0.3184
Total Execution Time	0.8681
Total queries time	0.0308

データベース:db_truyen_chu
クエリ:2
0.0064	"DESCRIBE `story_details`"
0.0244	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
--------------------------------------------------------
2015-01-31 17:45:25	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3898808
ベンチマーク
Loading Time: Base Classes	0.4946
Controller Execution Time ( Story / View Chapter )	0.5109
Total Execution Time	1.0206
Total queries time	0.2209

データベース:db_truyen_chu
クエリ:3
0.0061	"DESCRIBE `story_details`"
0.0006	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.2142	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:46:55	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3898808
ベンチマーク
Loading Time: Base Classes	0.7800
Controller Execution Time ( Story / View Chapter )	0.7268
Total Execution Time	1.5211
Total queries time	0.3383

データベース:db_truyen_chu
クエリ:3
0.0064	"DESCRIBE `story_details`"
0.0301	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3018	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:48:47	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	5676336
ベンチマーク
Loading Time: Base Classes	0.5319
Controller Execution Time ( Story / View Chapter )	0.8711
Total Execution Time	1.4186
Total queries time	0.4379

データベース:db_truyen_chu
クエリ:3
0.0088	"DESCRIBE `story_details`"
0.0373	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.3918	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
2015-01-31 17:49:05	PROFILE	::1	
URI 文字列	story/bong-hong-cho-tinh-dau/chapter/14-176.html
クラス/メソッド	story/view_chapter
メモリ使用量	3902248
ベンチマーク
Loading Time: Base Classes	0.4899
Controller Execution Time ( Story / View Chapter )	0.4927
Total Execution Time	0.9977
Total queries time	0.2069

データベース:db_truyen_chu
クエリ:3
0.0062	"DESCRIBE `story_details`"
0.0288	"SELECT * FROM (`story_details`) WHERE `story_details`.`id` =  '176'"
0.1719	"SELECT `story_details`.`id`, `story_details`.`chapter` FROM (`story_details`) WHERE `story_details`.`story_id` =  '1'"
--------------------------------------------------------
