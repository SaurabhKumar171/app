<script>
function checkSub(pack){
    var lang = $("#sel_lenguage").val();
    window.location.href="<?php echo base_url();?>welcome/ksa_he/"+pack+"/"+lang;
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

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<!-- <div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>Monthly Pack @ QAR 40/month</b></button>
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
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Daily Pack @ SAR 1/day</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">You will be charged 1 SAR per day , You will start the paid subscription after the free period automatically.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send UNSUBMC to 708900 for Zain customers and send U98 to 600990 for Mobily for free.<br/>Your subscription will be automatically renewed every day until you unsubscribe, Standard data browsing casts will be applied.<br/>To make use of this service you must be more than 18 year old or have received permission from your parent or person who is authorized to pay your bill.<br/>For any inquires please contact us on support@pheuture.com</p></div></center>
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

<!-- <div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>تجربة مجانية ليوم واحد</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<!-- <div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>حزمة الشهرية @ ريال قطري 40/شهر</b></button>
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
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>الباقة اليومية @ ريال 1 / يوم</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12" dir="rtl">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
</body>
</html>