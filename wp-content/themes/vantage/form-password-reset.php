<?php
// Template Name: Password Reset
?>

<div id="main" class="list">
	<div class="section-head">
		<h1><?php _e( 'Password Reset', APP_TD ); ?></h1>
	</div>

	<?php do_action( 'appthemes_notices' ); ?>

	<?php require APP_FRAMEWORK_DIR . '/templates/form-password-reset.php'; ?>
</div>

<div id="sidebar">
	<?php get_sidebar( app_template_base() ); ?>
</div>
