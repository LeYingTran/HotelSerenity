<?php
/**
 * Reservations page where users are able to input their
 * potential check in and check out dates along with their name.
 * The available rooms are displayed which may be selected for
 * booking.
 *
 * Created by: LeYing
 * Date: 15/09/2015
 * Last Modified: 1/10/2015
 */

    session_start();
?>
<!DOCTYPE html>

<html>
    <?php
    $styleList = array("bootstrap-3.3.5-dist/css/bootstrap.min.css", "bootstrap-3.3.5-dist/css/bootstrap-theme.min.css", "jquery-ui/jquery-ui.min.css", "css/style.css", "css/datepicker.css");

    $scriptList = array("jQuery/jquery-1.11.3.min.js","jquery-ui/jquery-ui.min.js", "bootstrap-3.3.5-dist/js/bootstrap.min.js", "js/calendar.js");
    include("phpFiles/header.php");
    ?>

    <header style='background-image:url("images/bg-lobby.jpg")'>
        <h1>Hotel Serenity</h1>
        <h2>Reservations</h2>
    </header>

    <div id="main">
        <?php
            $reservationFormOk = false;
            $displayAvailable = false;
            include("phpFiles/validateReservationForm.php");

        if (isset($_POST['book_list']) || isset($_POST['confirm'])) {
            $reservationFormOk = true;
        }

            if(!$reservationFormOk) {

            /* Reservation Form */
        ?>
        <section id="reserve">
            <form id="checkinForm" method="POST" novalidate>
                <h3>Make A Reservation</h3>
                <label for="checkin">Check-In: </label>
                <input type="text" id="checkin" name="checkin" value="<?php storeField("checkin"); ?>" placeholder="dd-m-yyyy">
                <span class="errors" id="checkinError"><?php echo $errormsg['checkin']; ?></span>
                <br>
                <label for="checkout">Check-Out: </label>
                <input type="text" id="checkout" name="checkout" value="<?php storeField("checkout"); ?>" placeholder="dd-m-yyyy">
                <span class="errors" id="checkoutError"><?php echo $errormsg["checkout"]; ?></span>
                <br>
                <label for="guestName">Name:</label>
                <input type="text" name="guestName" id="guestName" value="<?php storeField("guestName"); ?>" required>
                <span class="errors" id="nameError"><?php echo $errormsg['guestName']; ?></span>
                <br>
                <input type="submit" value="Check Availability" id="checkAvailability" name="checkAvailability">
            </form>
        </section>
        <?php
            }

        if (isset($_POST['okBack'])) {
            $displayAvailable = true;
        }
        if (isset($_POST['ok']) || isset($_POST['confirm'])) {
            include("phpFiles/confirm.php");
        }

            if ($displayAvailable) {
        ?>
            <div id="available">
                <form id="chooseRooms" method="POST">
                    <?php include("phpFiles/checkAvailable.php") ?>
                </form>
            </div>
        <?php } ?>
    </div>

    <?php include("phpFiles/footer.php"); ?>
    </body>
</html>