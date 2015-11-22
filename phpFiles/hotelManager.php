<?php
/**
 * Class that contains useful functions for managing the
 * hotel rooms xml file.
 *
 * Created by: LeYing Tran
 * Date: 25/09/2015
 * Last Modified: 29/09/2015
 */

class HotelManager {

    private $hotelXML;
    private $xml;

    /**
     * Constructor that sets and opens the xml File.
     * @param $file string The xml file to be opened.
     */
    function __construct ($file) {
        $this->hotelXML = $file;
        $this->xml = simplexml_load_file($this->hotelXML);
    }

    /**
     * Reloads xml page.
     */
    function reload() {
        $this->xml = simplexml_load_file($this->hotelXML);
    }

    /**
     * Gets the number of nodes in the xml file.
     * @return int Number of nodes in the xml file.
     */
    function countHotelRooms() {
        return count($this->xml->xpath("hotelRoom"));
    }

    /**
     * Function that opens the hotel rooms xml file, writes to it and saves.
     * @param $number int The number of the room being booked.
     * @param $roomType string The type of room.
     * @param $description string The description of the hotel room.
     * @param $price string The cost of the hotel room.
     */
    function addHotelRoom($number, $roomType, $description, $price) {
        $newHotelRoom = $this->xml->addChild('hotelRoom');
        $newHotelRoom->addChild('number', htmlspecialchars($number));
        $newHotelRoom->addChild('roomType', htmlspecialchars($roomType));
        $newHotelRoom->addChild('description', htmlspecialchars($description));
        $newHotelRoom->addChild('pricePerNight', htmlspecialchars($price));
    }


    /**
     * Function to unset/delete a node from the hotels xml file.
     * @param $nodeArray array The rooms/nodes to be deleted/unset.
     */
    function deleteHotelRoom($nodeArray) {
        $hotelRooms = $this->xml->xpath("hotelRoom");
        if (count($hotelRooms) > 0) {
            foreach ($nodeArray as $nodeNumber) {
                unset($hotelRooms[$nodeNumber][0]);
            }
        }
    }


    /**
     * Function that gets the information of a hotel room,
     * the room number, roomType, description and price.
     * @param $nodeNumber int The hotel room being requested.
     * @return array The array with the hotel room information.
     */
    function getHotelRoom($nodeNumber) {
        return $this->xml->xpath("hotelRoom")[$nodeNumber];
    }


    /**
     * Function that gets the node whose room type matches the
     * given type.
     * @param $type string The room type to be searched for.
     * @return array The booking nodes whose room type
     * matches the given room type.
     */
    function getHotelRoomByType($type) {
        return $this->xml->xpath("hotelRoom[roomType = \"$type\"]");
    }

    /**
     * Function that saves the contents of the xml file.
     */
    function save () {
        $this->xml->saveXML($this->hotelXML);
    }
}

?>