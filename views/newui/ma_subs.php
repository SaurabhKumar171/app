<script>
function checkSub(pack){
    var lang = $("#sel_lenguage").val();
    window.location.href="<?php echo base_url();?>welcome/ma_he/"+pack+"/"+lang;
}

$(function() {
    $("#sel_lenguage").change(function() {
        var lang = $("#sel_lenguage").val();
        if(lang=="ar"){
            $("#en_secion").hide();
            $("#fr_secion").hide();
            $("#ar_secion").show();
        }
        else if(lang=="fr"){
            $("#en_secion").hide();
            $("#ar_secion").hide();
            $("#fr_secion").show();
        }
        else{
            $("#ar_secion").hide();
            $("#fr_secion").hide();
            $("#en_secion").show();
        }
    });
});
</script>
<body>

<div id="waiting" style="display:none;"></div>
<div id="spinner"></div>

<div class="container">
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <select class="form-control" id="sel_lenguage" name="sel_lenguage">
        <option value="en">En</option>
        <option value="ar">Ar</option>
        <option value="fr">Fr</option>
        </select>
        </div>
        
    </div>
</div>
</div>



<div class="container" id="en_secion">

<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>Monthly Pack @ MRU 50/month</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>Weekly Pack @ MRU 20/week</b></button>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="result" style="color:#000;text-align:center;">&nbsp;</div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Daily Pack @ MRU 5/day</b></button>
        </div>
    </div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det"><b>You will subscribe in mComics for selected pack. This is an auto-renewal service.</b><br/>To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->

<!-- #######################  French  #########################-->
<div class="container" id="fr_secion" style="display:none;">

<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>1 day FREE trail</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<div class="row">
    <div class="col-md-12">
        <div class="result" style="color:#000;text-align:center;">&nbsp;</div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>Pack mensuel @ MRU 50/mois</b></button>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>Pack hebdomadaire @ MRU 20/semaine</b></button>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="result" style="color:#000;text-align:center;">&nbsp;</div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>Pack quotidien @ MRU 5/jour</b></button>
        </div>
    </div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det"><b>Vous vous abonnerez à mComics pour le pack sélectionné. Il s'agit d'un service de renouvellement automatique.</b><br/>Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  English section container -->


<!-- #########################  Arabic ############################## -->
<div class="container" id="ar_secion" style="display: none;">
<div class="row">
    <center><img class="logo" alt="mComics"  src="https://mcomics.club/images/NewImages/Logo.png" style="width:175px;"></center>
</div>

<!-- <div class="row" dir="rtl">
    <div class="col-md-12">
        <div class="col-md-4"></div>
        <center>
            <div class="col-md-4"><h5><b>تجربة مجانية ليوم واحد</b></h5></div>

        </center>
        <div class="col-md-4"></div>
    </div>
</div> -->

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('monthly')"><b>الباقة الشهرية @ MRU 50 / شهر</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('weekly')"><b>الباقة الأسبوعية @ MRU 20 / أسبوع</b></button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="result" style="color:#000;text-align:center;">&nbsp;</div>
<div class="col-md-4"></div>
<div class="col-md-4">
<button type="submit" class="form-control subscribe_btn" onclick="checkSub('daily')"><b>الباقة اليومية @ MRU 5 / يوم</b> </button>
</div>
</div>
</div>

 <!-- End of row -->

<div class="row">
    <div class="col-md-12" dir="rtl">
        <div class="col-md-4"></div>
        <center><div class="col-md-4"><p class="subscription_det"><b>سوف تشترك في mComics للحزمة المحددة. هذه خدمة تجديد تلقائي.</b><br/>لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com</p></div></center>
        <div class="col-md-4"></div>
    </div>
</div> <!-- End of row -->
<div style="height: 50px;"></div>
</div> <!-- End of  Arabic section container -->
</body>
</html>