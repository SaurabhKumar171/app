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
function checkSub(){
    window.location.href="<?php echo base_url().'welcome/bah_home?mobnum='.$mobnum; ?>";
}

function submit(){
    var lang = "<?php echo $lang; ?>";
    var tid = "<?php echo $tid; ?>";
    var selOperator = $("#selOperator").val();
    var mobnum = $("#mobnum").val();

    if(mobnum.length==8){
        mobnum = '973'+mobnum;
    }
    /*else if(mobnum.length==11){
        mobnum = '2'+mobnum;
    }*/
    var a1,a2,a3;

    if(lang=="en"){
        a1 = "Please select Operator";
        a2 = "Please enter Number";
        a3 = "Please enter valid Number";
    }
    else{
        a1 = "يرجى اختيار المشغل";
        a2 = "الرجاء إدخال الرقم";
        a3 = "الرجاء إدخال رقم صحيح";
    }

    if(selOperator==''){
        alert(a1);
    }
    else if(mobnum==''){
        alert(a2);
    }
    else if(mobnum.length<11){
        alert(a3);
    }
    else{
        var dataString='OperatorCode='+selOperator+'&Msisdn='+mobnum+'&OrderId='+tid+'&ReferenceCode=';
        console.log(dataString);
        window.location.href="<?php echo base_url().'welcome/bah_consent_callback?'; ?>"+dataString; 
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
            <h1 class="subheading">Please select Operator and enter your number</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <select class="form-control" id="selOperator" name="operator">
                    <option value="">Select Operator</option>
                    <option value="42604">stc - BHR</option>
                    <option value="42602">Zain - BHR</option>
                    <option value="42601">Batelco - BHR</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="Enter Number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'>
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        Subscribe
                   </div>
                </a>
            </div>
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
    <div class="footer-copyright text-center py-3">You will subscribe in mComics for <?php echo $subtype; ?>. To cancel your subscription, please send Unsub MC to 98726 for stc subscribers and to 94005 for Zain subscribers and to 94466 for Batelco subscribers. To cancel from the site please go to your profile then press Unsubscribe button. For any inquires please contact us on support@pheuture.com</div>
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
            <h1 class="subheading">يرجى اختيار المشغل وإدخال رقمك</h1>
            <br/><br/><br/>

            <div class="form-group" style="margin-top: 25px;">
                <select class="form-control" id="selOperator" name="operator">
                    <option value="">اختر المشغل</option>
                    <option value="42604">stc - BHR</option>
                    <option value="42602">Zain - BHR</option>
                    <option value="42601">Batelco - BHR</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 25px;">
                <input type='text' class="form-control" id="mobnum" placeholder="أدخل رقم" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='12'>
            </div>
            <br/>
            <div class="edit_btn">
                <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
                    <div class="header" style="overflow:visible;">
                        الإشتراك
                   </div>
                </a>
            </div>
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
    <div class="footer-copyright text-center py-3">سوف تشترك في mComics عن <?php echo $subtype; ?>. لإلغاء اشتراكك ، يرجى إرسال Unsub MC إلى 98726 عن مشتركين stc و 94005 عن مشتركين Zain وإلى 94466 لمشتركي Batelco. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم اضغط على زر إلغاء الاشتراك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</div>
</footer>
<?php } ?>