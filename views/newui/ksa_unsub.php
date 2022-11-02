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
            <div class="col-md-4"><h5><b>Kindly confirm if you want to cancel your subscription</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>welcome/ksa_unsub"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>Yes</b></button></a>
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

<div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>يرجى تأكيد ما إذا كنت تريد إلغاء اشتراكك</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<a class="sub-btn" href="<?php echo base_url();?>welcome/ksa_unsub"><button type="button" class="form-control subscribe_btn" onclick="submit()"><b>نعم</b></button></a>
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
