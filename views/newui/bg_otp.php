<script>
function checkSub(){
    window.location.href="http://103.239.252.108/blsdp_wap/subscribe.php?appid=OyeIM&apppass=oyeim&MSISDN=<?php echo substr($mobnum,-10);?>&subscriptionroot=21270MComicPortal&subscriptiongroupid=21270MComicPortal_Daily&shortcode=21270&success_url=http://mcomics.club/welcome/sub_bg/daily&fail_url=http://mcomics.club/welcome/sub_bgfail&api=http://mcomics.club/welcome/api%3Fmdn=<?php echo $mobnum;?>%26type=daily%26a=";
}

function submit(){
    var mobnum = "<?php echo $mobnum; ?>";
    var otp = $("#pin").val();

    var a1,a2,a3;
    var lang="en";
    if(lang=="en"){
        a1 = "Please enter OTP";
        a2 = 'There was some error. Please try again.'
        a3 = "Invalid OTP. Please try again.";
    }

    if(otp==''){
        alert(a1);
    }
    else{
        var dataString='otp='+otp+'&mobnum='+mobnum;
        $.ajax({
            url:"<?php echo base_url(); ?>welcome/bg_otp_verify",
            data:dataString,
            success:function(data){
                console.log(data);
                data = data.replace(/(\r\n|\n|\r)/gm, "");
                if(data=="success"){
                    setInterval(function(){checkSub();}, 2000);
                    $("#d1").hide();
                    $("#d2").show();

                }
                else if(data=="Invalid Pincode"){
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
}
</script>
<style type="text/css">
    body, html{
        background-color: #fff !important;
        background: #fff !important;
    }
    .edit_btn {
        /*border: 1px solid #000;
        background-color: #f6d749;*/

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
    a,a:hover,a:focus{
        color: #000;
        text-decoration:none;
        font-size: 18px;
       /* line-height: 30px;*/
    }
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
<div class="col-md-3"></div>
<div class="col-md-6">
    <center>
    <div id="d1">
    
    <h1 class="subheading">Please enter OTP</h1>

    <div class="col-md-4"></div>
    <div class="col-md-4" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="left-inner-addon ">
            <input type='text' class="form-control" id="pin" placeholder="OTP" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='4' autocomplete="off">
        </div>
    </div>
    <div class="col-md-4"></div>

    <div class="edit_btn" style="margin-bottom: 9px;">
        <a class="sub-btn" href="javascript:void(0);" onclick="submit()">
            <div class="header" style="overflow:visible;"><b>Confirm</b></div>           
        </a>
    </div>
    <div style="color: #000;font-size:12px;">* This service is auto renewable</div>


    </div>
    <div id="d2" style="display: none;">
        <h1 class="subheading">OTP Verified. Please wait...</h1>
    </div>
    </center>
</div>
