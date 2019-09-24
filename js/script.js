/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

(function ($, Drupal, window, document, undefined) {
	// Window load event used just in case window height is dependant upon images
	$(window).load(function() {
       var footerHeight = 0,
       $footer = $("#footer");

       function positionFooter() {

           footerHeight = $footer.height();// - 400
           var height_offset = 0;
           if ($(window).width() < 625) {
               height_offset = 400;
               height_offset = 0;
           }
           if ( ($(document.body).height()+(footerHeight)) < ($(window).height() - height_offset)) {
               //must stick to bottom
               $footer.css({
                    position: "fixed",
                    bottom: 0,
                    left:0,
                    right:0
               })
           } else {
               $footer.attr("style", "");
           }
           // alert($(window).width());
       }

       $(window).resize(positionFooter);
        positionFooter();
        setTimeout(positionFooter(), 5000);
	});
	
    //Adds placeholder text in the islandora solr simple search form
	$(document).ready(function () {
	  	$('#islandora-solr-simple-search-form input.form-text').attr('placeholder', 'Search...');
	});

})(jQuery, Drupal, this, this.document);
