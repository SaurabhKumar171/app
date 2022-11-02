<body>
<link rel="stylesheet" href="http://mcomics.club/css/signup.css">
<!-- New section -->
<style type="text/css">
    #signupForm{
        margin-top: 5px !important;
    }
</style>

<section class="slider">
<div class="container">
    <div class="row">
    <div class="col-sm-4"></div>

    <div class="col-sm-4" style="padding-left: 0px !important; padding-right: 0px !important;">
      <!-- Slider -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="1500">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <img src="https://mcomics.club/images/meme/create_cartoon.png" alt="" style="">
      </div>

      <div class="item">
        <img src="https://mcomics.club/images/meme/create_comics.png" alt="" style="">
      </div>
    
      <div class="item">
        <img src="https://mcomics.club/images/meme/bg_effect.png" alt="" style="">
      </div>
    </div>
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
  </div>
  <!-- End Slider -->
</div>
    <div class="col-sm-4"></div>
</div> <!--The end row class -->
</div>    
</section>




<div class="container">
    <form id="signupForm" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
            <center><div style="display: none;" class="alert alert-danger" role="alert" id="showerror_msg">
            </div></center>
            </div>
            <div class="col-md-4"></div>
        </div>
        <!-- <div style="margin-top:0px;"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <div class="left-inner-addon ">
                        <span class="countryCode">&nbsp;</span>
                        <input maxlength="12" value="<?php if(isset($_SESSION['mobnum'])){ echo $_SESSION['mobnum'];} ?>" type="tel" id="mobnum_pj" name="mobnum_pj" class="form-control" onblur="this.placeholder = 'Enter Number'" onfocus="this.placeholder = ''" placeholder="Enter Number" autocomplete="off" />
                    </div>
                </div>
            </div>
        </div> -->
        <div style="margin-top:10px;"></div>
        <div class="row">
            <div class="col-md-12">
            <div class="result" style="color:#000;text-align:center;">&nbsp;</div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <button style="cursor: pointer;" type="submit" class="form-control edit_btn nextbtn_pj"><b>Daily Pack @ LKR6 + Tax/day</b></button>
                    <Br/>
                    <center><a href="terms_condition.php" target="_blank">T&C</a> | <a href="privacy.php" target="_blank">Privacy Policy</a></center>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
<script>
$(".nextbtn_pj").click(function(e){
    /*e.preventDefault();
    e.stopPropagation(); 
    var mob=$('#mobnum_pj').val();
    if(mob==""){
        $("#showerror_msg").show();
        $('#mobnum_pj').focus();
        $('#mobnum_pj').css("border-color", "red");
        $("#showerror_msg").html("Please enter the number!");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#mobnum_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else if(mob.length<9){
        $("#showerror_msg").show();
        $('#mobnum_pj').focus();
        $('#mobnum_pj').css("border-color", "red");
        $("#showerror_msg").html("Please enter the correct number!");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#mobnum_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else{
        window.location.href="http://sd.mcomics.club/sd_otp?mobnum="+mob;
    }*/
    window.location.href="http://sd.mcomics.club/sd_url";
});
</script>

</html>