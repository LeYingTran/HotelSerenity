<?php
/**
 * Php page containing the form of two buttons used to navigate
 * the admin pages. Takes the user to different web pages depending
 * on the button clicked, and if page matches the button, the button is
 * disabled.
 * Included in all admin pages.
 *
 * Created by: LeYing
 * Date: 24/09/2015
 * Last modified: 29/09/2015
 */
?>

<div id="adminOptions">
    <h3>Admin Tasks</h3>
    <form action="#adminOptions" method="POST">
        <input type="submit" name="manBookingsLink" id="manBookingsLink" value="Manage Bookings"  <?php disableButton('manageBookings'); ?>>
    </form>
    <form method="POST">
        <input type="submit" name="manHotelsLink" id="manHotelsLink" value="Manage Rooms" <?php disableButton('manageHotelRooms'); ?>>
    </form>
<?php
    if(isset($_POST['manBookingsLink'])) {
        header("Location:manageBookings.php");
        exit;
    } else if (isset($_POST['manHotelsLink'])) {
        header("Location:manageHotelRooms.php");
        exit;
    }
?>
</div>