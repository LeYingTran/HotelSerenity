<?php
/**
 * Class containing useful functions for managing
 * the bookings xml file.
 *
 * Created by: LeYing Tran
 * Date: 25/09/2015
 * Last Modified: 29/09/2015
 */

class BookingsManager
{

    private $bookingsXML;
    private $xml;

    /**
     * Constructor that sets and opens the xml File.
     * @param $file string Xml file to be opened.
     */
    function __construct ($file)
    {
        $this->bookingsXML = $file;
        $this->xml = simplexml_load_file($this->bookingsXML);
    }


    /**
     * Reloads xml page.
     */
    function reload() {
        $this->xml = simplexml_load_file($this->bookingsXML);
    }


    /**
     * Gets the number of nodes in the xml file.
     * @return int Number of nodes in the xml file.
     */
    function countBookings() {
        return count($this->xml->xpath("booking"));
    }


    /**
     * Function that opens the bookings xml file, writes to it and saves.
     * @param $number int Number of the room being booked.
     * @param $name string Name of the person booking the room.
     * @param $checkin string Checkin date for the room being booked.
     * @param $checkout string Checkout date for the room being booked.
     */
    function addBooking($number, $name, $checkin, $checkout)
    {
        $indate = array_pad(explode("-", $checkin, 3), 3, null);
        $outdate =array_pad(explode("-", $checkout, 3), 3, null);

        $newBooking = $this->xml->addChild('booking');
        $newBooking->addChild('number', $number);
        $newBooking->addChild('name', $name);
        $newCheckin = $newBooking->addChild('checkin');
        $newCheckin->addChild('day', $indate[0]);
        $newCheckin->addChild('month', $indate[1]);
        $newCheckin->addChild('year', $indate[2]);
        $newCheckout = $newBooking->addChild('checkout');
        $newCheckout->addChild('day', $outdate[0]);
        $newCheckout->addChild('month', $outdate[1]);
        $newCheckout->addChild('year', $outdate[2]);
    }


    /**
     * Function to unset/delete a node/booking from the bookings xml file.
     * @param $nodeArray array Array of room numbers/nodes to be deleted/unset.
     */
    function deleteBooking($nodeArray)
    {
        $bookings = $this->xml->xpath("booking");
        if (count($bookings) > 0) {
            foreach ($nodeArray as $nodeNumber) {
                unset($bookings[$nodeNumber][0]);
            }
        }
    }


    /**
     * Function that gets the information of a booking,
     * the room number, name, checkin and checkout dates.
     * @param $nodeNumber int Booking being requested.
     * @return array Array with the booking information
     */
    function getBooking($nodeNumber) {
        return $this->xml->xpath("booking")[$nodeNumber];
    }


    /**
     * Function that gets the node whose room number matches the
     * given number.
     * @param $number int The room number to be searched for.
     * @return array The booking node whose room number
     * matches the given number.
     */
    function getBookingByRoom($number) {
        return $this->xml->xpath("booking[number = $number]");
    }


    /**
     * Function that saves the contents of the bookings xml file.
     */
    function save () {
        $this->xml->saveXML($this->bookingsXML);
    }
}

?>