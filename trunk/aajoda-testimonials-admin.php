<?php

	if (  isset($_POST['aajodatestimonials_opt_hidden']) && $_POST['aajodatestimonials_opt_hidden'] == 'Y' )  {

		$code = $_POST['aajodatestimonials_code'];
		$version = $_POST['aajoda_version'];

		update_option('aajodatestimonials_code', aajodasanitize_data($code));
		update_option('aajoda_version', $version);

		

		?>	

		<div class="updated"><p><strong><?php _e('Successfully saved.', 'aajodatestimonials'); ?></strong></p></div>

		<?php

	} else {

		$code = get_option('aajodatestimonials_code');

	}

?>




<div class="wrap">

<?php echo "<h2>" . __( 'Aajoda Testimonials Options', 'aajodatestimonials') . "</h2>"; ?>

<form  name="aajoda_testimonials_form" method="post" action="<?php echo admin_url('options-general.php').'?page='.$_GET['page']; ?>">

	<input type="hidden" name="aajodatestimonials_opt_hidden" value="Y">
	<div class="metabox-holder">
			<div class="postbox"> 

			<h3><?php _e('Aajoda WP Shortcodes', 'aajodatestimonials'); ?></h3>

			<div style="padding:0 10px 20px 10px;" >

				<p><?php _e('Fetch your Aajoda WP shortcode from <a href="https://www.aajoda.com/customerdash/customerintegration2" target="_blank">aajoda.com</a> and paste it in any post or page in your website like this:', 'aajodatestimonials'); ?></p>
				<code>
				[aajoda id="xxxxxxxxx"]
				</code>
			</div>

		</div>
	</div>

	<!-- version select -->
	<div>
		<h3><?php _e('Select version', 'aajodatestimonials'); ?></h3>

		<?php $version = get_option( 'aajoda_version' ); ?>
		
		<select name='aajoda_version'>
		<option value='2.1' <?php selected( $version, "2.1" ); ?>>2.1 </option>
			<option value='2.0' <?php selected( $version, "2.0" ); ?>>2.0 </option>
		</select>
	</div>
	
	
	<div class="metabox-holder">
		<div class="postbox"> 
			<h3><?php _e('Optional Aajoda Style', 'aajodatestimonials'); ?></h3>
			<div style="padding:0 10px 10px 10px;" >
				<p><?php _e('Place optional css style for Aajoda in this textarea below', 'aajodatestimonials'); ?></p>
				<textarea name="aajodatestimonials_code" rows="8" cols="90" /><?php echo stripslashes($code); ?></textarea>
			</div>
		</div>
	</div>

	<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save','aajodatestimonials') ?>" />
</form>

</div>