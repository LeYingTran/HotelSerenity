/**
 * Module that runs the bootstrap carousel
 * on the website home/index page.
 *
 * Created by: LeYing Tran
 * Date: 30/09/2015
 * Last modified: 30/09/2015
 */
var Carousel = (function () {
    "use strict";

    /* Public interface to the module */
    var pub = {};

    /* Public setup function that calls the calendar setup */
    pub.setup = function () {
        $(".carousel").carousel({
            interval: 3000
        });
    };

    /* Expose the public interface */
    return pub;
}());

/* Call setup when document is ready */
$(document).ready(Carousel.setup);

