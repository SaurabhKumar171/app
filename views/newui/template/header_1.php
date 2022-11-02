<?php if ( (isset($_COOKIE['adnet'])) && (!empty($_COOKIE['adnet'])) && ($_COOKIE['adnet']=="facebook")) {
?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '367052115021778'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=367052115021778&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
<script>
fbq('track', 'Purchase', {value: 0.1, currency: 'USD'});
</script>
<?php } ?>
<script>
var cell = "<?php echo $this->session->userdata('cellc_sa'); ?>";
</script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Chrome, Firefox OS and Opera -->
        <title>
            <?php 
            if($_SERVER['HTTP_HOST']=='mtoon.xyz'){
            echo 'mtoon';
            }
            else if($_SERVER['HTTP_HOST']=='mtoonapp.com'){
            echo 'mtoon';
            }
            else if($_SERVER['HTTP_HOST']=='u.mtoonapp.com'){
            echo 'mtoon';
            }
            else if($_SERVER['HTTP_HOST']=='twinkle.pheuture.com'){
            echo 'Twinkle';
            }
            else if($_SERVER['HTTP_HOST']=='comiceffect.pheuture.com'){
            echo 'ComicEFFECT';
            }
            else{
            echo 'mComics';
            }
            ?>    
        </title>
        <meta property="og:url" content="<?php echo base_url(); ?>mlogin/" />
        <meta property="og:title" content="mComics" />
        <meta property="og:type" content="website" />
        <meta charset="utf-8">
        <?php
        if(isset($sid))
        {
        $cmid=$sid;
        $makeComicsPage=file_get_contents(base_url().'makeComicsPage?id=$cmid');
        ?>
        <meta property="og:image" itemprop="image" content="<?php echo base_url(); ?>fShare/<?php echo $cmid; ?>.png" /> 
        <meta property="og:image:secure_url"  itemprop="image"  content="<?php echo base_url(); ?>fShare/<?php echo $cmid; ?>.png" />      
        <?php } else { ?>
        <meta property="og:image" itemprop="image" content="<?php echo base_url(); ?>images/comics-default-image.jpg" />
        <?php   }  ?>
        <!-- <meta property="og:image" content="<?php echo base_url(); ?>images/comics-default-image.jpg" /> -->
        <!-- <meta property="og:description" content="Make your own comic strip, instantly, only on mComics." />
        <meta property="description" content="Make your own comic strip, instantly, only on mComics." /> -->
        <meta name="facebook-domain-verification" content="m1rdqygovegvqubwpl1lek0errq0ji" />
        <meta property="og:description" content="Check out my comics on mComics." />
        <meta property="og:image:alt" content="mComics">
        
        <meta property="fb:app_id" content="305982996937048" />
        <meta name="theme-color" content="#DD5511">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#DD5511">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#DD5511">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="author" content="thebestdesigns">
        
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/logo.png" />
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
        <script src="<?php echo base_url(); ?>js/jquery-1.12.0.min.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-listslider.css">
        
        <!-- start tiny slider -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>flickity/flickity.css" type="text/css" media="screen"/>
        <script  src="<?php echo base_url(); ?>flickity/flickity.js"></script>       
        <!-- end tiny slider -->


        <!-- remove this code block. This code is used for demo purpose-->
        <script>
        <?php if($_SERVER['HTTP_HOST']=="m.mcomics.club"){ ?>
             $(window).load(function () {
                setTimeout(function(){
                    $("#spinner").fadeOut("slow");
                } , 900); 
            })
        <?php }else{ ?>
            $(window).load(function () {
               $("#spinner").fadeOut("slow");
            })
        <?php } ?>
        </script>
        <script type="text/javascript">
          if(cell=="cellc"){
              setTimeout(function(){ window.location = "<?php echo base_url(); ?>welcome/cellc_msg";}, 10*60*1000);
          }
        </script>
        <style>
        body,html{
                 top:0px !important;
                 background-color: #ffffff !important;
                 background: #ffffff !important;
                 height: 100% !important;
                 overflow-x: hidden;
                 /*font-family: MyWebFont;*/
            }
            @font-face {
            font-family: "MyWebFont";
            /*src: url(Font/AntipastoPro_trial.ttf);*/
            src: url("<?php echo base_url(); ?>fonts/Font/AntipastoPro_trial.ttf");
            }
            #spinner {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('<?php echo base_url(); ?>images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
            }
            .goog-te-gadget-icon{
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
             .goog-te-banner-frame, div#:0.targetLanguage::after,.goog-te-gadget > span{
                display: none !important;
            }
            .unsub_btn{
                width: 75px;
                background-color: #f6d749;
                border-radius: 25px;
                margin-left: 36px;
                text-align: center;
            }
            .unsub-btn{
                color: #000;
                font-size: 14px;
            }
        </style>
        <script>
            $(document).ready(function () {
//                $("body").on("contextmenu", function (e) {
//                    return false;
//                });
                $('body').bind("cut copy paste", function (e) {
                    e.preventDefault();
                });
            });
        </script>

    </head>

<body>
<div id="spinner"></div>
<!-- New Header -->
<!-- Moodit Logo -->
<?php
$check=$this->session->userdata("showlogo");
if($check=="yes")
{
?>
<center><img src="<?php echo base_url(); ?>images/mcomic_lnew.png"></center>
<?php } ?>
<!-- End Moodit Logo -->

<div class="row" style="padding-top: 10px; padding-bottom: 5px; padding-left: 15px; padding-right: 15px; border-bottom: 1px solid #d5d5d5;"> 

<div class="col-md-4 col-xs-4">
<a href="<?php echo base_url(); ?>viewprofile"><img align="left" src="<?php echo base_url(); ?>images/NewImages/Profile.png" style="width:30px;"></a>
<?php 
if ($_SERVER['HTTP_HOST']=='sd.mcomics.club'){
?>
    <div class="unsub_btn">
        <a class="unsub-btn" href="http://sd.mcomics.club/unsubscribe_mcomics">
            <div class="header" style="overflow:visible;padding: 5px;">
            <i aria-hidden="true" class="fa fa-trash" style=""></i> Unsub </div>
        </a>
    </div>
<?php
}
?>
</div>

<?php 
if ($_SERVER['HTTP_HOST']=='mtoon.xyz'){
    $logoname= base_url()."/images/NewImages/mtoon-logo-242x96.png";
}
else if($_SERVER['HTTP_HOST']=='mtoonapp.com'){
    $logoname= base_url()."/images/NewImages/mtoon-logo-242x96.png";  
}
else if($_SERVER['HTTP_HOST']=='u.mtoonapp.com'){
    $logoname= base_url()."/images/NewImages/mtoon-logo-242x96.png";  
}
else{
    $logoname= base_url().'images/NewImages/Logo.png';
}
?>

<?php
if($_SERVER['HTTP_HOST']=='twinkle.pheuture.com'){
?>
<div class="col-md-4 col-xs-4" style="">
 <span class="centerspan"><h3>Twinkle</h3></span>
</div>
<?php }else if($_SERVER['HTTP_HOST']=='comiceffect.pheuture.com'){?>
<div class="col-md-4 col-xs-4" style="">
 <span class="centerspan"><h3>ComicEFFECT</h3></span>
</div>
<?php }else{ ?>
<div class="col-md-4 col-xs-4" style="">
 <span class="centerspan"><a href="<?php echo base_url(); ?>index"><img class="logo" alt="mComics" src="<?php echo $logoname; ?>" style="width:100px; margin-top: -5px;"></a></span>
</div>
<?php } ?>


<div class="col-md-4 col-xs-4">
<?php if($is_bl){ ?>
<a href="<?php echo base_url(); ?>unsubscribe_mcomics"><img align="right" src="http://oyeim.com/images/assests/logout.png" style="width:25px; margin-top:4px;" alt="Unsubscribe" title="Unsubscribe"></a>
<?php } ?>

<a href="<?php echo base_url(); ?>viewPhotoEffect"><img align="right" src="<?php echo base_url(); ?>images/NewImages/View.png" style="width:30px;" alt="mComics" ></a>
</div>

</div>
<!-- The End New Header -->


        <?php if($_SERVER['HTTP_HOST']=="m.mcomics.club"){ ?>
        <div id="google_translate_element" style="float: right;margin-right: 10px;display: none;"></div>
         <style type="text/css">
             .goog-logo-link,#google_translate_element{
              display: none !important;
             }
             div#:0.targetLanguage::after,.goog-te-gadget{
              display: none !important;
             }
           </style>
        <?php
            $currenturl = current_url();
            if($currenturl == 'http://m.mcomics.club/index.php/phoCartoon'){
                $layoutgoogle = 'HORIZONTAL';
            }else{
                $layoutgoogle = 'SIMPLE';
            }
        ?>
        <script>
            function deleteCookie(name) {
                    document.cookie = "googtrans=; expires=0; path=/";
                    document.cookie = "googtrans=%2Ffr";
                    document.cookie = "googtrans=%2Ffr%2Fen; expires=3600000; path=/; domain=m.mcomics.club";
                }
                deleteCookie('googtrans');
                 console.log(document.cookie);
            function googleTranslateElementInit() {
              new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,fr', layout: google.translate.TranslateElement.InlineLayout.<?php echo $layoutgoogle; ?>, autoDisplay: false}, 'google_translate_element');
            }
        </script>
        <script  src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php } ?>


<!-- fab icon -->
        <style>

        .fab {
            position: fixed;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            color: #fff;
            transition: box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transition-delay: 0.2s;
            //box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
            right: 20px;
            bottom: 80px;
            z-index: 9999;
        }
        </style>

        <?php
            if($_SERVER['HTTP_HOST']=="z.mcomics.club"){
        ?>
                <div class="fab">
                    <img src="<?php base_url(); ?>images/zain.png">
                </div>
        <?php
            }
        ?>
        <!-- fab icon -->
        <script>
            // $('#bbtn').click(function(){
            //     alert("test");
            // }
        </script>