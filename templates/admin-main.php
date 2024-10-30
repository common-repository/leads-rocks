<?php settings_errors( 'leads-rocks_messages' ); ?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		// Security
		settings_fields( 'leads-rocks' );

		// Sections & Fields
		do_settings_sections( 'leads-rocks' );

		// Save Button
		submit_button( 'Save Settings' );
		?>
	</form>
</div>