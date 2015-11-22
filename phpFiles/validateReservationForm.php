<?php
/**
 * Validation php page that calls validation functions for the reservations form.
 * Included in the reservations page for checking user input.
 *
 * Created by: LeYing
 * Date: 24/09/2015
 * Last Modified: 29/09/2015
 */

    $errormsg = array(
        "checkin" => "",
        "checkout" => "",
        "guestName" => ""
    );

    if (isset($_POST['checkAvailability'])) {
        $reservationFormOk = true;

        $fields = array(
            "checkin",
            "checkout",
            "guestName"
        );

        foreach ($fields as $field) {
            if(isset($_POST[$field])) {
                $_SESSION[$field] = htmlentities($_POST[$field]);
            }
        }

        /* Validate reservations form. */
        if (strEmpty($_POST['checkin'])) {
            $errormsg["checkin"] = "<br> * Please select a date.";
            $reservationFormOk = false;
        } else if (!validDate($_POST['checkin'])) {
            $errormsg["checkin"] = "<br> * Please check date is not in the past and of correct format.";
            $reservationFormOk = false;
        }

        if (strEmpty($_POST['checkout'])) {
            $errormsg["checkout"] = "<br> * Please select a date.";
            $reservationFormOk = false;
        } else if (!validDate($_POST['checkout'])) {
            $errormsg['checkout']  = "<br> * Please check date is not in the past and of correct format.";
            $reservationFormOk = false;
        } else if (!strEmpty($_POST['checkin']) && !strEmpty($_POST['checkout'])) {
            $errormsg['checkout'] = checkDates($_POST['checkin'], $_POST['checkout']);
            if ($errormsg['checkout'] !== "") {
                $reservationFormOk = false;
            }
        }

        if (strEmpty($_POST['guestName'])) {
            $errormsg["guestName"] = "<br> * Please enter a name to book under.";
            $reservationFormOk = false;
        } else {
            if (!isLetters($_POST['guestName'])) {
                $errormsg["guestName"] = "<br> * Check name is made up of characters.";
                $reservationFormOk = false;
            }
        }
    }

    /* If validation is successful, set display flag to true to display available rooms. */
    if ($reservationFormOk) {
        $reservationFormOk = false;
        $displayAvailable = true;
    }

?>