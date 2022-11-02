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
        else{
            echo 'mComics';
        }
        ?>
            
        </title>
        <meta name="facebook-domain-verification" content="m1rdqygovegvqubwpl1lek0errq0ji" />
        <meta property="og:url" content="<?php echo base_url(); ?>mlogin/" />
        <meta property="og:title" content="mComics" />
        <meta property="og:type" content="website" />
        <meta charset="utf-8">
        <meta property="og:image"  content="<?php echo base_url(); ?>images/comics-default-image.jpg" />
        <meta property="og:image:alt" content="mComics">
        <meta property="og:description" content="Make your own comic strip, instantly, only on mComics." />
        
       
        <meta property="fb:app_id" content="305982996937048" />
        <meta name="theme-color" content="#DD5511">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#DD5511">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#DD5511">

        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#4285f4">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="My App"> <!-- New in iOS6 -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='apple-touch-fullscreen' content='yes'>
        

        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <meta name="author" content="thebestdesigns">
       
        <link rel="shortcut icon" href="https://mcomics.club/images/logo.png" />
        <link href="https://mcomics.club/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://mcomics.club/css/font-awesome.min.css">
        <script src="https://mcomics.club/js/jquery-1.12.0.min.js"></script>
        <script src="https://mcomics.club/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://mcomics.club/css/style.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="https://mcomics.club/css/jquery-listslider.css">

        <!-- start tiny slider -->
        <link rel="stylesheet" href="https://mcomics.club/flickity/flickity.css" type="text/css" media="screen"/>
        <script  src="https://mcomics.club/flickity/flickity.js"></script>       
        <!-- end tiny slider -->

        <!-- login validation-->
        <!-- <script  src="<?php echo base_url(); ?>js/form.validate.js"></script> -->
        <script  src="https://mcomics.club/js/form.validate.js"></script>
        <script  src="https://mcomics.club/js/jquery.validate.min.js"></script>
        <!-- login validation -->

        <!-- ajaxEvents -->
        <script  src="https://mcomics.club/js/ajaxEvents.js"></script>
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
          body{
                top:0px !important;
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
            #waiting {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                opacity: .4;
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
            #spinner {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('<?php echo base_url(); ?>images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
            }

        </style>
        <script>
            $(document).ready(function () {
//                $("body").on("contextmenu", function (e) {
//                    return false;
//                });
//                $('body').bind("cut copy paste", function (e) {
//                    e.preventDefault();
//                });
                $('#showloader').click(function () {
                    $('#waiting').show();
                });
            });


           
        </script>
    </head>
    <body>

        <div id="waiting" style="display:none;"></div>
        <div id="spinner"></div>
        <!--        <nav role="navigation" class="navbar navbar-default navbar-fixed-top" style="background-color:transparent">-->
        <nav  class="navbar navbar-default" style="background-color:transparent">
                <!-- <div class="navbar-header pull-left">
                    <ul class="nav pull-left">
                        <li class="pull-left">
                            <a href="javascript:void(0);" style="color:#fff; margin-top: 5px;visibility:hidden;">
                                <img src="<?php base_url(); ?>images/back.png" style="width:30px;"/>
                            </a>
                        </li>
                    </ul>
                </div> -->
                    <?php
                    $check=$this->session->userdata("showlogo");
                    if($check=="yes")
                    {
                    ?>
                    <span class="centerspan"><img src="https://mcomics.club/images/mcomic_lnew.png"></span>
                    <?php } ?>

                   
                    <span class="centerspan">
                        <?php 
                        if($_SERVER['HTTP_HOST']=='mtoon.xyz')
                        {
                            echo '<a href="javascript:void(0);"><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/mtoon-logo-242x96.png" style="width:100px; padding-top: 3px;"></a>';
                        }
                        else if($_SERVER['HTTP_HOST']=='mtoonapp.com')
                        {
                            echo '<a href="javascript:void(0);"><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/mtoon-logo-242x96.png" style="width:100px; padding-top: 3px;"></a>';
                        }
                        else
                        {
                            echo '<a href="javascript:void(0);"><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:100px; padding-top: 3px;"></a>';
                        }
                        
                        ?>
                        <!-- <a href="javascript:void(0);" style="text-align: center;color:#fff; height: 10px; width:100%;"  class="navbar-brand" id="logo">mComics</a> -->
                        <!-- <a href="javascript:void(0);"><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:100px; padding-top: 3px;"></a> -->
                    </span>

                <!-- <div class="navbar-header pull-right">

                    <ul class="nav pull-left">

                        <li class="pull-right" id="showloader">
                            <a href="javascript:void(0);" style="color:#fff; margin-top: 5px;visibility:hidden;">
                                <img src="<?php base_url(); ?>images/home.png" style="width:30px;"/>
                            </a>
                        </li>
                    </ul>
                </div> -->
        </nav>
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
             <script>
              //console.log(document.cookie);
                function deleteCookie(name) {
                   document.cookie = "googtrans=; expires=0; path=/";
                                document.cookie = "googtrans=%2Ffr";
                                document.cookie = "googtrans=%2Ffr%2Fen; expires=3600000; path=/; domain=m.mcomics.club";
                }
                deleteCookie('googtrans');
              
               
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                }
                /*$(window).load(function () {
                  setTimeout(function(){
                     $("#spinner").fadeOut("slow");
                  } , 1000); 
                    
                });*/
                 
            </script>
            <script  src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <?php  } ?>
