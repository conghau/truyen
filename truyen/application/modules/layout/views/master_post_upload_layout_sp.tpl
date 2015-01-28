		{if $post['upload_status'] == 1}
		<div class="upload_processing" data-post-id="{$post['id']}">
		<div class="file_wrap clearfix">
			{'label_upload_encrypting'|lang}
		</div>
		</div>
		{else if count($post['uploads']) > 0}
		<div class="file_wrap clearfix">
			<table width="100%" class="ct file_part">
				{$num_row = min(2, intval(ceil(count($post['uploads'])/2)))}
				{$j = 0}
				{for $i=1 to $num_row}
				<tr>
				{for $n=1 to 2}
					<td width="50%">
					{if $j|array_key_exists:$post['uploads']}
					<li class="{$post['uploads'][$j]['file_extension']|get_file_ext_type}">
					<a href="{$fixed_base_url}file/download/{$post['uploads'][$j]['file_id']}">
						<p class="dot filename">{$post['uploads'][$j]['file_name']}</p>
					</a>
					<p>({$post['uploads'][$j]['file_size']|file_size_format:0})</p>
					</li>
					{/if}
					{$j = $j + 1}
					</td>
				{/for}
				</tr>
				{/for}
			</table> 
			{if $post['file_count'] > 4}
				<p class="tx_fileinfo">{'label_upload_display_more_files'|lang}</p>
			{/if}
			<ul class="btns2 clearfix mb10">
				<li><a href="#file_modal" class="file_modal r2l" data-rel="{$post['id']}">{'label_list_file'|lang}</a></li>
				<li><a href="{$fixed_base_url}file/preview/{$post['id']}" class="ajaxopen">{'label_preview'|lang}</a></li>
			</ul>
			<p class="nokori uploads_expired">{'label_time_remain'|lang}<span class="remain_time"></span>
			<input type="hidden" value="{$post['uploads_expired_at']}"/>
			</p>
		</div><!--/file_wrap-->
		{/if}
