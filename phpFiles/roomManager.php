<?php
/**
 * Class with useful functions for managing the
 * room Types xml file.
 *
 * Created by: LeYing
 * Date: 28/09/2015
 * Last Modified: 29/09/2015
 */

class RoomManager {

    private $roomXML;
    private $xml;

    /**
     * Constructor that sets and opens the xml File.
     * @param $file string The xml file to be opened.
     */
    function __construct ($file) {
        $this->roomXML = $file;
        $this->xml = simplexml_load_file($this->roomXML);
    }

    /**
     * Reloads xml page.
     */
    function reload() {
        $this->xml = simplexml_load_file($this->roomXML);
    }

    /**
     * Gets the number of nodes in the xml file.
     * @return int Number of nodes in the xml file.
     */
    function countRoomTypes() {
        return count($this->xml->xpath("roomType"));
    }


    /**
     * Function that gets the information of a room type,
     * the room name, description and maximum guests it can hold.
     * @param $nodeNumber int The room type being requested.
     * @return array The array with the room type information.
     */
    function getRoomType($nodeNumber) {
        return $this->xml->xpath("roomType")[$nodeNumber];
    }
}

?>