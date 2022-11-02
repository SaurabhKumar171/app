<script>
  function tc_show_en(){
      $("#main").hide();
      $("#popupen").show();
      window.scrollTo(0,0);
  }

  function close_en(){
    $("#main").show();
      $("#popupen").hide();
      window.scrollTo(0,0);
  }
</script>
<style type="text/css">
    a,a:hover,a:focus{
        color: #fff;
        text-decoration:none;
        font-size: 18px;
       /* line-height: 30px;*/
    }
    .edit_btn a{
      font-size: 17px;
    }
    .close {
  position: absolute;
  top: 0;
  right: 0;
  color: black !important;
}
   
</style>
<div id="main">
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
  <!--   <h1>Choose a Subscription</h1> -->

          <div class="header" style="overflow:visible;">              
           <h1><?php echo $msg; ?></h1>
        </div>

    <div class="edit_btn" >

        <a class="sub-btn" href="<?php echo base_url().'mtnsa_wp_subs'; ?>">
            <div class="header" style="overflow:visible;">              
               Subscribe
            </div>
        </a>
    </div>
     
    

    </div>
    </center>
</div>

<div class="col-md-4"></div>
<div class="col-md-3">
    <center style="margin-top:100px;">
        <a href="javascript:void(0);" style="color:black;" onclick="tc_show_en()" id="tc_show_en"><u><b>Terms and Conditions</b></u></a>
    </center>
</div>
</div>

<div id="popupen" class="mobilepopup" style="display: none;">
  <h2 class="close" onclick="close_en()" id="close_en">X</h2>
    <div class="mobilediv heading"><center><h1>Terms and Conditions</h1></center></div>
    <br/>
    <h2>By subscribing to the service, you are accepting all Terms & Conditions of the service:</h2>
    <br/><br/><br/>
    <div>
          Your subscription will be automatically renewed and your account will be debited with selected pack per charging cycle until you unsubscribe.
      </div>
      <br/>
      <div>
          Un-subscription can be done on the My Account Page or by SMS by sending Unsub MC to 98726.
      </div>
      <br/>
      <div>
          Standard data browsing casts will be applied. For any inquires please contact us on support@pheuture.com
      </div>
      
      <br/><br/>
</div>