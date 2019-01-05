$(function(){
    $(".pronounce").click(function(){
        var audio = new Audio();
        audio.src=this.dataset.audio;
        audio.play();
    });
});
