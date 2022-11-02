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
    /*a,a:hover,a:focus{
        color: #000;
        text-decoration:none;
        font-size: 18px;
    }*/
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

</style>
<script>
var lang = "<?php echo $lang; ?>";
console.log("1 : "+lang);

function checkSub(){
    window.location.href="<?php echo base_url().'welcome/qat_home?mobnum='.$mobnum; ?>";
}
function otp(){
    var sid = <?php echo $sid; ?>;

    var dataString='sid='+sid;

    var a1,a2;
    if(lang=="en"){
        a1 = "PIN sent successfully";
        a2 = 'There was some error. Please try again.'
    }
    else{
        a1 = "تم إرسال رقم التعريف الشخصي بنجاح";
        a2 = 'كان هناك بعض الخطأ. حاول مرة اخرى.';
    }

    $.ajax({
        url:"<?php echo base_url(); ?>welcome/qat_otp_resend",
        data:dataString,
        success:function(data){
            data = data.replace(/(\r\n|\n|\r)/gm, "");
            if(data=="success"){
                alert(a1);
            }
            else{
                alert(a2);
            }
        },
        error: function(data) {
            alert(a2);
        }
    });
}
function submit(){
    var sid = <?php echo $sid; ?>;
    var otp = $("#pin").val();

    var a1,a2,a3;
    if(lang=="en"){
        a1 = "Please enter PIN";
        a2 = 'There was some error. Please try again.'
        a3 = "Invalid Pincode";
    }
    else{
        a1 = "الرجاء إدخال رقم التعريف الشخصي";
        a2 = 'كان هناك بعض الخطأ. حاول مرة اخرى.';
        a3 = "الرمز السري غير صالح";
    }

    if(otp==''){
        alert(a1);
    }
    else{
        var dataString='sid='+sid+'&otp='+otp;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/qat_otp_verify",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    setInterval(function(){checkSub();}, 5000);
                    $("#d1").hide();
                    $("#d2").show();

                }
                else if(data=="Invalid Pincode"){
                    alert(a3);
                }
                else{
                    alert(a2);
                }
            },
            error: function(data) {
                alert(a2);
            }
        }); 
    }  
}
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
            <h1 class="subheading">Please enter PIN code to Subscribe</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="Enter PIN code" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6'>
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        Subscribe
                   </div>
                </a>
            </div>
            <br/><br/>
            <a class="sub-btn" href="javascript:void(0);" onclick="otp()">Resend PIN code</a>

        </div>

        <div id="d2" style="display:none;">
            <div class="header" style="overflow:visible;">
                Your subscription is successful.
            </div>
        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;">
    <div class="footer-copyright text-center py-3">You will subscribe in mComics for 1 day FREE trial then <?php echo $subtype; ?>. You will start the paid subscription after the free period automatically. To cancel your subscription, please send Unsub MC to 92413 for Ooredoo subscribers and to 97814 for Vodafone subscribers. To cancel from the site please go to your profile then press Unsubscribe button. For any inquires please contact us on support@pheuture.com</div>
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
            <h1 class="subheading">الرجاء إدخال رمز PIN للاشتراك</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="أدخل رمز PIN code" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6'>
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        الإشتراك
                   </div>
                </a>
            </div>
            <br/><br/>
            <a class="sub-btn" href="javascript:void(0);" onclick="otp()">اعادة ارسال الرمز</a>

        </div>

        <div id="d2" style="display:none;">
            <div class="header" style="overflow:visible;">
                اشتراكك ناجح
            </div>
        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3">سوف تشترك في mComics إلى عن على نسخة تجريبية مجانية ليوم واحد <?php echo $subtype; ?>. ستبدأ الاشتراك المدفوع بعد الفترة المجانية لمدة يوم واحد تلقائيًا فترة البدء المجانية للمشتركين الجدد فقط. لإلغاء اشتراكك ،يرجى إرسال UNSUB MC الي92413 عن مشتركين اوريدو و الي 97814 عن مشتركين فوادفون .لإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم اضغط على زر إلغاء الاشتراك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</div>
</footer>
<?php } ?>