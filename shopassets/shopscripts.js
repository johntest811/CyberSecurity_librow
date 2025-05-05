/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project


$(document).ready(function() {
    $('#searchBtn').click(function() {
        var searchQuery = $('input[name="search"]').val();
        // Redirect to the same page with the search query as a parameter
        window.location.href = 'Shop.html?search=' + searchQuery;
    });
});


