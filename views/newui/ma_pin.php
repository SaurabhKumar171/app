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
    window.location.href="<?php echo base_url().'welcome/ma_home?mobnum='.$mobnum; ?>";
}
function otp(){
    var sid = <?php echo $sid; ?>;

    var dataString='sid='+sid;

    var a1,a2,a3;
    if(lang=="en"){
        a1 = "PIN sent successfully";
        a2 = 'There was some error. Please try again.';
        a3 = 'Send Verification Code Limit Exceeded';
    }
    else if(lang=="fr"){
        a1 = "Code PIN envoyé avec succès";
        a2 = 'Il y a eu une erreur. Veuillez réessayer.';
        a3 = "Limite d'envoi du code de vérification dépassée";
    }
    else{
        a1 = "تم إرسال رقم التعريف الشخصي بنجاح";
        a2 = 'كان هناك بعض الخطأ. حاول مرة اخرى.';
        a3 = 'تم تجاوز حد إرسال رمز التحقق';
    }

    $.ajax({
        url:"<?php echo base_url(); ?>welcome/ma_otp_resend",
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

    var a1,a2,a3,a4,a5;
    if(lang=="en"){
        a1 = "Please enter PIN";
        a2 = 'There was some error. Please try again.';
        a3 = "Invalid Pincode";
        a4 = "Sorry, we couldn’t complete your registration, wait a couple of minutes and try again.";
        a5 = "Sorry, you don’t have enough Credit.";
    }
    else if(lang=="fr"){
        a1 = "Veuillez entrer le NIP";
        a2 = 'Il y a eu une erreur. Veuillez réessayer.';
        a3 = "Code PIN invalide";
        a4 = "Désolé, nous n’avons pas pu terminer votre inscription, attendez quelques minutes et essayez à nouveau.";
        a5 = "Désolé, vous n’avez pas assez de crédit.";
    }
    else{
        a1 = "الرجاء إدخال رقم التعريف الشخصي";
        a2 = 'كان هناك بعض الخطأ. حاول مرة اخرى.';
        a3 = "الرمز السري غير صالح";
        a4 = "عذرًا ، لم نتمكن من إكمال تسجيلك ، انتظر بضع دقائق وحاول مرة أخرى.";
        a5 = "عذرًا ، ليس لديك رصيد كافٍ.";
    }

    if(otp==''){
        alert(a1);
    }
    else{
        var dataString='sid='+sid+'&otp='+otp+'&tid='+tid+'&mobnum='+mobnum;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/ma_otp_verify",
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
                else if(data=="dr"){
                    alert(a4);
                }
                else if(data=="ipf"){
                    alert(a5);
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

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail then <?php echo $subtype; ?> (auto-renewal)</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

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
        <center><div class="col-md-4"><p class="subscription_det"><?php echo $disclaimer; ?></p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->

<?php }else if($lang=="fr"){ ?>
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
                <input type='text' class="form-control" id="pin" placeholder="Entrez le code PIN" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' autocomplete="off">
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail then <?php echo $subtype; ?> (auto-renewal)</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<!-- <div style="margin-top:30px;"></div> -->
<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Confirmer</b></button>
</div>
</div>
</div>
<div class="row">
    <center><a class="sub-btn" href="javascript:void(0);" onclick="otp()">Renvoyer le code PIN</a></center>
</div>
 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det"><?php echo $disclaimer; ?></p></div></center>
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

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b><?php echo $pack; ?></b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>تأكد</b></button>
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
