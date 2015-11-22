<?php
/**
 * Created by: LeYing
 * Date: 19/09/2015
 */

if(isset($_POST['book_list']) && count($_POST['book_list']) > 0) {
    $str = "<p>Your Booking:</p>" . "<br>";
    $str .= "<p><b>" . $_SESSION['guestName'] . "</b><br>";
    $str .= "<b>Check-in: </b>" . $_SESSION['checkin'] . "<br>";
    $str .= "<b>Check-out: </b>" . $_SESSION['checkout'] . "<br>";

    $hManager = new HotelManager(($conf['hotelXML']));

    if ($hManager->countHotelRooms() > 0) {
        $totalPrice = 0;
        $str .= "<table id=\"bookingsConf\">";
        for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
            $hotelRoom = $hManager->getHotelRoom($i);
            $roomNum = $hotelRoom->number;
            $roomType = $hotelRoom->roomType;
            $price = doubleval($hotelRoom->pricePerNight);
            if (in_array($roomNum, $_POST['book_list'])) {
                $str .= "<tr><td>$roomNum</td><td>$roomType</td><td>$$price</td></tr>";
                $totalPrice += $price;
            }
        }
        $str .= "</table><br>Total Price: $" . $totalPrice . "</p>";
        $str .= "<input type=\"submit\" name=\"back\" class=\"back\" value=\"Cancel\">";
        $str .= "<input type=\"submit\" name=\"confirm\" value=\"Confirm\">";
        $str .= "</form>";
    } else {
        $str = "There are no rooms available.";
    }
    echo $str;
}