<style>
.result{
   // width:100%;
    /*border:2px solid #fff;
    border-radius:5px;*/
   
    cursor: pointer;
}
.result2{
    width: 100%;
    border-radius:5px
}
.notAva {
    background-color: #dd5511;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    padding: 20px;
    text-align: center;
}
.modal-content{
    background-color: transparent;
    box-shadow: none;
    border: medium none;
}
.modal-body{
    padding: 0px;
}
.modal-footer {
    //border-top: 1px solid #e5e5e5;
    padding: 10px 0px;
    //text-align: right;
}
.close2 {
    border: 2px solid #ccc;
    border-radius: 50%;
    color: #ccc;
    margin-left: 10px;
    margin-top: 10px;
    opacity: 0.96;
    padding: 5px 6px;
    position: fixed;
}
.close:hover, .close:focus {
    color: #fff;
    cursor: pointer;
    opacity: 0.96;
}
.item-image {
  position: relative;
  overflow: hidden;
  padding-bottom: 50%;
}
.item-image img {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
}
.item-content {
  padding: 15px;
  background: #72cffa;
}
.item-text {
  position: relative;
  overflow: hidden;
  height: 100px;
}
.heading_caption{
font-family: Mywebfont !important;
}
</style>

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


<script>
$(document).ready(function () {
    $('.result').click(function(){
        var id = $(this).attr('data-id');
        $('#result').html("");
        //alert(id);
        var data = "genid="+id+"&back=viewPhotoEffect";
        $.ajax({
                type:'POST',
                data:data,
                url: "<?php echo base_url()?>popupPhotoEffect/",
                beforeSend:function(data){
                   //$("#spinner2").show();
                },
                success:function(data){
                    $('#result').html(data);
                    $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                },
                error(){
                  console.log("An error occurs");
                  //$("#spinner2").hide();    
                }
           });
    });       
});


</script>

<div class="container" style="margin-bottom:100px">
  <div class="row">
    <div class="col-md-12 col-xs-12" style="margin-bottom:20px;">
      <center>       
        <?php echo $photoeffects; ?>
     <!--    <div class="heading_caption" style="font-size:20px">My Photo Effects</div> -->
      </center>
       <input type="hidden" name="userlanguage" id="userlanguage" value="<?php echo $userlanguage; ?>" />
    </div>
    <?php
        if(count($result)!=0){
            foreach ($result as $row) {
        ?>
            <div class="col-xs-6 col-sm-3" style="margin-bottom:5px;" id="showPhoto_<?php echo $row->id; ?>">
                <div class="item-image">
                <img src="<?php echo $littledataurl; ?>uploadPhoCartoon/<?php echo $row->final_img; ?>" class="img-responsive result"data-toggle="modal" data-target="#myModal" data-id="<?php echo $row->id; ?>">
                </div>
            </div>
        <?php
            }
        }else{
            echo'<div class="col-md-4"></div><div class="col-md-4">';
            echo '<div class="notAva">'.$yettocreate.'</div>';
            echo '</div>';
        }
    ?>

  </div>
</div>


<div id="result"></div>


