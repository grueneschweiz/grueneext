/**
 * jQuery wrapper
 */
(function ($) {
    var ShortCodeGen = new ShortCodeGen();

    /**
     * handles all the functionality of the short code generator
     */
    function ShortCodeGen() {

        var self = this;

        /**
         * initiatelize
         *
         * add change events
         * add submit event
         */
        this.init = function init() {

            // hide all elements exept the type selector
            $('.grueneext-short-code-type-element').hide();

            // add change events
            $('select[name="grueneext-select-type"]').change(function () {
                // hide all selects execpt the type selector
                $('.grueneext-short-code-type-element').hide();
                // show the corresponding one to the type selection
                $('#grueneext-short-code-' + $(this).val()).show();
            });

            $('#grueneext-submit-short-code').click(function (event) {
                // dont submit
                event.preventDefault();

                // check if input is valid
                if (false === self.isInputValid()) {
                    return; // BREAKPOINT
                }

                // create shortcode
                var shortcode = self.generateShortcode();

                // insert shortcode
                window.send_to_editor(shortcode);

                // close thickbox
                tb_remove();
            });
        };

        /**
         * validate input
         *
         * returns true if valid. else false and shows an error message.
         *
         * @return    bool    true if valid, else false
         */
        this.isInputValid = function isInputValid() {

            $('#grueneext-short-code-message').hide();

            switch ($('select[name="grueneext-select-type"]').val()) {

                case '-1':
                    // close thickbox
                    tb_remove();
                    return false;
                    break;

                case 'hide_n_show':
                    if ('' !== $('input[name="grueneext-hide_n_show-display"]').val()) {
                        return true;
                    } else {
                        $('#grueneext-short-code-message').text('Please enter a title.').show();
                        return false;
                    }
                    break;

                case 'button':
                    var link = $('input[name="grueneext-button-link"]').val();
                    if ('' !== link && true === self.validUrl(link)) {
                        return true;
                    } else {
                        $('#grueneext-short-code-message').text('Please enter a valid url.').show();
                        return false;
                    }
                    break;

                case 'progressbar':
                    if (isNaN(parseFloat($('input[name="grueneext-progressbar-max"]').val()))) {
                        $('#grueneext-short-code-message').text('Please enter a max value (only numbers accepted).').show();
                        return false;
                    } else if (isNaN(parseFloat($('input[name="grueneext-progressbar-value"]').val()))) {
                        $('#grueneext-short-code-message').text('Please enter a value (only numbers accepted).').show();
                        return false;
                    } else {
                        return true;
                    }

                default:
                    $('#grueneext-short-code-message').text('Please select a type.').show();
                    return false;
            }
        };


        /**
         * generate shortcode
         *
         * @return    string    the shortcode
         */
        this.generateShortcode = function generateShortcode() {

            switch ($('select[name="grueneext-select-type"]').val()) {
                case 'hide_n_show':
                    var text = $('input[name="grueneext-hide_n_show-display"]').val();
                    return '[hide_n_show display="' + text + '"]Replace this text with your toggle content.[/hide_n_show]';
                    break;

                case 'button':
                    var text = $('input[name="grueneext-button-link"]').val(),
                        color = $('select[name="grueneext-button-color"]').val();
                    return '[button link="' + text + '" color="' + color + '"]Replace this text with the text on your button.[/button]';
                    break;

                case 'progressbar':
                    var max = $('input[name="grueneext-progressbar-max"]').val(),
                        value = $('input[name="grueneext-progressbar-value"]').val(),
                        unit = $('input[name="grueneext-progressbar-unit"]').val(),
                        show_value = $('select[name="grueneext-progressbar-show_value"]').val(),
                        color = $('select[name="grueneext-progressbar-color"]').val();
                    return '[progressbar max="' + max + '" value="' + value + '" unit="' + unit + '" show_value="' + show_value + '" color="' + color + '"]';
                    break;

                default:
                    return false;
            }
        };

        this.validUrl = function validUrl(url) {
            return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        };


    }

    /**
     * fires after DOM is loaded
     */
    $(document).ready(function () {
        ShortCodeGen.init();

    });

    /**
     * fires on resizeing of the window
     */
    jQuery(window).resize(function () {

    });

})(jQuery);