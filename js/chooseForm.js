/**
 * On the click of the admin options buttons,
 * change the location of the window to the desired
 * page.
 *
 * Created by LeYing
 * Date: 22/09/2015.
 */

var ChooseForm = (function() {
    "use strict";
    var pub = {};

    pub.setup = function() {
        $("#manBookingsLink").on("click", function() {
            window.location.href = "manageBookings.php";
            return false;
        });
        $("#manHotelsLink").on("click", function() {
            window.location.href = "manageHotelRooms.php";
            return false;
        });
    };

    /* Expose public interface. */
    return pub;
}());

/* Calls the setup function for Return when the document is ready */
$(document).ready(ChooseForm.setup);