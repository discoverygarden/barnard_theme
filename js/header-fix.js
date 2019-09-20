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
                    var admin_menu_height = $('#admin-menu-wrapper').outerHeight(true);
                    var admin_menu = $('#admin-menu-wrapper').length;

                    if (admin_menu > 0) {
                        navbar_height += admin_menu_height;
                    }

                    $('body').css('margin-top', (navbar_height + "px"));

                    if ($('.front').length > 0) {
                        var multiplyer = 2;

                        if (admin_menu > 0) {
                            multiplyer = 1;
                        }
                        navbar_height += ($('#header').outerHeight(true) * multiplyer);
                        if ($(window).width() < 540) {
                            navbar_height = 120;
                        }
                        $('#content').css('margin-top', (navbar_height + "px"));
                    }
                };

                $(window).resize(function() {
                    outter_height_fix();
                });

                outter_height_fix();

            });
        }
    };
})(jQuery, Drupal, this, this.document);