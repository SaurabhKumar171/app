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

    var a1,a2,a3;
    if(lang=="en"){
        a1 = "PIN sent successfully";
        a2 = 'There was some error. Please try again.'
        a3 = 'Send Verification Code Limit Exceeded';
    }
    else{
        a1 = "تم إرسال رقم التعريف الشخصي بنجاح";
        a2 = 'كان هناك بعض الخطأ. حاول مرة اخرى.';
        a3 = 'تم تجاوز حد إرسال رمز التحقق';
    }

    $.ajax({
        url:"<?php echo base_url(); ?>welcome/qat_otp_resend",
        data:dataString,
        success:function(data){
            data = data.replace(/(\r\n|\n|\r)/gm, "");
            if(data=="success"){
                alert(a1);
            }
            else if(data=="Send Verification Code Limit Exceeded"){
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
function submit(){
    var sid = <?php echo $sid; ?>;
    var tid = "<?php echo $transactionId; ?>";
    var mobnum = "<?php echo $mobnum; ?>";
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
        var dataString='sid='+sid+'&otp='+otp+'&tid='+tid+'&mobnum='+mobnum;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/qat_otp_verify",
            data:dataString,
            success:function(data){
                console.log(data);
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
<div id="waiting" style="display:none;"></div>
<div id="spinner"></div>

<div class="container" id="en_secion">

<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <input type='text' class="form-control" id="pin" placeholder="Enter PIN code" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' autocomplete="off">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail then <?php echo $subtype; ?> (auto-renewal)</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<!-- <div style="margin-top:30px;"></div> -->
<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Confirm</b></button>
</div>
</div>
</div>
<div class="row">
    <center><a class="sub-btn" href="javascript:void(0);" onclick="otp()">Resend PIN code</a></center>
</div>
 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">If you are a new customer you will subscribe in mComics for 1-day free trail period, then you will be charged <?php echo $subtype; ?> you can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 92413 for Ooredoo customer or to 97814 for Vodafone subscribers for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->

<?php }else{ ?>

<!-- #########################  Arabic ############################## -->
<div class="container" id="ar_secion">
<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <input type='text' class="form-control" id="pin" placeholder="أدخل رمز PIN" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' autocomplete="off">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b><?php echo $pack; ?></b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>تاكيد</b></button>
</div>
</div>
</div>
<div class="row" dir="rtl">
    <center><a class="sub-btn" href="javascript:void(0);" onclick="otp()">اعادة ارسال الرمز</a></center>
</div>
 <!-- End of row -->

<div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det"><?php echo $disclaimer; ?></p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
<?php } ?>
</body>
</html>
