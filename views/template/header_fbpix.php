<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Chrome, Firefox OS and Opera -->
        <title>mComics</title>
        <meta property="og:url" content="http://mcomics.club/mlogin/" />
        <meta property="og:title" content="mComics" />
        <meta charset="utf-8">
        <meta property="og:image"  content="http://mcomics.club/images/comics-default-image.jpg" />
        <meta property="og:image:alt" content="mComics">
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Check out my comics on mComics." />
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
       
        <link rel="shortcut icon" href="http://mcomics.club/images/logo.png" />
        <link href="http://mcomics.club/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="http://mcomics.club/css/font-awesome.min.css">
        <script src="http://mcomics.club/js/jquery-1.12.0.min.js"></script>
        <script src="http://mcomics.club/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="http://mcomics.club/css/style.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="http://mcomics.club/css/jquery-listslider.css">

        <!-- start tiny slider -->
        <link rel="stylesheet" href="http://mcomics.club/flickity/flickity.css" type="text/css" media="screen"/>
        <script  src="http://mcomics.club/flickity/flickity.js"></script>       
        <!-- end tiny slider -->

        <!-- login validation-->
        <!-- <script  src="http://mcomics.club/js/form.validate.js"></script> -->
        <script  src="<?php echo base_url(); ?>js/form.validate.js"></script>
        <script  src="http://mcomics.club/js/jquery.validate.min.js"></script>
        <!-- login validation -->

        <!-- ajaxEvents -->
        <script  src="http://mcomics.club/js/ajaxEvents.js"></script>
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
                background: url('http://mcomics.club/images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
            }
            #waiting {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                opacity: .4;
                background: url('http://mcomics.club/images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
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
                background: url('http://mcomics.club/images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
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
             fbq('init', '317226516243144'); 
            fbq('track', 'ConversionDailogMcomics');
        </script>
        <noscript>
            <img height="1" width="1" src="https://www.facebook.com/tr?id=317226516243144&ev=ConversionDailogMcomics&noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
    </head>
    <body>

        <div id="waiting" style="display:none;"></div>
        <div id="spinner"></div>
        <!--        <nav role="navigation" class="navbar navbar-default navbar-fixed-top" style="background-color:transparent">-->
        <nav class="navbar navbar-default" style="background-color:transparent">
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
                    <center><img src="<?php echo base_url(); ?>images/mcomic_lnew.png"></center>
                    <?php } ?>

                   
                    <center>
                        <!-- <a href="javascript:void(0);" style="text-align: center;color:#fff; height: 10px; width:100%;"  class="navbar-brand" id="logo">mComics</a> -->
                       <a href="javascript:void(0);"><img class="logo" alt="mComics" src="https://mcomics.club/images/NewImages/Logo.png" style="width:100px; padding-top: 3px;"></a>
                    </center>

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
