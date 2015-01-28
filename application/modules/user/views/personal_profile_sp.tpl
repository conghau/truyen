{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
    {$title}
{/block}

{* This block is defined in the master.php template *}
{block name=body}
<div class="static_all_wrap">
{if $userinfo->id != $user->id && $userinfo->status != $smarty.const.STATUS_DISABLE} 
	{if $blacklist->user_id == NULL}
		<p class="mb10"><a href="{$fixed_base_url}user/send_to/{$userinfo->id}"><img src="{$fixed_base_url}assets/img/{$language}/btn_can1.png" alt="このユーザーを宛先にして新規のコンサルト/カンファ送信を作成" width="100%"></a></p>
	{/if}
	{if $blacklist->user_id == NULL}
		<p class="mb10"><a href="{$fixed_base_url}user/{$userinfo->id}/add_blacklist_user"><img src="{$fixed_base_url}assets/img/{$language}/btn_can2.png" alt="このユーザーからの送信を拒否する" width="100%"></a></p>
	{else}
		<p class="mb10"><a href="{$fixed_base_url}user/{$userinfo->id}/remove_blacklist_user"><img src="{$fixed_base_url}assets/img/{$language}/btn_can3.png" alt="このユーザーからの送信拒否を解除する。" width="100%"></a></p>
	{/if}
{/if}
<div class="static_wrap">
<div class="prf_box">
	<div class="clearfix">
		<div class="prfbox1 mic{$userinfo->category_id} mic">
		<p class="cate">{$userinfo->qualification|h}</p>
		</div>
		
		<div class="prfr">
		<p class="name">{$userinfo->last_name_ja|cat:' '|cat:$userinfo->first_name_ja|h}</p>
		<p class="namealpha">{$userinfo->last_name|cat:' '|cat:$userinfo->first_name|h}</p>
		</div>
	</div>
	
		<p class="s">{$gender_types[$userinfo->gender].label|lang}
			{sprintf(('label_personal_birthday'|lang),$userinfo->birthday|date_format:"Y{'label_year'|lang}m{'label_month'|lang}d{'label_day'|lang}")}
		</p>
		<p class="s">{$userinfo->organization|h}　{$userinfo->department|h}　{$userinfo->position|h}</p>
</div>
</div>

<div class="static_wrap">
<table class="inq">
<tr>
<th>{'label_personal_domain'|lang}</th></tr><tr>
<td>{if $userinfo->domain_flag != $list_profile_status['2']['id']} 
		{$userinfo->domain|h|nl2br}
	{/if}
</td>
</tr>
<tr>
<th>{'label_personal_university'|lang}</th></tr><tr>
<td>{if $userinfo->university_flag != $list_profile_status['2']['id']} 
		{$userinfo->university|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_history'|lang}</th></tr><tr>
<td>{if $userinfo->history_flag != $list_profile_status['2']['id']} 
		{$userinfo->history|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_scholar'|lang}</th></tr><tr>
<td>{if $userinfo->scholar_flag != $list_profile_status['2']['id']} 
		{$userinfo->scholar|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_author'|lang}</th></tr><tr>
<td>{if $userinfo->author_flag != $list_profile_status['2']['id']} 
		{$userinfo->author|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_society'|lang}</th></tr><tr>
<td>{if $userinfo->society_flag != $list_profile_status['2']['id']} 
		{$userinfo->society|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_hobby'|lang}</th></tr><tr>
<td>{if $userinfo->hobby_flag != $list_profile_status['2']['id']} 
		{$userinfo->hobby|h|nl2br}
	{/if}
</td>
</tr>

<tr>
<th>{'label_personal_message'|lang}</th></tr><tr>
<td>{if $userinfo->message_flag != $list_profile_status['2']['id']} 
		{$userinfo->message|h|nl2br}
	{/if}
</td>
</tr>
</table>
</div>
</div>
<a class="modalOpen" id="btn-dialog"></a>
{/block}
{block name="stylesheet"}
	<style>
		.mic{
			background-position-x: 50%;
			background-position-y: 0px;
		}
	</style>
{/block}