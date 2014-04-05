<div class="wrap">

	<h2><?php _e_psc('Photos Contest - Configuration'); ?></h2>

	<form name="configuration" id="configuration" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<?php wp_nonce_field('psc_settings', 'psc_settings_nonce') ?>

		<h3><?php _e_psc('Contest'); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Vote Open Date'); ?>
				</th>
				<td>
					<input class="datepicker" type="text" size="15" name="vote_open_date" value="<?php echo psc_format_datetime( psc_get_option('vote_open_date')); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Vote Close Date'); ?>
				</th>
				<td>
					<input class="datepicker" type="text" size="15" name="vote_close_date" value="<?php echo psc_format_datetime( psc_get_option('vote_close_date')); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
		</table>

		<h3><?php _e_psc('Email Notification'); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Vote Subject'); ?>
				</th>
				<td>
					<input type="text" size="80" name="vote_subject" value="<?php echo psc_get_option('vote_subject'); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Vote Message'); ?>
				</th>
				<td>
					<textarea rows="6" cols="80" name="vote_message"><?php echo psc_get_option('vote_message'); ?></textarea>
					<p class="description">
					<?php _e_psc('Available variables: [blog_name], [blog_url], [vote_link]'); ?>
					</p>
				</td>
			</tr>
		</table>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Register Subject'); ?>
				</th>
				<td>
					<input type="text" size="80" name="register_subject" value="<?php echo psc_get_option('register_subject'); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Register Message'); ?>
				</th>
				<td>
					<textarea rows="6" cols="80" name="register_message"><?php echo psc_get_option('register_message'); ?></textarea>
					<p class="description">
					<?php _e_psc('Available variables: [blog_name], [blog_url]'); ?>
					</p>
				</td>
			</tr>
		</table>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Approval Subject'); ?>
				</th>
				<td>
					<input type="text" size="80" name="approval_subject" value="<?php echo psc_get_option('approval_subject'); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Approval Message'); ?>
				</th>
				<td>
					<textarea rows="6" cols="80" name="approval_message"><?php echo psc_get_option('approval_message'); ?></textarea>
					<p class="description">
					<?php _e_psc('Available variables: [first_name], [last_name], [project_name], [project-id], [blog_name], [blog_url]'); ?>
					</p>
				</td>
			</tr>
		</table>
		
		<h3><?php _e_psc('Social Sharing'); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Twitter Message'); ?>
				</th>
				<td>
					<textarea rows="2" cols="80" name="twitter_text"><?php echo psc_get_option('twitter_text'); ?></textarea>
					<p class="description">
						<?php _e_psc('This is the Twitter Message. Maximum length of 140 characters.');?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Twitter Message'); ?>
				</th>
				<td>
					<input type="text" size="30" name="twitter_hash" value="<?php echo psc_get_option('twitter_hash'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Twitter Hashtag');?>
					</p>
				</td>
			</tr>
		</table>
		
		<h3><?php _e_psc('Advanced options'); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Bit.ly Login'); ?>
				</th>
				<td>
					<input type="text" size="40" name="bitly_login" value="<?php echo psc_get_option('bitly_login'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Bit.ly Login');?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Bit.ly API Key'); ?>
				</th>
				<td>
					<input type="text" size="40" name="bitly_api_key" value="<?php echo psc_get_option('bitly_api_key'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Bit.ly API Key');?>
					</p>
				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e_psc('Google API Key'); ?>
				</th>
				<td>
					<input type="text" size="40" name="google_api_key" value="<?php echo psc_get_option('google_api_key'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Google API Key');?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Facebook Client ID'); ?>
				</th>
				<td>
					<input type="text" size="40" name="facebook_client_id" value="<?php echo psc_get_option('facebook_client_id'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Facebook Client ID');?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Facebook Secret Key'); ?>
				</th>
				<td>
					<input type="text" size="40" name="facebook_secret_key" value="<?php echo psc_get_option('facebook_secret_key'); ?>" />
					<p class="description">
						<?php _e_psc('This is the Facebook Secret Key');?>
					</p>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.datepicker').datetimepicker({
			format : "Y-m-d H:i"
		});
	});
</script>
