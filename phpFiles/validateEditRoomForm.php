<?php
/**
 * Validation php page that calls validation functions for the edit room form.
 * Included in the edit rooms page for editing info on existing hotel rooms.
 *
 * Created by: LeYing
 * Date: 25/09/2015
 * Last Modified: 29/09/2015
 */

if (isset($_SESSION['edit'])) {
    $fields = array(
        "roomNum",
        "roomType",
        "description",
        "price"
    );

    /* Validate Edit Room Form. */
    if (isset($_POST['editRoomSubmit'])) {
        $editFormOk = true;
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $_SESSION[$field] = htmlspecialchars($_POST[$field]);
            }
        }
        if (strEmpty($_POST['roomNum'])) {
            $errormsg["roomNum"] = "<br> * Please enter a room number. (Max: 4 digits)";
            $editFormOk = false;
        } else if (!isDigits($_POST['roomNum'])) {
            $errormsg["roomNum"] = "<br> * Room number should be digits only.";
            $editFormOk = false;
        }
        if ($hManager->countHotelRooms() > 0) {
            $oldHotelRoom = $hManager->getHotelRoom($_SESSION['edit']);
            $oldRoomNum = (string)$oldHotelRoom->number;
            for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
                $hotelRoom = $hManager->getHotelRoom($i);
                $roomNum = (string)$hotelRoom->number;
                if ($_POST['roomNum'] === $roomNum && $_POST['roomNum'] !== $oldRoomNum) {
                    $errormsg["roomNum"] = "<br> * Room number is already taken.";
                    $editFormOk = false;
                    break;
                }
            }
        }

        if (strEmpty($_POST['description'])) {
            $errormsg["description"] = "<br> * Please enter a room description.";
            $editFormOk = false;
        }

        if (strEmpty($_POST['price'])) {
            $errormsg["price"] = "<br> * Please enter a price.";
            $editFormOk = false;
        } else if (isDigits($_POST['price']) && !isMoneyFormat($_POST['price'])) {
            $_SESSION['price'] = addEndZeroes($_POST['price']);
        } else if (!isMoneyFormat($_POST['price'])) {
            $errormsg['price'] = "<br> * Price should be digits and rounded to 2 decimal places.";
            $editFormOk = false;
        }
    }

    /* Successful validation, change old values to new values in xml. */
    if ($editFormOk) {
        $oldHotelRoom = $hManager->getHotelRoom($_SESSION['edit']);
        $oldRoomNum = (string)$oldHotelRoom->number;
        $oldHotelRoom->number = $_SESSION['roomNum'];
        $oldHotelRoom->roomType = $_SESSION['roomType'];
        $oldHotelRoom->description = $_SESSION['description'];
        $oldHotelRoom->pricePerNight = $_SESSION['price'];
        $hManager->save();
        $bManager = new BookingsManager($conf['bookingXML']);
        $bookedRooms = $bManager->getBookingByRoom($oldRoomNum);
        foreach($bookedRooms as $bookedRoom) {
            $bookedRoom->number = $_SESSION['roomNum'];
        }
        $bManager->save();

        foreach ($fields as $field) {
            unset($_SESSION[$field]);
        }
        unset($_SESSION['edit']);

        header("Location:manageHotelRooms.php");
        exit;
    }
}
?>