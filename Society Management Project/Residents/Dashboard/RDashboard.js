function loadPage(pageUrl) {
  // Use AJAX to load the content into the #content div
  $.ajax({
    url: pageUrl,
    method: 'GET',
    success: function (data) {
      $('#content').html(data); // Load the returned data into the #content div
    },
    error: function () {
      $('#content').html('<p>Error loading the page. Please try again.</p>');
    }
  });

  
}
