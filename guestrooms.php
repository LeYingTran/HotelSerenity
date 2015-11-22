<?php
/**
 * Guestrooms page which gets information on the available rooms
 * and displays them under their respective sections and images.
 *
 * Created by: LeYing
 * Date: 28/09/2015
 * Last Modified: 29/09/2015
 */
?>

<!DOCTYPE html>
<html>
<?php
$styleList = array("bootstrap-3.3.5-dist/css/bootstrap.min.css", "bootstrap-3.3.5-dist/css/bootstrap-theme.min.css", "css/style.css");

$scriptList = array("jQuery/jquery-1.11.3.min.js", "bootstrap-3.3.5-dist/js/bootstrap.min.js");
include("phpFiles/header.php");
include("phpFiles/roomManager.php");
?>

<header style='background-image:url("images/rooms.jpg")'>
    <h1>Hotel Serenity</h1>
    <h2>Guestrooms and Suites</h2>
</header>

<div id="main">
    <?php
    $rManager = new RoomManager($conf['roomXML']);
    $hManager = new HotelManager($conf['hotelXML']);
    $rManager->reload();
    $hManager->reload();
    $infoArray = array();

    /* Display rooms of the type whose more was pressed. */
    if ($rManager->countRoomTypes() > 0) {
        for ($i = 0; $i < $rManager->countRoomTypes(); $i++) {
            if (isset($_POST[$i . "Submit"])) {
                $roomType = $rManager->getRoomType($i);
                $room = $roomType->id;
                $description = $roomType->description;
                $hotelRooms = $hManager->getHotelRoomByType($room);
                $infoArray[$i] = printRooms($hotelRooms, $description);
            } else {
                unset($infoArray[$i]);
            }
        }
    }

    /* Make section for each room type with picture, room type and more info button */
    if ($rManager->countRoomTypes() > 0) {
        for ($i = $rManager->countRoomTypes() - 1; $i >= 0; $i--) {
            $roomType = $rManager->getRoomType($i);
            $id = $roomType->id;
            $description = $roomType->description;
            $maxGuests = $roomType->maxGuests;
            $imgName = strtolower(str_replace(" ", "", $id));
            $buttonName = $i . "Submit";
            $moreButton = "<input type=\"submit\" name=$buttonName  value=\"More\" class=\"more\">";
            $str = "<section id=$imgName>" .
                "<img src=\"images/$imgName.jpg\" alt=\"$description\" class=\"img-responsive\">" .
                "<h3>$id</h3>" .
                "<p>Max Guests: $maxGuests</p>";
            if (isset($infoArray[$i])) {
                $str  .= "<div class=\"detail\">$infoArray[$i]</div>";
                } else {
                $str .= "<form method=\"POST\" action=\"#$imgName\">" . $moreButton . "</form>";
            }
            $str .= "</section>";
            echo $str;
        }
    } else {
        echo "There are currently no rooms available.";
    }
    ?>
</div>

<?php
include("phpFiles/footer.php");
?>
</body>
</html>
