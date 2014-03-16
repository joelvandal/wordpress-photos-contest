<div class="wrap">

	<h2><?php _e('Photos Contest - Configuration', PSC_PLUGIN); ?></h2>

	<form name="configuration" id="configuration" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<?php wp_nonce_field('psc_settings', 'psc_settings_nonce') ?>

		<h3><?php _e('Contest', PSC_PLUGIN); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e('Vote Open Date', PSC_PLUGIN); ?>
				</th>
				<td>
					<input class="datepicker" type="text" size="15" name="vote_open_date" value="<?php echo psc_format_datetime( psc_get_option('vote_open_date')); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e('Vote Close Date', PSC_PLUGIN); ?>
				</th>
				<td>
					<input class="datepicker" type="text" size="15" name="vote_close_date" value="<?php echo psc_format_datetime( psc_get_option('vote_close_date')); ?>" />
					<p class="description">
					</p>
				</td>
			</tr>
		</table>

		<h3><?php _e('Social Sharing',PSC_PLUGIN); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e('Twitter Message', PSC_PLUGIN); ?>
				</th>
				<td>
					<textarea rows="2" cols="80" name="twitter_text"><?php echo psc_get_option('twitter_text'); ?></textarea>
					<p class="description">
						<?php _e('This is the Twitter Message. Maximum length of 140 characters.', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e('Twitter Message', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="30" name="twitter_hash" value="<?php echo psc_get_option('twitter_hash'); ?>" />
					<p class="description">
						<?php _e('This is the Twitter Hashtag', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>
		</table>
		
		<h3><?php _e('Advanced options',PSC_PLUGIN); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th align="left">
					<?php _e('Bit.ly Login', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="40" name="bitly_login" value="<?php echo psc_get_option('bitly_login'); ?>" />
					<p class="description">
						<?php _e('This is the Bit.ly Login', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e('Bit.ly API Key', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="40" name="bitly_api_key" value="<?php echo psc_get_option('bitly_api_key'); ?>" />
					<p class="description">
						<?php _e('This is the Bit.ly API Key', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e('Google API Key', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="40" name="google_api_key" value="<?php echo psc_get_option('google_api_key'); ?>" />
					<p class="description">
						<?php _e('This is the Google API Key', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e('Facebook Client ID', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="40" name="facebook_client_id" value="<?php echo psc_get_option('facebook_client_id'); ?>" />
					<p class="description">
						<?php _e('This is the Facebook Client ID', PSC_PLUGIN);?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th align="left">
					<?php _e('Facebook Secret Key', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="40" name="facebook_secret_key" value="<?php echo psc_get_option('facebook_secret_key'); ?>" />
					<p class="description">
						<?php _e('This is the Facebook Secret Key', PSC_PLUGIN);?>
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
