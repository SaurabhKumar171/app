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
    /*height: 60px;*/
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
    window.location.href="<?php echo base_url();?>welcome/bah_he/"+pack+"/"+lang;
}

$(function() {
    $("#lang").change(function() {
        var lang = $("#lang").val();
        console.log
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
                    Monthly Pack @ BHD 2.5 /month (5% VAT excluded)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('weekly')">
                <div class="header" style="overflow:visible;">
                    Weekly Pack @ BHD 0.7 /week (5% VAT excluded)
                </div>
            </a>
        </div>
        <div class="edit_btn">
            <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('daily')">
                <div class="header" style="overflow:visible;">
                     Daily Pack @ BHD 0.15 /day (5% VAT excluded)
               </div>
            </a>
        </div>
    </div>
    <div id="d2" style="display:none;">

    <h1 class="subheading">اختيار الاشتراك</h1>
    

    <div class="edit_btn" style="margin-top: 25px;">
        <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('monthly')">
            <div class="header" style="overflow:visible;">
                حزمة الشهرية @ دينار بحريني 2.5 /شهر (الاسعار غير خاضعة لضريبة القيمة المضافة بنسبة ٥ %)
            </div>
        </a>
    </div>
    <div class="edit_btn">
        <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('weekly')">
            <div class="header" style="overflow:visible;">
                حزمة الأسبوعية @ دينار بحريني 0.7 /أسبوع (الاسعار غير خاضعة لضريبة القيمة المضافة بنسبة ٥ %)
            </div>
        </a>
    </div>
    <div class="edit_btn">
        <a class="sub-btn" href="javascript:void(0);" onclick="checkSub('daily')">
            <div class="header" style="overflow:visible;">
                 حزمة اليومية @ دينار بحريني 0.15 /يوم (الاسعار غير خاضعة لضريبة القيمة المضافة بنسبة ٥ %)
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
    <div class="footer-copyright text-center py-3">To cancel your subscription, please send Unsub MC to 98726 for stc subscribers and to 94005 for Zain subscribers and to 94466 for Batelco subscribers. To cancel from the site please go to your profile then press Unsubscribe button. For any inquires please contact us on support@pheuture.com</div>
</footer>
</div>
<div id="d4" style="display:none;">
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3">لإلغاء اشتراكك ، يرجى إرسال Unsub MC إلى 98726 عن مشتركين stc و 94005 عن مشتركين Zain وإلى 94466 لمشتركي Batelco. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم اضغط على زر إلغاء الاشتراك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</div>
</footer>
</div>