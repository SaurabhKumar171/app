<script>
function checkSub(pack){
    var lang = $("#sel_lenguage").val();
    window.location.href="<?php echo base_url();?>welcome/qat_he/"+pack+"/"+lang;
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
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>Monthly Pack @ QAR 30/month</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>Weekly Pack @ QAR 7/week</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Daily Pack @ QAR 1/day</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">If you are a new customer you will subscribe in mComics for 1-day free trail period, then you will be charged as per selected pack you can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 92413 for Ooredoo customer or to 97814 for Vodafone subscribers for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
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

<div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>تجربة مجانية ليوم واحد</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>حزمة الشهرية @ ريال قطري 30/شهر</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>حزمة الأسبوعية @ ريال قطري 7/أسبوع</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>حزمة اليومية @ ريال قطري 1/يوم</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12" dir="rtl">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">إذا كنت عميلًا جديدًا ، فستشترك في mComics لمدة تجريبية مجانية لمدة يوم واحد ، وبعد ذلك ستتم محاسبتك وفقًا لحزمة محددة يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
</body>
</html>