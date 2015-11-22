/**
 * Module that builds a check-in and a
 * check-out calendar for the reservations
 * page using jquery datepicker.
 *
 * Created by: LeYing Tran
 * Date: 15/09/2015
 * Last modified: 15/09/2015
 */

var Calendar = (function () {
    "use strict";

    /* Public interface to the module */
    var pub = {};

    /**
     * Function that sets up the checkin and checkout
     * calendars with desired display settings. Also
     * makes sure that checkout calendar always starts
     * a day later than the selected checkin date.
     */
    function setupCal() {
        var date1, newDate;
        $("#checkin").datepicker({
            inline: true,
            firstDay: 0,
            showOtherMonths: true,
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dateFormat: 'dd-m-yy',
            minDate: 0,
            onSelect: function (date) {
                date1 = $("#checkin").datepicker('getDate');
                date = new Date(Date.parse(date1));
                date.setDate(date.getDate() + 1);
                newDate = date.toDateString();
                newDate = new Date(Date.parse(newDate));
                $("#checkout").datepicker("option", "minDate", newDate);
            }
        });

        $("#checkout").datepicker({
            inline: true,
            firstDay: 0,
            showOtherMonths: true,
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dateFormat: 'dd-m-yy',
            minDate: 1
        });
    }

    /* Public setup function that calls the calendar setup */
    pub.setup = function () {
        setupCal();
    };

    /* Expose the public interface */
    return pub;
}());

/* Call setup when document is ready */
$(document).ready(Calendar.setup);