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
<script>
$(document).ready(function(){
    checkSub();             
});

setInterval(function(){checkSub();}, 5000);

function checkSub(){
    $.ajax({
        url:"<?php echo base_url(); ?>chkDubaiSub",
        success:function(data){
            console.log(data);
            if(data>0){
                window.location.href="<?php echo base_url(); ?>";
            }
        },
        error: function(data) {
            //alert('There was some error. Please try again.');
        }
    });
}
</script>
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
        <div class="edit_btn" style="margin-top: 25px;">
            <a class="sub-btn" href="<?php echo base_url();?>welcome/sub_mtnnga/monthly">
                <div class="header" style="overflow:visible;">
                    Monthly Pack @ NGN 100/per month
                </div>
            </a>
        </div>
        <!-- <div class="edit_btn" style="margin-top: 25px;">
            <a class="sub-btn" href="<?php echo base_url();?>welcome/sub_mtnnga/weekly">
                <div class="header" style="overflow:visible;">
                    Weekly Pack @ NGN 50/per week
                </div>
            </a>
        </div> -->
        <div class="edit_btn" style="margin-top: 25px;">
            <a class="sub-btn" href="<?php echo base_url();?>welcome/sub_mtnnga/daily">
                <div class="header" style="overflow:visible;">
                    Daily Pack @ NGN 10/per day
                </div>
            </a>
        </div>
    </div>
    </center>
</div>
