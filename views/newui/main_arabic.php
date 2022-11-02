<style>
.bckColor{

    //background-color: #A83378;
    border:1px solid #fff;
    color:#fff;
    border-radius: 5px;
    cursor: pointer;
}
/*@media only screen and (max-width: 400px)*/
@media (min-width: 300px) and (max-width: 600px)
{
   #mainsection{
    /*margin-top: -30px;*/

  }
  .cartoon{
    margin-top: 20px;
    width: 100%;
  }
  .comics{
    margin-top: 20px;
    width: 100%;
  }
  .effect{
    margin-top: 20px;
    width: 100%;
  }
  .logo{
    /*width: 100px;*/
  }
}

@media (min-width: 600px) and (max-width: 1024px)
{
  #mainsection{
    /*margin-top: -30px;*/

  }
  .cartoon{
    margin-top: 20px;
    width: 100%;
  }
  .comics{
    margin-top: 20px;
    width: 100%;
  }
  .effect{
    margin-top: 20px;
    width: 100%;
  }
  .logo{
    /*width: 100px;*/
  }
}

@media (min-width: 1025px) and (max-width: 1280px)
{
#mainsection{
    /*margin-top: -30px;*/

  }
  .cartoon{
    margin-top: 20px;
    width: 100%;
  }
  .comics{
    margin-top: 20px;
    width: 100%;
  }
  .effect{
    margin-top: 20px;
    width: 100%;
  }
  .logo{
    /*width: 100px;*/
  }    
}
</style>

<div class="container">

<div class="row " id="mainsection">
  <div style="text-align:center;">

    <div class="col-md-12" style="margin-bottom:20px;">&nbsp;</div>
    <div class="col-md-12 settings_3">


      <div class="col-md-4 col-xs-12 bckColor" onclick="location.href='<?php echo base_url(); ?>phoCartoonEmoji'">
      <img class="cartoon img-responsive" src="<?php echo base_url(); ?>images/NewImages/Cartoons_arabic.png" />
      </div>


      <div class="col-md-4 col-xs-12 bckColor" onclick="location.href='<?php echo base_url(); ?>phoCartoon'">
      <img class="comics img-responsive" src="<?php echo base_url(); ?>images/NewImages/Comics_arabic.png" />
      </div>


      <div class="col-md-4 col-xs-12 bckColor" onclick="location.href='<?php echo base_url(); ?>backgroundEffect'">
      <img class="effect img-responsive" src="<?php echo base_url(); ?>images/NewImages/Effects_arabic.png" />
      </div>

    </div>
  </div>
</div>
<div class="col-md-12" style="margin-top:30px;text-align:center">
  <?php
    if($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
        echo "<a href='http://sd.mcomics.club/terms_condition.php' target='_blank'>T&C</a> | <a href='http://sd.mcomics.club/privacy.php' target='_blank'>Privacy Policy</a>";
    }

  ?>
</div>
<div class="row">
<div class="col-md-12" style="margin-bottom:100px;">
    &nbsp;
</div>
</div>
 <?php
$check=$this->session->userdata("showlogo");
if($check=="yes")
{
?>
<center>Powered By Moodit</center>
<?php } ?>
</div>

</body>
</html> 
