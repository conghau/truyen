<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

2015-01-30 16:51:09	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	6028200
ベンチマーク
Loading Time: Base Classes	0.0187
Controller Execution Time ( Story / View Story )	0.0269
Total Execution Time	0.0460
Total queries time	0.0008

データベース:db_truyen
クエリ:2
0.0005	"DESCRIBE `storys`"
0.0003	"DESCRIBE `story_details`"
--------------------------------------------------------
2015-01-30 16:56:46	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	7312856
ベンチマーク
Loading Time: Base Classes	0.0184
Controller Execution Time ( Story / View Story )	0.1890
Total Execution Time	0.2079
Total queries time	0.0151

データベース:db_truyen
クエリ:3
0.0005	"DESCRIBE `storys`"
0.0003	"DESCRIBE `story_details`"
0.0143	"SELECT * FROM (`story_details`)"
--------------------------------------------------------
2015-01-30 17:06:43	PROFILE	::1	
URI 文字列	story/detail/1
クラス/メソッド	story/view_story
メモリ使用量	7213832
ベンチマーク
Loading Time: Base Classes	0.0189
Controller Execution Time ( Story / View Story )	0.1899
Total Execution Time	0.2092
Total queries time	0.0147

データベース:db_truyen
クエリ:2
0.0005	"DESCRIBE `story_details`"
0.0142	"SELECT * FROM (`story_details`)"
--------------------------------------------------------
