		{if $post['upload_status'] == 1}
		<div class="upload_processing" data-post-id="{$post['id']}">
		<div class="file_wrap clearfix">
			{'label_upload_encrypting'|lang}
		</div>
		</div>
		{else if count($post['uploads']) > 0}
		<div class="file_wrap clearfix">
			<ul class="file_part">
				{foreach $post['uploads'] as $upload}
				<li class="{$upload['file_extension']|get_file_ext_type}">
					<a href="{$fixed_base_url}admin_tools/download/{$upload['file_id']}">
						<p class="dot filename">{$upload['file_name']}</p>
					</a>
					<p>({$upload['file_size']|file_size_format:0})</p>
				</li>
				{/foreach}
			</ul>
			{if $post['file_count'] > 5}
				<p class="tx_fileinfo">{'label_upload_display_more_files'|lang}</p>
			{/if}
			<ul class="file_btn">
				<li>
				{if count($post['uploads']) == 1}
					<a href="{$fixed_base_url}file/download/{$post['uploads'][0]['file_id']}"><img src="{$fixed_base_url}assets/img/{$language}/btn_file_dl.png" alt="{'label_full_download'|lang}"></a>
				{else}
					{''|form_open}
					<a href="{$fixed_base_url}admin_tools/file/{$post['id']}/get_and_zip_file" class ="btn-beforedownload"><img src="{$fixed_base_url}assets-admin/img/btn_file_dl.png" alt="{'label_full_download'|lang}"></a>
					<a href="{$fixed_base_url}admin_tools/file/{$post['id']}/batch_download" class="btn-batchdownload hide"><img src="{$fixed_base_url}assets-admin/img/btn_file_comp.png" alt="{'label_full_download'|lang}"></a>
					{''|form_close}
				{/if}
				</li>
				<li><a href="{$fixed_base_url}admin_tools/file_list/{$post['id']}" class="file_modal">
						<img src="{$fixed_base_url}assets-admin/img/btn_file_list.png" alt="{'label_list_file'|lang}">
					</a>
				</li>
				<li><a href="{$fixed_base_url}file/preview/{$post['id']}/admin/TB_iframe" class="thickbox">
						<img src="{$fixed_base_url}assets-admin/img/btn_file_preview.png" alt="{'label_preview'|lang}">
					</a>
				</li>
				<li class="nokori uploads_expired">{'label_time_remain'|lang}
					<span class="remain_time"></span>
					<input type="hidden" value="{$post['uploads_expired_at']}"/>
				</li>
			</ul>
		</div><!--/file_wrap-->
		{/if}
