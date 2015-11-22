<?php
/**
 * Admin page that displays a menu for admin staff
 * to choose what tasks they want to carry out.
 *
 * Created by: LeYing
 * Date: 20/09/2015
 * Last Modified: 29/09/2015
 */

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
    <?php include("phpFiles/adminNav.php"); ?>
</div>

<?php include("phpFiles/footer.php"); ?>
</body>
</html>

