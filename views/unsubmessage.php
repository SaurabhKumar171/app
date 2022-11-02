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
  <!--   <h1>Choose a Subscription</h1> -->

    <div class="edit_btn" >
        <a class="sub-btn" href="<?php echo base_url(); ?>">
            <div class="header" style="overflow:visible;">              
               <?php echo $msg; ?>
            </div>
        </a>
    </div>
     
    

    </div>
    </center>
</div>
