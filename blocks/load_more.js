jQuery(function ($) {
    $(document).ready(function () {
      $(document).on('click', '#load-more', function() {
        console.log("triggered");
          var button = $(this);
          var currentPage = parseInt(button.data('current-page'));
          var maxPages = parseInt(button.data('max-pages'));
          var nextPage = currentPage + 1;
  
          button.text('Loading...');
  
          $.ajax({
              url: ajax_posts.ajaxurl,
              type: 'POST',
              data: {
                  action: 'load_more_posts',
                  page: nextPage,
              },
              success: function(response) {
                console.log("response: "+response);
                  var $response = $(response).find('#response-feed-block').remove();
                  if ($response.length > 0) {
                      $('#feed-block').append($response).delay(2000);
                      button.data('current-page', nextPage);
                      if (nextPage === maxPages) {
                          button.remove();
                      } else {
                          button.text('Load More');
                      }
                  } else {
                      button.remove();
                  }
              },
              error: function() {
                  console.log('Error loading more posts.');
              }
          });
      });
  });
  });
  