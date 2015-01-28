{* Extend our master template *}
{extends file="master_sp.tpl"}

{* This block is defined in the master.php template *}
{block name=title}
	{$title}
{/block}
{* This block is defined in the master.php template *}
{block name=body}
	<div class="static_all_wrap">
		<h2 class="ttl">{'label_inquiry_title'|lang}</h2>
		<p class="mb10">{'label_inquiry_confirm_info'|lang}</p>
		{$fixed_base_url|cat:'inquiry/store'|form_open:'id=form-main'}
			<div class="static_wrap">
			{if $is_logged_in eq TRUE}
				<table class="inq">
					<tbody>
						<tr>
							<th>{'label_inquiry_type'|lang}</th>
						</tr>
						<tr>
							<td>
								{$inquiry.category|lang}<input type="hidden" name="category" value="{$inquiry.category_id|h}">
							</td>
						</tr>
						<tr>
							<th>{'label_inquiry_content'|lang}</th>
						</tr>
						<tr>
							<td>
								{$inquiry.body|h|nl2br}<input type="hidden" name="content" value="{$inquiry.body|h}">
							</td>
						</tr>
					</tbody>
				</table>
			{else}
				<table class="inq">
				<tbody>
					<tr>
						<th>{'lalel_inquiry_mr_name'|lang}</th>
					</tr>
					<tr>
						<td>{$inquiry.user_name}<input type="hidden" name="user_name" value="{$inquiry.user_name|h}"></td>
					</tr>
					<tr>
						<th>{'label_inquiry_mail_address'|lang}</th>
					</tr>
					<tr>
						<td>{$inquiry.email}<input type="hidden" name="email" value="{$inquiry.email|h}"></td>
					</tr>
					<tr>
						<th>{'label_inquiry_type'|lang}</th>
					</tr>
					<tr>
						<td>{$inquiry.category|lang}<input type="hidden" name="category" value="{$inquiry.category_id|h}"></td>
					</tr>
					<tr>
						<th>{'label_inquiry_content'|lang}</th>
					</tr>
					<tr>
						<td>{$inquiry.body|h|nl2br}<input type="hidden" name="content" value="{$inquiry.body|h}"></td>
					</tr>
				</tbody>
			</table>
			{/if}
			</div>
			<div class="btn">
				<a href="#" id="btn-complete" class="com">{'label_inquiry_button_send'|lang}</a>
			</div>
			<div class="btn">
				<a href="#" id="btn-edit" class="com">{'label_inquiry_button_back'|lang}</a>
			</div>
		{''|form_close}	
	</div>
	<a href="" class="modalOpen" id="btn-dialog"></a>
	<div class="modalBase">
		<div class="modalMask"></div>
		<div class="modalWrap">
			<div class="modal">
				<div class="ctbox">
					<div class="ctinner_m">
						<p id="dialog_p" style="text-align: left"></p>
						<p class="ct m20">
							<a id="btn_ok" href="{$fixed_base_url}"><img src="{$fixed_base_url}assets/img/{$language}/modal_hai.png"></a>
						</p>
					</div><!--/ctinner-->
				</div><!--/ctbox-->
			</div>
		</div>
	</div>



<!-- footer -->
<div class="mt20">
<div class="foot">
	<ul>
		<li><a href="{$fixed_base_url}other/security">{'label_footer_security'|lang}</a></li>
		<li><a href="{$fixed_base_url}other/term" >{'label_footer_terms'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/privacypolicy" target="_blank">{'label_footer_privacy'|lang}</a></li>
		<li><a href="http://www.qlife.co.jp/" target="_blank">{'label_footer_company'|lang}</a></li>
		<li><a href="{$fixed_base_url}inquiry">{'label_footer_inquiry'|lang}</a></li>
	</ul>
	<p>© 2014 QLife, Inc. </p>
</div>
</div>

{/block}
{block name=stylesheet}
	<style>
		table td {
			word-break:break-word;
		}
	</style>
{/block}
{block name=javascript}
{literal}
	<script type="text/javascript">
	var posted = false;
	$('document').ready(function(){
		$("#btn-edit").on('click', function() {
			$('#form-main').attr('action', '{/literal}{$fixed_base_url|cat:'inquiry'}{literal}');
			$('#form-main').submit();
		});
		$("#btn-complete").click(function() {
			$(this).attr("disabled", "disabled");
			if (posted) {
				return false;
			}
			var urlAction = $('#form-main').attr('action');	
			var cct = $("input[name=csrf_token]").val();
			posted = true;
			$.ajax({
				type: 'POST',
				url: urlAction,
				data: {csrf_token: cct},
				dataType :'html',
				success : function(data){
					try {
						var obj = JSON && JSON.parse(data) || $.parseJSON(data);
						$('#dialog_p').append(obj);
						$('#btn-dialog').click();
					} catch (e) {
						window.location.replace("{/literal}{$fixed_base_url}{literal}");
					}
				},
				error: function (data, status, e){
					window.location.replace("{/literal}{$fixed_base_url}{literal}");
				}
			});
			return false;
		});
	});
	</script>
{/literal}	
{/block}