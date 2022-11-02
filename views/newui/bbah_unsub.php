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
        <div class="col-md-4"></div>
        <center>
            <!-- <div class="col-md-4"><h5><b><?php echo $subtype; ?> (Subject to 10%VAT)</b></h5></div> -->
            <div class="col-md-4"><h5><b>Please confirm your unsubscription reequest :</b></h5></div>
        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>welcome/bah_unsub"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Yes</b></button></a>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>viewprofile"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>No</b></button></a>
</div>
</div>
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

<div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <!-- <div class="col-md-4"><h5><b><?php echo $pack; ?></b></h5></div> -->
            <div class="col-md-4"><h5><b>يرجى تأكيد طلب إلغاء الاشتراك الخاص بك:</b></h5></div>
        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>welcome/bah_unsub"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>نعم</b></button></a>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>viewprofile"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>لا</b></button></a>
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
