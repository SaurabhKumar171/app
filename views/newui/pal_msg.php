<body>
<link rel="stylesheet" href="http://mcomics.club/css/signup.css">
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


<div class="container">
    <form id="signupForm" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
            <center><div class="alert alert-danger" role="alert" id="showerror_msg"><?php echo $msg; ?>
            </div></center>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div style="margin-top:0px;"></div>
    </form>
    <div class="row">
        <div class="col-md-12" dir="rtl">
            <div class="col-md-4"></div>
            <center><div class="col-md-4"><p class="subscription_det">Mtoonمرحبا بك في.<br/>Mtoon يهدف إلى خلق طريقة جديدة للتعبير لأولئك الذين ليس لديهم موهبة الرسم.<br/>سعر الخدمة 1.16 ش.ض تخصم يوميا<br/>يمكنك إلغاء الاشتراك في أي وقت. للإلغاء ، يرجى الانتقال إلى ملف التعريف الخاص بك والنقر على [إلغاء الاشتراك]</p></div></center>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>

</body>
<script>
$(".nextbtn_pj").click(function(e){
    e.preventDefault();
    e.stopPropagation(); 
    var mob=$('#mobnum_pj').val();
    if(mob==""){
        $("#showerror_msg").show();
        $('#mobnum_pj').focus();
        $('#mobnum_pj').css("border-color", "red");
        $("#showerror_msg").html("Please enter mobnum !");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#mobnum_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else{
        window.location.href="http://mtoonapp.com/pal_otp?mobnum="+mob;
    }
});
</script>

</html>