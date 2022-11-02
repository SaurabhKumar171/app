<script>
var cell = "<?php echo $this->session->userdata('cellc_sa'); ?>";
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <meta name="facebook-domain-verification" content="m1rdqygovegvqubwpl1lek0errq0ji" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Chrome, Firefox OS and Opera -->
        <meta name="theme-color" content="#DD5511">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#DD5511">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#DD5511">

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta property="og:title" content="mComics" />
        
        <meta property="og:image" itemprop="image" content="https://mcomics.club/fShare/<?php echo $id; ?>.png" />
        <!-- <meta property="og:description" content="Make your own comic strip, instantly, only on mComics." />
        <meta property="description" content="Make your own comic strip, instantly, only on mComics." /> -->
        <meta property="og:description" content="Check out my comics on mComics." />
        <meta property="og:image:alt" content="mComics">

        
        <meta property="og:type"               content="article" />

        <meta property="og:url"              content="<?php echo base_url(); ?>Comics?mComic_id=<?php echo $id;?>" />
        <meta property="fb:app_id" content="305982996937048" />
       

        
        <meta name="keywords" content="comics, comic, comic strip, make your own comics" />


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
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/logo.png" />
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
        <script src="<?php echo base_url(); ?>js/jquery-1.12.0.min.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-listslider.css">
        
        <!-- start tiny slider -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>flickity/flickity.css" type="text/css" media="screen"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>flickity/flickity.js"></script>       
        <!-- end tiny slider -->
        
        <!-- ajaxEvents -->
        <script type="text/javascript" src="<?php echo base_url(); ?>js/ajaxEvents.js"></script>
        <script type="text/javascript">
          if(cell=="cellc"){
              setTimeout(function(){ window.location = "http://mcomics.club/welcome/cellc_msg";}, 10*60*1000);
          }
        </script>
        <script type="text/javascript">
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
            src: url("<?php echo base_url(); ?>fonts/Font/AntipastoPro-Medium_trial.ttf");
            }
            #spinner {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
            }
             .goog-te-gadget-icon{
                display: none;
            }
            .goog-te-gadget-simple{
                padding: 3px 5px;
            
            }
            @media screen and (max-width: 768px) {

                #google_translate_element{
                    margin-bottom: 10px;
                }
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function () {
//                $("body").on("contextmenu", function (e) {
//                    return false;
//                });
//                $('body').bind("cut copy paste", function (e) {
//                    e.preventDefault();
//                });
            });
        </script>
    </head>
    <body>
        <div id="spinner"></div>
        <!--        <nav role="navigation" class="navbar navbar-default navbar-fixed-top" style="background-color:transparent">-->
        <nav role="navigation" class="navbar navbar-default" style="background-color:transparent">
            <div class="container">
                <?php
                    if($mobnum!=''){
                ?>
                            <div class="navbar-header pull-left">
                                <ul class="nav pull-left">
                                    <li class="pull-left">
                                        <a title="Back" href="<?php echo $backword; ?>" style="color:#000; margin-top: 5px;">
                                            <img src="<?php echo base_url(); ?>images/NewImages/back.png" style="width:30px;"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <center>
                                    <a href="javascript:void(0);" style="text-align: center;color:#000; height: 10px;"  class="navbar-brand" id="logo">
                                            <?php
                                            if($_SERVER['HTTP_HOST']=="z.mcomics.club"){
                                                    echo '<img src="'.base_url().'images/telesis.png" style="float: left;margin-top: -5px;width: 91px;"/>';
                                            }
                                            ?>
                                    <?php 
                                        if ($_SERVER['HTTP_HOST']=='mtoon.xyz'){
                                            echo 'mtoon';
                                        }
                                        else if($_SERVER['HTTP_HOST']=='mtoonapp.com'){
                                        echo 'mtoon';
                                        }
                                        else{
                                            echo 'mComics';
                                        }

                                    ?>
                                        
                                    </a>
                                </center>
                            </div>
                            <div class="navbar-header pull-right">

                                <ul class="nav pull-left">
                                    <!--li class="pull-right">
                                        <a title="Logout" href="<?php base_url(); ?>logout" style="color:#fff; margin-top:10px;">
                                            <i aria-hidden="true" class="fa fa-power-off" style="font-size:20px;"></i>
                                        </a>
                                    </li-->
                                    <li class="pull-right">
                                        <a title="Next" href="<?php echo $forward; ?>" <?php echo $onclickEvent; ?> style="color:#fff; margin-top: 5px;">
                                            <img src="<?php base_url(); ?>images/<?php echo $forward_img; ?>" style="width:30px;"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                <?php        
                    }else{

                ?>
                            
                            
                                <center>
                                    <a href="<?php echo base_url(); ?>" style="text-align: center;color:#000; height: 10px;"  class="navbar-brand" id="logo">
                                            <?php
                                            if($_SERVER['HTTP_HOST']=="z.mcomics.club"){
                                                    echo '<img src="'.base_url().'images/telesis.png" style="float: left;margin-top: -5px;width: 91px;"/>';
                                            }
                                            ?>
                                    mComics</a>
                                </center>
                            </div>
                <?php        
                    }
                ?>

                
            </div>
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
            <script type="text/javascript">
                function deleteCookie(name) {
                   document.cookie = "googtrans=; expires=0; path=/";
                    document.cookie = "googtrans=%2Ffr";
                    document.cookie = "googtrans=%2Ffr%2Fen; expires=3600000; path=/; domain=m.mcomics.club";
                }
                deleteCookie('googtrans');
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
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