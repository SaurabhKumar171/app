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
    <div class='error' style='text-align:center'>
      <?php
         if(isset($error))
            echo $error;
         ?>
    </div>
    <h1 class="subheading">Choose a Subscription</h1>
    <br/><br/>
    <h1 class="subheading">Daily Pack @ Tk 2.67 (inclusive VAT+SD+SC)</h1>
    <div class="edit_btn" style="margin-bottom: 9px;">
        <!-- <a class="sub-btn" href="<?php echo base_url(); ?>bg_otp"> -->
            <!-- <a class="sub-btn" href="http://103.239.252.108/blsdp_wap/subscribe.php?appid=OyeIM&apppass=oyeim&MSISDN=<?php echo substr($mobnum,-10);?>&subscriptionroot=21270MComicPortal&subscriptiongroupid=21270MComicPortal_Daily&shortcode=21270&success_url=http://mcomics.club/welcome/sub_bg/daily&fail_url=http://mcomics.club/welcome/sub_bgfail&api=http://mcomics.club/welcome/api%3Fmdn=<?php echo $mobnum;?>%26type=daily%26a="> -->
            <a class="sub-btn" href="http://103.239.252.108/wap_based_sms_consent/subscribe.php?appid=OyeIM&apppass=oyeim&MSISDN=<?php echo substr($mobnum,-10);?>&subscriptionroot=21270MComicPortal&subscriptiongroupid=21270MComicPortal_Daily&shortcode=21270&success_url=http://mcomics.club/welcome/sub_bg/daily&fail_url=http://mcomics.club/welcome/sub_bgfail&msg=start%20mc&servicename=mComics&api=http://202.164.209.130/bg/web_callback.php">
            <div class="header" style="overflow:visible;">
                Subscribe
           </div>           
        </a>
    </div>
    <div style="color: #000;font-size:12px;">* This service is auto renewable</div>


    </div>
    </center>
</div>
