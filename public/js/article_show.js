$(document).ready(function () {

  $('.js-like-article').on('click', function (e) {
    e.preventDefault()

    var $link = $(e.currentTarget)
    $link.toggleClass('fa-heat-o').toggleClass('fa-heat')

    $.ajax({
      method: 'POST',
      url: $link.attr('href')
    }).done(function (data) {
      $('.js-like-article-count').html(data.hearts)
      $link.toggleClass('fa-heat').toggleClass('fa-heat-o')
    })

  })

});
