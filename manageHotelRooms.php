<?php
/**
 * Manage Hotel Rooms page where admin staff are able to
 * add a new hotel room, delete existing rooms or select a room
 * whose info needs editing.
 *
 * Created by: LeYing
 * Date: 24/09/2015
 * Last modified: 29/09/2015
 */

session_start();
?>

<!DOCTYPE html>

<html>
<?php
$styleList = array("bootstrap-3.3.5-dist/css/bootstrap.min.css", "bootstrap-3.3.5-dist/css/bootstrap-theme.min.css", "jquery-ui/jquery-ui.min.css", "css/style.css");

$scriptList = array("jQuery/jquery-1.11.3.min.js","jquery-ui/jquery-ui.min.js", "bootstrap-3.3.5-dist/js/bootstrap.min.js");
include("phpFiles/header.php");
?>

<header style='background-image:url("images/bg-clock.jpg")'>
    <h1>Hotel Serenity</h1>
    <h2>Administration</h2>
</header>

<div id="main">
    <h3>Manage Hotel Rooms</h3>
    <?php
    include("phpFiles/adminNav.php");
    $newRoomFormOk = false;
    $hotelRoomsList = false;
    $editFormOk = false;
    $bManager = new BookingsManager($conf['bookingXML']);
    $hManager = new HotelManager($conf['hotelXML']);
    if (isset($_POST['manBookingsLink'])) {
        $bManager->reload();
        $hManager->reload();
    }
    $errormsg = array(
        "roomNum" => "",
        "description" => "",
        "price" => ""
    );
    include("phpFiles/validateAddRoomForm.php");

    /* Display Add room form. */
    if(!$newRoomFormOk) {
        ?>
        <form id="addRoomForm" method="POST" action="#addRoomForm" novalidate>
            <h3>Add A Room</h3>
            <p>(To existing hotel rooms list.)</p>
            <br>
            <label for="roomType">Room Type:</label>
            <select name="roomType" id="roomType">
                <option <?php if(checkSelect('roomType') === "Single") { echo "selected";}  else ?> value="Single">Single</option>
                <option <?php if(checkSelect('roomType') === "Twin") { echo "selected";}  else ?> value="Twin">Twin</option>
                <option <?php if(checkSelect('roomType') === "Double") { echo "selected";}  else ?> value="Double">Double</option>
                <option <?php if(checkSelect('roomType') === "King") { echo "selected";}  else ?> value="King">King</option>
                <option <?php if(checkSelect('roomType') === "Triple Room") { echo "selected";}  else ?> value="Triple Room">Triple Room</option>
            </select>
            <br>
            <label for="roomNum">Room Number:</label>
            <input type="text" name="roomNum" id="roomNum" value="<?php storeField("roomNum"); ?>" maxlength="4" required>
            <span class="errors" id="roomNumError"><?php echo $errormsg['roomNum']; ?></span>
            <br>
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" value="<?php storeField("description"); ?>" required>
            <span class="errors" id="descriptionError"><?php echo $errormsg['description']; ?></span>
            <br>
            <label for="price">Price Per Night: $</label>
            <input type="text" name="price" id="price" placeholder="0.00" maxlength="9" value="<?php storeField("price"); ?>" required>
            <span class="errors" id="priceError"><?php echo $errormsg['price']; ?></span>
            <br>
            <input type="submit" name="addRoomSubmit" id="addRoomSubmit" value="Submit">
        </form>
        <?php
    }

    /* Set error message array for each hotel room. */
    if(!$hotelRoomsList) {
        if ($hManager->countHotelRooms() > 0) {
            for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
                $hotelRoom = $hManager->getHotelRoom($i);
                $roomError = $hotelRoom->number . "Error";
                $errormsg[$roomError] = "";
            }
        }

        /* If confirm is pressed, delete the deletable rooms. */
        if (isset($_POST['confirm'])) {
            if (isset($_SESSION['okDeleteArray'])) {
                $hManager->deleteHotelRoom($_SESSION['okDeleteArray']);
                $hManager->save();
                unset($_SESSION['okDeleteArray']);
            }
        }

        /* If go to bookings is pressed, redirect user to bookings page. */
        if (isset($_POST['goBookings'])) {
            header("Location:manageBookings.php#displayBookings");
            exit;
        }

        /* Get checked delete rooms and check against booking dates to see if room is deletable. */
        if (isset($_POST['deleteRoomsSubmit'])) {
            if (isset($_POST['delete_room_list'])) {
                $_SESSION['delete_room_list'] = $_POST['delete_room_list'];
                $deleteArray = $_POST['delete_room_list'];
                $okDeleteArray = array();
                $errorStr = "";
                foreach ($deleteArray as $deleteItem) {
                    $roomNum = $hManager->getHotelRoom($deleteItem)->number;
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
                    $_SESSION['okDeleteArray'] = $okDeleteArray;
                }
            }

            /* Display confirm delete pop up box. */
            ?>
            <br>
            <section id="confirmDelete">
                    <h3>Confirm Delete</h3>
                    <form action="#editRooms" method="POST">
                        <?php
                        /* Confirmation Printing */
                        if (isset($_SESSION['okDeleteArray']) && count($_SESSION['okDeleteArray']) > 0) {
                            $str = "<p>You are about to delete: </p>";
                            $str .= "<table class=\"deleteConf\">";
                            foreach ($_SESSION['okDeleteArray'] as $delItem) {
                                $hotel = $hManager->getHotelRoom($delItem);
                                $room = $hotel->number;
                                $type = $hotel->roomType;
                                $cost = $hotel->pricePerNight;
                                $str .= "<tr><td>$room</td><td>$type</td><td>$$cost</td></tr>";
                            }
                            $str .= "</table><br>";
                            if (count($_POST['delete_room_list']) > count($okDeleteArray)) {
                                $str .= "<p><span class=\"errors\">Check hotel rooms table for room deletion errors.</span></p>";
                            }
                            $str .= "<input type=\"submit\" name=\"back\" class=\"back\" value=\"Cancel\">";
                            $str .= "<input type=\"submit\" name=\"confirm\" value=\"Confirm\">";
                        } else if (isset($_SESSION['delete_room_list']) && count($_SESSION['delete_room_list']) > 0) {
                            $str = "<p><span class=\"errors\">Check hotel rooms table for room deletion errors.</span></p>";
                            $str .= "<input type=\"submit\" name=\"goBookings\" id=\"goBookings\" value=\"Go to Bookings\">";
                        } else {
                            $str = "<p>No rooms are selected.</p>";
                            $str .= "<input type=\"submit\" value=\"OK\">";
                        }
                        echo $str;
                        unset($_SESSION['delete_room_list']);
                        ?>
                    </form>
                </section>
            <?php
        }

        /* If the edit button is pressed, redirect to edit rooms page. */
        if (isset($_POST['editRoomsSubmit'])) {
            if (isset($_POST['edit'])) {
                $_SESSION['edit'] = $_POST['edit'];
                header("Location: editRooms.php#editRoomForm");
                exit;
            } else {
                header("Location: manageHotelRooms.php#editRooms");
                exit;
            }
        }

        /* Display all hotel rooms. */
        ?>
        <div id="editRooms">
            <h3>Hotel Rooms</h3>
            <form id="hotelRoomsForm" action="#confirmDelete" method="POST">
                <?php
                if ($hManager->countHotelRooms() > 0) {
                    $str = "<table id=\"hotelRoomsTable\"><tr><th>Room</th><th>Type</th><th>Description</th><th>Price</th><th>Delete</th><th>Edit</th></tr>";
                    for ($i = 0; $i < $hManager->countHotelRooms(); $i++) {
                        $hotelRoom = $hManager->getHotelRoom($i);
                        $number = $hotelRoom->number;
                        $roomType = $hotelRoom->roomType;
                        $description = $hotelRoom->description;
                        $price = $hotelRoom->pricePerNight;
                        $roomError = $number . "Error";
                        $delete = "<input type=\"checkbox\" name=\"delete_room_list[]\" value=\"" . $i . "\" class=\"deleteRoom\">";
                        $edit = "<input type=\"radio\" name=\"edit\" class=\"edit\" value=\"" . $i . "\">";
                        $span = "<span class=\"errors\" id=\"$roomError\">" . $errormsg[$roomError] . "</span>";
                        $str .= "<tr><td>$number</td><td>$roomType</td><td>$description</td><td>$$price</td><td>$delete</td><td>$edit</td></tr>";
                        $str .= "<tr><td colspan='6'>$span</td></tr>";
                    }
                    $str .= "<tr><td></td><td></td><td></td><td></td><td><input type=\"submit\" name=\"deleteRoomsSubmit\" id=\"deleteRooms\" value=\"Delete\">" .
                        "</td><td><input type=\"submit\" name=\"editRoomsSubmit\" id=\"editHotelRooms\" value=\"Edit\"></td></tr>";
                    $str .= "</table>";
                    echo $str;
                } else {
                    echo "<p>There are currently no hotel rooms.</p>";
                }
                ?>
            </form>
        </div>
    <?php } ?>
</div>

<?php include("phpFiles/footer.php"); ?>
</body>
</html>
