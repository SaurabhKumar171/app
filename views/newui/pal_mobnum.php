<script>
/*function checkSub(){
    window.location.href="<?php echo base_url().'welcome/pa_home?mobnum='.$mobnum; ?>";
}*/

function submit(){
    console.log("reach");
    var lang = "<?php echo $lang; ?>";
    var tid = "<?php echo $tid; ?>";
    var selOperator = $("#selOperator").val();
    var mobnum = $("#mobnum").val();

    if(mobnum.length==9){
        mobnum = '972'+mobnum;
    }
    /*else if(mobnum.length==11){
        mobnum = '2'+mobnum;
    }*/
    //console.log(mobnum.length);

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
    else if(mobnum.length<12){
        alert(a3);
    }
    else{
        var dataString='OperatorCode='+selOperator+'&Msisdn='+mobnum+'&OrderId='+tid+'&ReferenceCode=';
        console.log(dataString);
        window.location.href="<?php echo base_url().'welcome/pal_consent_callback?'; ?>"+dataString; 
    }  
}
function exit(){
    window.location.href="<?php echo base_url();?>"; 
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
            <option value="42506">Ooredoo - Palestine</option>
        </select>
        </div>
    </div>
</div>
<div style="margin-top:15px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="left-inner-addon ">                
                <span class="countryCode">&nbsp;</span>
                <input maxlength="12" value="" type="tel" id="mobnum" name="mobnum" class="form-control" onblur="this.placeholder = 'ex. 972xxxxxxxx'" onfocus="this.placeholder = ''" placeholder="ex. 972xxxxxxxx" autocomplete="off" />
            </div>
        </div>
    </div>
</div>
<div style="margin-top:10px;"></div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail then 0.15 JOD (auto-renewal)</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> --> <!-- End of row -->


<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Get Pin</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" style="background-color: red;" class="form-control subscribe_btn" onclick="exit()"><b>Exit</b></button>
</div>
</div>
</div>

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
    <!-- <form style="margin-top: 20px;"> -->
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="selOperator" name="selOperator">
        <!-- <option value="">Select Operator</option> -->
        <option value="42506">Ooredoo - Palestine</option>
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
                <input maxlength="12" value="" type="tel" id="mobnum" name="mobnum" class="form-control" onblur="this.placeholder = 'ex. 972xxxxxxxx'" onfocus="this.placeholder = ''" placeholder="ex. 972xxxxxxxx" autocomplete="off" />
            </div>
        </div>
    </div>
</div>
<div style="margin-top:10px;"></div>

<!-- <div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b><?php echo $pack; ?></b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> --> <!-- End of row -->


<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" class="form-control subscribe_btn" onclick="submit()"><b>احصل على  رمز التحقيق</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="button" style="background-color: red;" class="form-control subscribe_btn" onclick="exit()"><b>خروج</b></button>
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
