<?php
/**
 * Group of functions that can be used throughout any of the website pages.
 * Useful functions for form validation, printing etc.
 *
 * Created by: LeYing Tran
 * Date: 17/09/2015
 * Last Modified: 02/09/2015
 */


    /**
     * Function that removes white space and checks whether the string is empty.
     * @param $str string The string to be checked if empty.
     * @return bool Whether the string is empty.
     */
    function strEmpty($str) {
        return strlen(trim($str)) == 0;
    }


    /**
     * Function that checks whether an input string is only made up of
     * letters, spaces, hyphens, dots, commas or apostrophes.
     * Used to check a name or description.
     * @param $str string The input string to be matched.
     * @return int Whether the string matches the regex.
     */
    function isLetters($str) {
        $pattern = '/^([A-Za-z]+[\-\'\ \.\,]*)+$/';
        return preg_match($pattern, $str);
    }


    /**
     * Function that takes a string and checks if the string is made up
     * of digits.
     * @param $str string The string to be checked if completely made up of digits.
     * @return int Whether the string is made up of digits or not.
     */
    function isDigits($str) {
        $pattern = '/^[0-9]+$/';
        return preg_match($pattern, $str);
    }


    /**
     * Function that takes a string and checks if the given string is in
     * money format.
     * @param $str string The price string to be checked.
     * @return int Whether the string is in a money format of 2 decimal places.
     */
    function isMoneyFormat($str) {
        $pattern = '/^[0-9]+\.[0-9]{2}$/';
        return preg_match($pattern, $str);
    }


    /**
     * Function that adds '.00' to the end of a given string.
     * Used for price if the user does not input the 2 decimal places.
     * @param $str string The string the two zeroes will be added to.
     * @return string The new string with the '.00'.
     */
    function addEndZeroes($str) {
        $str = $str . ".00";
        return $str;
    }


    /**
     * Function that checks whether a string matches the expected format
     * for a date input.
     * @param $str string The input string to be checked if in date format.
     * @return int Whether the input matches the expected date format.
     */
    function isDate($str) {
        $pattern = '/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])-([1-9]|1[0-2])-[0-9]{4}$/';
        return preg_match($pattern, $str);
    }


    /**
     * Function that stores a field value so the user input can be remembered
     * if the form is incorrectly submitted.
     * @param $textName string The input field name the stored value is to go in.
     */
    function storeField($textName) {
        if (isset($_SESSION["$textName"])) {
            $value = $_SESSION["$textName"];
            echo "$value";
        }
    }


    /**
     * Function that checks what was stored in session for the select input
     * and sets the option as selected if it finds an existing session value.
     * @param $selectName string The name of the select input.
     * @return string The value in the session.
     */
    function checkSelect($selectName) {
        if(isset($_SESSION[$selectName])) {
            $value = $_SESSION[$selectName];
            return $value;
        }
    }


    /**
     * Function that rearranges a date from format dd-m-yyyy to an integer
     * made up of concatenation of the year followed by month and then day.
     * @param $date string The date string to be rearranged.
     * @return string The large number string.
     */
    function rearrangeDate($date) {
        $receivedDate = array_pad(explode("-", $date, 3), 3, null);
        if ($receivedDate[1] <= "9") {
            $changedDate = $receivedDate[2] . "0" .$receivedDate[1] . $receivedDate[0];
        } else {
            $changedDate = $receivedDate[2] . $receivedDate[1] . $receivedDate[0];
        }
        return $changedDate;
    }


    /**
     * Function that takes a year number and determines whether the year is
     * a leap year or not.
     * @param $year int The string year to be checked.
     * @return bool Whether the year is a leap year or not.
     */
    function leapYear($year) {
        return (($year % 4 === 0) && ($year % 100 !== 0)) || ($year % 400 === 0);
    }


    /**
     * Function that checks whether a date is valid.
     * Checks whether date occurs in the past, number of days depending on month
     * and whether it is a leap year.
     * @param $date string The date string to be checked.
     * @return bool Whether the proposed date is a valid date or not.
     */
    function validDate($date) {
        if (isDate($date)) {
            $currentDay = array_pad(explode('-', date('d-m-Y'), 3), 3, null);
            $today = $currentDay[2] . $currentDay[1] . $currentDay[0];
            $splitDate = array_pad(explode("-", $date, 3), 3, null);
            if (rearrangeDate($date) >= $today) {
                $bigMonths = array('1', '3', '5', '7', '8', '10', '12');
                $smallMonths = array('4', '6', '9', '11');
                if ($splitDate[2] >= 2015) {
                    if (in_array($splitDate[1], $bigMonths)) {
                        return (int)$splitDate[0] <= 31;
                    } else if (in_array($splitDate[1], $smallMonths)) {
                        return (int)$splitDate[0] <= 30;
                    } else if ($splitDate[1] === "2") {
                        if (leapYear((float)$splitDate[2])) {
                            return (int)$splitDate[0] <= 29;
                        }
                        return (int)$splitDate[0] <= 28;
                    }
                }
            }
        }
        return false;
    }


    /**
     * Function that checks two dates to determine whether
     * check out date occurs before check in or is on the same day
     * as check in.
     * @param $checkin string The potential check in date to be checked.
     * @param $checkout string The potential check out date to be checked.
     * @return string Notification for result of the checking.
     */
    function checkDates($checkin, $checkout) {
        $indate = rearrangeDate($checkin);
        $outdate = rearrangeDate($checkout);
        if (($outdate - $indate) < 0) {
            return "<br> * Check-out date should be after check-in date.";
        } else if (($outdate - $indate) === 0) {
            return "<br> * Please stay a minumum of one night";
        }
        return "";
    }


    /**
     * Checks dates to determined whether the times will collide.
     * @param $checkIn string The potential check in date to be checked.
     * @param $checkOut string The potentical check out date to be checked.
     * @param $bookedIn string The existing booked in date.
     * @param $bookedOut string The existing booked out date.
     * @return bool Whether the potential dates and existing booking dates clash.
     */
    function checkClash($checkIn, $checkOut, $bookedIn, $bookedOut) {
        if((($checkOut <= $bookedIn) && ($checkIn < $bookedIn)) || (($checkIn >= $bookedOut) && ($checkOut > $bookedOut))) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Checks the booked dates with current dates to determine whether a room
     * is occupied or will be occupied in the future and returns the appropriate
     * notification.
     * @param $roomNum int The room number that is being checked.
     * @param $name string The guest name for the room being checked.
     * @param $checkin string Guest's check in date to be checked.
     * @param $checkout string Guest's check out date to be checked.
     * @return string Notification string if trying to delete room.
     */
    function checkBookedIn($roomNum, $name, $checkin, $checkout) {
        $currDay = array_pad(explode('-', date('d-m-Y'), 3), 3, null);
        $today = $currDay[2] . str_replace("0", "", $currDay[1]) . $currDay[0];
        $indate = rearrangeDate($checkin);
        $outdate = rearrangeDate($checkout);
        if ($indate <= $today && $outdate > $today) {
            return "* Room $roomNum is currently booked out by $name. Cannot delete room.<br>";
        } else if ($indate > $today) {
            return "* Room $roomNum is booked by $name for the $checkin. Please relocate before deleting.<br>";
        }
    }


    /**
     * Function that checks if a buttons name matches a string to the
     * page's name. If they are the same, the string 'disabled' is
     * returned to disable a button.
     * @param $pageName string Page name string to be matched to.
     */
    function disableButton($pageName) {
        if(basename($_SERVER['PHP_SELF'], ".php") == $pageName) {
            echo "disabled";
        } else {
            echo "";
        }
    }


    /**
     * Function that takes an array of hotel room information from the hotel xml and
     * a bed description from the roomTypes xml, and turns it into a table of information
     * for the guestrooms php page.
     * @param $roomArray array Array of information from the hotel xml.
     * @param $description string Description of bed type from roomTypes xml.
     * @return string String of information to be displayed on guestrooms page.
     */
    function printRooms ($roomArray, $description) {
        if (count($roomArray) > 0) {
            $str = "<table><tr><th>Room</th><th>Description</th><th>Bed</th><th>Price</th></tr>";
            foreach ($roomArray as $room) {

                $str .= "<tr><td>$room->number</td>" .
                    "<td>$room->description</td>" .
                    "<td>$description</td>" .
                    "<td>$$room->pricePerNight</td>" .
                    "</tr>";
            }
            $str .= "</table>";
            return $str;
        } else {
            return "<p>There are currently no rooms of this type.</p>";
        }
    }

?>