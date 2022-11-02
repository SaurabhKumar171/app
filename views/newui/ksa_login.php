<script>
var lang = "<?php echo $lang; ?>";
function otp(){
    var mobnum = $("#mobnum").val();

    var a1,a2,a3,a4;

    if(lang=="en"){
        a1 = "There was some error. Please try again.";
        a2 = "Password sent successfully";
        a3 = "Please enter Username";
        a4 = "This username does not exist. Please check.";
    }
    else{
        a1 = "كان هناك بعض الخطأ. حاول مرة اخرى.";
        a2 = "تم إرسال كلمة المرور بنجاح";
        a3 = "الرجاء إدخال اسم المستخدم";
        a4 = "اسم المستخدم هذا غير موجود. يرجى المراجعة.";
    }

    if(mobnum==''){
        alert(a3);
    }
    else{
        var dataString='id='+mobnum;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/ksa_password_resend_login",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    alert(a2);
                    //$("#a_pass").hide();
                }
                else{
                    alert(a4);
                }
            },
            error: function(data) {
                alert(a1);
            }
        });
    }

    
}
function submit(){
    var mobnum = $("#mobnum").val();
    var otp = $("#pin").val();

    var a1,a2,a3,a4;

    if(lang=="en"){
        a1 = "There was some error. Please try again.";
        a2 = "Please enter Password";
        a3 = "Please enter Username";
        a4 = "Incorrect password. Please try again.";
    }
    else{
        a1 = "كان هناك بعض الخطأ. حاول مرة اخرى.";
        a2 = "الرجاء إدخال كلمة المرور";
        a3 = "الرجاء إدخال اسم المستخدم";
        a4 = "كلمة سر خاطئة. حاول مرة اخرى.";
    }

    if(mobnum==''){
        alert(a3);
    }
    else if(otp==''){
        alert(a2);
    }
    else{
        var dataString='mobnum='+mobnum+'&pass='+otp;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/ksa_password_verify",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    var dString='id='+mobnum;
                    $.ajax({
                        url:"<?php echo base_url(); ?>welcome/ksa_get_mobnum",
                        data:dString,
                        success:function(data){
                            data = data.replace(/(\r\n|\n|\r)/gm, "");
                            window.location.href="<?php echo base_url().'welcome/ksa_home?mobnum='; ?>"+data;     
                        },
                        error: function(data) {
                            alert(a1);
                        }
                    });
                }
                else{
                    alert(a4);
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
                <input type='text' class="form-control" id="mobnum" placeholder="Enter Username" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'/>
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
                <input type='text' class="form-control" id="pin" placeholder="Enter Password" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' />
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
</div>  -->

<!-- <div style="margin-top:30px;"></div> -->
<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Submit</b></button>
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
                <input type='text' class="form-control" id="mobnum" placeholder="ادخل اسم المستخدم" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'/>
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

<!-- <div class="row" dir="rtl">
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
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>تسجيل الدخول</b></button>
</div>
</div>
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
