/**
 * On the click of the return button, set the
 * page location to the reservations page.
 *
 * Created by LeYing
 * Date: 20/09/2015.
 * Last modified: 29/09/2015
 */
var Back = (function() {
        "use strict";
        var pub = {};

    pub.setup = function() {
        $(".back").on("click", function() {
            window.location.href = "reservations.php#reserve";
            return false;
        });
    };

    /* Expose public interface. */
    return pub;
}());

/* Calls the setup function for Return when the document is ready */
$(document).ready(Back.setup);
