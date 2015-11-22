<?php
/**
 * Index/Home page for the website.
 *
 * Created by: LeYing
 * Date: 29/09/2015
 * Last Modified: 29/09/2015
 */
?>

<!DOCTYPE html>
<html>
<?php
$styleList = array("bootstrap-3.3.5-dist/css/bootstrap.min.css", "bootstrap-3.3.5-dist/css/bootstrap-theme.min.css", "css/indexstyle.css");

$scriptList = array("jQuery/jquery-1.11.3.min.js", "bootstrap-3.3.5-dist/js/bootstrap.min.js", "js/carousel.js");
include("phpFiles/header.php");
?>

<header class="carousel slide">
    <div class="carousel-inner">
        <div class="item active">
            <div class="fill" style="background-image:url('images/lobby.jpg');">
            </div>
        </div>
        <div class="item">
            <div class="fill" style="background-image:url('images/dining.jpg');">
            </div>
        </div>
        <div class="item">
            <div class="fill" style="background-image:url('images/pool.jpg');">
            </div>
        </div>
    </div>
</header>
<div id="main">
    <h1>Hotel Serenity</h1>
    <div class="overviewBox">
        <p>Welcome to <strong><em>Hotel Serenity</em></strong>!</p>
        <p>Escape reality with our range of affordable <a href="guestrooms.php">rooms</a>, free Wi-Fi and top-notch facilities.</p>
        <p>Don't hesitate! <a href="reservations.php">Book now</a>!</p>
    </div>
</div>
<?php include("phpFiles/footer.php"); ?>
</body>
</html>