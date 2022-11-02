<script>
function checkSub(pack){
    //var lang = $("#sel_lenguage").val();
    window.location.href="<?php echo base_url();?>welcome/ken_he/"+pack;
}

$(function() {
    $("#sel_lenguage").change(function() {
        var lang = $("#sel_lenguage").val();
        if(lang=="ar"){
            $("#en_secion").hide();
            $("#ar_secion").show();
        }
        else{
            $("#ar_secion").hide();
            $("#en_secion").show();
        }
    });
});
</script>
<body>

<div id="waiting" style="display:none;"></div>
<div id="spinner"></div>

<!-- <div class="container">
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="sel_lenguage" name="sel_lenguage">
        <option value="en">En</option>
        <option value="ar">Ar</option>
        </select>
        </div>
        
    </div>
</div>
</div> -->



<div class="container" id="en_secion">

<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>Monthly Pack @ KES 200/month</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>Weekly Pack @ KES 50/week</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Daily Pack @ KES 10/day</b></button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det">1 Day free trail for new subscribers only , then you will be charged as per selected pack.<br/>You will start the paid subscription after the free period automatically.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send UNSUB MC to 70080 for Telkom customers for free.<br>Your subscription will be automatically renewed every day until you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->
</body>
</html>