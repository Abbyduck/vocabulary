<!DOCTYPE html>
<html>
<header>

</header>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Vocabulary-Review</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/words.css">
<script src="js/jquery.min.js"></script>

<script src="js/bootstrap.min.js"></script>
<script src="js/words.js"></script>

<body>
<div><center><img src="images/ducky_walk_dribbble.gif" style="width:70px"> <strong> Reviewing...</strong><img src="images/duckyrunwalk_dribbble.gif" style="width:70px"></center></div>

<br>
<div class="container">
    <form action="?s=WordsPool.getReviewWords&page= <?php echo @$page; ?>&openid=<?php echo @$openid; ?>" method="post">
        <div class="row">
            <div class="col-xs-5"></div>
            <div class="col-xs-1">Forget </div>
            <div class="col-xs-1"> </div>
            <div class="col-xs-1">Pass </div>
            <div class="col-xs-1"> </div>
            <div class="col-xs-1">Mark </div>
        </div>
        <?php foreach(@$words as $word){ ?>

        <div class="row">
            <div>
                <div class="col-xs-3 pronounce <?php if($word['pass']){echo 'pass';}elseif($word['forget']){echo 'forget';} ?>" id="word_<?php echo $word['id'] ?>" data-audio="<?php echo $word['audio'] ?>"> <?php if($word['mark']){echo '*';} ?> <?php echo  "<strong>".$word['content']."</strong> </br> [".$word['pronunciation']."]" ?></div>
<!--                <div class="col-xs-1"><div class=" audio" data-audio="--><?php //echo $word['audio'] ?><!--"></div></div>-->
                <div class="col-xs-2"></div>
            </div>
            <input name="id_<?php echo $word['id'] ?>" value="<?php echo $word['id'] ?>" hidden>
            <div class="col-xs-1"><input type="checkbox"  name="forget_<?php echo $word['id'] ?>" ></div>
            <div class="col-xs-1"> </div>
            <div class="col-xs-1"><input type="checkbox"  name="pass_<?php echo $word['id'] ?>" <?php if($word['pass']){echo 'checked';} ?>>  </div>
            <div class="col-xs-1"> </div>
            <div class="col-xs-1"><input type="checkbox"  name="<?php if($word['mark']){echo 'no';} ?>mark_<?php echo $word['id'] ?>" <?php if($word['mark']){echo 'checked';} ?>> </div>
            <div class="hide_<?php echo $word['id'] ?>">
                <div class="col-xs-12"><hr></div>
                <div class="col-xs-12"> <?php echo  $word['cndf'] ?></div>
                <div class="col-xs-12 review_remark" id="remark_<?php echo  $word['id'] ?>">Remark: <?php echo  $word['remark'] ?></div>
                <div class="col-xs-12 review_example">Example:</br><?php echo  $word['example'] ?></div>
            </div>
        </div>

        <?php }?>

        <input class="btn btn-primary" type="submit" value="<?php echo count(@$words)<10 ? 'Finish':'Next Page' ?>">
    </form>
</div>

</body>
<script>
    $("[id^='word_']").click(function(){
        var c='hide_'+ this.id.substr(5);
        var hide_class = $("."+c);
        if(hide_class.css('display') === 'none'){
            $("[class^='hide_']").hide();
            hide_class.show();
        }else{
            hide_class.hide();
        }
    });

    var touchtime = new Date().getTime();
    $(".review_remark").click(function(){
        if( new Date().getTime() - touchtime < 500 ){
            if( $(this).children().length==0){
                var val= $(this).text().substr(7);
                $(this).text("Remark:");
                var input="<input name='"+this.id+"' value='"+val+"'>";
                $(this).append(input);
            }
        }else{
            touchtime = new Date().getTime();
        }
    });
    $(".audio").click(function(){
        var audio = new Audio();
        audio.src=this.dataset.audio;
        audio.play();
    });
</script>
</html>
