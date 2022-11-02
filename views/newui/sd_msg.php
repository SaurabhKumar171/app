<body>
<link rel="stylesheet" href="http://mcomics.club/css/signup.css">

<div class="container">
    <form id="signupForm" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
            <center><div class="alert alert-danger" role="alert" id="showerror_msg"><?php echo $msg; ?>
            </div></center>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div style="margin-top:0px;"></div>
    </form>
</div>

</body>
<script>
$(".nextbtn_pj").click(function(e){
    e.preventDefault();
    e.stopPropagation(); 
    var mob=$('#mobnum_pj').val();
    if(mob==""){
        $("#showerror_msg").show();
        $('#mobnum_pj').focus();
        $('#mobnum_pj').css("border-color", "red");
        $("#showerror_msg").html("Please enter mobnum !");
        setTimeout(function(){
            $("#showerror_msg").hide();
            $('#mobnum_pj').css("border-color", "");           
        }, 5000);
        return false;
    }
    else{
        window.location.href="http://mtoonapp.com/pal_otp?mobnum="+mob;
    }
});
</script>

</html>