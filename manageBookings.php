<?php
/**
 * Manage bookings admin page that has navigation for admin staff
 * and displays all existing bookings along with the option to delete
 * the booking. If deleting, a confirmation pop up occurs above the
 * bookings table.
 *
 * Created by: LeYing
 * Date: 24/09/2015
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
    <h3>Manage Bookings</h3>
    <?php include("phpFiles/adminNav.php");
    $bManager = new BookingsManager($conf['bookingXML']);
    if (isset($_POST['manBookingsLink'])) {
        $bManager->reload();
    }

    /* If confirm button is pressed, delete the selected bookings. */
    if (isset($_POST['confirm'])) {
        if(isset($_SESSION['cancel_booking_list'])) {
            $bManager->deleteBooking($_SESSION['cancel_booking_list']);
            $bManager->save();
        }
        $bManager->reload();
        unset($_SESSION['cancel_booking_list']);
    }

    /* Display confirm delete pop up box if delete/save is pressed */
    if (isset($_POST['deleteBooking'])) {
    ?>
    <section id="confirmDelete">
        <h3>Confirm Delete</h3>
    <form action="#displayBookings" method="POST">
                <?php
                /* Confirmation printing */
                if(isset($_POST['cancel_booking_list']) && count($_POST['cancel_booking_list']) > 0) {
                    $_SESSION['cancel_booking_list'] = $_POST['cancel_booking_list'];
                    $str = "<p>You are about to delete: </p>";
                    $str .= "<table class=\"deleteConf\">";
                    foreach($_POST['cancel_booking_list'] as $num) {
                        $booking = $bManager->getBooking($num);
                        $roomNum = $booking->number;
                        $name = $booking->name;
                        $checkin = $booking->checkin->day . "-" . $booking->checkin->month . "-" . $booking->checkin->year;
                        $checkout = $booking->checkout->day . "-" . $booking->checkout->month . "-" . $booking->checkout->year;
                        $str .= "<tr><td>$roomNum</td><td>$name</td><td>$checkin</td><td>$checkout</td></tr>";
                    }
                    $str .= "</table><br>";
                    $str .= "<input type=\"submit\" name=\"back\" class=\"back\" value=\"Cancel\">";
                    $str .= "<input type=\"submit\" name=\"confirm\" value=\"Confirm\">";
                } else {
                        $str = "<p>No bookings were selected.</p>";
                        $str .= "<input type=\"submit\" value=\"OK\">";
                }
                echo $str;
                ?>
    </form>
        </section>
    <?php }
    /* Display all bookings */
    ?>
    <div id="displayBookings">
        <h3>Bookings</h3>
        <form id="delBookingForm" action="#confirmDelete" method="POST">
            <?php
            if ($bManager->countBookings() > 0)  {
                $str = "<table id=\"bookingsTable\"><tr><th>Room</th><th>Name</th><th>In</th><th>Out</th><th>Delete</th></tr>";
                for($i = 0; $i < $bManager->countBookings(); $i++) {
                    $booking = $bManager->getBooking($i);
                    $number = $booking->number;
                    $name = $booking->name;
                    $checkin = $booking->checkin->day . "-" . $booking->checkin->month . "-" . $booking->checkin->year;
                    $checkout = $booking->checkout->day . "-" . $booking->checkout->month . "-" . $booking->checkout->year;
                    $cancelBooking = "<input type=\"checkbox\" name=\"cancel_booking_list[]\" value=\"" . $i . "\" class=\"cancelBook\">";
                    $str .= "<tr><td>$number</td><td>$name</td><td>$checkin</td><td>$checkout</td><td>$cancelBooking</td></tr>";
                }
                $str .= "<tr><td></td><td></td><td></td><td><td><input type=\"submit\" name=\"deleteBooking\" id=\"deleteBooking\" value=\"Delete\"></td></tr>";
                $str .= "</table>";
                echo $str;
            } else {
                echo "<p>There are currently no bookings.</p>";
            }
            ?>
        </form>
    </div>
</div>

<?php
include("phpFiles/footer.php");
?>
</body>
</html>
