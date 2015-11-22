<?php
/**
 * Edit Rooms page which displays the admin navigation
 * and the edit form for editing an existing hotel room.
 *
 * Created by: LeYing Tran
 * Date: 27/09/2015
 * Last Modified: 29/09/2015
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
    <h3>Edit Rooms</h3>
    <?php
    include("phpFiles/adminNav.php");
    ?>
    <div id="editRoomForm">
        <h3>Edit Room</h3>
        <?php
        $editFormOk = false;
        $hManager = new HotelManager($conf['hotelXML']);
        $errormsg = array(
            "roomNum" => "",
            "description" => "",
            "price" => ""
        );

        /* Validate Edit Form. */
        include("phpFiles/validateEditRoomForm.php");

        /* If nothing is selected. */
        if (!isset($_SESSION['edit'])) {
            echo "<p>Please select a room to edit from the <i>Manage Rooms</i> page.</p>";
        } else {

            /* Set the old hotel room details as current values of Edit Room Form. */
            if (!$editFormOk) {
                if (!isset($_POST['editRoomSubmit'])) {
                    if ($hManager->countHotelRooms() > 0 && isset($_SESSION['edit'])) {
                        $roomToEdit = $_SESSION['edit'];
                        $editHotelRoom = $hManager->getHotelRoom($roomToEdit);
                        $_SESSION['roomNum'] = $editHotelRoom->number;
                        $_SESSION['roomType'] = (string)$editHotelRoom->roomType;
                        $_SESSION['description'] = $editHotelRoom->description;
                        $_SESSION['price'] = $editHotelRoom->pricePerNight;
                    }
                }

                /* Display edit room form. */
                ?>
                <form method="POST" action="#editRoomForm" novalidate>
                    <label for="roomNum">Room Number:</label>
                    <input type="text" name="roomNum" id="roomNum" value="<?php echo $_SESSION['roomNum']; ?>"
                           maxlength="4" required>
                    <span class="errors" id="roomNumError"><?php echo $errormsg['roomNum']; ?></span>
                    <br>
                    <label for="roomType">Room Type:</label>
                    <select name="roomType" id="roomType">
                        <option <?php if (checkSelect('roomType') === "Single") {
                            echo "selected";
                        }  else ?> value="Single">Single
                        </option>
                        <option <?php if (checkSelect('roomType') === "Twin") {
                            echo "selected";
                        }  else ?> value="Twin">Twin
                        </option>
                        <option <?php if (checkSelect('roomType') === "Double") {
                            echo "selected";
                        }  else ?> value="Double">Double
                        </option>
                        <option <?php if (checkSelect('roomType') === "King") {
                            echo "selected";
                        }  else ?> value="King">King
                        </option>
                        <option <?php if (checkSelect('roomType') === "Triple Room") {
                            echo "selected";
                        }  else ?> value="Triple Room">Triple Room
                        </option>
                    </select>
                    <br>
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description"
                           value="<?php echo $_SESSION['description']; ?>"
                           required>
                    <span class="errors" id="descriptionError"><?php echo $errormsg['description']; ?></span>
                    <br>
                    <label for="price">Price Per Night: $</label>
                    <input type="text" name="price" id="price" placeholder="0.00"
                           value="<?php echo $_SESSION['price']; ?>" maxlength="10" required>
                    <span class="errors" id="priceError"><?php echo $errormsg['price']; ?></span>
                    <br>
                    <input type="submit" name="editRoomSubmit" id="editRoomSubmit" value="Submit">
                </form>
                <?php
            }
        }
        ?>
    </div>
</div>
<?php
include("phpFiles/footer.php");
?>
</body>
</html>