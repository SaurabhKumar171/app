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
        url:"<?php echo base_url(); ?>welcome/ku_password_resend",
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
            url:"<?php echo base_url(); ?>welcome/ku_password_verify",
            data:dataString,
            success:function(data){
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    window.location.href="<?php echo base_url().'welcome/ku_home?mobnum='.$mobnum; ?>";
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
            <h1 class="subheading">Please enter Password to continue</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="<?php echo $id; ?>" readonly />
            </div>
            <br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="Enter Password" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' />
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        Submit
                   </div>
                </a>
            </div>
            <?php if($sms_sts==0){ ?>
                <br/><br/>
                <a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">Resend Password</a>
            <?php } ?>

        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;">
    <div class="footer-copyright text-center py-3"><?php echo $disclaimer; ?></div>
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
            <h1 class="subheading">الرجاء إدخال كلمة المرور للمتابعة</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="<?php echo $id; ?>" readonly />
            </div>
            <br/>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="pin" placeholder="أدخل كلمة المرور" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='6' />
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        إرسال
                   </div>
                </a>
            </div>
            <?php if($sms_sts==0){ ?>
                <br/><br/>
                <a class="sub-btn" href="javascript:void(0);" id='a_pass' onclick="otp()">إعادة إرسال كلمة المرور</a>
            <?php } ?>

        </div>
    </center>
</div>
<div class="col-md-3"></div>
</div>
</div>
<footer class="page-footer font-small blue"  style="margin-top: 30px;" dir="rtl">
    <div class="footer-copyright text-center py-3"><?php echo $disclaimer; ?></div>
</footer>
<?php } ?>