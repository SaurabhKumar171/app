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
    <h1 class="subheading">Please confirm your Unsubscription request</h1>

    <div class="edit_btn" style="margin-top: 40px;">
        <a class="sub-btn" href="<?php echo base_url();?>welcome/kuw_unsub">
            <div class="header" style="overflow:visible;">
                Yes
            </div>
        </a>
    </div>
    <div class="edit_btn" style="margin-top: 20px;">
        <a class="sub-btn" href="<?php echo base_url();?>viewprofile">
            <div class="header" style="overflow:visible;">
                No
            </div>
        </a>
    </div>
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
    <h1 class="subheading">يرجى تأكيد طلب إلغاء الاشتراك الخاص بك</h1>

    <div class="edit_btn" style="margin-top: 40px;">
        <a class="sub-btn" href="<?php echo base_url();?>welcome/kuw_unsub">
            <div class="header" style="overflow:visible;">
                نعم
            </div>
        </a>
    </div>
    <div class="edit_btn" style="margin-top: 20px;">
        <a class="sub-btn" href="<?php echo base_url();?>viewprofile">
            <div class="header" style="overflow:visible;">
                لا
            </div>
        </a>
    </div>
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