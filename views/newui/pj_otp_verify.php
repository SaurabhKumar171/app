<body>
<link rel="stylesheet" href="http://mcomics.club/css/signup.css">
<style type="text/css">
    #signupForm{
        margin-top: 5px !important;
    }
</style>
<section style="padding-top: 15px; padding-bottom: 15px;">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
      <center>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mx-auto">          
        <img style="text-align: center;" src="https://mcomics.club/images/meme/mtoon-logo-50.png"> </div></center>
      <center>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mx-auto">
          <!-- <a href="#"><img src="https://mcomics.club/images/meme/profile.png" width="25px;"></a> -->
      </div>
      </center>
    </div>
  </div>
</section>

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
        <img src="https://mcomics.club/images/meme/ar/create_cartoon.png" alt="" style="">
      </div>

      <div class="item">
        <img src="https://mcomics.club/images/meme/ar/create_comics.png" alt="" style="">
      </div>
    
      <div class="item">
        <img src="https://mcomics.club/images/meme/ar/bg_effect.png" alt="" style="">
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
        <div style="margin-top:0px;"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <div class="left-inner-addon ">
                        <span class="countryCode">&nbsp;</span>
                        <input maxlength="4" value="" type="tel" id="otp_pj" name="otp_pj" class="form-control" onblur="this.placeholder = '???????? OTP'" onfocus="this.placeholder = ''" placeholder="???????? OTP" autocomplete="off" />
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top:10px;"></div>
        <div class="row">
            <div class="col-md-12">
            <div class="result" style="color:#000;text-align:center;">&nbsp;</div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <button style="cursor: pointer;" type="submit" class="form-control edit_btn nextbtn_pj"><b>????????????????</b></button>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-4">
            <center><p style="padding-top: 10px;"><b>?????????? ???? ???? Mtoon. ?????????????????? ?? ???????? ?????????? 1.16 ???????? / ??????????.</b></p></center>
          </div>
          <div class="col-md-4"></div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12" dir="rtl">
            <div class="col-md-4"></div>
            <center><div class="col-md-4"><p class="subscription_det">Mtoon?????????? ???? ????.<br/>Mtoon ???????? ?????? ?????? ?????????? ?????????? ?????????????? ???????????? ?????????? ?????? ?????????? ?????????? ??????????.<br/>?????? ???????????? 1.16 ??.?? ???????? ??????????<br/>?????????? ?????????? ???????????????? ???? ???? ??????. ?????????????? ?? ???????? ???????????????? ?????? ?????? ?????????????? ?????????? ???? ???????????? ?????? [?????????? ????????????????]</p></div></center>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>

</body>
<script>
$(".nextbtn_pj").click(function(e){
    e.preventDefault();
    e.stopPropagation(); 
    var otp=$('#otp_pj').val();
    if(otp==""){
        $("#showerror_msg").show();
        $('#otp_pj').focus();
        $('#otp_pj').css("border-color", "red");
        $("#showerror_msg").html("???????????? ?????????? OTP !");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#otp_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else if(otp.length<4){
        $("#showerror_msg").show();
        $('#otp_pj').focus();
        $('#otp_pj').css("border-color", "red");
        $("#showerror_msg").html("???????????? ?????????? OTP ???????????? !");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#otp_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else{
        var mob = "<?php echo $mobnum; ?>";
        window.location.href="http://mtoonapp.com/pal_verify?mobnum="+mob+"&otp="+otp;
    }
});
</script>

</html>