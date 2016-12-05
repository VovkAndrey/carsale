/* ========================================================================
 * # Main JS file
 *    Docs:
 *    http://api.jquery.com/
 *    https://github.com/AllThingsSmitty/jquery-tips-everyone-should-know
 *    https://github.com/peterkokot/awesome-jquery
 ========================================================================*/

(function ($) {
    // Scripts that will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute.
    $(document).ready(function () {
      $(document).foundation();
    });

    // Scripts that will run after the whole page is loaded (images, videos, iframes. etc)
    $(window).on('load', function () {

    });

    // Scripts that will run on window resize
    $(window).on('resize', function () {

    });

})(jQuery); // Fully reference jQuery after this point.
