<div id="grueneext-short-code-generator" style="display: none;">
    <div class="wrapper">

        <div id="grueneext-short-code-message" class="notice-warning"></div>

        <form action="#" method="post">
            <p id="grueneext-select-type">
                <label for="grueneext-select-type"><?php _e( 'Which type of special function shall be inserted?',
						'grueneext' ); ?></label><br/>
                <select name="grueneext-select-type">
                    <option value="-1"><?php _e( 'Select special function', 'grueneext' ); ?></option>
                    <option value="hide_n_show"><?php _e( 'Hide and Show', 'grueneext' ); ?></option>
                    <option value="button"><?php _e( 'Button', 'grueneext' ); ?></option>
                    <option value="progressbar"><?php _e( 'Progressbar', 'grueneext' ); ?></option>
                    <option value="donation_form"><?php _e( 'Donation form', 'grueneext' ); ?></option>
                </select>
            </p>

            <div id="grueneext-short-code-hide_n_show" class="grueneext-short-code-type-element">
                <p id="grueneext-select-hide_n_show" class="grueneext-short-code-input">
                    <label for="grueneext-hide_n_show-display"><?php _e( 'Enter the title of your hide and show element',
							'grueneext' ); ?></label><br/>
                    <input type="text" name="grueneext-hide_n_show-display"
                           placeholder="<?php esc_attr_e( 'Enter your "Hide and Show" title here.', 'grueneext' ); ?>">
                </p>
            </div>

            <div id="grueneext-short-code-button" class="grueneext-short-code-type-element">
                <p id="grueneext-select-button-link" class="grueneext-short-code-input">
                    <label for="grueneext-button-link"><?php _e( 'Enter the link where the button leads to',
							'grueneext' ); ?></label><br/>
                    <input type="url" name="grueneext-button-link"
                           placeholder="<?php esc_attr_e( 'Where shall the button lead to (URL)?', 'grueneext' ); ?>">
                </p>
                <p id="grueneext-select-button-color" class="grueneext-short-code-input">
                    <label for="grueneext-button-color"><?php _e( 'Chose color scheme', 'grueneext' ); ?></label><br/>
                    <select name="grueneext-button-color">
                        <option value="magenta" selected="selected"><?php _e( 'Magenta', 'grueneext' ); ?></option>
                        <option value="green"><?php _e( 'Green', 'grueneext' ); ?></option>
                    </select>
                </p>
            </div>

            <div id="grueneext-short-code-progressbar" class="grueneext-short-code-type-element">
                <p id="grueneext-select-progressbar-max" class="grueneext-short-code-input">
                    <label for="grueneext-progressbar-max"><?php _e( 'Enter the maximum value.',
							'grueneext' ); ?></label><br/>
                    <input type="number" name="grueneext-progressbar-max" placeholder="100">
                </p>
                <p id="grueneext-select-progressbar-value" class="grueneext-short-code-input">
                    <label for="grueneext-progressbar-value"><?php _e( 'Enter the current value.',
							'grueneext' ); ?></label><br/>
                    <input type="number" name="grueneext-progressbar-value" placeholder="37">
                </p>
                <p id="grueneext-select-progressbar-unit" class="grueneext-short-code-input">
                    <label for="grueneext-progressbar-unit"><?php _e( 'Enter the unit (optional).',
							'grueneext' ); ?></label><br/>
                    <input type="text" name="grueneext-progressbar-unit" placeholder="%">
                </p>
                <p id="grueneext-select-progressbar-show_value" class="grueneext-short-code-input">
                    <label for="grueneext-progressbar-show_value"><?php _e( 'Choose if the value should be displayed',
							'grueneext' ); ?></label><br/>
                    <select name="grueneext-progressbar-show_value">
                        <option value="show" selected="selected"><?php _e( 'Show value', 'grueneext' ); ?></option>
                        <option value="hide"><?php _e( 'Hide value', 'grueneext' ); ?></option>
                    </select>
                </p>
                <p id="grueneext-select-progressbar-color" class="grueneext-short-code-input">
                    <label for="grueneext-progressbar-color"><?php _e( 'Chose color scheme',
							'grueneext' ); ?></label><br/>
                    <select name="grueneext-progressbar-color">
                        <option value="magenta" selected="selected"><?php _e( 'Magenta', 'grueneext' ); ?></option>
                        <option value="green"><?php _e( 'Green', 'grueneext' ); ?></option>
                    </select>
                </p>
            </div>

            <div id="grueneext-short-code-donation_form" class="grueneext-short-code-type-element">
                <div class="notice-info"><p><?php _e('You need to have a contract with RaiseNow to use this function.','grueneext') ?></p></div>
                <p id="grueneext-select-donation_form-api_key" class="grueneext-short-code-input">
                    <label for="grueneext-donation_form-api_key"><?php _e( 'Enter the API key you received from RaiseNow',
					        'grueneext' ); ?></label><br/>
                    <input type="text" name="grueneext-donation_form-api_key"
                           placeholder="<?php esc_attr_e( 'Enter the API key here.', 'grueneext' ); ?>">
                </p>
                <p id="grueneext-select-donation_form-language" class="grueneext-short-code-input">
                    <label for="grueneext-donation_form-language"><?php _e( 'Choose language for the donation form',
                            'grueneext' ); ?></label><br/>
                    <select name="grueneext-donation_form-language">
                        <option value="de" selected="selected"><?php _e( 'German', 'grueneext' ); ?></option>
                        <option value="fr"><?php _e( 'French', 'grueneext' ); ?></option>
                    </select>
                </p>
            </div>

            <input id="grueneext-submit-short-code" type="submit"
                   value="<?php esc_attr_e( 'Insert shortcode', 'grueneext' ); ?>"
                   class="button button-primary button-large">
        </form>
    </div>
</div>
