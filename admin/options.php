<div class="wrap">
	<h2><?php _e( 'Grueneext options', 'grueneext' ); ?></h2>
	
	<form action="options.php" method="post">
			
			<?php
				settings_fields( GRUENEEXT_PLUGIN_PREFIX . '_options' );
				do_settings_sections( GRUENEEXT_PLUGIN_PREFIX . '_options' );
				submit_button();
			?>
			
	</form>
</div>