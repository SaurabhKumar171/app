<?php
/*$date=gmdate("Y-m-d H:i:s\Z");
$lang="en";
$theme="light";
$fullscreen = "1";
$publicKey="qpY078eSE7WeS5fAcM13";
$privateKey="YBOblVj8ZdFGijE9elgn";
$digest=$publicKey.":".hash_hmac ("sha256",$date.$lang.$theme.$fullscreen , $privateKey);
$js = "http://enrichment-staging.tpay.me/idxml.ashx/js-staging?date=".$date."&lang=".$lang."&theme=".$theme."&fullscreen=".$fullscreen."&digest=".$digest."&simulate=true&operatorcode=60201&msisdn=201286438693";*/
?>
<!-- <script src="<?php //echo $js;?>"></script> -->
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
span.bold-red {
    color: red;
    font-weight: bold;
}
</style>
<script>
/*$(document).ready(function(){
    var chk_he = TPay.HeaderEnrichment.enriched();
    if(chk_he){
        alert(TPay.HeaderEnrichment.operatorCode());
    }
    else{
        alert("false");
    }
});*/

/*setInterval(function(){checkSub();}, 5000);

function checkSub(){
    $.ajax({
        url:"<?php echo base_url(); ?>chkDubaiSub",
        success:function(data){
            console.log(data);
            if(data>0){
                window.location.href="<?php echo base_url(); ?>";
            }
        },
        error: function(data) {
            //alert('There was some error. Please try again.');
        }
    });
}*/
</script>
<?php if($lang=="en"){ ?>
<div class="row">
<div class="col-md-12">
<div class="col-md-3"></div>
<div class="col-md-6">
    <center>
    <div id="d1">
    <div class='error' style='text-align:center'>
      <?php
         if(isset($error))
            echo $error;
         ?>
    </div>
    <h1 class="subheading">Please confirm your Unsubscription request</h1>

    <div class="edit_btn" style="margin-top: 40px;">
        <a class="sub-btn" href="<?php echo base_url();?>welcome/egy_unsub">
            <div class="header" style="overflow:visible;">
                Yes
            </div>
        </a>
    </div>
    <div class="edit_btn" style="margin-top: 20px;">
        <a class="sub-btn" href="<?php echo base_url();?>viewprofile">
            <div class="header" style="overflow:visible;">
                No
            </div>
        </a>
    </div>
    </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;">
    <div class="footer-copyright text-center py-3">To cancel your subscription, please send Unsub MC for free to 5030 for Orange subscribers and to 2303 for Vodafone subscribers and to 4041 for WE subscribers and to 1722 for Etisalat subscribers.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com<br/>Terms and conditions apply.</div>
</footer>
<?php }else{ ?>
<div class="row">
<div class="col-md-12">
<div class="col-md-3"></div>
<div class="col-md-6">
    <center>
    <div id="d1">
    <div class='error' style='text-align:center'>
      <?php
         if(isset($error))
            echo $error;
         ?>
    </div>
    <h1 class="subheading">يرجى تأكيد طلب إلغاء الاشتراك الخاص بك</h1>

    <div class="edit_btn" style="margin-top: 40px;">
        <a class="sub-btn" href="<?php echo base_url();?>welcome/egy_unsub">
            <div class="header" style="overflow:visible;">
                نعم
            </div>
        </a>
    </div>
    <div class="edit_btn" style="margin-top: 20px;">
        <a class="sub-btn" href="<?php echo base_url();?>viewprofile">
            <div class="header" style="overflow:visible;">
                لا
            </div>
        </a>
    </div>
    </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3">لإلغاء اشتراكك ، يرجى إرسال Unsub MC مجانا إلى 5030 لمشتركي Orange وإلى 2303 لمشتركي Vodafone وإلى 4041 لمشتركي WE وإلى 1722 لمشتركي Etisalat.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com<br/>تطبق الشروط و الاحكام</div>
</footer>
<?php } ?>