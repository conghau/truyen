{literal}
	function confirm_reload(event) {
		if (event) {
			event.preventDefault();
		}
		$(".ctbox .ctinner_m").hide();
		$(".ctbox .ttl").hide();
		$(".ctbox .ctinner").hide();
		$("#reload-confirm-dialog").show();
		$('.modalOpen').click();
	}

	$(".modal .ctbox").append(
		'<div class="ctinner_m" style="display:none" id="reload-confirm-dialog">'
		+'<p style="margin-bottom:10px;">{/literal}{'label_invalid_session'|lang}{literal}</p>'
		+'<p class="ct m20">'
			+ '<a onclick="modal_reload(false)"><img src="{/literal}{$fixed_base_url}{literal}assets/img/{/literal}{$language}{literal}/modal_iie.png"/></a>'
			+ '<a onclick="modal_reload(true)"><img src="{/literal}{$fixed_base_url}{literal}assets/img/{/literal}{$language}{literal}/modal_hai.png"/></a>'
		+'</p>'
		+'</div>');

	function modal_reload(confirm){
		$('.modalBase').removeClass("ready shown");
		$(".ctbox .ctinner_m").show();
		$(".ctbox .ttl").show();
		$(".ctbox .ctinner").show();
		$("#reload-confirm-dialog").hide();

		if (confirm){
			window.location.reload();
		}
	}

	function alert_no_more_uploads(event) {
		if (event) {
			event.preventDefault();
		}
		$(".ctbox .ctinner_m").hide();
		$(".ctbox .ttl").hide();
		$(".ctbox .ctinner").hide();
		$("#no-more-upload-alert-dialog").show();
		$('.modalOpen').click();
	}

	$(".modal .ctbox").append(
		'<div class="ctinner_m" style="display:none" id="no-more-upload-alert-dialog">'
		+'<p style="margin-bottom:10px;">{/literal}{'label_upload_error_max_number_of_files'|lang}{literal}</p>'
		+'<p class="ct m20">'
			+ '<a onclick="modal_no_more_uploads()"><img src="{/literal}{$fixed_base_url}{literal}assets/img/{/literal}{$language}{literal}/modal_close.png"/></a>'
		+'</p>'
		+'</div>');

	function modal_no_more_uploads(){
		$('.modalBase').removeClass("ready shown");
		$(".ctbox .ctinner_m").show();
		$(".ctbox .ttl").show();
		$(".ctbox .ctinner").show();
		$("#no-more-upload-alert-dialog").hide();
	}

	function alert_exceed_file_size(event) {
		if (event) {
			event.preventDefault();
		}
		$(".ctbox .ctinner_m").hide();
		$(".ctbox .ttl").hide();
		$(".ctbox .ctinner").hide();
		$("#exceed-file-size-alert-dialog").show();
		$('.modalOpen').click();
	}

	$(".modal .ctbox").append(
		'<div class="ctinner_m" style="display:none" id="exceed-file-size-alert-dialog">'
		+'<p style="margin-bottom:10px;">{/literal}{'label_upload_exceed_file_size'|lang}{literal}</p>'
		+'<p class="ct m20">'
			+ '<a onclick="modal_exceed_file_size()"><img src="{/literal}{$fixed_base_url}{literal}assets/img/{/literal}{$language}{literal}/modal_close.png"/></a>'
		+'</p>'
		+'</div>');

	function modal_exceed_file_size(){
		$('.modalBase').removeClass("ready shown");
		$(".ctbox .ctinner_m").show();
		$(".ctbox .ttl").show();
		$(".ctbox .ctinner").show();
		$("#exceed-file-size-alert-dialog").hide();
	}


{/literal}
