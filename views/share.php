<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
       <!--  <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> -->
        

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="Comics" />
        <meta property="og:description"        content="Make your own comic strip, instantly, only on Globe network!" />
        <meta property="og:image"              content="<?php echo base_url().$path.$result['final_img']; ?>" />
        <meta property="og:url"                 content="<?php echo base_url(); ?>shareComics?id=<?php echo $id;?>&type=<?php echo $type;?>" />
        <meta property="fb:app_id"              content="305982996937048" />
        

        <meta name="description" content="Make your own comic strip, instantly, only on Globe network!" />
        <meta name="keywords" content="comics, comic, comic strip, make your own comics" />


        <title>mComics</title>
        <link rel="shortcut icon" href="<?php base_url(); ?>images/logo.png" />
        <link href="<?php base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php base_url(); ?>css/font-awesome.min.css">
        <script src="<?php base_url(); ?>js/jquery-1.12.0.min.js"></script>
        <script src="<?php base_url(); ?>js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php base_url(); ?>css/style.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="<?php base_url(); ?>css/jquery-listslider.css">
        
        <!-- start tiny slider -->
        <link rel="stylesheet" href="<?php base_url(); ?>flickity/flickity.css" type="text/css" media="screen"/>
        <script type="text/javascript" src="<?php base_url(); ?>flickity/flickity.js"></script>       
        <!-- end tiny slider -->
        
        <!-- ajaxEvents -->
        <script type="text/javascript" src="<?php base_url(); ?>js/ajaxEvents.js"></script>
        <script type="text/javascript">
            $(window).load(function () {
                $("#spinner").fadeOut("slow");
            })
        </script>
        <style>
            #spinner {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('images/rolling.gif') 50% 50% no-repeat rgb(249,249,249);
            }
        </style>

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
                                        <a title="Back" href="<?php echo $backword; ?>" style="color:#fff; margin-top: 5px;">
                                            <img src="<?php echo base_url(); ?>images/back.png" style="width:30px;"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <center>
                                    <a href="javascript:void(0);" style="text-align: center;color:#fff; height: 10px;"  class="navbar-brand" id="logo">mComics</a>
                                </center>
                            </div>
                            <div class="navbar-header pull-right">

                                <ul class="nav pull-left">
                                    <li class="pull-right">
                                        <a title="Logout" href="<?php base_url(); ?>logout" style="color:#fff; margin-top:10px;">
                                            <i aria-hidden="true" class="fa fa-power-off" style="font-size:20px;"></i>
                                        </a>
                                    </li>
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
                            <div>
                                <center>
                                    <a href="<?php echo base_url(); ?>" style="text-align: center;color:#fff; height: 10px;"  class="navbar-brand" id="logo">mComics</a>
                                </center>
                            </div>
                <?php        
                    }
                ?>

                
            </div>
        </nav>
<!-- fb share -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=305982996937048";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!-- -->
<div class="container">
    <div class="row">
        <?php
            if($result!=''){
                ?>
                <div class="col-md-12 settings_2">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        <center>
                            <img src="<?php echo base_url().$path.$result['final_img']; ?>" style="width:80%;"/>
                        </center>
                    </div>
                </div>

                <center>
                    <?php
                    $fb = 'https://www.facebook.com/dialog/share?app_id=1129345027105654&amp;display=popup&amp';
                    $href = 'href=' . base_url() . 'sharemComic?id=' . $id;
                    $redirect = 'redirect_uri=' . base_url() . 'sharemComic?id=' . $id;
                    ?>



                    <?php 
                        if($type!='face'){
                    ?>

                    <span style="cursor:pointer;" onclick="location.href = 'https://www.facebook.com/dialog/share?app_id=305982996937048&amp;display=popup&amp;href=<?php echo urlencode(base_url() . "shareComics?id=$id&type=$type"); ?>&amp;redirect_uri=<?php echo urlencode(base_url() . "shareComics?id=$id&type=$type"); ?>';">
                        <img src="<?php base_url(); ?>images/FB_share.png"/>
                    </span>
                    <?php
                        }
                    ?>
                </center>
                <?php
            }else{
                ?>
                    <div class="col-md-12">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <center>
                                <img src="<?php echo base_url().'images/404.png'; ?>" style="width:80%;margin-top:100px;"/>
                            </center>
                        </div>
                        
                    </div>
                <?php
            }
        ?>
    </div>
</div>








