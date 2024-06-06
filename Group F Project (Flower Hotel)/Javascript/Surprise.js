

$(document).ready(function () {
  $("#music-btn").mouseenter(function(){
    $("#music-box").trigger('play');
 });
  $("#music-btn").mouseleave(function(){
    $("#music-box").trigger('pause');
});
  });

