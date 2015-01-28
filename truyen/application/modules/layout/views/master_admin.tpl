<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>{block name=title}{/block}</title>

<link rel="stylesheet" href="{$fixed_base_url}assets-admin/css/style.css"/>
<link rel="stylesheet" href="{$fixed_base_url}assets-admin/css/custom.css"/>
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets-admin/css/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets-admin/js/jquery.pageslide.css" />
<link rel="stylesheet" type="text/css" href="{$fixed_base_url}assets-admin/js/thickbox.css" media="screen"/>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery.ui.datepicker-ja.min.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/modal.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="{$fixed_base_url}assets-admin/js/linedot.js"></script>

{block name=javascript}{/block}
{block name=stylesheet}{/block}
</head>
<body>
	<div class="header">
		<div class="ctwrap clearfix">
			<h1 class="logo"><a href="{$fixed_base_url}admin_tools"><img src="{$fixed_base_url}assets-admin/img/logo.png"></a></h1>
			<div class="logout">
				<a href="{$fixed_base_url}admin_tools/logout" id="btn-logout"><img src="{$fixed_base_url}assets-admin/img/logout.jpg"></a>
			</div>
		</div>
	</div>
	
	<div class="ctwrap clearfix">
		<div class="side">
			<div class="sidecate">
				<ul class="sidemenu" >
					<li style="border-top: 1px solid #999" {if $controller eq 'index'} class="select" {/if}><a href="{$fixed_base_url}admin_tools">{'label_index_management'|lang}</a>
				</ul>
			</div>
			
			<!--利用者管理-->
			<div class="sidecate">
				<div class="top"><h2>{'label_title_user_management'|lang}</h2></div>
				<ul class="sidemenu">
					<li {if $controller eq 'admin'} class="select" {/if}>
						{if $admin->role eq $role_super_admin}
							<a href="{$fixed_base_url}admin_tools/admin">{'label_admin_management'|lang}</a>
						{else}
							<a href="{$fixed_base_url}admin_tools/admin/{$admin->id}/edit">{'label_admin_management'|lang}</a>
						{/if}
					</li>
					<li {if $controller eq 'user'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/user">{'label_user_management'|lang}</a></li>
					<li {if $controller eq 'approve'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/approve">{'label_approve_management'|lang}</a></li>
					<li {if $controller eq 'group'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/group">{'label_group_management'|lang}</a></li>
					<li {if $controller eq 'qualification'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/qualification">{'label_qualification_manement'|lang}</a></li>
				</ul>
			</div>
				
			<!--投稿管理-->
			<div class="sidecate">
				<div class="top"><h2>{'label_post_management'|lang}</h2></div>
				<ul class="sidemenu">
					<li {if $controller eq 'post'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/post">{'label_thread_comment_management'|lang}</a></li>
					<li {if $controller eq 'file'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/file">{'label_file_management'|lang}</a></li>
				</ul>
			</div>
			
			<!--ログ/集計管理-->
			<div class="sidecate">
				<div class="top"><h2>{'label_log_aggregation_management'|lang}</h2></div>
				<ul class="sidemenu">
					<li {if $controller eq 'userlog'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/userlog">{'label_user_log_management'|lang}</a></li>
					<li {if $controller eq 'grouplog'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/grouplog">{'label_group_log_management'|lang}</a></li>
					<li {if $controller eq 'threadlog'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/threadlog">{'label_thread_log_management'|lang}</a></li>
					<li {if $controller eq 'filelog'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/filelog">{'label_file_log_management'|lang}</a></li>
					<li {if $controller eq 'entrylog'} class="select" {/if}><a href="{$fixed_base_url}admin_tools/entrylog">{'label_entry_log_management'|lang}</a></li>
				</ul>
			</div>
			
		</div> <!-- /side -->
		
		<div class="mainarea">
			{block name=body}{/block}
		</div>
	</div>
	{literal}
	<script type="text/javascript" src="{/literal}{$fixed_base_url}assets-admin/js/thickbox.js{literal}"></script>
	<script type="text/javascript">
		if (!$(".modalOpen").length){
			$("body").append('<div class="modalOpen"></div>'
			+'<div class="modalBase">'
			+	'<div class="modalMask"></div>'
			+	'<div class="modalWrap">'
			+		'<div class="modal">'
			+			'<div class="ctbox"></div>'
			+		'</div>'
			+	'</div>'
			+'</div>');
			$.getScript("{/literal}{$fixed_base_url}{literal}assets/js/modal.js");
		}

		$(".modal .ctbox").append(
			'<div class="ctinner_m" style="display:none" id="logout-confirm-dialog">'
			+'<p style="margin-bottom:10px;">{/literal}{(isset($msg_confirm_logout))?$msg_confirm_logout :""}{literal}</p>'
			+'<p class="ct m20">'
				+ '<a onclick="modal_logout_clicked(false)" style="cursor: pointer"><img src="{/literal}{$fixed_base_url}assets-admin/img/modal_iie.png{literal}"/></a>'
				+ '<a onclick="modal_logout_clicked(true)" style="cursor: pointer"><img src="{/literal}{$fixed_base_url}assets-admin/img/modal_hai.png{literal}"/></a>'
			+'</p>'
			+'</div>');

		$("#btn-logout").click(confirm_logout);
		function confirm_logout(event) {
			event.preventDefault();
			$(".ctbox .ctinner_m").hide();
			$(".ctbox .ttl").hide();
			$(".ctbox .ctinner").hide();
			
			$("#logout-confirm-dialog").show();

			$('.modalOpen').click();
		}

		function modal_logout_clicked(confirm){
			$('.modalBase').removeClass("ready shown");
			
			$(".ctbox .ctinner_m").show();
			$(".ctbox .ttl").show();
			$(".ctbox .ctinner").show();
			$("#logout-confirm-dialog").hide();

			if (confirm){
				window.location.replace("{/literal}{$fixed_base_url}admin_tools/logout{literal}");
			}
		}
	
	</script>
	{/literal}
  {include file="../../layout/views/tracking_script.tpl"}
</body>
</html>