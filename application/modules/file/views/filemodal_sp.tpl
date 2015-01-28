<div class="in">
	{if !$auth}
		{literal}
		<script type="text/javascript">
			window.parent.location.reload();
		</script>
		{/literal}
	{else}
		<p><img src="{$fixed_base_url}assets_sp/img/{$language}/backarrow_l.png" width="13%" class="close"></p>
		<h2>{'label_attach_list'|lang}</h2>
		<div class="modal_inner">
		{if count($files) > 0}
			<ul class="file_lists">
			{foreach $files as $file}
				<li class="clearfix">
					{if ($file['file_id'] == '0')}
						<a> {$file['file_name']} <span class="filesize"> {'L-F-0034-E'|lang} </span> </a>
					{else}
						<a href="{$fixed_base_url}file/download_file_list/{$file['file_id']}" style="word-wrap: break-word;">
						{$file['file_name']} <span class="filesize">{$file['file_size']|file_size_format:0} </span>
						</a>
					{/if}
					</li>
			{/foreach}
			</ul>
		{/if}
		</div>
	{/if}
</div>