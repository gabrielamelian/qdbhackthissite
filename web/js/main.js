$(function () {
  $('.vote-button').click(function () {
    var input = $(this);
    var isPressed = input.hasClass('pressed');
    if(!isPressed) {
      var action = input.data('action');
      var quoteId = input.data('id');

      $.post('/quotes/'+ quoteId + '/vote', {value: action}, function (resp) {
        // get current status
        var scoreElem = $('#score-' + quoteId).text(resp);
        //var scoreSplat = scoreElem.text().slice(1, -2).split('/');
        //var prevAction = null;
        var bothButtons = $('.vote-button-' + quoteId).removeClass('pressed');

        //// calculate what changed
        //var score = scoreSplat[0];
        //var total = scoreSplat[1];
        //var scoreStr = calculateNewScore(score, total, prevAction, action);

        //// update the DOM.
        //scoreElem.text(scoreStr);
        input.addClass("pressed");
      });
    }
  });
});

/**
 * Logic for the updating of the score within the DOM.
 *
 * @param prevScore prev score as present in the DOM.
 * @param prevTotal previous total points as present in the DOM.
 * @param prevAction either null, upvote or downvote. 
 * @param currAction what are we doing? either upvote or downvote.
 * @return a new string to be placed in the DOM.
 */
function calculateNewScore(prevScore, prevTotal, prevAction, currAction) {
  var score = prevScore;
  var total = prevTotal;

  if(!prevAction) {
    total += 1;
    if(currAction == "upvote") {
      score += 1;
    } else {
      score -= 1;
    }
  } else {
    if(currAction == "upvote") {
      score += 2;
    } else {
      score -= 2;
    }
  }

}
