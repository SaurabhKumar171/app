<style>
.close {
  position: absolute;
  top: 0;
  right: 0;
  color: black !important;
}
    #popup, #popup1, #popupar, #popupen{
          color: #333;
          //display: none;
          padding: 1em;
          border: 0px solid #333;
          background-color: white;
          text-align: left;
          z-index: 1001;
          overflow: auto;
          font-size: 16px;
          position: relative;
          //margin: auto;
          margin-top:6%;
          top: 0;
          bottom: 0;
          left: 20px;
          //width: 60%;
          max-width: 570px;
          height: auto;
          //max-height:550px;
          border-radius: 5px;
    }
    #popup.userinfopopup{
        max-height:300px;
      }
      #popup.unsubinfopopup{
        max-height:400px;
      }
      #popup.passcodepopup,#popup.contactinfopopup{
        max-height: 270px;
      }
      #popup.subinfopopup{
        max-height: 450px;
      }
      #popup.tcpopup{
       /* max-height: 600px;*/
         max-height: 480px;
      }
      #popup, #popup1, #popupar, #popupen div.heading{
        font-size: 20px;
        font-weight: bold;
      }
      .companyname{
        font-weight: bold;
        text-align: center;
        font-size: 20px;
      }
      #overlay{
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: #bbb;
        opacity: .80;
      }
      #close_popup {
          border: 2px solid #000;
          border-radius: 50%;
          cursor: pointer;
          font-size: 16px;
          padding: 3px 7px;
          float: right;
      }
.red{
  background: red !important;
}
.topcorner{
       position:absolute;
       top:30px;
       right:36px;
      }
select {

    width: 50px;
    height: 30px;
    margin: 25px;

}
#signupForm{
    margin-top: 5px !important;
} 
</style>
<body>
<link rel="stylesheet" href="http://mcomics.club/css/signup.css">

<section style="padding-top: 15px; padding-bottom: 15px;">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
      <center>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mx-auto">          
        <img style="text-align: center;" src="https://mcomics.club/images/meme/mtoon-logo-50.png"> </div></center>
      <center>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mx-auto">
          <!-- <a href="#"><img src="https://mcomics.club/images/meme/profile.png" width="25px;"></a> -->
      </div>
      </center>
    </div>
  </div>
</section>

<div class="container" id="maindiv">
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
    
                <div id="popup1" class="container" <?php if($lang=="ar"){ ?> style="display: none;" <?php } ?> >
      <div class="mobilediv heading"><center>Terms and Conditions</center></div>
      <br/>
      <h4>By subscribing to mToon you have agreed to the following terms and conditions:</h4>
      <div>
          Free for 24 hours then AED 3.25 per day VAT included.
      </div>
      <br/>
      <div>
          After free trial, you will be charged as per selected pack automatically.
      </div>
      <br/>
      <div>
            A standard subscription charges are applicable on subscribing to mToon:
            <br/>
            Daily Plan at AED 3.25 (VAT Inclusive) valid for 1 day.
        </div>
        <br/>
        <div>
            No commitment, you can cancel any time by sending C MTD to 1111.
        </div>
        <br/>
        <div>
            If you have any questions, you may submit an inquiry at support@pheuture.com
        </div>
        <br/>
        <center><h4>For complete T&C <u><a href="javascript:void(0);" id="tc_show_en" style="color: black;">click here</a></u></h4></center>
        <br/><br/>
      </div>
            
            
                <div id="popup1" class="container" <?php if($lang=="en"){ ?> style="display: none;" <?php } ?> dir="rtl">
      <div class="mobilediv heading"><center>الأحكام والشروط</center></div>
      <br/>
      <h4>بالاشتراك في mToon ، فإنك توافق على الشروط والأحكام التالية:</h4>
      <div>
          مجانًا لمدة 24 ساعة ثم 3.25 درهمًا إماراتيًا في اليوم متضمنًا ضريبة القيمة المضافة.
      </div>
      <br/>
      <div>
          بعد انتهاء الفترة التجريبية المجانية ، سيتم محاسبتك تلقائيًا حسب الحزمة المحددة.
      </div>
      <br/>
      <div>
            تنطبق رسوم الاشتراك القياسية على الاشتراك في mToon:
            <br/>
            الخطة اليومية بسعر 3.25 درهم (شاملة ضريبة القيمة المضافة) صالحة لمدة يوم واحد.
        </div>
        <br/>
        <div>
            لا يوجد التزام ، يمكنك الإلغاء في أي وقت بإرسال C MTD إلى 1111.
        </div>
        <br/>
        <div>
            إذا كان لديك أي أسئلة ، يمكنك إرسال استفسار على support@pheuture.com
        </div>
        <br/>
        <center><h4>للحصول على الشروط والأحكام كاملة <u><a href="javascript:void(0);" id="tc_show_ar" style="color: black;">انقر هنا</a></u></h4></center>
        <br/><br/>
      </div>
</div>

<div id="popupar" class="container" style="display: none;" dir="rtl">
  <h2 class="close" id="close_ar">X</h2>
    <div class="mobilediv heading"><center>الأحكام والشروط</center></div>
    <br/>
    <h4>بالاشتراك في mToon ، فإنك توافق على الشروط والأحكام التالية:</h4>
    <div>
        مجانًا لمدة 24 ساعة ثم 3.25 درهمًا إماراتيًا في اليوم متضمنًا ضريبة القيمة المضافة.
    </div>
    <br/>
    <div>
        بعد انتهاء الفترة التجريبية المجانية ، سيتم محاسبتك تلقائيًا حسب الحزمة المحددة.
    </div>
    <br/>
    <div>
          تنطبق رسوم الاشتراك القياسية على الاشتراك في mToon:
          <br/>
          الخطة اليومية بسعر 3.25 درهم (شاملة ضريبة القيمة المضافة) صالحة لمدة يوم واحد.
      </div>
      <br/>
      <div>
          يتم تقديم فترة تجريبية مجانية لمدة يوم واحد لجميع المشتركين الجدد. إذا كنت قد استمتعت بعرض التجربة المجانية في الماضي ، فسيتعين عليك الاختيار من خطة الاشتراك القياسية للاشتراك.
      </div>
      <br/>
      <div>
          سيتم تجديد اشتراكك في mToon تلقائيًا وفقًا لخطة الاشتراك الخاصة بك ، حتى توقف الخدمة.
      </div>
      <br/>
      <div>
          لا يوجد التزام ، يمكنك الإلغاء في أي وقت بإرسال C MTD إلى 1111.
      </div>
      <br/>
      <div>
          للاستفادة من هذه الخدمة ، يجب أن يكون عمر الشخص أكثر من 18 عامًا أو أن يكون قد حصل على إذن من والديك أو الشخص المفوض بدفع فاتورتك.
      </div>
      <br/>
      <div>
          5٪ ضريبة القيمة المضافة شاملة السعر أعلاه.
      </div>
      <br/>
      <div>
          إذا كان لديك أي أسئلة ، يمكنك إرسال استفسار على support@pheuture.com
      </div>
      <br/><br/>
</div>

<div id="popupen" class="container" style="display: none;">
  <h2 class="close" id="close_en">X</h2>
    <div class="mobilediv heading"><center>Terms and Conditions</center></div>
    <br/>
    <h4>By subscribing to mToon you have agreed to the following terms and conditions:</h4>
    <div>
        Free for 24 hours then AED 3.25 per day VAT included.
    </div>
    <br/>
    <div>
        After free trial, you will be charged as per selected pack automatically.
    </div>
    <br/>
    <div>
          A standard subscription charges are applicable on subscribing to mToon:
          <br/>
          Daily Plan at AED 3.25 (VAT Inclusive) valid for 1 day.
      </div>
      <br/>
      <div>
          A FREE TRIAL period of 1 day is offered to all new subscribers. If you have enjoyed FREE TRIAL offer in the past, you will have to choose from the standard subscription plan to subscribe.
      </div>
      <br/>
      <div>
          Your subscription to mToon will be renewed automatically as per your subscription plan, until you stop the service.
      </div>
      <br/>
      <div>
          No commitment, you can cancel any time by sending C MTD to 1111.
      </div>
      <br/>
      <div>
          To make use of this service, one must be more than 18 years old or have received permission from your parents or person who is authorized to pay your bill.
      </div>
      <br/>
      <div>
          5% VAT is Inclusive the above price.
      </div>
      <br/>
      <div>
          If you have any questions, you may submit an inquiry at support@pheuture.com
      </div>
      <br/><br/>
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
        window.location.href="http://mComicsapp.com/pal_otp?mobnum="+mob;
    }
});

$("#tc_show_en").click(function(){
    $("#maindiv").hide();
    $("#popupen").show();
    window.scrollTo(0,0);
});
$("#close_en").click(function(){
    $("#maindiv").show();
    $("#popupen").hide();
    window.scrollTo(0,0);
});

$("#tc_show_ar").click(function(){
    $("#maindiv").hide();
    $("#popupar").show();
    window.scrollTo(0,0);
});
$("#close_ar").click(function(){
    $("#maindiv").show();
    $("#popupar").hide();
    window.scrollTo(0,0);
});
</script>

</html>