<?php
/**
 * Validation php page that calls validation functions for the add room form.
 * Included in the manage hotel rooms page for adding hotel rooms.
 *
 * Created by: LeYing
 * Date: 25/09/2015
 * Last Modified: 29/09/2015
 */

$fields = array(
    "roomNum",
    "roomType",
    "description",
    "price"
);

if (isset($_POST['addRoomSubmit'])) {
    $newRoomFormOk = true;

    foreach($fields as $field) {
        if(isset($_POST[$field])) {
            $_SESSION[$field] = htmlspecialchars($_POST[$field]);
        }
    }

    /* Validate Add Room Form. */
    if (strEmpty($_POST['roomNum'])) {
        $errormsg["roomNum"] = "<br> * Please enter a room number. (Max: 4 digits)";
        $newRoomFormOk = false;
    } else if (!isDigits($_POST['roomNum'])) {
        $errormsg["roomNum"] = "<br> * Room number should be digits only.";
        $newRoomFormOk = false;
    }
    if ($hManager->countHotelRooms() > 0) {
        for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
            $hotelRoom = $hManager->getHotelRoom($i);
            $roomNum = (string)$hotelRoom->number;
            if ($_POST['roomNum'] === $roomNum) {
                $errormsg["roomNum"] = "<br> * Room number is already taken.";
                $newRoomFormOk = false;
                break;
            }
        }
    }

    if (strEmpty($_POST['description'])) {
        $errormsg["description"] = "<br> * Please enter a room description.";
        $newRoomFormOk = false;
    }

    if (strEmpty($_POST['price'])) {
        $errormsg["price"] = "<br> * Please enter a price.";
        $newRoomFormOk = false;
    } else if (isDigits($_POST['price']) && !isMoneyFormat($_POST['price'])) {
        $_SESSION['price'] = addEndZeroes($_POST['price']);
    } else if (!isMoneyFormat($_POST['price'])) {
        $errormsg['price'] = "<br> * Price should be digits and rounded to 2 decimal places.";
        $newRoomFormOk = false;
    }
}

/* If successful validation, add th room to xml. */
if ($newRoomFormOk) {
    $newRoomFormOk = false;
    $hManager->addHotelRoom($_SESSION['roomNum'], $_SESSION['roomType'],
        htmlspecialchars($_SESSION['description']), $_SESSION['price']);
    $hManager->save();
    foreach($fields as $field) {
        unset($_SESSION[$field]);
    }
}

?>