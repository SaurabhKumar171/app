<style type="text/css">
    body, html{
        background-color: #fff !important;
        background: #fff !important;
    }
    .edit_btn {
    border: 1px solid #fff;
    border-radius: 25px;
    color: #000000;
    cursor: pointer;
    display: inline-block;
    font-size: 20px;
    padding: 18px;
    width: 100%;
    height: 60px;
    background-color: #f6d749;
    }
    .header{
      color: #000;
    }
    a,a:hover,a:focus{
        color: #000;
        text-decoration:none;
        font-size: 18px;
       /* line-height: 30px;*/
    }
    .packbutton{
        background-color: mediumseagreen;
        padding: 5px;
        margin-bottom: 5px;
    }
    .subheading{
        color: #000;
        font-weight: bold;
    }
    @media only screen and (max-width: 480px){
        .subheading{
            font-size: 24px;
        }
    }
    .edit_btn a{
      font-size: 17px;
    }
    .topcorner{
       position:absolute;
       top:0;
       right:0;
    }
</style>
<script>
function checkSub(pack){
    var lang = $("#lang").val();
    window.location.href="<?php echo base_url();?>welcome/qat_he/"+pack+"/"+lang;
}

$(function() {
    $("#lang").change(function() {
        var lang = $("#lang").val();
        if(lang=="ar"){
            $("#d1").hide();
            $("#d2").show();
            $("#d3").hide();
            $("#d4").show();
        }
        else{
            $("#d1").show();
            $("#d2").hide();
            $("#d3").show();
            $("#d4").hide();
        }
    });
});
</script>
<div class="row">
<div class="col-md-12">
<div class="col-md-3"></div>
<div class="col-md-6">
    <center>
    <div class='error' style='text-align:center'>
      <?php
         if(isset($error))
            echo $error;
         ?>
    </div>
    <div class="form-group topcorner">
        <select class="form-control" id="lang" name="lang">
            <option value="en">En</option>
            <option value="ar">Ar</option>
        </select>
    </div>
    <br/>
    <div id="d1">
        <h1 class="subheading">Choose a Subscription</h1>

        <div class="edit_btn" style="margin-top: 25px;">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('monthly')">
                <div class="header" style="overflow:visible;">
                    Monthly Pack @ QAT 30/month (1 day free)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('weekly')">
                <div class="header" style="overflow:visible;">
                    Weekly Pack @ QAT 7/week (1 day free)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('daily')">
                <div class="header" style="overflow:visible;">
                     Daily Pack @ QAT 1/day (1 day free)
               </div>
            </a>
        </div>
    </div>
    <div id="d2" style="display:none;">
        <h1 class="subheading">اختيار الاشتراك</h1>

        <div class="edit_btn" style="margin-top: 25px;">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('monthly')">
                <div class="header" style="overflow:visible;">
                    حزمة الشهرية @ ريال قطري 30/شهر (يوم واحد مجانا)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('weekly')">
                <div class="header" style="overflow:visible;">
                    حزمة الأسبوعية @ ريال قطري 7/أسبوع (يوم واحد مجانا)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('daily')">
                <div class="header" style="overflow:visible;">
                     حزمة اليومية @ ريال قطري 1/يوم (يوم واحد مجانا)
               </div>
            </a>
        </div>
    </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<div id="d3">
<footer class="page-footer font-small blue"  style="margin-top: 30px;">
    <div class="footer-copyright text-center py-3">You will start the paid subscription after the free period automatically. To cancel your subscription, please send Unsub MC to 92413 for Ooredoo subscribers and to 97814 for Vodafone subscribers. To cancel from the site please go to your profile then press Unsubscribe button. For any inquires please contact us on support@pheuture.com</div>
</footer>
</div>
<div id="d4" style="display:none;">
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3">ستبدأ الاشتراك المدفوع بعد الفترة المجانية لمدة يوم واحد تلقائيًا فترة البدء المجانية للمشتركين الجدد فقط. لإلغاء اشتراكك ،يرجى إرسال UNSUB MC الي92413 عن مشتركين اوريدو و الي 97814 عن مشتركين فوادفون .لإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم اضغط على زر إلغاء الاشتراك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</div>
</footer>
</div>