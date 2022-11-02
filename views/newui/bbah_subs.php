<script>
function checkSub(pack){
    var lang = $("#sel_lenguage").val();
    //alert(lang);
    //console.log(lang);
    window.location.href="<?php echo base_url();?>welcome/bah_he/"+pack+"/"+lang;
}

$(function() {
    $("#sel_lenguage").change(function() {
        var lang = $("#sel_lenguage").val();
        if(lang=="ar"){
            $("#en_secion").hide();
            $("#ar_secion").show();
        }
        else{
            $("#ar_secion").hide();
            $("#en_secion").show();
        }
    });
});
</script>
<body>

<div id="waiting" style="display:none;"></div>
<div id="spinner"></div>

<div class="container">
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="sel_lenguage" name="sel_lenguage">
        <option value="en">En</option>
        <option value="ar">Ar</option>
        </select>
        </div>
        
    </div>
</div>
</div>



<div class="container" id="en_secion">

<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')" style="height: 64px;"><b>Monthly Pack @ BHD 2.5 (Subject to 10%VAT) /month</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')" style="height: 64px;"><b>Weekly Pack @ BHD 0.7 (Subject to 10%VAT) /week</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')" style="height: 64px;"><b>Daily Pack @ BHD 0.15 (Subject to 10%VAT) /day</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">By subscribing to the service, you are accepting all Terms & Conditions of the service & authorize Batelco to share your mobile number with our partner mComics, who manages this subsription service.<br/>Your subscription will be automatically renewed and your account will be debited with selected pack per charging cycle until you unsubscribe.<br/>Un-subscription can be done on the My Account Page or by SMS by sending Unsub MCOMICS to 94466 for Batelco subscribers for free.<br/>Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br/>For any inquires please contact us on support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->



<!-- #########################  Arabic ############################## -->
<div class="container" id="ar_secion" style="display: none;">
<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')" style="height: 90px;"><b>حزمة الشهرية @ دينار بحريني 2.5 /شهر (Subject to 10%VAT)</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')" style="height: 90px;"><b>حزمة الأسبوعية @ دينار بحريني 0.7 /أسبوع (Subject to 10%VAT)</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')" style="height: 90px;"><b>حزمة اليومية @ دينار بحريني 0.15 /يوم (Subject to 10%VAT)</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12" dir="rtl">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>سيتم تجديد اشتراكك تلقائيًا وسيتم خصم حسابك مع الباقة المحددة لكل دورة شحن حتى تقوم بإلغاء الاشتراك.<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال Unsub MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
</body>
</html>