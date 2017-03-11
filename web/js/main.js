$(function () {
  $('.vote-button').click(function () {
    var input = $(this);
    var isPressed = input.hasClass('pressed');
    if(!isPressed) {
      var action = input.data('action');
      var quoteId = input.data('id');

      $.post('/quotes/'+ quoteId + '/vote', {value: action}, function (resp) {
        var scoreElem = $('#score-' + quoteId).text(resp);
        var bothButtons = $('.vote-button-' + quoteId).removeClass('pressed');
        input.addClass("pressed");
      });
    }
  });
});
