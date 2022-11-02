<script>
var cell = "<?php echo $this->session->userdata('cellc_sa'); ?>";
</script>
<style>
#versionBar2 {
    background-color: #212121;
    bottom: 0;
    box-shadow: 0 10px 10px -10px black inset;
    height: 60px;
    left: 0;
    line-height: 0px;
    position: fixed;
    text-align: center;
    width: 100%;
    z-index: 11;
}
#versionBar {
        background-color: #ffffff;
        bottom: 0px;
        height: 55px;
        left: 0;
        line-height: 0px;
        position: fixed;
        text-align: center;
        width: 100% !important;
        z-index: 11;
        }
#ftxt{
font-size: 10px;
font-family: Mywebfont !important;
/*font-weight: bold;*/
}
.active{
border-bottom: 5px solid #000000;
}
.fixFooterActiveState{
    background-color: #DD5511;
    color:#fff;
    line-height: 15px;
    padding: 15px 0;
}
.fixFooterInActiveState{
    background-color: #000;
    color: #fff;
    line-height: 15px;
    padding: 15px 0;
}
</style>
<script type="text/javascript">
  if(cell=="cellc"){
      setTimeout(function(){ window.location = "https://mcomics.club/welcome/cellc_msg";}, 10*60*1000);
  }
</script>
<?php
$check=$this->session->userdata("showlogo");
if($check=="yes")
{
?>
<div id="versionBar">
    <div class="col-md-12">
        <div class="row" style="margin-top: -10px; color: #ffffff;">
           <p align="left">Powered by Moodiit</p>
        </div>
    </div>
</div>
<?php } ?>
<?php
if($tabstatus==1){
?>
<div id="versionBar" class="" style="border-top: 1px solid #d5d5d5;">
<div class="col-md-4 col-xs-4 <?php if($activeTab=="phoCartoonEmoji"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>phoCartoonEmoji'" style="position: relative;">
<img style="padding-top: 5px;" align="center" src="<?php echo base_url(); ?>images/NewImages/Create-Cartoons.png"  width="30px;">
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $Create_Cartoon; ?></p>
</div>

<div class="col-md-4 col-xs-4 <?php if($activeTab=="phoCartoon"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>phoCartoon'" style="position: relative;">
<center><img style="padding-top: 5px;" class="logo" align="center" src="<?php echo base_url(); ?>images/NewImages/Create-Comics.png" width="30px;" ></center>
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $Create_Comics; ?></p>
</div>

<div class="col-md-4 col-xs-4 <?php if($activeTab=="backgroundEffect"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>backgroundEffect'" style="position: relative;">
<img style="padding-top: 5px;" align="center" src="<?php echo base_url(); ?>images/NewImages/BackGround-Effect.png" width="30px;">
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $Create_background; ?></p>
</div>

</div>
    <?php
    }else if($tabstatus==2){
        ?>
<?php //echo $activeTab;  ?>
<div id="versionBar" class="" style="border-top: 1px solid #d5d5d5;">
<div class="col-md-4 col-xs-4 <?php if($activeTab=="viewEmojiEffect"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>viewEmojiEffect'" style="position: relative;">
<img style="padding-top: 5px;" align="center" src="<?php echo base_url(); ?>images/NewImages/Create-Cartoons.png"  width="30px;">
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $view_Cartoon; ?></p>
</div>

<div class="col-md-4 col-xs-4 <?php if($activeTab=="viewComics"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>viewComics'" style="position: relative;">
<center><img style="padding-top: 5px;" class="logo" align="center" src="<?php echo base_url(); ?>images/NewImages/Create-Comics.png" width="30px;" ></center>
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $view_Comics; ?></p>
</div>

<div class="col-md-4 col-xs-4 <?php if($activeTab=="viewPhotoEffect"){ echo "active"; } ?>" onclick="location.href='<?php echo base_url(); ?>viewPhotoEffect'" style="position: relative;">
<img style="padding-top: 5px;" align="center" src="<?php echo base_url(); ?>images/NewImages/BackGround-Effect.png" width="30px;">
<p id="ftxt" style="padding-top: 5px;" align="center"><?php echo $view_background; ?></p>
</div>

</div>

<!--div id="versionBar2">
<div class="col-md-12">
<div class="row">

<a href="<?php echo base_url(); ?>viewPhotoEffect">
<div class="col-md-4 col-xs-4 <?php echo $res = ($activeTab=='viewPhotoEffect' ? "fixFooterActiveState" : "fixFooterInActiveState") ?>">
<img src="<?php echo base_url(); ?>images/Cartoon-Effect.png" style="width:40px"/>
</div>
</a>
<a href="<?php echo base_url(); ?>viewEmojiEffect">
<div class="col-md-4 col-xs-4 <?php echo $res = ($activeTab=='viewEmojiEffect' ? "fixFooterActiveState" : "fixFooterInActiveState") ?>" style=" border-left: 1px solid #fff;border-right: 1px solid #fff;">
<img src="<?php echo base_url(); ?>images/Photo-effect.png" style="width:40px"/>
</div>
</a>
<a href="<?php echo base_url(); ?>viewComics">
<div class="col-md-4 col-xs-4 <?php echo $res = ($activeTab=='viewComics' ? "fixFooterActiveState" : "fixFooterInActiveState") ?>">
<img src="<?php echo base_url(); ?>images/Comics.png" style="width:40px"/>
</div>
</a>

</div>
</div>
</div-->
        <?php
    }
    ?>
    <!-- <div class="fixFooter1">
        Comics
    </div>
    <a class="cartton" href="<?php echo base_url(); ?>phoCartoon" style="float:left;width:33%">
        <div class="fixFooter2">
            Photo<br/>Effect
        </div>
    </a>
    <a class="cartton" href="<?php echo base_url(); ?>phoCartoonEmoji">
        <div class="fixFooter3">
            Cartoon<br/>Effect
        </div>
    </a> -->
