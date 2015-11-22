<?php
/**
 * Created by: LeYing
 * Date: 27/09/2015
 */

if (isset($_POST['delete_room_list'])) {
    $deleteArray = $_POST['delete_room_list'];
    $okDeleteArray = array();
    $errorStr = "";
    foreach ($deleteArray as $deleteItem) {
        $roomNum = (int)$hManager->getHotelRoom($deleteItem)->number;
        $booking = $bManager->getBookingByRoom($roomNum);
        if (count($booking) > 0) {
            for ($i = 0; $i < count($booking); $i++) {
                $name = $booking[$i]->name;
                $checkin = $booking[$i]->checkin->day . "-" . $booking[$i]->checkin->month . "-" . $booking[$i]->checkin->year;
                $checkout = $booking[$i]->checkout->day . "-" . $booking[$i]->checkout->month . "-" . $booking[$i]->checkout->year;
                $errorStr .= checkBookedIn($roomNum, $name, $checkin, $checkout);
            }
            $errormsg[$roomNum . "Error"] = $errorStr;
        }
        if ($errormsg[$roomNum . "Error"] === "") {
            array_push($okDeleteArray, $deleteItem);
        }
    }
    $hManager->deleteHotelRoom($okDeleteArray);
    $hManager->save();

    //header("Location: manageHotelRooms.php#editRooms");
}
?>


