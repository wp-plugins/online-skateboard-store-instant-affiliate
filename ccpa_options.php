<?php

add_action('admin_init', 'ccpa_options_init' );
add_action('admin_menu', 'ccpa_options_add_page');

function ccpa_options_init(){
	register_setting( 'ccpa_options', 'ccpa_prodsperpost', '' );

}

function ccpa_options_add_page() {
	add_options_page($GLOBALS['affiliate_name'] . " Affiliate Options", $GLOBALS['affiliate_name'] . " Affiliate Options", 'manage_options', 'ccpa_options', 'ccpa_optionspage');
}

function ccpa_optionspage() {
	?>
	<div class="wrap">
		<h2><?php echo $GLOBALS['affiliate_name']; ?> Affiliate Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('ccpa_options'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Products Per Post: (We recommend 4)</th>
					<td><input type="text" name="ccpa_prodsperpost" value="<?php echo get_option('ccpa_prodsperpost'); ?>" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}


