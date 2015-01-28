【QLifeBOX】Contact|{$inq_info['category']}[{$inq_info['id']}]
<div class="mail-body">
	<p>User agent： {$user_agent}</p>
	{if $user_id neq ''}
		<p>User ID ： {$user_id}</p>
	{/if}
	<p>Full name ： {$inq_info['user_name']}　様</p>
	<p>Email ： {$inq_info['email']}</p>
	<p>Contact type ： {$inq_info['category']}</p>
	<p>Inquiry ： <br>
		{$inq_info['body']|nl2br}
	</p>
</div>