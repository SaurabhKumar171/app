<script>
/*function checkSub(){
    window.location.href="<?php echo base_url().'welcome/qat_home?mobnum='.$mobnum; ?>";
}*/

function submit(){
    console.log("reach");
    var lang = "<?php echo $lang; ?>";
    var tid = "<?php echo $tid; ?>";
    var selOperator = $("#selOperator").val();
    var mobnum = $("#mobnum").val();

    if(mobnum.length==8){
        mobnum = '974'+mobnum;
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
        window.location.href="<?php echo base_url().'welcome/qat_consent_callback?'; ?>"+dataString; 
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
    <!-- <form  style="margin-top: 20px;"> -->
    
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="selOperator" name="selOperator">
        <!-- <option value="">Select Operator</option> -->
        <option value="42701">Ooredoo - QAT</option>
        <option value="42702">Vodafone- QAT</option>
        </select>
        </div>
    </div>
</div>
        <!-- <div style="margin-top:30px;"></div> -->
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <span class="countryCode">&nbsp;</span>
                <input maxlength="11" value="" type="tel" id="mobnum" name="mobnum" class="form-control" onblur="this.placeholder = 'ex. 97450127031'" onfocus="this.placeholder = ''" placeholder="ex. 97450127031" autocomplete="off" />
            </div>
        </div>
    </div>
</div>
<div style="margin-top:10px;"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail then <?php echo $subtype; ?> (auto-renewal)</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->


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
    <!-- <form style="margin-top: 20px;"> -->
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="selOperator" name="selOperator">
        <!-- <option value="">Select Operator</option> -->
        <option value="42701">Ooredoo</option>
        <option value="42702">Vodafone</option>
        </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">
                <span class="countryCode">&nbsp;</span>
                <input maxlength="11" value="" type="tel" id="mobnum" name="mobnum" class="form-control" onblur="this.placeholder = 'ex. 97450127031'" onfocus="this.placeholder = ''" placeholder="ex. 97450127031" autocomplete="off" />
            </div>
        </div>
    </div>
</div>
<div style="margin-top:10px;"></div>

<div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b><?php echo $pack; ?></b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->


<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>الإشتراك</b></button>
</div>
</div>
</div>


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
