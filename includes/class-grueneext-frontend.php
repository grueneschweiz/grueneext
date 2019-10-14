<?php

/**
 * lock out script kiddies: die an direct call
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( ! class_exists( 'Grueneext_Frontend' ) ) {

    /**
     * This class contains all the extra stuff of the frontend
     */
    class Grueneext_Frontend {

        /**
         * Add shortcode for slidetoggle content
         *
         * Use [hide_n_show display="The title"]The content[/hide_n_show]
         * in the editor to slide toggle some content. Replace 'The title'
         * with the caption of the togglebutton and 'The content' with
         * the content to slide in or out. When the page ist loaded,
         * the content is hidden.
         * Use the conditional arguments 'css' to add some custom styles,
         * 'add_class' to add some custom classes, 'header' to define
         * the heading hierarchy.
         *
         * @param array $atts given from the add_shortcode function
         * @param string $content given from the add_shortcode function
         *
         * @return string
         */
        public function hide_n_show( $atts, $content = '' ) {
            extract(
                shortcode_atts(
                    array(
                        'display'   => 'hide_n_show display="[Enter your display text here]"',
                        'css'       => '',
                        'class'     => 'grueneext_hide_n_show',
                        'add_class' => '',
                        'header'    => 'h2',
                    ),
                    $atts
                )
            );

            // prevent false header tags
            $header = in_array( strtolower( trim( $header ) ),
                array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? trim( $header ) : 'h2';

            return '<div class="' . esc_attr( $class ) . ' ' . esc_attr( $add_class ) . '" style="' . esc_attr( $css ) . '">'
                   . '<div class="grueneext_hide_n_show_display"><' . $header . '><a href="#">' . $display . '</a><' . $header . '></div>'
                   . '<div class="grueneext_hide_n_show_content">' . do_shortcode( $content ) . '</div>'
                   . '</div>';
        }

        /**
         * Add shortcode for button
         *
         * Use [button link="http://www.example.com/"]The content[/button]
         * in the editor to display a button. Replace 'http://www.example.com/'
         * with the link of your choice.
         * Use the conditional arguemnts 'css' to add some custom styles,
         * 'add_class' to add some custom classes, 'target' to define a link target
         * and 'color' with the properties 'green' or 'magenta' to change the background
         * colors.
         *
         * @param array $atts given from the add_shortcode function
         * @param string $content given from the add_shortcode function
         *
         * @return string
         */
        public function button( $atts, $content = '' ) {
            extract(
                shortcode_atts(
                    array(
                        'css'       => '',
                        'class'     => 'grueneext_button',
                        'add_class' => '',
                        'link'      => '',
                        'color'     => 'magenta',
                        'target'    => '',
                    ),
                    $atts
                )
            );

            $color  = 'grueneext_button_' . $color;
            $target = empty( $target ) ? '' : ' target="' . $target . '"';

            return '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $class ) . ' ' . esc_attr( $color ) . ' ' . esc_attr( $add_class ) . '" style="' . esc_attr( $css ) . '"' . esc_attr( $target ) . '>'
                   . do_shortcode( $content )
                   . '</a>';
        }

        /**
         * Add shortcode for progressbar
         *
         * Use [progressbar] with the required arguments 'max' and 'value'
         * and the optional ones 'show_current_number' and 'unit'.
         * Use the conditional arguements 'css' to add some custom styles,
         * 'add_class' to add some custom classes, and 'color' with the
         * properties 'green' or 'magenta' to change the background
         * colors.
         *
         * @param array $atts given from the add_shortcode function
         * @param string $content given from the add_shortcode function
         *
         * @return string
         */
        public function progressbar( $atts ) {
            extract(
                shortcode_atts(
                    array(
                        'css'        => '',
                        'class'      => 'grueneext_progressbar',
                        'add_class'  => '',
                        'color'      => 'magenta',
                        'max'        => 100,
                        'value'      => 37,
                        'unit'       => '',
                        'show_value' => 'show',
                    ),
                    $atts
                )
            );
            $max        = (float) $max;
            $value      = (float) $value;
            $color      = 'grueneext_progressbar_' . $color;
            $show_value = 'show' == $show_value ? true : false;

            if ( '%' === trim( $unit ) ) {
                $value = round( $value / $max, 3 ) * 100;
                $max   = $value > 100 ? $value : 100;
            }

            return '<div class="' . esc_attr( $class ) . ' ' . esc_attr( $color ) . ' ' . esc_attr( $add_class ) . '" style="' . esc_attr( $css ) . '" data-max="' . esc_attr( $max ) . '" data-value="' . esc_attr( $value ) . '">'
                   . '<div class="grueneext_progressbar_label">' . esc_html( $value ) . esc_html( $unit ) . '</div>'
                   . '</div>';
        }

        /**
         * Add shortcode for raise now donation form
         *
         * Use [donation_form] with the required arguments 'api_key' and 'language'.
         * Use the conditional arguements 'css' to add some custom styles and
         * 'add_class' to add some custom classes.
         *
         * @param array $atts given from the add_shortcode function
         * @param string $content given from the add_shortcode function
         *
         * @return string
         */
        public function donation_form( $atts ) {
            $languages = [ 'de', 'fr' ];

            extract(
                shortcode_atts(
                    array(
                        'api_key'   => '',
                        'language'  => '',
                        'css'       => '',
                        'class'     => 'grueneext_donationform',
                        'add_class' => '',
                    ),
                    $atts
                )
            );

            $api_key = trim( $api_key );
            if ( empty( $api_key ) ) {
                return '<div>' . sprintf( _x( 'Missing api key in shortcode. Shortcode must have the form: %s',
                        '%s will be replaced with an example shortcode', 'grueneext' ),
                        '[donation_form api_key="API_KEY" language="LANG"]' ) . '</div>';
            }

            $language = trim( strtolower( $language ) );
            if ( ! in_array( $language, $languages ) ) {
                return '<div>' . sprintf( _x( 'Unknown language key in shortcode. Accepted values are %1$s. Shortcode must have the form: %2$s',
                        '%1$s will be replaced with the accepted language keys. %2$s will be replaced with an example shortcode',
                        'grueneext' ), implode( ', ', $languages ),
                        '[donation_form api_key="API_KEY" language="LANG"]' ) . '</div>';
            }

            $options       = get_option( GRUENEEXT_PLUGIN_PREFIX . '_donation_options' );
            $custom_css    = $options['custom_css'];
            $custom_script = $options['custom_script'];

            return '<div class="' . esc_attr( $class ) . ' ' . esc_attr( $add_class ) . '" style="' . esc_attr( $css ) . '">'
                   . '<div class="dds-widget-container" data-widget="lema"></div>'
                   . '<script language="javascript" src="https://widget.raisenow.com/widgets/lema/' . esc_attr( $api_key ) . '/js/dds-init-widget-' . esc_attr( $language ) . '.js" type="text/javascript"></script>'
                   . '<script type="text/javascript">' . $custom_script . '</script>'
                   . '<style type="text/css" scoped>' . $custom_css . '</style>'
                   . '</div>';
        }
    }
}