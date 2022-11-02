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
            url:"<?php echo base_url(); ?>welcome/qat_password_resend_login",
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
            url:"<?php echo base_url(); ?>welcome/qat_password_verify",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    var dString='id='+mobnum;
                    $.ajax({
                        url:"<?php echo base_url(); ?>welcome/qat_get_mobnum",
                        data:dString,
                        success:function(data){
                            data = data.replace(/(\r\n|\n|\r)/gm, "");
                            window.location.href="<?php echo base_url().'welcome/qat_home?mobnum='; ?>"+data;     
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
            <h1 class="subheading">Please enter Username and Password to continue</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="Enter Username" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'/>
            </div>
            <br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="Enter Password" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' />
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        Login
                   </div>
                </a>
            </div>
            <br/><br/>
            <a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">Resend Password</a>
        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;">
    <div class="footer-copyright text-center py-3">To cancel your subscription, please send Unsub MC to 92413 for Ooredoo subscribers and to 97814 for Vodafone subscribers. To cancel from the site please go to your profile then press Unsubscribe button. For any inquires please contact us on support@pheuture.com</div>
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
            <h1 class="subheading">الرجاء إدخال اسم المستخدم وكلمة المرور للمتابعة</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="ادخل اسم المستخدم" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'/>
            </div>
            <br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="أدخل كلمة المرور" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' />
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        تسجيل الدخول
                   </div>
                </a>
            </div>
            <br/><br/>
            <a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">إعادة إرسال كلمة المرور</a>
        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3">ستبدأ الاشتراك المدفوع بعد الفترة المجانية لمدة يوم واحد تلقائيًا فترة البدء المجانية للمشتركين الجدد فقط. لإلغاء اشتراكك ،يرجى إرسال UNSUB MC الي 92413 عن مشتركين اوريدو و الي 97814 عن مشتركين فوادفون .لإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم اضغط على زر إلغاء الاشتراك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</div>
</footer>
<?php } ?>
<br/><br/>