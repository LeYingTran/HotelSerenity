<?php
/**
 * PHP page that is included in the reservations page.
 * Used to take the user's proposed dates, checks them against
 * existing bookings then generates the available hotel rooms for
 * the proposed time period.
 *
 * Created by: LeYing
 * Date: 18/09/2015
 * Last Modified: 29/09/2015
 */
    $checkin = rearrangeDate($_SESSION['checkin']);
    $checkout = rearrangeDate($_SESSION['checkout']);
    $name = $_SESSION['guestName'];

    $bManager = new BookingsManager($conf['bookingXML']);
    $hManager = new HotelManager($conf['hotelXML']);
    $bManager->reload();
    $hManager->reload();


    /* Check if proposed dates clash with existing booked dates. */
    $booked = array();
    if ($bManager->countBookings() > 0) {
        for ($i = 0; $i < $bManager->countBookings(); $i++) {
            $booking = $bManager->getBooking($i);
            $clash = false;
            if ($booking->checkin->month < 10) {
                $bookedIn = $booking->checkin->year . "0" . $booking->checkin->month . $booking->checkin->day;
                $bookedOut = $booking->checkout->year . "0" . $booking->checkout->month . $booking->checkout->day;
            } else {
                $bookedIn = $booking->checkin->year .$booking->checkin->month . $booking->checkin->day;
                $bookedOut = $booking->checkout->year . $booking->checkout->month . $booking->checkout->day;
            }
            $clash = checkClash($checkin, $checkout, $bookedIn, $bookedOut);
            if ($clash) {
                $bookedRoom = $booking->number;
                array_push($booked, $bookedRoom);
            }
        }
    }

    /* Only print available rooms. */
    if ($hManager->countHotelRooms() > 0) {
        if (count($booked) >= $hManager->countHotelRooms()) {
            echo "<p>There are no available rooms for your selected dates.";
            exit;
        }

        $str = "<table id=\"availableRooms\"><tr><th>Room</th><th>Type</th><th>Price</th><th>Book</th></tr>";
        unset($_SESSION['book_list']);
        for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
            $hotelRoom = $hManager->getHotelRoom($i);
            $roomNum = (string)$hotelRoom->number;
            $roomType = $hotelRoom->roomType;
            $price = $hotelRoom->pricePerNight;
            $book = "<input type=\"checkbox\" name=\"book_list[]\" value=\"" . $roomNum . "\" class=\"book\">";

            if(!in_array($roomNum, $booked)) {
                $str .= "<tr><td>$roomNum</td><td>$roomType</td><td>$$price</td><td>$book</td></tr>";
            }
        }
        $str .= "<tr><td></td><td></td><td></td><td><input type=\"submit\" class=\"ok\" name=\"ok\" value=\"Book\"></td></tr>";
        $str .= "</table>";
        echo $str;
    } else {
        echo  "There are currently no rooms available.";
    }

?>