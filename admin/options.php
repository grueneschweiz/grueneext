<div class="wrap">
    <h2><?php _e( 'Online donation options', 'grueneext' ); ?></h2>

    <form action="options.php" method="post">
		
		<?php
		settings_fields( GRUENEEXT_PLUGIN_PREFIX . '_donation_settings' );
		do_settings_sections( GRUENEEXT_PLUGIN_PREFIX . '_donation_settings' );
		submit_button();
		?>

    </form>
</div>