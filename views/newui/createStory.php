<script>

    var rowCount = 1;
    function addMoreRows(frm) {
        rowCount++;
        var checkoption = 0;
        nextOption = $("#selectOption");
        
        if(nextOption){
            var checkoption = 1;
        }

        var imgname = "<?php echo $myCreatedCharacter['avatarURL']; ?>";
        var resWidth = imgname.search("character");

        if(resWidth < 1){
            styleWidth = "vertical-align:baseline;width:58px;";
        }else{
            styleWidth = "vertical-align:baseline;width:58px;";
        }

        var recRow = '<div class="col-md-12 items" id="items_' + rowCount + '" style="display:none;padding-left:0px;padding-right:0px;">' +
                '<div class="col-md-4"></div><div class="col-md-4" style="padding-left:0px;padding-right:0px;">' +
                '<center><div id="item_' + rowCount + '" style="padding:10px;">' +
                '<table class="table comic_bg_screen_' + rowCount + '" style="background-image: url(<?php echo base_url(); ?>images/comics/bg/bg009.gif);background-repeat: no-repeat;background-size: cover;min-height: 450px;max-height: 300px;width:200px;">' +
                '<tr>' +
                '<td colspan="2" style="vertical-align:bottom;">' +
                '<div class="narration narration_screen_' + rowCount + '">' +
                '<span  class="narration_bubble_screen_' + rowCount + '">' +
                '<?php echo $narrationscreentext; ?>' +
                '</span>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '<tr style="height:200px;">' +
                '<td style="vertical-align:bottom;">' +
                ' <div id="left_screen_' + rowCount + '" class="b159 left_talk_screen_' + rowCount + '">' +
                '<span  class="left_bubble_screen_' + rowCount + '">' +
                '<?php echo $leftscreentext; ?>' +
                '</span>' +
                ' </div>' +
                '</td>' +
                '<td style="vertical-align:bottom;">' +
                ' <div id="right_screen_' + rowCount + '" class="a163 right_talk_screen_' + rowCount + '">' +
                '<span  class="right_bubble_screen_' + rowCount + '">' +
                '<?php echo $rightscreentext; ?> ' +
                '</span>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                ' <tr>' +
                '<td style="vertical-align:bottom;padding:0px;text-align:center;"> ' +
                '<img class="leftCharacter_screen_' + rowCount + '" src="<?php echo base_url() . 'output/' . $myCreatedCharacter['avatarURL']; ?>"  style="'+styleWidth+'"/>' +
                '</td>' +
                '<td style="vertical-align:bottom;padding:0px;text-align:center;">' +
                ' <img class="rightCharacter_screen_' + rowCount + '" src="<?php echo base_url() ?>images/comics/bg/person013.png" style="height: 180px; width: 135px;vertical-align:baseline;">' +
                '</td>' +
                '</tr>' +
                '</table>' +
                '</div>' +
                '<!--a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');">' +
                '<i aria-hidden="true" class="fa fa-minus-circle" style="color:#000;vertical-align:baseline"></i></a--></div></center></div>';

        $("#selectOption").append($('<option class="optionsValue" value="' + rowCount + '" id="itemC_' + rowCount + '"><?php echo $Screentxt; ?>-' + rowCount + '</option>'));

        jQuery('#addedRows').append(recRow);
        $('#popup').html("<?php echo $Screenaddedtxt; ?>.");
        $('#overlay, #popup').css('display', 'block');
        $('#overlay, #popup').fadeIn(1000).fadeOut(2000);
         var selVal = $("#selectOption").val();
        $(".items").hide();
        $("#items_" + selVal).show();
        if(checkoption == 1){
            screenval_change(selVal);
        }
    }

    function removeRow(removeNum) {
        jQuery('#rowCount' + removeNum).remove();
    }
    function deleteRows(){
            var nextOption = $("#selectOption option:selected").val();

            if(nextOption!=1){
                $("#selectOption option[value='" +nextOption+ "']").remove();
                var lastindex= $('#selectOption option:last-child').val();
                $("#items_" + nextOption).remove();
                var selVal = $("#selectOption option[value='"+lastindex +"']").prop('selected', 'selected');
                $("#items_" + lastindex).show();
                $('#popup').html("<?php echo $Screenrmxt; ?>.");
                $('#overlay, #popup').css('display', 'block');
                $('#overlay, #popup').fadeIn(1000).fadeOut(2000);
                screenval_change(lastindex);
            }else{
                $('#popup').html("<?php echo $Screencntremovetxt; ?>.");
                $('#overlay, #popup').css('display', 'block');
                $('#overlay, #popup').fadeIn(1000).fadeOut(2000);
            }

            
    }
     function screenval_change(lastindex){
            $('.workingTab').removeClass('active');
            $('.workingTab').removeClass('activeTab');
            $('.workingTab').addClass('inactiveTab');
            var selVal = lastindex;

            var i;
            for (i = 1; i < 9; i++) {
                $('.myTab' + i).attr("onclick", "getStoryEvents('screen_" + selVal + "'," + i + ");");
            }
            var screenName = 'screen_' + selVal;
            $.ajax({
                type: 'POST',
                url: '<?php base_url(); ?>/welcome/storyCharacter',
                data: "screenName=" + screenName + "&id=1",
                success: function (data) {
                    $('.result').html(data);
                },
                error: function (data, error) {
                    console.log(data);
                    alert(" Can't do because: " + error);
                }
            });
            $('.myTab1').addClass('active');    
    }
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
 // Load the Google Transliterate API
    google.load("elements", "1", {
        packages: "transliteration"
    });
    google.setOnLoadCallback(onLoad);
</script>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td
    {
        border:none;
    }
    /*    .{
            height: 40px;
            width: 70%;
            background-color: #000;
            border-top: none;
            border-right: none;
            border-left: none;
            border-bottom: 2px solid #FF3B47;
            color: #fff;
            font-size: 15px;
        }*/
    select {
        background-color: #fff;
        border-top: none;
        border-right: none;
        border-left: none;
        border-bottom: 2px solid #FF3B47;
        display: inline-block;
        font: inherit;
        line-height: 1.5em;
        padding: 0.5em 3.5em 0.5em 1em;
        margin: 0;      
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-appearance: none;
        -moz-appearance: none;
        color:#000;
    }
    select.subject-dropdown {
        background-image:
            linear-gradient(45deg, transparent 50%, #000 50%),
            linear-gradient(135deg, #000 50%, transparent 50%),
            linear-gradient(to right, #000, #000);
        background-position:
            calc(100% - 20px) calc(1em + 2px),
            calc(100% - 15px) calc(1em + 2px),
            calc(100% - 2.5em) 0.5em;
        background-size:
            5px 5px,
            5px 5px,
            1px 1.5em;
        background-repeat: no-repeat;
    }
    option.optionsValue {

        background-color: #000;
        color:#000;
    }
    .modal {
    }
    .vertical-alignment-helper {
        display:table;
        height: 100%;
        width: 100%;
    }
    .vertical-align-center {
        /* To center vertically */
        display: table-cell;
        vertical-align: middle;
    }
    .modal-content {
        width:inherit;
        height:inherit;
        /* To center horizontally */
        margin: 0 auto;
    }
    .narration {
        font-family: Mywebfont !important;
    }

</style>

<link rel="stylesheet" href="<?php echo $littledataurl; ?>css/bubbleRight.css">
<link rel="stylesheet" href="<?php echo $littledataurl; ?>css/bubbleLeft.css">


<div id="confirm" class="modal fade">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-body">
                    <?php echo $saveconfirm; ?> 
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" data-value="1"><?php echo $continue; ?></button>
                    <button type="button" data-dismiss="modal" class="btn" data-value="0"><?php echo $cancel; ?> </button>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="overlay"></div>
<div id="popup"></div>


<div class="container">
    <center></center>
    <div class="row">
        <div class="col-md-12" style=" padding-left:0px;padding-right: 0px;">
            <div class="col-md-4">
            </div>
            <div style="text-align:center;color:#000;padding-left:0px;padding-right: 0px;"  class="col-md-4">
              <!--   <i>*To change the content of comic go below</i> -->
                 <?php echo $starcontent; ?>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
            </div>
            <div style="text-align:center;padding:0px 0px 2px 0px"  class="col-md-4">
                <a href="javascript:void(0);" onclick="deleteRows(this.form);">
                    <i  style="color:#000;vertical-align:bottom" class="fa fa-minus-circle fa-2x" aria-hidden="true"></i>
                </a>
                <select class="subject-dropdown" id="selectOption">
                    <option class="optionsValue" value="1" id="itemC_1"><?php echo $Screentxt; ?>-1</option>
                </select>
<!--                <span class="imageTitleName2">Screen-1 </span>-->
                <a href="javascript:void(0);" onclick="addMoreRows(this.form);">
                    <i style="color:#000;vertical-align:bottom" class="fa fa-plus-circle fa-2x" aria-hidden="true"></i>
                </a>
            </div>

        </div>
    </div>
    <span id="wait" style="color:#000;display:none;">Please wait ...</span>
    <input type="hidden" name="userlanguage" id="userlanguage" value="<?php echo $userlanguage; ?>" />
    <input type="hidden" id="charID" value="<?php echo $myChar; ?>"/>
    <input type="hidden" id="title" value="<?php echo $titleName; ?>"/>
    <input type="hidden" id="baseURL" value="<?php echo base_url(); ?>"/>
    <div class="row" id="getComic">
        <div class="col-md-12 items" id="items_1" style="padding-left:0px;padding-right:0px;">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="padding-left:0px;padding-right:0px;">
                <center>
                    <div id="item_1" style="padding:10px;">
                        <table class="table comic_bg_screen_1" style="background-image: url('<?php echo base_url(); ?>images/comics/bg/bg009.gif');background-repeat: no-repeat;background-size: cover;min-height: 450px;max-height: 300px;width:200px;">
                            <tr>
                                <td colspan="2" style="vertical-align:bottom;"> 
                                    <div class="narration narration_screen_1">
                                        <span  class="narration_bubble_screen_1">                                            
                                            <?php echo $narrationscreentext;  ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr style="height:200px;">
                                <td style="vertical-align:bottom;"> 
                                    <div id="left_screen_1" class="b159 left_talk_screen_1">
                                        <span  class="left_bubble_screen_1">
                                            <?php echo str_replace("\\", "", $leftscreentext);  ?>
                                        </span>
                                    </div>
                                </td>
                                <td style="vertical-align:bottom;">
                                    <div id="right_screen_1" class="a163 right_talk_screen_1">
                                        <span  class="right_bubble_screen_1">
                                            <?php echo $rightscreentext;  ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                            <?php
                                $stringWord = $myCreatedCharacter['avatarURL'];

                                if (strpos($stringWord, 'character') !== false) {
                                    $styleWidth = "vertical-align:baseline;";
                                }else{
                                    $styleWidth = "vertical-align:baseline;width:58px;";
                                }
                            ?>

                                <td style="vertical-align:bottom;padding:0px;text-align:center;"> 
                                    <img class="leftCharacter_screen_1" src="<?php echo base_url() . 'output/' . $myCreatedCharacter['avatarURL']; ?>" style="<?php echo $styleWidth; ?>"/>
                                </td>
                                <td style="vertical-align:bottom;padding:0px;text-align:center;">
                                    <img class="rightCharacter_screen_1" src="<?php echo base_url() ?>images/comics/bg/person013.png" style="height: 180px; width: 135px;vertical-align:baseline;">
                                </td>
                            </tr>
                        </table>    
                    </div>
                </center>
            </div>
        </div>

        <span id="rowId">
            <div id="addedRows"></div>
        </span>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
            </div>
            <div style="text-align:center;padding:0px 0px 2px 0px;"  class="col-md-4">
                <span class="imageTitleName" style="color: #000 !important; font-family: Mywebfont !important;"><?php echo $backgroundFooter; ?></span>
            </div>

        </div>
    </div>
</div>
<div style="margin-top:0px;">
    <ul class="nav nav-tabs" role="tablist" style="background-color: #000 !important;">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <?php
        $i = 1;
        foreach ($result as $row) {
            if($tab==$i){
                $active = 'activeTab';
            } else {
                $active = 'inactiveTab';
            }
            
            ?>
            <!--            <li class="active myTab<?php echo $row->id; ?>">-->
             <?php

                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                    if( $row->name == 'Right Dialogue'){
                        $row->name = 'නිවැරදි සංවාදය';
                    }else if( $row->name == 'Right Speech Bubble'){
                        $row->name = 'නිවැරදි කථාව බබල්'; 
                    }else if( $row->name == 'Right Character'){
                        $row->name = 'නිවැරදි චරිතය'; 
                    }else if( $row->name == 'Left Dialogue'){
                        $row->name = 'වාමාංශික සංවාදය'; 
                    }else if( $row->name == 'Left Speech Bubble'){
                        $row->name = 'වමේ Speech Bubble';  
                    }else if( $row->name == 'Left Character'){
                        $row->name = 'වම් චරිතය';   
                    }else if( $row->name == 'Narration'){
                        $row->name = 'කථාව';  
                    }else if( $row->name == 'Background'){
                        $row->name = 'පසුබිම';  
                    }
                                
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                    if( $row->name == 'Right Dialogue'){
                        $row->name = 'রাইট ডায়ালগ'; 
                    }else if( $row->name == 'Right Speech Bubble'){
                        $row->name = 'রাইট স্পিচ বাবল';  
                    }else if( $row->name == 'Right Character'){
                        $row->name = 'ডান অক্ষর';  
                    }else if( $row->name == 'Left Dialogue'){
                        $row->name = 'বাম সংলাপ'; 
                    }else if( $row->name == 'Left Speech Bubble'){
                        $row->name = 'বাম বক্তৃতা বাবল';  
                    }else if( $row->name == 'Left Character'){
                        $row->name = 'বাম অক্ষর';  
                    }else if( $row->name == 'Narration'){
                        $row->name = 'আখ্যান'; 
                    }else if( $row->name == 'Background'){
                        $row->name = 'পটভূমি';
                    }            
                }else if($this->session->userdata('userlanguage') == 'FRENCH'){
                    if( $row->name == 'Right Dialogue'){
                        $row->name = 'Bon dialogue';
                    }else if( $row->name == 'Right Speech Bubble'){
                        $row->name = 'Bulle de parole droite';
                    }else if( $row->name == 'Right Character'){
                        $row->name = 'Caractère droit'; 
                    }else if( $row->name == 'Left Dialogue'){
                        $row->name = 'Dialogue de gauche'; 
                    }else if( $row->name == 'Left Speech Bubble'){
                        $row->name = 'Bulle de dialogue gauche';  
                    }else if( $row->name == 'Left Character'){
                        $row->name = 'Caractère laissé';
                    }else if( $row->name == 'Narration'){
                        $row->name = 'Narration';  
                    }else if( $row->name == 'Background'){
                        $row->name = 'Contexte'; 
                    }              
                }else if($this->session->userdata('userlanguage') == 'ARABIC'){
                    if( $row->name == 'Right Dialogue'){
                        $row->name = 'الحوار الصحيح';
                    }else if( $row->name == 'Right Speech Bubble'){
                        $row->name = 'فقاعة الخطاب الصحيح';
                    }else if( $row->name == 'Right Character'){
                        $row->name = 'الطابع الصحيح'; 
                    }else if( $row->name == 'Left Dialogue'){
                        $row->name = 'الحوار الأيسر'; 
                    }else if( $row->name == 'Left Speech Bubble'){
                        $row->name = 'فقاعة الخطاب الأيسر';  
                    }else if( $row->name == 'Left Character'){
                        $row->name = 'الشخصية اليسرى';
                    }else if( $row->name == 'Narration'){
                        $row->name = 'رواية';  
                    }else if( $row->name == 'Background'){
                        $row->name = 'خلفية'; 
                    }              
                }else{
                        
                }

            ?>
            <li title="<?php echo $row->name; ?>" id="" class="myTab<?php echo $row->id; ?> workingTab <?php echo $active; ?>" style="cursor:pointer">
                <a href="javascript:void(0);" onclick="getStoryEvents('screen_1',<?php echo $row->id; ?>);" style="cursor:pointer">
                    <img style="width:50px;height:50px;" src="<?php echo base_url(); ?>images/comics/bg/<?php echo $row->path; ?>" alt="No Image"/>
                </a>
            </li>

            <?php
            $i++;
        }
        ?>
        <li>&nbsp;&nbsp;&nbsp;</li>       
    </ul>

    <span class="result">

    <?php
        if(isset($result2) && $result2!=''){
    ?>
            <script>
                var nodeList = document.querySelectorAll('.carousel');

                for (var i = 0, t = nodeList.length; i < t; i++) {
                    var flkty = Flickity.data(nodeList[i]);
                    if (!flkty) {
                        new Flickity(nodeList[i]);
                    }
                }
            </script>


            <div style="width: 100%;
                 position: absolute;
                 top: 100%;
                 height: 200px;
                 bottom: 1px;
                 overflow: auto;text-align: center;
                 background-color: #ffd8d8;">

                <?php
                if ($type != '') {
                ?>
                        <a href="<?php echo base_url(); ?>selGen?id=<?php echo $backid; ?>&tab=<?php echo $tab; ?>">
                            <div class="wrapper2" style="display:inline-block;margin:3px 3px ;">
                                <div class="inner2">
                                    <img class="sliderImg" src="<?php echo base_url(); ?>images/icon-avatar.png" style="height:89px;">
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo base_url(); ?>changeface?id=<?php echo $backid; ?>&tab=<?php echo $tab; ?>">
                            <div class="wrapper2" style="display:inline-block;margin:3px 3px ;">
                                <div class="inner2">
                                    <img class="sliderImg" src="<?php echo base_url(); ?>images/icon-funnyface.png" style="height:89px;">
                                </div>
                            </div>
                        </a>
                <?php


                    foreach ($myAvatar as $row) {
                    ?>
                        <div class="wrapper2" style="display:inline-block;margin:3px 3px;">
                            <div class="inner2">
                                <img onclick="createMyComic('<?php echo $screenName; ?>', '<?php echo $type; ?>', '<?php echo $row->avatarURL; ?>');" class="sliderImg" src="<?php echo base_url(); ?>output/<?php echo $row->avatarURL; ?>" style="height:89px;">
                                <?php //echo $row->param;  ?>
                            </div>
                        </div>
                        <?php
                    }


                }
                ?>
                <?php
                foreach ($result2 as $row) {
                    ?>
                    <div class="wrapper2" style="display:inline-block;margin:3px 3px ;">
                        <div class="inner2">
                            <img onclick="createMyComic('<?php echo $screenName; ?>', '<?php echo $row->code1; ?>', '<?php echo $row->id; ?>');" class="sliderImg" src="<?php echo base_url(); ?>images/comics/bg/<?php echo $row->path; ?>" style="height:89px;">
                            <?php //echo $row->param;  ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>



    <?php
        }else{
        ?>
        <div style="width: 100%;
             position: absolute;
             top: 100%;
             height: 200px;
             bottom: 1px;
             overflow: auto;text-align: center;
             background-color: #ffd8d8;">
             <?php
             
                     foreach ($storyImages as $row) {
                         ?>
                        <div class="wrapper2" style="display:inline-block;margin:3px 3px ;">
                            <div class="inner2">
                                <img onclick="createMyComic('screen_1', '<?php echo $row->code1; ?>', '<?php echo $row->id; ?>');" class="sliderImg" src="<?php echo base_url(); ?>images/comics/bg/<?php echo $row->path; ?>" style="height:89px;">
                                <?php //echo $row->param; ?>
                            </div>
                        </div>
                        <?php
                    }
            ?>
        </div>
        <?php
        }
        ?>
        
    </span>
</div>

