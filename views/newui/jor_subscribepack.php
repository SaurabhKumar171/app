<script>
function checkSub(pack){
    var lang = $("#sel_lenguage").val();
    window.location.href="<?php echo base_url();?>welcome/jor_he/"+pack+"/"+lang;
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
            <button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Subscribe</b></button>
                <h3>Daily Pack @ JOD 0.15/day</h3>
        </div>
    </div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">1 Day free trial for new subscribers only for Orange and Umniah, then you will be charged 0.15 JOD daily. No free trial for Zain<br/>You will start the paid subscription after the free period automatically for Orange and Umniah.<br/>You can unsubscribe any time, to cancel from mComics portal, please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange users, to 91825 for Umniah users and to 97970 for Zain users for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe.<br>Standard data browsing charges will be applied.<br>To make use of this service you must be more than 18 year old or have received permission from your parent or person who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
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
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>الإشتراك</b></button>
<h3>الباقة اليومية @ 0.15 دينار أردني / يوم</h3>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12" dir="rtl">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">درب مجاني ليوم واحد للمشتركين الجدد فقط في Orange و Umniah  ، بعد ذلك سيتم تحصيل 0.15 دينار أردني في اليوم ، بدون نسخة تجريبية مجانية لـ Zain، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية لـ Orange و Umniah.<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
</body>
</html>