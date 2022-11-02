<script>
function sendData()
{
  $("#spinner2").show();    
  /*$('.resMsg').html("Uploading image....");   */
  $('.resMsg').html("<?php echo $uploadingtxt; ?>");   
  $('.resMsg').show();  
  var data = new FormData($('#posting_comment')[0]);
  $.ajax({
      type:"POST",
      url:"<?php echo base_url().'cartooniestupload';?>",
      data:data,
      dataType: 'json',
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSubmit: function () {
        $("#spinner2").show();
      },
      success:function(data)
      {
        console.log(data.response);
        var request_id = data.request_id;
        var num = data.num;

        if(data.response=='true'){
          var genid = data.genrated_id;                 
          genreateImg(data.request_id,data.num,genid);
        }else if(data.response=='false') {
            $('.resMsg').html("Please Try Later.");
            $("#spinner2").hide();  
        }else if(data.response=='error') {
          $("#spinner2").hide();  
            $('.resMsg').html(data.message);
        }
        setTimeout(function(){
                $('.resMsg').html("");
        } , 5000); 
      },
       error(){
          console.log("An error occur");
          $("#spinner2").hide();  
       }
  });

}



function sendData2()
{
  $("#spinner2").show();    
  $('.resMsg').html("Uploading image....");   
  $('.resMsg').show();  
  var data = new FormData($('#posting_comment2')[0]);
  $.ajax({
      type:"POST",
      url:"<?php echo base_url().'cartooniestupload';?>",
      data:data,
      dataType: 'json',
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSubmit: function () {
        $("#spinner2").show();
      },
      success:function(data)
      {
        console.log(data.response);
        var request_id = data.request_id;
        var num = data.num;

        if(data.response=='true'){
          var genid = data.genrated_id;                 
          genreateImg(data.request_id,data.num,genid);
        }else if(data.response=='false') {
            $('.resMsg').html("Please Try Later.");
            $("#spinner2").hide();  
        }else if(data.response=='error') {
          $("#spinner2").hide();  
            $('.resMsg').html(data.message);
        }
        setTimeout(function(){
                $('.resMsg').html("");
        } , 5000); 
      },
       error(){
          console.log("An error occur");
          $("#spinner2").hide();  
       }
  });

}




function genreateImg(request_id,num,genid){
          //console.log(genid);
          //alert("sdfgfhfd ---" + genid);
          var data = 'request_id='+request_id+'&num='+num;
        $.ajax({
          type:'POST',
          dataType: 'json',
          data:data,
          url: "<?php echo base_url()?>level55/",
          beforeSend:function(data){
             $("#spinner2").show();
          },
          success:function(data){
            //console.log(data.status);
            var status = (data.status).toLowerCase();
            if(status=='ok'){
                console.log(genid);
                window.clearTimeout(genreateImg);
                saveCartoonURL(genid);
            }else if(status=='inprogress'){
              $('.resMsg').html(data.desc);
                setTimeout(function(){
                   genreateImg(request_id,num,genid)
               }, 5000);
            }else if(status=='bad request'){
              $('.resMsg').html(data.desc);
              setTimeout(function(){
                        $('.resMsg').html("");
                      } , 5000); 
                      $("#spinner2").hide();
            }else{
              $('.resMsg').html(data.desc);
              setTimeout(function(){
                        $('.resMsg').html("");
                      } , 5000); 
                      $("#spinner2").hide();  
              //alert(data.desc);
            }
          },
          error(){
            console.log("An error occurs");
          }
        });
    }

    function saveCartoonURL(genid){
          //var phoCartoonid = $('#phoCartoonid').val();
          var data = 'genid='+genid;
        $.ajax({
        type:'POST',
        dataType: 'json',
        data:data,
        url: "<?php echo base_url()?>getFinalResult2/",
        beforeSend:function(data){
           $("#spinner2").show();
        },
        success:function(data){
          console.log(data);
          if(data.message=='true'){
             console.log(data.message);
             $('.resMsg').show();   
             setTimeout(function(){
                      location.href= "<?php echo base_url().'welcome/selCartoonEmoji/';?>"+genid
                      //location.href= "<?php //echo base_url().'Newtest/selCartoonEmoji/';?>"
                    } , 5000); 
          }else{
            $("#spinner2").hide();  
             console.log(data.message);
          }
          
        },
        error(){
          console.log("An error occurs");
          $("#spinner2").hide();  
        }
           });
    }

</script>
<style>
.resMsg{
  color:#000;
  font-size:15px;
  font-weight: bold;
}
.phoSel{
   cursor: pointer;
}
a.phoSel:hover{
   text-decoration: none;
}
.phoText{
   font-size: 12px;
   color:#fff;
   margin-top:-50px;
}
.phoText:hover{
   font-size: 12px;
   color:#fff;
   margin-top:-50px;
}
.heading_caption{
   font-size: 18px;
   margin-bottom: 10px;
}
div.upload {
    width: 100%;
    height: 57px;
    background: url("<?php echo $littledataurl; ?>images/upload.png") no-repeat scroll 0px 0;
    overflow: hidden;
}

div.upload input {
    display: block !important;
    width: 100% !important;
    height: 57px !important;
    opacity: 0 !important;
    overflow: hidden !important;
}
div.selfie {
    background: rgba(0, 0, 0, 0) url("<?php echo $littledataurl; ?>images/shutter-2008488_640.png") no-repeat scroll 0px 0;
    cursor: pointer;
    height: 60px;
    overflow: hidden;
    padding: 17px;
    width: 100%;
}

div.selfie input {
    display: block !important;
    opacity: 0 !important;
    overflow: hidden !important;
    cursor: pointer;
}
 #spinner2 {
        background: rgb(249, 249, 249) url("<?php echo $littledataurl; ?>images/rolling.gif") no-repeat scroll 50% 50%;
      height: 100%;
      left: 0;
      opacity: 0.5;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 9999;
    }
</style>
  <div class="col-md-12 col-xs-12" style="margin-bottom:0px">
      <center>
        <div class="heading_caption" style="font-size:20px; color: #000; padding-top: 10px;"><?php echo $backgroundTitle; ?></div>
      </center>
    </div>
   <div id="spinner2" style="display:none"></div>
   <div class="container" style="margin-bottom:100px">
      <div class="row" style="background-color: #f6d749;">
            <div class="col-md-2" style="text-align:center">
            </div>
            <div class="col-md-4  col-xs-6" style="text-align:center">
              <img src="<?php echo $littledataurl; ?>phoCartoons/phoCartoonEmojiOriginal.jpg" style="width:100%; border-radius: 25px;"> 
            </div>
            <div class="col-md-4  col-xs-6" style="text-align:center">
              <img src="<?php echo $littledataurl; ?>phoCartoons/phoCartoonEmoji.jpg" style="width:100%; border-radius: 25px;"> 
            </div>
      </div>
      <div class="row" style="background-color: #f6d749; height: 80px; border-bottom-right-radius:50% 100%;
        border-bottom-left-radius:50% 100%;"><center><img style="padding-top: 5px;" align="center" src="<?php echo base_url(); ?>images/NewImages/dwn.png" width="100px"></center></div>
      <div class="row">
            <div class="col-md-4 col-xs-2" style="text-align:center">
            </div>
            <div class="col-md-4  col-xs-8" style="text-align:center;margin-top:50px;">
                <style>
                  input[type="file"] {
                    display: none;
                  }
                  .upload2 {
                    /*border: 1px solid #fff;
                    border-radius: 5px;
                    color: #fff;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 17px;
                    padding: 10px;
                    width: 100%;*/
                    border: 1px solid #fff;
                    border-radius: 25px;
                    color: #000000;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 20px;
                    padding: 18px;
                    width: 100%;
                    height: 60px;
                    background-color: #efe3b1;
                }
                </style>
              <form name="posting_comment" id="posting_comment">
              <center>

              <label class="upload2">
               <input id="file" name="file" type="file" onchange="return sendData()"/>
                  <?php echo $upload; ?>
              </label>

              <!-- <div class="upload">
                  <input id="file" name="file" type="file" onchange="return sendData()"/>
                </div> -->
                  <!-- <input type="button"  value="Post" onclick = "return sendData()"/> -->
              </form>
              </center>
              <br/>
              <?php
                $useragent=$_SERVER['HTTP_USER_AGENT'];

                if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
                  ?>
                    <center>
                      <form name="posting_comment2" id="posting_comment2">
                        <label class="upload2">
                         <input id="file" name="file" type="file" onchange="return sendData2()"/>
                            <?php echo $Selfie; ?>
                        </label>

                        <!-- <div class="selfie">
                            <input id="file" name="file" type="file" onchange="return sendData2()"/>
                          </div> -->
                            <!-- <input type="button"  value="Post" onclick = "return sendData()"/> -->
                        </form>
                    </center>
                  <?php
                }
              ?>
              <span class="resMsg"></span>
            </div>
      </div>
   </div>
