/**
 * @file
 * Drupal behaviour to fix the header when the admin menu is present.
 */

/**
 * Drupal behaviour to fix layout padding of the header vs body.
 */
(function ($, Drupal, window) {
    Drupal.behaviors.mobile_header_fix = {
        attach: function() {
            $(window).load(function() {
                var outter_height_fix = function() {
                    var navbar_height = 0;

                    if ($('#admin-menu-wrapper').length > 0) {
                        navbar_height += $('#admin-menu-wrapper').outerHeight(true);
                    }

                    console.log(navbar_height);
                    navbar_height += "px";
                    $('body').css('padding-top', navbar_height);
                };

                $(window).resize(function() {
                    outter_height_fix();
                });

                outter_height_fix();

            });
        }
    };
})(jQuery, Drupal, this, this.document);