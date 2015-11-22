<?php
/**
 * Confirmation php page that displays the user's
 * bookings and offers the choice of cancelling or confirming
 * their booking.
 *
 * Created by: LeYing
 * Date: 19/09/2015
 * Last Modified: 01/10/2015
 */
?>
<section id="confirmBookings">
    <h3>Confirm Booking</h3>
    <?php

    /* If have a list of checkboxes, set a session to hold them. */
    if (isset($_POST['book_list'])) {
        $_SESSION['book_list'] = $_POST['book_list'];
    }

    /* If confirm, save booking. */
    if (isset($_SESSION['book_list'])) {
        if (isset($_POST['confirm'])) {
            $bManager = new BookingsManager($conf["bookingXML"]);
            $bookArray = $_SESSION['book_list'];
            foreach ($bookArray as $book) {
                $bManager->addBooking($book, $_SESSION['guestName'], $_SESSION['checkin'], $_SESSION['checkout']);
            }
            $bManager->save();
            $_SESSION = array();
            session_destroy();

            $reservationFormOk = true;
            $str = "<p>Thank you!<br>Your booking was successful.</p>";
            $str .= "<p>We look forward to hosting you.</p>";
            $str .= "<form action=\"reservations.php#reserve\" method=\"POST\">" .
                "<input type=\"submit\" name=\"back\" class=\"back\" value=\"Return\">" .
                "</form>";
            echo $str;
        }

        /* Display the confirmation form. */
        ?>
        <form method="POST">
            <?php

            /* Confirmation printing. */
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
                    $str .= "</table><br><p>Total Price Per Night: $" . $totalPrice . "</p>";
                    $str .= "<input type=\"submit\" name=\"back\" class=\"back\" value=\"Cancel\">";
                    $str .= "<input type=\"submit\" name=\"confirm\" value=\"Confirm\">";
                } else {
                    $str = "<p>There are no rooms available.</p>";
                }
                echo $str;
            }
            ?>
        </form>

        <?php
        /* If nothing is selected, print out and offer button to remove popup. */
    } else {
        $displayAvailable = true;
        ?>
        <form  method="POST">
            <?php
            $str = "<p>No rooms were selected</p>";
            $str .= "<input type=\"submit\" name=\"okBack\" class=\"back\" value=\"OK\">";
            echo $str;
            ?>
        </form>
    <?php } ?>
</section>



