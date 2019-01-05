<!DOCTYPE html>
<html>
<header>


</header>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Vocabulary</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/words.css">
<script  src="js/jquery.min.js"></script>

<script  src="js/bootstrap.min.js"></script>
<script  src="js/idangerous.swiper.min.js"></script>



<body >

<div><center><img src="images/ducky_walk_dribbble.gif" style="width:70px">Hello！The <strong> <?php echo @$days; ?></strong> day !!<img src="images/duckyrunwalk_dribbble.gif" style="width:70px"></center></div>

<div class="col-xs-12">
    <div class=" box1">
        <div class="sliderr" id="ha"></div>
        <span class="m">All</span>
        <span class="w">Swipe</span>
    </div>
</div>
<div class="container all" style="display: none">
    <form action="?s=WordsPool.checkin" method="post">
        <div class="row">
            <div class="col-xs-9"></div>
            <div class="col-xs-1">pass </div>
            <div class="col-xs-1">mark </div>
        </div>
        <?php foreach(@$words as $word){ ?>

        <div class="row">
            <div >
                <div class="col-xs-2" data-toggle="modal" data-target="#myModal" data-endf='<?php echo $word['endf'] ?>'  data-ex="<?php echo $word['example'] ?>" ><?php echo  "<strong>".$word['content']."</strong> </br> [".$word['pronunciation']."]" ?></div>
                <div class="col-xs-1"  ><div class="pronounce audio" data-audio="<?php echo $word['audio'] ?>"></div></div>
                <div class="col-xs-6"  id="word_<?php echo $word['id'] ?>"> <?php echo  $word['cndf'] ?></div>
            </div>
            <input name="id_<?php echo $word['id'] ?>" value="<?php echo $word['id'] ?>" hidden>
            <div class="col-xs-1"><input type="checkbox"  name="pass_<?php echo $word['id'] ?>" > </div>
            <div class="col-xs-1"><input type="checkbox"  name="mark_<?php echo $word['id'] ?>" > </div>
        </div>

        <?php }?>
        <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>
<div  class="swiper">
    <form action="?s=WordsPool.checkin" method="post" id="swipe_form">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide10">
                    <?php foreach(@$words as $k=> $word){ ?>
                        <div class="col-xs-2"></div>
                        <div class="col-xs-5">
                            <span><?php echo  "<strong>".$word['content']."</strong> " ?></span>
                        </div>
                        <div class="pass_btn">
                            <input  type="checkbox" id="pass_<?php echo $word['id'] ?>"  name="pass_<?php echo $word['id'] ?>"  />
                            <label class="label-btn" for="pass_<?php echo $word['id'] ?>"></label>
                        </div>
                    <?php }?>
                    <br>
                    <div class="col-xs-4"></div>
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
                <?php foreach(@$words as $k=> $word){ ?>

                    <div class="swiper-slide slide<?php echo $k%5?> pronounce"  data-audio="<?php echo $word['audio'] ?>">
                        <div class="line">
                            <div class="content"><?php echo  "<strong>".$word['content']."</strong>  [".$word['pronunciation']."]" ?>&nbsp;&nbsp;&nbsp;</div>
                            <div class="pronounce audio" data-audio="<?php echo $word['audio'] ?>"> &nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
                        <div class="mark_btn">
                            <input  type="checkbox" id="mark_<?php echo $word['id'] ?>"  name="mark_<?php echo $word['id'] ?>"/>
                            <label class="label-btn" for="mark_<?php echo $word['id'] ?>"></label>
                        </div>
                        <div class="cndf"> <?php echo  $word['cndf'] ?></div>
                        <div class="endf"> <?php echo  $word['endf'] ?></div>
                        <div><strong>Example:</strong></div>
                        <div class="example"> <?php echo  $word['example'] ?></div>
                        <div class="remark" ><strong><button class="btn btn-primary btn-xs" id="remark_<?php echo $word['id'] ?>">Remark</button></strong></div>
                        <input name="id_<?php echo $word['id'] ?>" value="<?php echo $word['id'] ?>" hidden>
                        <br>
                    </div>
                <?php }?>

            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="css/idangerous.swiper.css">
<script>

    $(function(){
        if($("#ha").css("left")=="65px"){
            $(".all").hide();
            $(".swiper").show();
        }else{
            $(".swiper").hide();
            $(".all").show();

        }
    });

    $(".box1").click(function(){
        console.log($("#ha").css("left"));
        if($("#ha").css("left")=="5px"){
            $("#ha").css("left","65px");
            $(".all").hide();
            $(".swiper").show();
        }else{
            $("#ha").css("left","5px");
            $(".swiper").hide();
            $(".all").show();

        }
    })

</script>
<script>

    var mySwiper = undefined; new Swiper('.swiper-container',{
//        pagination: '.pagination',
//        paginationClickable: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        watchActiveIndex: true,
        resizeReInit : true,
//        mousewheelControl:true,
        mode:"vertical"

    });



    $("#id").click(function(){
        $("#slide-box").css({
            'left':'300px'
        });
    });
    //    $("[id^='word_']").click(function(){
    //        var c='hide_'+ this.id.substr(5);
    //        var hide_class = $("."+c);
    //        if(hide_class.css('display') === 'none'){
    //            $("[class^='hide_']").hide();
    //            hide_class.show();
    //        }else{
    //            hide_class.hide();
    //        }
    //    });
    $(".content").click(function(){
        var top=$(this).offset().clientTop+$(this).height();
        console.log(this);
        $('#df').css({
            'margin-top': top,
        });
        $('#myModal').on('show.bs.modal', function (event){
            var button = $(event.relatedTarget);
            var endf = button.data('endf');
            var example = button.data('ex');
            var modal = $(this);
            modal.find('[id="def"]').text( endf);
            modal.find('[id="example"]').text( example);
            console.log(example);

        });
    });
    $(".pronounce").click(function(){
        var audio = new Audio();
        audio.src=this.dataset.audio;
        audio.play();
    });

    $(".remark button").click(function(){
        var n=this.id;
        var remark='<textarea  aria-label="Remark" name="'+n+'" style="width:100%;" for="swipe_form"></textarea>';
        $(this).parent().parent().append(remark);
        $(this).attr('disabled','disabled');
    });
</script>
</body>

</html>
