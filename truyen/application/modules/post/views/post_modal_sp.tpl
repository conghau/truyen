{literal}
	function confirm_reload(event) {
		if (confirm('{/literal}{'label_invalid_session'|lang}{literal}')){
			window.location.reload();
		}
	}

	function alert_no_more_uploads(event) {
		alert('{/literal}{'label_upload_error_max_number_of_files'|lang}{literal}');
	}

	function alert_exceed_file_size(event) {
		alert('{/literal}{'label_upload_exceed_file_size'|lang}{literal}');
	}
{/literal}
