<style type="text/css">
    a,a:hover,a:focus{
        color: #000000;
        text-decoration:none;
        font-size: 18px;
       /* line-height: 30px;*/
    }
    .edit_btn a{
      font-size: 17px;
    }
    .leftalign{
      text-align: left;
      border: 0;
    }
    span.leftalign{
      display: inline-block;
      width: 47%;
    }
    span.rightalign{
      display: inline-block;
      width: 48%;
    }
    .righttalign{
      text-align: right;
    }

    select {

  /* styling */
  /*background-color: black;*/
  border: thin solid blue;
  border-radius: 4px;
  display: inline-block;
  font: inherit;
  line-height: 1.5em;
  padding: 0.5em 3.5em 0.5em 1em;

  /* reset */

  margin: 0;      
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
    color: #000;
    border: #000;
}


/* arrows */
option{
  padding: 4px;
}
select.classic {
  background-image:
    linear-gradient(45deg, transparent 50%, white 50%),
    linear-gradient(135deg, white 50%, transparent 50%),
    linear-gradient(to right, gray, gray);
  background-position:
    calc(100% - 20px) calc(1em + 2px),
    calc(100% - 15px) calc(1em + 2px),
    100% 0;
  background-size:
    5px 5px,
    5px 5px,
    2.5em 2.5em;
  background-repeat: no-repeat;
}

select.classic:focus {
  background-image:
    linear-gradient(45deg, white 50%, transparent 50%),
    linear-gradient(135deg, transparent 50%, white 50%),
    linear-gradient(to right, gray, gray);
  background-position:
    calc(100% - 15px) 1em,
    calc(100% - 20px) 1em,
    100% 0;
  background-size:
    5px 5px,
    5px 5px,
    2.5em 2.5em;
  background-repeat: no-repeat;
  border-color: grey;
  outline: 0;
  color: #000;
}

.languagemessage{
  display: none;
}


#footer-content-cellc {
  position: absolute;
  bottom: 0;
  left: 0;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#changelanguage").change(function() {
      //get the selected value
      var selectedValue = this.value;
      //alert(selectedValue);

      //make the ajax call
      $.ajax({
          url: 'languageChange',
          type: 'GET',
          data: {option : selectedValue},
          success: function(data) {
              console.log("Data sent!");
              $(".lmsg").text(data);
              $(".languagemessage").show();
              setTimeout(function(){
                location.reload();
            } , 500); 
          }
      });
  });
});
</script>
<?php
if($this->session->userdata('cellc_sa')){
?>
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
    <div class="edit_btn" style="border: 0;" >
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
              <?php echo $subscription; ?>
            </div>
        </a>
    </div>
     <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $Pack; ?></span>
              <span class="rightalign"> <?php echo 'Daily'; ?> </span>
            </div>
        </a>
    </div>
    <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $PackAmount; ?></span>
              <span class="rightalign"> <?php echo 'R 5'; ?> </span>
            </div>
        </a>
    </div>
    <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign">Helpline</span>
              <span class="rightalign">Call us : 135</span>
            </div>
        </a>
    </div>
    </div>
    </center>
</div>
<p id="footer-content-cellc">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <center><a style="font-size:10px;" href="http://13.234.185.60/wap-cellc/IntView/terms&conditions/tc_non_adult.php"><u>Click to View Terms & Conditions</u></a></center>
    </div>
    <div class="col-md-4"></div>
</p>
<?php }
else{
?>

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
    <div class="edit_btn" style="border: 0;" >
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
              <?php echo $subscription; ?>
            </div>
        </a>
    </div>
    <?php if(!(($_SERVER['HTTP_HOST']=='q.mcomics.club') || (strpos( $mdn, '=' ) !== false )  || strlen($mdn) > 400)){ ?>
    <div class="edit_btn leftalign" >
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
              <span class="leftalign"><?php echo $Mobile; ?></span>
              <span class="rightalign"> <?php echo $mdn; ?> </span>
            </div>
        </a>
    </div>
  <?php } ?>
     <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $Pack; ?></span>
              <span class="rightalign"> <?php echo $pack; ?> </span>
            </div>
        </a>
    </div>
     <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $PackAmount; ?></span>
              <span class="rightalign"> <?php echo $amount; ?> </span>
            </div>
        </a>
    </div>
     <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $subdate; ?></span>
              <span class="rightalign"> <?php echo $SubDate; ?> </span>
            </div>
        </a>
    </div>
    <?php if($oper=="42601"){ ?>
    <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
              <?php echo $submsg; ?>
            </div>
        </a>
    </div>
    <?php } ?>
    <?php if(substr($mobnum,0,4)=="9475"){ ?>
    <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $servicestatus; ?></span>
              <span class="rightalign"> <?php echo $active; ?> </span>
            </div>
        </a>
    </div>
    <?php } 
    if(!$this->session->userdata('cellc_sa_user')){ ?>
     <div class="edit_btn leftalign">
        <a class="sub-btn">
            <div class="header" style="overflow:visible;">
               <span class="leftalign"><?php echo $clang; ?></span>
              <span class="rightalign"> <?php echo $changelanguage; ?> </span>
            </div>
        </a>
    </div>
    <?php } ?>

     <!-- <div class="edit_btn leftalign languagemessage">
        <a class="sub-btn"> 
            <div class="header" style="overflow:visible;">
               <span class="leftalign"></span>
              <span class="rightalign lmsg"> </span>
            </div>
        </a>
    </div> -->
     <div class="edit_btn" style="border: 1px solid #000;">
        <a class="sub-btn" href="<?php echo base_url().'unsubscribe_mcomics';?>">
            <div class="header" style="overflow:visible;">
               <i aria-hidden="true" class="fa fa-trash" style="font-size:20px;"></i> <?php echo $unsub; ?>
            </div>
        </a>
    </div>
<?php if($this->session->userdata('cellc_sa_user')){ ?>
<p id="footer-content-cellc">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <center><a style="font-size:10px;" href="http://13.234.185.60/wap-cellc/IntView/terms&conditions/tc_non_adult.php"><u>Click to View Terms & Conditions</u></a></center>
    </div>
    <div class="col-md-4"></div>
</p>
    <?php }
    if(substr($mobnum,0,3)=="880")    //robi
    {?>    
    <!-- <div class="edit_btn leftalign">
        <a class="sub-btn"> 
            <div class="header" style="overflow:visible;">
               <span class="leftalign">
                <a href="<?php //echo base_url();?>robifaq" target="_blank" style="color:#f00c">FAQ</a></span>
              
            </div>
        </a>
    </div> -->
    <?php
    }
}
    ?>

    </div>
    </center>
</div>
