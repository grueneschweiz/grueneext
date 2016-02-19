/**
 * jQuery wrapper
 */
( function( $ ) {
	var Progressbar = new Progressbar();
     var Hide_n_show = new Hide_n_show();
	
	/**
	 * handles all the functionality of the short code generator
	 */
	function Progressbar() {
		
		var self = this;
		
		/**
		 * initiatelize the progressbar
		 */
		this.init = function init() {
			
               var max, 
                   value, 
                   $progressbars = $( 'div.grueneext_progressbar' );
                              
               $progressbars.each( function() {
                    $( this ).progressbar({
                         value : parseFloat( $( this ).attr( 'data-value' ) ),
                         max   : parseFloat( $( this ).attr( 'data-max' ) )
                    });
               });
		};
	}
     
     /**
     * adds hide and show functionality
     */
     function Hide_n_show() {
          
          var self = this;
          
          /**
		 * initialize hide_n_show
		 */
		this.init = function init() {
			$.each( $( 'div.grueneext_hide_n_show' ), function( index, element ) {
				$( element ).find( 'div.grueneext_hide_n_show_display' ).click( function( event ) {
					event.preventDefault();
					$( this ).toggleClass( 'grueneext_hide_n_show_closed' )
					         .toggleClass( 'grueneext_hide_n_show_open' );
					$( element ).find( 'div.grueneext_hide_n_show_content' ).slideToggle( 'fast' );
				} );
			} );
			
			$( 'div.grueneext_hide_n_show_content' ).hide();
			$( 'div.grueneext_hide_n_show_display' ).addClass( 'grueneext_hide_n_show_closed' );
		};
     }
     
	/**
	 * fires after DOM is loaded
	 */
	$( document ).ready(function() {
		Progressbar.init();
          Hide_n_show.init();
	});
	
	/**
	 * fires on resizeing of the window
	 */
	jQuery( window ).resize( function() {
		
	});
	
} )( jQuery );