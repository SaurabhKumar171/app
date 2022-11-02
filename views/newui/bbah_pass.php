<script>
var lang = "<?php echo $lang; ?>";

function otp(){
    var mobnum = '<?php echo $mobnum; ?>';
    var oper = '<?php echo $oper; ?>';

    var dataString='oper='+oper+'&mobnum='+mobnum;

    var a1,a2;

    if(lang=="en"){
        a1 = "There was some error. Please try again.";
        a2 = "Password sent successfully";
    }
    else{
        a1 = "كان هناك بعض الخطأ. حاول مرة اخرى.";
        a2 = "تم إرسال كلمة المرور بنجاح";
    }

    $.ajax({
        url:"<?php echo base_url(); ?>welcome/bah_password_resend",
        data:dataString,
        success:function(data){
            data = data.replace(/(\r\n|\n|\r)/gm, "");
            if(data=="success"){
                alert(a2);
                $("#a_pass").hide();
            }
            else{
                alert(a1);
            }
        },
        error: function(data) {
            alert(a1);
        }
    });
}
function submit(){
    var mobnum = '<?php echo $id; ?>';
    //var mobnum = $("#mobnum").val();;
    var otp = $("#pin").val();

    var a1,a2,a3;

    if(lang=="en"){
        a1 = "There was some error. Please try again.";
        a2 = "Please enter PIN";
        a3 = "Incorrect password. Please try again.";
    }
    else{
        a1 = "كان هناك بعض الخطأ. حاول مرة اخرى.";
        a2 = "الرجاء إدخال رقم التعريف الشخصي";
        a3 = "كلمة سر خاطئة. حاول مرة اخرى.";
    }

    if(otp==''){
        alert(a2);
    }
    else{
        var dataString='mobnum='+mobnum+'&pass='+otp;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/bah_password_verify",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    window.location.href="<?php echo base_url().'welcome/bah_home?mobnum='.$mobnum; ?>";
                }
                else{
                    alert(a3);
                }
            },
            error: function(data) {
                alert(a1);
            }
        }); 
    }  
}
</script>
<body>
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
                <input type='text' class="form-control" id="mobnum" placeholder="<?php echo $id; ?>" readonly />
            </div>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <input type='text' class="form-control" id="pin" placeholder="Enter Password" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='4' />
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b><?php echo $subtype; ?> (Subject to 10%VAT)</b></h5></div>

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
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Subscribe</b></button>
</div>
</div>
</div>

<div class="row">
    <center><a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">Resend Password</a></center>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">By subscribing to the service, you are accepting all Terms & Conditions of the service & authorize Batelco to share your mobile number with our partner mComics, who manages this subsription service.<br/>Your subscription will be automatically renewed and your account will be debited with <?php echo $subtype; ?> (Subject to 10%VAT) charging cycle until you unsubscribe.<br/>Un-subscription can be done on the My Account Page or by SMS by sending Unsub MCOMICS to 94466 for Batelco subscribers for free.<br>Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
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
                <input type='text' class="form-control" id="mobnum" placeholder="<?php echo $id; ?>" readonly />
            </div>
        </div>
    </div>
</div>
</br>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <input type='text' class="form-control" id="pin" placeholder="أدخل كلمة المرور" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='4' />
            </div>
        </div>
    </div>
</div>

<div class="row" dir="rtl">
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
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>الإشتراك</b></button>
</div>
</div>
</div>

<div class="row">
    <center><a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">إعادة إرسال كلمة المرور</a></center>
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
