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
<img class="cartoon img-responsive" src="<?php echo base_url(); ?>images/NewImages/Cartoons.png" />
</div>


<div class="col-md-4 col-xs-12 bckColor" onclick="location.href='<?php echo base_url(); ?>phoCartoon'">
<img class="comics img-responsive" src="<?php echo base_url(); ?>images/NewImages/Comics.png" />
</div>


<div class="col-md-4 col-xs-12 bckColor" onclick="location.href='<?php echo base_url(); ?>backgroundEffect'">
<img class="effect img-responsive" src="<?php echo base_url(); ?>images/NewImages/Effects.png" />
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

 <?php
$check=$this->session->userdata("qataruser");
$qatarlang=$this->session->userdata("qatarlang");
if($check=="yes")
{
?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <?php
        if($qatarlang=="en"){ ?>
        <center><div class="col-md-4"><p class="subscription_det">If you are a new customer you will subscribe in mComics for 1-day free trail period, then you will be charged as per selected pack you can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 92413 for Ooredoo customer or to 97814 for Vodafone subscribers for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com</p></div></center>
        <?php
        }else{ ?>
        <center><div class="col-md-4"><p class="subscription_det">إذا كنت عميلًا جديدًا ، فستشترك في mComics لمدة تجريبية مجانية لمدة يوم واحد ، وبعد ذلك ستتم محاسبتك وفقًا لحزمة محددة يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك. لأية استفسارات يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <?php } ?>
        <div class="col-md-4"></div>
    </div>
</div>
<?php } ?>
</div>


</body>
</html> 
