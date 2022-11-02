<link rel="stylesheet" href="<?php echo $littledataurl; ?>css/bubbleRight.css">
<link rel="stylesheet" href="<?php echo $littledataurl; ?>css/bubbleLeft.css">
<!-- fb share -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1129345027105654";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script src="<?php echo base_url(); ?>js/html2canvas.js"></script>
<script>
$(document).ready(function(){

    
var element = $("#myCarousel"); // global variable
var getCanvas; // global variable
 
    $(window).on('load', function () {
        $('#loadings').show();
         $("#btn-Convert-Html2Image").hide();
         html2canvas(element, {
         onrendered: function (canvas) {
                //$("#previewImage").append(canvas);
                getCanvas = canvas;
             }
         });

       
        window.setTimeout(function(){
            var imgageData = getCanvas.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-Html2Image").attr("download", "<?php echo $titleName['title']; ?>.png").attr("href", newData);
            $('#loadings').hide();
             $("#btn-Convert-Html2Image").show();
        },1000);


    });

    $("#btn-Convert-Html2Image").on('click', function () {
        $('#loadings').show();
        $("#btn-Convert-Html2Image").hide();
        html2canvas(element, {
         onrendered: function (canvas) {
                //$("#previewImage").append(canvas);
                getCanvas = canvas;
             }
         });
        
        window.setTimeout(function(){
            var imgageData = getCanvas.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-Html2Image").attr("download", "<?php echo $titleName['title']; ?>.png").attr("href", newData);
           //    $('#loadings').hide();
        },1200);
        window.setTimeout(function(){
                $('#loadings').hide();
                $("#btn-Convert-Html2Image").show();
            },1300);
         

       
    });

});

</script>


<!-- -->
<div class="container">
    <div class="row">
        <div style="text-align:center; padding:20px;" class="settings">
            <div class="col-md-12 settings_2">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <div class="heading_caption2" style="color: #000 !important;">
                        <?php echo $titleName['title']; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>//<![CDATA[
    $(window).load(function () {
// invoke the carousel
        $('#myCarousel').carousel({
            interval: 3000
        });

        /* SLIDE ON CLICK */

        $('.carousel-linked-nav > li > a').click(function () {

            // grab href, remove pound sign, convert to number
            var item = Number($(this).attr('href').substring(1));

            // slide to number -1 (account for zero indexing)
            $('#myCarousel').carousel(item - 1);

            // remove current active class
            $('.carousel-linked-nav .active').removeClass('active');

            // add active class to just clicked on item
            $(this).parent().addClass('active');


            //download image code

             $('#loadings').show();
             $("#btn-Convert-Html2Image").hide();
             html2canvas($('#myCarousel'), {
                onrendered: function (canvas) {
                    //$("#previewImage").append(canvas);
                    getCanvas = canvas;
                 }
             });

            window.setTimeout(function(){
                var imgageData = getCanvas.toDataURL("image/png");
                // Now browser starts downloading it instead of just showing it
                var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
                $("#btn-Convert-Html2Image").attr("download", "<?php echo $titleName['title']; ?>.png").attr("href", newData);
                //$('#loadings').hide();
            },1000);
            window.setTimeout(function(){
                $('#loadings').hide();
                 $("#btn-Convert-Html2Image").show();
            },1200);


            // don't follow the link
            return false;
        });

        /* AUTOPLAY NAV HIGHLIGHT */

// bind 'slid' function
        $('#myCarousel').bind('slid', function () {

            // remove active class
            $('.carousel-linked-nav .active').removeClass('active');

            // get index of currently active item
            var idx = $('#myCarousel .item.active').index();

            // select currently active item and add active class
            $('.carousel-linked-nav li:eq(' + idx + ')').addClass('active');

        });


        //download image code on slide image

        $("#myCarousel").on("slide.bs.carousel", function(e){
            $('#loadings').show();
             $("#btn-Convert-Html2Image").hide();
            window.setTimeout(function(){
                html2canvas($('#myCarousel'), {
                    onrendered: function (canvas) {
                       // $("#previewImage").append(canvas);
                        getCanvas = canvas;
                     }
                 });
                },200);

            window.setTimeout(function(){
                var imgageData = getCanvas.toDataURL("image/png");
                // Now browser starts downloading it instead of just showing it
                var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
                $("#btn-Convert-Html2Image").attr("download", "<?php echo $titleName['title']; ?>.png").attr("href", newData);
                
            },1000);
            window.setTimeout(function(){
                $('#loadings').hide();
                 $("#btn-Convert-Html2Image").show();
            },1500);


        });


    });//]]> 

</script>

<div class="container">
    <div class="row" style="margin-right: -46px; margin-left: -37px;">
        <div style="text-align:center;" class="settings">
            <div class="col-md-12 settings_2">
                <div class="col-md-4">

                </div>
                <div class="col-md-4" style="padding-left:0px;padding-right:0px;">
                    <div id="myCarousel" class="carousel slide">
                        <!-- Carousel items -->
                        <div class="carousel-inner">
                            <?php
                            $i = 1;
                            foreach ($result as $row) {
                                

                                if ($i == 1) {
                                    $active = 'active';
                                } else {
                                    $active = '';
                                }
                                ?>
                                <div  class="item sliderPad <?php echo $active; ?>">
                                    <table class="table comic_bg_screen_1" style="background-image: url('<?php echo base_url(). $row->backImage; ?>');background-repeat: no-repeat;background-size: cover;min-height: 450px;max-height: 300px;max-width:100%;margin-bottom:5px;">
                                        <tr>
                                            <td colspan="2" style="vertical-align:bottom;"> 
                                                <div class="narration">
                                                    <?php echo $row->narration; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="height:200px;">
                                            <td style="vertical-align:bottom;color: #000;"> 
                                                <div class="<?php echo $row->leftBubble; ?>">
                                                    <?php echo $row->leftComment; ?>
                                                </div>
                                            </td>
                                            <td style="vertical-align:bottom;color: #000;">
                                                <div class="<?php echo $row->rightBubble; ?>">
                                                    <?php echo $row->rightComment; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $stringWord1 = $row->leftImg;

                                                if (strstr($stringWord1, 'character') !== false) {
                                                    $width1 = "vertical-align:baseline;margin-left: 42px;";
                                                }else if (strstr($stringWord1, 'person') !== false) {
                                                    $width1 = "vertical-align:baseline;margin-left: 42px;";
                                                }else{
                                                    $width1 = "vertical-align:baseline;margin-left: 42px;width:58px;";
                                                }

                                                $stringWord2 = $row->rightImg;

                                                if (strstr($stringWord2, 'character') !== false) {
                                                    $width2 = "height: 180px; width: 135px; vertical-align:baseline;margin-left: 42px;";
                                                }else if (strstr($stringWord2, 'person') !== false) {
                                                    $width2 = "height: 180px; width: 135px; vertical-align:baseline;margin-left: 42px;";
                                                }else{
                                                    $width2 = "vertical-align:baseline;margin-left: 42px;width:58px;";
                                                }
                                            ?>

                                            <td style="vertical-align:bottom;padding:0px;text-align:center;"> 
                                                <img src="<?php echo base_url() . $row->leftImg; ?>" style="<?php echo $width1; ?>"/>
                                            </td>
                                            <td style="vertical-align:bottom;padding:0px;text-align:center;">
                                                <img src="<?php echo base_url() . $row->rightImg; ?>" style="vertical-align:baseline;<?php echo $width2; ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php
                                $i++;
                            }
                            ?>
                        </div>
                        
                    </div>

                    <!-- LINKED NAV -->
                    <ol class="carousel-linked-nav pagination">
                        <?php
                        for ($j = 1; $j < $i; $j++) {
                            if ($j == 1) {
                                ?>
                                <li class="active">
                                    <a href="#<?php echo $j; ?>">
                                        <i class="fa fa-dot-circle-o" aria-hidden="true">
                                        </i>
                                    </a>
                                </li>
                                <?php
                            } else {
                                ?>
                                <li>
                                    <a href="#<?php echo $j; ?>">
                                        <i class="fa fa-dot-circle-o" aria-hidden="true">
                                        </i>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ol>

                </div>
            </div>
        </div>
    </div>
</div>

<center>

    <div style="margin-top:15px;">
        <div class="" id="downloadimage" style="font-size:30px;">
            <a id="btn-Convert-Html2Image" style="cursor: pointer;"><i class="fa fa-cloud-download" aria-hidden="true"></i></a>
        </div>
    </div>
    <div id="previewImage">
    </div>
    <div id="loadings" style="display: none;font-size:30px;"><i class="fa fa-spinner" aria-hidden="true"></i>
    </div>
     <?php $fbshare = $this->session->userdata('fbshare_domain'); ?>
   <div Align="center" style="font-weight: bold; font-size: 1.5em;"><?php echo $sharevia; ?></div>
    <!-- <span style="cursor:pointer;" onclick="location.href = 'https://www.facebook.com/dialog/share?app_id=1129345027105654&amp;display=popup&amp;href=<?php echo urlencode("http://littledata.in/mcomt/Comics?mComic_id=$sid"); ?>&amp;redirect_uri=<?php echo urlencode("http://littledata.in/mcomt/home?view=$fbshare"); ?>';">
        <img src="https://img.icons8.com/color/2x/facebook.png"  style="width: 50px;" />
    </span> -->
    <!-- <span style="cursor:pointer;line-height: 10px;" onclick="location.href = 'http://mcomics.in/welcome/fbredirect/<?php echo $id; ?>/sharemComic/<?php echo $sid; ?>/<?php echo $titlename; ?>';" class="fb-xfbml-parse-ignore">
        <img src="https://img.icons8.com/color/2x/facebook.png"  style="width: 50px;" />
    </span> -->
    <?php
    $url="http://mcomics.club/Comics?mComic_id=".$sid;
    ?>
    <span><a href="whatsapp://send?text=<?php echo $url; ?>" data-action="share/whatsapp/share"><img src="https://img.icons8.com/color/2x/whatsapp.png" style="width: 50px;"></a></span>
    <!-- <span><a href="viber://forward?text=<?php echo $url; ?>"><img src="https://img.icons8.com/ios/2x/viber-filled.png" style="width: 50px;"></a></span> -->
   
</center>

<div style="height: 10px;"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script>
$(document).ready(function(){
//alert("test");
var mob='<?php echo $this->session->userdata('mobnum'); ?>';
var comid='<?php echo $sid; ?>';
//alert(mob+"---"+comid); return false;

$.ajax({
     type: "POST",
     url: "<?php echo base_url(); ?>/Welcome/fblikes", 
     data: {'mobnum':mob, 'cid':comid},     
     success: function(response)
     {
        return true;
     }
     });


});
</script>
<style>
    
    .pagination {
        border-radius: 50%;
        display: inline-block;
        margin: 0px 0;
        padding-left: 0;
    }
    .pagination > li:first-child > a, .pagination > li:first-child > span{
        border-radius: 50%;
    }
    .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: transparent;
        border-color: transparent;
        color: #fff;
        border-radius: 50%;
        cursor: default;
        z-index: 3;
    }
    .pagination > li > a, .pagination > li > span{
        padding: 0px;
        background-color: transparent;
        border-color: transparent;
    }
    .pagination > li > a:hover, .pagination > li > span:hover,.pagination > li > a:focus, .pagination > li > span:focus{
        padding: 0px;
        background-color: transparent;
        border-color: transparent;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td
    {
        border:none;
    }
    .carousel-inner > .item > img, .carousel-inner > .item > a > img {
        width: 80%;
        margin: auto;
    }
    .carousel-caption{
        text-shadow:none;
    }
    @media screen and (max-width:640px) {
        .carousel-caption{
            left:0%;
            right: 0%;
        }

    }

    @media  (max-width:320px){
        .carousel-caption{
            left:0%;
            right: 0%;
        }

    }
    .fa{
        color:#000;
    }
</style>