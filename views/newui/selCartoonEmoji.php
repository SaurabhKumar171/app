<style>
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
#versionBar {
    background-color: transparent;
    bottom: 0;
    box-shadow: none;
    left: 0;
    line-height: 35px;
    max-height: 200px;
    min-height: 200px;
    //position: fixed;
    text-align: center;
    width: 100%;
    z-index: 11;
    overflow-y: auto;
}
.fixFooter1{
    background-color: #000;
    float: left;
    color:#fff;
    width: 50%;
    padding: 10px 0;
}
.fixFooter2{
    background-color: #DD5511;
    color:#fff;
    padding: 10px 0;
}
a.cartton,a.cartton:hover,a.cartton:focus{
    color:#fff;
    text-decoration: none;
}
.emojipanel{
  line-height: 45px;
    padding: 3px;
  cursor: pointer;
}
.emoji{
  padding:0px;
  border:2px solid #fff;
  border-radius:5px;
}
.active{
  border: 1px solid #bbb;
  border-radius: 5px;
  padding: 2px;
}
.inactive{
  border: 1px solid #fff;
  border-radius: 5px;
  padding: 2px;
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

</style>
<script>
$(document).ready(function () {
    $('.emoji').click(function(){
      $("#spinner2").show();
      var name = $(this).attr('name');
      var param = $(this).attr('param');
      var emoji_name = $(this).attr('emoji_name');
      var event_status = $(this).attr('event');
      //alert("name : "+name + " param : "+param + " emoji_name : "+emoji_name);
      console.log(event_status);

       $('.emoji').removeClass('active');
       $(this).removeClass('inactive');
       $(this).addClass('active');

      $('.emoji_name').html(emoji_name);
        $('#result').html("");
        var data = "name="+name+"&param="+param+"&emoji_name="+emoji_name;
        $.ajax({
                type:'POST',
                dataType: 'json',
                data:data,
                url: "<?php echo base_url()?>editimage/",
                beforeSend:function(data){
                   $("#spinner2").show();
                },
                success:function(data){
                   genreateImg(data.request_id,data.num,data.genid);
                },
                error(){
                  console.log("An error occurs");
                  //$("#spinner2").hide();    
                }
           });
    });       
});


function genreateImg(request_id,num,genid){
          //console.log(genid);
          //alert("sdfgfhfd ---" + genid);
          var data = 'request_id='+request_id+'&num='+num+"&genid="+genid;
        $.ajax({
          type:'POST',
          dataType: 'json',
          data:data,
          url: "<?php echo base_url()?>level555/",
          beforeSend:function(data){
             $("#spinner2").show();
          },
          success:function(data){
            //console.log(data.status);
            var status = (data.status).toLowerCase();
            var description = (data.description).toLowerCase();
            if(status=='ok'){
                //console.log(genid);
                //console.log("<?php echo base_url(); ?>uploadPhoCartoon/"+data.fimg);
                $('.neweditablrimg').attr("src","<?php echo $littledataurl; ?>uploadPhoCartoon/"+data.fimg);
                window.clearTimeout(genreateImg);
                $("#spinner2").hide();
                
            }else if(status=='inprogress'){
              $('.resMsg').html(data.desc);
                setTimeout(function(){
                   genreateImg(request_id,num,genid)
               }, 5000);
            }else if(status=='bad request'){
              alert(description+' <?php echo $facenotfound; ?>');
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

</script>
   <div class="col-md-12 col-xs-12" style="margin-bottom:0px">
      <center>
        <?php echo $previewimage;       ?>
      </center>
    </div>
   <div class="container" style="margin-bottom:10px;">
    <div id="spinner2" style="display:none"></div>
      <div class="row">
            <div class="col-md-3 col-xs-2" style="text-align:center">
            </div>
            <div class="col-md-6  col-xs-8" style="text-align:center">
              <img class="neweditablrimg" src="<?php echo $littledataurl; ?>uploadPhoCartoon/<?php echo $genResult['final_img'] ?>" style="width:100%">
            </div>
            <div class="col-md-3 col-xs-2" style="text-align:center">
            </div>
      </div>
      
  </div>
  <div class="">
        <div class="col-md-12" style="text-align:center;margin-top:8px;font-weight:bold;color:#fff">
            <span class="emoji_name">
            <?php
            if(ucwords($genResult['emoji_name']) == 'Cartoon Effect'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='කාටූන් බලපෑම';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                  $genResult['emoji_name'] ='কার্টুন প্রভাব';          
                }else if($this->session->userdata('userlanguage') == 'FRENCH'){
                  $genResult['emoji_name'] ='Effet de dessin animé';          
                }else if($this->session->userdata('userlanguage') == 'ARABIC'){
                  $genResult['emoji_name'] ='تأثير الرسوم المتحركة';          
                }
            }
            //echo $genResult['emoji_name'];
              /*if(ucwords($genResult['emoji_name']) == 'Animated Smile'){

                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='සිනමා උච්චාරණය';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড হাসি';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Cartoon effect'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='කාටූන් බලපෑම';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='কার্টুন প্রভাব';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated Sad'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='සජිවීකරණ කතාව';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড স্যাড';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated ops'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ඇනටික් ඔප්ස්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড ওপস';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated Squint eyed'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ඇනිවට් පෙට්ටි ඇස්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড চিকন আইড';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated Wink'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='සිනමා පටය';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড সিন্ধু';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated Flirt'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ඇනට් ෆ්ලැට්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড ফ্লার্ট';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Animated Offended'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ඇනඩිං';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অ্যানিমেটেড অভিযুক্ত';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Smileহাসি'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ස්මයිල්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='হাসি';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Sad'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='දුක';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='দু: খিত';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Ops'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ඔප්ස්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অপস';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Squint eyed'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='නරකයි';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='স্কুইন্ট আইড';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Wink'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ගහන්න';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='চক্ষুর পলক';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Flirt'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ෆ්ලයිට්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='ছিনাল';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Offended'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='නරකයි';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='বিক্ষুব্ধ';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Troll'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ට්රෝල්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='দানব';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Alien'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='විදේශිකයෙක්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='পরক';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Martian'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='අඟහරු';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='মঙ্গল';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Bulb head'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='බල්බ හිස';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='বাল্ব মাথা';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Tough guy'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='දුකෙන්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='শক্ত লোক';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Grotesque'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='උග්රයි';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='অদ্ভুত';          
                }
              }else if(ucwords($genResult['emoji_name']) == 'Fat-cheeked'){
                if($this->session->userdata('userlanguage') == 'SINHALESE'){
                  $genResult['emoji_name'] ='ෆැට්-කම්මුල්';
                }else if($this->session->userdata('userlanguage') == 'BANGLA'){
                   $genResult['emoji_name'] ='চর্বি হত্তয়া';          
                }
              }*/

            ?>
            <?php echo ucwords($genResult['emoji_name']); ?>
            </span>
        </div>
    </div>
<div id="versionBar">
  <div style="width:100%;background-color:#523a37;color:#fff;padding:5px;text-align:center; font-family: Mywebfont !important;">
   <!--  Apply Face Morphing -->
    <?php echo $applyface; ?>
  </div>
  <div style="background-color: #fff;">
  <center>
    <?php
      $tmp = false;
      foreach ($result as $value) {
        //echo $value->emoji_name.' '.$value->name.' '.$value->param.' '.$value->img.'<br/>';
        echo '<span class="emojipanel">';
        // echo '<img src="'.base_url().'css/emoji/'.$value->img.'" class="emoji '.$res = ($tmp==true ? "active" : "inactive").'" title="'.$value->emoji_name.'"> ';

        if($value->$emojiname==$genResult['emoji_name']){
            $tmp = true;
        }

        echo '<img src="'.$littledataurl.'css/emoji/'.$value->img.'" class="emoji '.$res = ($tmp==true ? "active" : "inactive").'" title="'.$value->$emojiname.'" name="'.$value->name.'" param="'.$value->param.'" event="emoji_'.$value->id.'" id="emoji_'.$value->id.'" emoji_name="'.$value->$emojiname.'"> ';
        echo '</span>';
        $tmp = false;
      }
      
    ?>  
  </center>
  </div>
</div>
<?php if($_SERVER['HTTP_HOST']=="m.mcomics.club"){ ?>
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
      console.log(document.cookie);
      
  </script>
 <?php } ?>
 <?php
$check=$this->session->userdata("showlogo");
if($check=="yes")
{
?>
<center>Powered By Moodit</center>
<?php } ?>
</div>