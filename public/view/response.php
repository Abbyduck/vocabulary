<!DOCTYPE html>
<html>
<header>


</header>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>hahah</title>
<body>
<div id="container">
<center><strong> <?php echo @$msg; ?></strong></center>


<?php if(@$location) { ?>
    <center><span id="redirect">Redirect to Review page after <span id="second"></span> seconds.</span></center>
    <script>
        var ua=window
        var seceond = 3;
        function showTime() {
            document.getElementById('second').innerHTML =seceond;
            seceond -= 1;
            if (seceond < 0) {
                document.getElementById('container').innerHTML ='<center>Jump</center>';
                if(navigator.userAgent.toLowerCase().indexOf("micromessenger"))
                {
                    window.location.href="?s=WordsPool.getReviewWords&openid=<?php echo @$openid; ?>&id="+10000*Math.random();
                }else{
                    location.href ="?s=WordsPool.getReviewWords&openid=<?php echo @$openid; ?>";
                }
                clearInterval( setTimeout(function() { showTime() },1000));
            }else{
                setTimeout(function() { showTime() },1000);
                console.log(seceond);
            }
        }
        showTime();
    </script>

<?php } ?>


</div>

</body>


</html>
