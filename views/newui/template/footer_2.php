<script>
var cell = "<?php echo $this->session->userdata('cellc_sa'); ?>";
</script>
<script src="js/jquery-listslider.js"></script>

<script>
    $('.nav.nav-tabs').listslider({
        left_label: '<span class="glyphicon glyphicon-chevron-left"></span>',
        right_label: '<span class="glyphicon glyphicon-chevron-right"></span>'
    });
</script>
<script type="text/javascript">
  if(cell=="cellc"){
      setTimeout(function(){ window.location = "<?php echo base_url(); ?>welcome/cellc_msg";}, 10*60*1000);
  }
</script>
<style type="text/css">
	.goog-te-gadget-icon,.hideimg{
    display: none !important;
}
.goog-te-gadget-simple{
    padding: 3px 5px;

}
 #\:2\.container{
    display: none  !important;
    visibility: hidden  !important;
}
@media screen and (max-width: 768px) {

    #google_translate_element{
        margin-bottom: 10px;
    }
}

<?php if($_SERVER['HTTP_HOST']=="m.mcomics.club"){ ?>
  .download{
    width: 40%;
    font-size: 13px;
  }
<?php } ?>
</style>

<?php
$check=$this->session->userdata("showlogo");
if($check=="yes")
{
?>
<center style="margin-top:50px;">Powered By Moodit</center>
<?php } ?>                        