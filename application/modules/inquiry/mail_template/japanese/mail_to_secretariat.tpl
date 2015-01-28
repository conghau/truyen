【QLifeBOX】お問い合わせ受付|{$inq_info['category']}[{$inq_info['id']}]
<div class="mail-body">
	<p>ユーザーエージェント ： {$user_agent}</p>
	{if $user_id neq ''}
		<p>ユーザーID ： {$user_id}</p>
	{/if}
	<p>お名前 ： {$inq_info['user_name']}　様</p>
	<p>メールアドレス ： {$inq_info['email']}</p>
	<p>お問い合わせ種別 ： {$inq_info['category']}</p>
	<p>お問い合わせ内容 ： <br>
		{$inq_info['body']|nl2br}
	</p>
</div>