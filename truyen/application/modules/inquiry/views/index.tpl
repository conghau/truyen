{* Extend our master template *}
{extends file="master.tpl"}

{block name=title}
	{$title}
{/block}

{block name=body1}
	<div class="static_all_wrap wrapper">
		<h2 class="ttl">{'label_inquiry_title'|lang}</h2>
		<p class="mb10">{'label_inquiry_info'|lang}</p>
	{$fixed_base_url|cat:'inquiry/confirm'|form_open:'id=form-main'}
		<div class="static_wrap">
		{if $is_logged_in eq TRUE}
			<table class="inq">
				<tbody>
				<tr>
					<th>
						{'label_inquiry_type'|lang}
					</th>
					<td>
						<select id="category" name="category">
							{foreach from=$inquiry_types item=type}
								{if isset($category) && $category eq $type.id}
									<option selected="selected" value="{$type.id}">{$type.label|lang}</option>
								{else}
									<option value="{$type.id}">{$type.label|lang}</option>
								{/if}
							{/foreach}	
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="label_birthday" class=" control-label">{'label_inquiry_content'|lang}</label><a style="color:red">※</a></th>
					<td>	
						<textarea name="content" rows="10" cols="30">{$content|h}</textarea>
						{form_error('content','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				</tbody>
			</table>
		{else}
			<table class="inq">
			<tbody>
				<tr>
					<th>{'lalel_inquiry_mr_name'|lang}</th>
					<td><input id="user_name" type="text" name="user_name" value="{$user_name|h}" maxlength="128">
					{form_error('user_name','<p class="alert-error"> ※ ','</p>')}</td>
				</tr>
				<tr>
					<th><label class=" control-label">{'label_inquiry_mail_address'|lang}</label><a style="color:red">※</a></th>
					<td><input type="text" id="mail_address" name="mail_address" value="{$email|h}" maxlength="128">
						{form_error('mail_address','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
				<tr>
					<th>{'label_inquiry_type'|lang}</th>
					<td>
						<select id="category" name="category">
							{foreach from=$inquiry_types item=type}
								{if isset($category) && $category eq $type.id}
									<option selected="selected" value="{$type.id}">{$type.label|lang}</option>
								{else}
									<option value="{$type.id}">{$type.label|lang}</option>
								{/if}
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="label_birthday" class=" control-label">{'label_inquiry_content'|lang}</label><a style="color:red">※</a></th>
					<td>	
						<textarea name="content" rows="10" cols="50">{$content|h}</textarea>
							{form_error('content','<p class="alert-error"> ※ ','</p>')}
					</td>
				</tr>
			</tbody>
			</table>
		{/if}
			<div class="mt20 ct">
				<a href="" id="submit"><img src="{$fixed_base_url}assets/img/{$language}/btn_confirm.png"></a>
			</div>
		</div>	
	{''|form_close}
	</div>

	<div class="bt0">
		<div class="footer">
			<div class="linkarea">
				<a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a> |
				<a href="{$fixed_base_url}other/term">{'label_footer_terms'|lang}</a> |
				<a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a> |
				<a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a> |
				<a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a>
			</div>
			<p class="ct">© 2014 QLife, Inc.</p> 
		</div>
	</div>

{/block}

{block name=javascript}
{literal}
	<script type="text/javascript">
		$('#submit').click(function(){
			var cct_cookie = $.cookie('csrf_token');
			if(cct_cookie == undefined) {
				window.location.replace("{/literal}{$fixed_base_url}login{literal}");
				return false;
			}
			$('#form-main').submit();
			return false;
		});
	</script>
{/literal}
{/block}