<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('comic_model');
        $this->load->model('subscribe_model');
        $this->load->library('session');
        
        $mobnum = $this->session->userdata('mobnum');
        /*if(substr($mobnum,0,3)=="880"){ //bangladesh
            $this->session->set_userdata('userlanguage', 'ENGLISH');
        }else{
           // $this->session->set_userdata('userlanguage', 'SINHALESE');
             $this->session->set_userdata('userlanguage', 'ENGLISH');
        }*/

        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$mobnum'");
        if($query->num_rows()>0){
            $result         = $query->result();
            $language   = $result[0]->language;
            if($language == 'english'){
                $this->session->set_userdata('userlanguage', 'ENGLISH');
            }else if($language == 'bangla'){
                $this->session->set_userdata('userlanguage', 'BANGLA');
            }else if($language == 'french'){
                $this->session->set_userdata('userlanguage', 'FRENCH');
            }else if($language == 'arabic'){
                $this->session->set_userdata('userlanguage', 'ARABIC');
            }else if($language == 'bahasa'){
                $this->session->set_userdata('userlanguage', 'BAHASA');
            }else if($language == 'burmese'){
                $this->session->set_userdata('userlanguage', 'BURMESE');
            }else{
                //$this->session->set_userdata('userlanguage', 'SINHALESE');
                $this->session->set_userdata('userlanguage', 'ENGLISH');
            }
            //$this->session->set_userdata('userlanguage', 'ENGLISH');
        }else{
        	if ($_SERVER['HTTP_HOST']=='zj.mcomics.club' || $_SERVER['HTTP_HOST']=='zj.mooddiit.com' || $_SERVER['HTTP_HOST']=='mtoon.xyz') { //for myanmar burmese
	            $this->session->set_userdata('userlanguage', 'ARABIC');
                $this->session->set_userdata('fbshare_domain', 'zj');
	        }
            else{
            	$this->session->set_userdata('userlanguage', 'ENGLISH');
            }
        }

        if ($_SERVER['HTTP_HOST']=='a.mcomics.club' || $_SERVER['HTTP_HOST']=='z.mcomics.club') {
            $this->session->set_userdata('mobnum', "919718698337");
            $this->session->set_userdata('userlanguage', 'ARABIC');
            $this->session->set_userdata('fbshare_domain', 'a');
        }
        if ($_SERVER['HTTP_HOST']=='b.mcomics.club') { //for myanmar burmese
            $this->session->set_userdata('mobnum', "919997970414");
            $this->session->set_userdata('subuser', "YES");
            $this->session->set_userdata('userlanguage', 'BURMESE');
            $this->session->set_userdata('showlogo', 'yes');
            $this->session->set_userdata('fbshare_domain', 'b');
        }

        if($_SERVER['HTTP_HOST']=='fb.mcomics.club'){
            $mobnum              = $this->session->userdata('mobnum');
            if($mobnum!='' and strlen($mobnum) >5){ 
            //for redirect from littledata.in/mcomt/ for facebook share issue
                if(substr($mobnum,0,3)=='962'){
                    //redirect('http://zj.mcomics.club');
                    redirect('http://zj.mooddiit.com');
                }else if(substr($mobnum,0,3)=='964'){
                    redirect('http://zi.mcomics.club');
                }else if(substr($mobnum,0,3)=='965'){
                    redirect('http://vk.mcomics.club');
                }
            }
        }
        $fbshare = $this->session->userdata('fbshare_domain');
        if(isset($fbshare) && $fbshare!=''){

        }else{
            if ($_SERVER['HTTP_HOST']=='zj.mcomics.club' || $_SERVER['HTTP_HOST']=='zj.mooddiit.com') { //for zain jorda
            $this->session->set_userdata('fbshare_domain', 'zj');
            }else if ($_SERVER['HTTP_HOST']=='zi.mcomics.club' || $_SERVER['HTTP_HOST']=='zi.mooddiit.com') { //for zain iraq
                $this->session->set_userdata('fbshare_domain', 'zi');
            }else  if ($_SERVER['HTTP_HOST']=='b.mcomics.club') { //for myanmar burmese
                $this->session->set_userdata('fbshare_domain', 'b');
            }else  if ($_SERVER['HTTP_HOST']=='a.mcomics.club') { 
                $this->session->set_userdata('fbshare_domain', 'a');
            }else  if ($_SERVER['HTTP_HOST']=='z.mcomics.club') { 
                $this->session->set_userdata('fbshare_domain', 'z');
            }else{ 
                $this->session->set_userdata('fbshare_domain', 'mc');
            }
        }
        
            
        if ($_SERVER['HTTP_HOST']=='m.mcomics.club') {
            redirect("http://mcomics.club");
        }
        //$this->session->unset_userdata('comic');
        //$this->session->sess_destroy();
    }

    public function readFile() {
        $this->load->view('readFile');
    }

    public function login() {
        $this->load->view('template/header_auth');
        $data['country_list'] = $this->comic_model->country_list();
        $this->load->view('signup',$data);
    }

    public function sendPassword() {

        $country 	= explode('|',$this->input->post('country'));
        $countryName= $country[0];        
        $code 		= $country[1];
        $mobnum 	= $this->input->post('mobnum');
        $password 	= rand(1234,9876);
        $mtnngcode =  substr($mobnum,0,6);
        if($code=='91' && $countryName=='ind'){
            $mobnum = '91'.$mobnum;
            if( $mobnum == '919871666000' or $mobnum == '919873032445' or $mobnum == '919718698337'){
                $this->session->set_userdata('mobnum', $mobnum);
                 echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                    $result = $this->comic_model->update_password($code,$mobnum,$password);
                }else{
                    $result = $this->comic_model->create_user($code,$mobnum,$password);
                }
                if($result==0){
                    $this->pushSMS($countryName,$code,$mobnum,$password);
                    $this->session->set_userdata('mobnum', $mobnum);
                    echo '{"message":"true","response":"OTP has been sent."}';
                }else{
                    echo '{"message":"false","response":"Please try later."}';
                }
            }
            
        }else if($code=='971' && $countryName=='dubai'){
            $mobnum = '971'.$mobnum;
            $eti_series = substr($mobnum,0,5);
            if($eti_series=="97150" || $eti_series=="97154" || $eti_series=="97155" || $eti_series=="97156" || $eti_series=="97158"){
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"redirectetisalat","response":"OTP has been sent."}';
            }else{
                if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                    $result = $this->comic_model->update_password($code,$mobnum,$password);
                }else{
                    $result = $this->comic_model->create_user($code,$mobnum,$password);
                }
                if($result==0){
                    $this->pushSMS($countryName,$code,$mobnum,$password);
                    $this->session->set_userdata('mobnum', $mobnum);
                    echo '{"message":"true","response":"OTP has been sent."}';
                }else{
                    echo '{"message":"false","response":"Please try later."}';
                }
            }
           
        }else if($code=='962' && $countryName=='jordan'){
            $mobnum = '962'.$mobnum;
            $this->session->set_userdata('mobnum', $mobnum);
            echo '{"message":"jordan","response":"OTP has been sent."}';
            /*if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                $result = $this->comic_model->update_password($code,$mobnum,$password);
            }else{
                $result = $this->comic_model->create_user($code,$mobnum,$password);
            }
            if($result==0){
                $this->pushSMS($countryName,$code,$mobnum,$password);
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                echo '{"message":"false","response":"Please try later."}';
            }*/           
        }else if($code=='880' && $countryName=='bang'){
            $mobnum = '880'.$mobnum;
            if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                $result = $this->comic_model->update_password($code,$mobnum,$password);
            }else{
                $result = $this->comic_model->create_user($code,$mobnum,$password);
            }
            if($result==0){
                $this->pushSMS($countryName,$code,$mobnum,$password);
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                echo '{"message":"false","response":"Please try later."}';
            }
        }else if($code=='234' && $countryName=='nigeria' && ($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803"  || $mtnngcode=="234806" || $mtnngcode=="234810" || $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")){      //mtn nigeria
            $mobnum = '234'.$mobnum;
            if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                $result = $this->comic_model->update_password($code,$mobnum,$password);
            }else{
                $result = $this->comic_model->create_user($code,$mobnum,$password);
            }
            if($result==0){
                $this->pushSMS("mtnnigeria",$code,$mobnum,$password);
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                echo '{"message":"false","response":"Please try later."}';
            }
        }else if($code=='234' && $countryName=='nigeria'){      //airtel nigeria
            $mobnum = '234'.$mobnum;
            if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                $result = $this->comic_model->update_password($code,$mobnum,$password);
            }else{
                $result = $this->comic_model->create_user($code,$mobnum,$password);
            }
            if($result==0){
                $this->pushSMS($countryName,$code,$mobnum,$password);
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                echo '{"message":"false","response":"Please try later."}';
            }
        }else if($code=='94' && substr($mobnum,0,2)=='75' && $countryName=='srilanka'){      //airtel srilanka
            $mobnum = '94'.$mobnum;
            if(count($this->comic_model->findUser($code,$mobnum)) > 0){
                $result = $this->comic_model->update_password($code,$mobnum,$password);
            }else{
                $result = $this->comic_model->create_user($code,$mobnum,$password);
            }
            if($result==0){
                $this->pushSMS($countryName,$code,$mobnum,$password);
                $this->session->set_userdata('mobnum', $mobnum);
                echo '{"message":"true","response":"OTP has been sent."}';
            }else{
                echo '{"message":"false","response":"Please try later."}';
            }
        }else if($code=='94' && $countryName=='srilanka'){      //srilanka dialo
            echo '{"message":"redirectsl","response":"OTP has been sent."}';
        }
        else{
            echo 'out';
        }
    }


    public function passwordVerify() {
        $mobnum 	= $this->session->userdata('mobnum');
        $pwd 		= $this->input->post('password');
        $result = $this->comic_model->userVarification($mobnum, $pwd);
        if ($result['id'] != '') {
            echo 0;
           // $this->session->set_userdata('mobnum', $result['mobnum']);
        } else {
            echo 1;
        }
    }


    public function pushSMS($countryName,$code,$mobnum,$password){
    	if($countryName=='ind'){
    		$msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://111.118.180.237/sendSms/sendSms.php?msg=".urlencode($msg)."&mdn=$code.$mobnum";
            file_get_contents($url);
            return 0;
    	}else if($code=='880' && substr($mobnum,0,5)=='88019' && $countryName=='bang'){ //BANGLALINK
            
            $msg = "Hi! $password is your Login OTP for mComics.";
            //file_get_contents("http://111.118.180.237:13013/cgi-bin/sendsms?username=bg&password=foobar&from=21270&to=%2B$mobnum&text=".urlencode($msg));
            file_get_contents("http://202.164.209.130/bg/sendsms.php?mobnum=$mobnum&msg=".urlencode($msg));
            $this->db->query("insert into bg_mcomics.sms_logs(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='bang'){ //ROBI
            
            $msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://localhost/robi/sdpgame/sendSms.php?msg=".urlencode($msg)."&mobnum=$mobnum";
            @file_get_contents($url);
            $this->db->query("insert into robi_mcomics.sms_logs(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='mtnnigeria'){     //mtn nigeria
            $msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://111.118.180.237/mtnngdirect/sdpmcomics/sendSms.php?Mobnum=$mobnum&msg=".urlencode($msg);
            @file_get_contents($url);
            $this->db->query("insert into mtnngria_mc.sms_logs_sdp(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='jordan'){     //mtn nigeria
            $msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://111.118.180.237/zain_jordan/sendSms.php?mdn=$mobnum&msg=".urlencode($msg);
            @file_get_contents($url);
            $this->db->query("insert into zain_jordan.sms_logs_sdp(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='nigeria'){     //airtel nigeria
            $msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://111.118.180.237:13013/cgi-bin/sendsms?username=airtelngria&password=foobar&from=55221&to=%2B$mobnum&text=".urlencode($msg);
            @file_get_contents($url);
            $this->db->query("insert into airtel_nigeria.sms_logs(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='dubai'){
            $msg = "Hi! $password is your Login OTP for mComics.";
            $url = "http://111.118.180.237/dubai_du/sendSms.php?mdn=$mobnum&msg=".urlencode($msg);
            @file_get_contents($url);
            $this->db->query("insert into dubai_mcomics.sms_logs(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else if($countryName=='srilanka'){
            $msg = "Hi! $password is your Login OTP for mComics.";
           //$url = "http://111.118.180.237/airtelsl/mcomics/sendSms.php?mdn=$mobnum&msg=".urlencode($msg);
            //@file_get_contents($url);
            @file_get_contents("http://111.118.180.237:13013/cgi-bin/sendsms?username=airtellk&password=foobar&from=7274&to=%2B$mobnum&text=".urlencode($msg));

            $this->db->query("insert into dubai_mcomics.sms_logs(msisdn,msg) values('$mobnum', '$msg')");
            return 0;
        }else{
    		return 1;
    	}
    }

    public function auth() {
        $this->load->view('template/header_auth');
        $this->load->view('login');
    }

    public function verify() {
        $mobnum = $this->input->post('mobnum');
        $pwd = $this->input->post('pwd');
        $mobnum = '91'.$mobnum;
        $result = $this->comic_model->userVarification($mobnum, $pwd);
        if ($result['id'] != '') {
            echo 0;
            $this->session->set_userdata('mobnum', $result['mobnum']);
        } else {
            echo 1;
        }
    }

    public function userLoged() {
        // if ($this->session->userdata('mobnum') == '') {
        //     if ($_SERVER['HTTP_HOST']=='m.mcomics.club') {
        //         if($this->session->userdata('mobnum') == '1111111111' || $this->session->userdata('subuser') == 'no' ){
        //             redirect(base_url() . 'mlogin', 'refresh');
        //         }else{
        //             redirect(base_url() . 'index', 'refresh');
        //         }
                
        //     }else{
        //         redirect(base_url() . 'auth', 'refresh');
        //     }           
        // }

        if ($_SERVER['HTTP_HOST']=='mcomics.club' or $_SERVER['HTTP_HOST']=='vk.mcomics.club' or $_SERVER['HTTP_HOST']=='j.mcomics.club') {
            if($this->session->userdata('mobnum') == '' || $this->session->userdata('mobnum') == '1111111111' || $this->session->userdata('subuser') == 'no' ){
                redirect(base_url() . 'mlogin');
            }else{
                $mobnum = $this->session->userdata('mobnum');
                /*if(substr($mobnum,0,3)=="971")  //dubai
                {
                    $query = $this->db->query("select productID from dubai_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                }else if(substr($mobnum,0,3)=="880")    //robi
                {
                    $query = $this->db->query("select amount from robi_mcomics.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                }else{
                    $result = array();
                    $query = '';
                } */
                $query = $this->checkSubUser();                
                
                if(count($query) and $query->num_rows()>0){                    
                }else if($mobnum=="919871666000"  or $mobnum == '919873032445'  or $mobnum == '919718698337'){
                }else{
                    $this->session->sess_destroy();
                    //redirect('/index'); 
                    redirect(base_url() . 'index', 'refresh');
                }
                //redirect(base_url() . 'index', 'refresh');
            }
            
        }else if ($_SERVER['HTTP_HOST']=='a.mcomics.club' || $_SERVER['HTTP_HOST']=='z.mcomics.club') {
            $this->session->set_userdata('mobnum', "919718698337");
            $this->session->set_userdata('userlanguage', 'ARABIC');
        }else if ($_SERVER['HTTP_HOST']=='zi.mcomics.club' || $_SERVER['HTTP_HOST']=='zi.mooddiit.com') {
            if(!$this->session->userdata("mobnum")){
                redirect(base_url().'getMDNZI');    
            }
        }
        /*else if ($_SERVER['HTTP_HOST']=='j.mcomics.club') {
            redirect(base_url().'jor_subs');die;
        }*/
        else{
            if ($this->session->userdata('mobnum') == '') {
                redirect(base_url() . 'mlogin', 'refresh');
                //redirect(base_url() . 'auth', 'refresh');
            }
        }


    }

    public function checkSubUser(){
        $mobnum = $this->session->userdata('mobnum');
        $mtnngcode = substr($mobnum,0,6);

        if ($_SERVER['HTTP_HOST']=='u.mtoonapp.com') {
            $query = $this->db->query("select SubStatus from anest_eti_uae_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
        }
        else if(substr($mobnum,0,3)=="971")  //dubai
        {
            $eti_series = substr($mobnum,0,5);
            if($eti_series=="97150" || $eti_series=="97154" || $eti_series=="97155" || $eti_series=="97156" || $eti_series=="97158"){
                $query = $this->db->query("select productID from etisalat_uae_mcom.sub_users where mobnum='$mobnum' and Unsub=0 and date(subdate)=curdate()");
                if($query->num_rows()==0){
                    $query = $this->db->query("select productID from etisalat_uae_mcom.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                }
                //print_r($query->row());die;
            }else{
                $query = $this->db->query("select productID from dubai_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            }
        }
        else if(strlen($mobnum)>400)    //vodacom sa
        {            
            $query = $this->db->query("select mobnum from vodacom_sa.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,2)=="27")    //cellc south africa
        {
            $query = $this->db->query("select SubStatus from cellc_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()==0){
                $query = $this->db->query("select SubStatus from mtnsa_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            }
        }
        else if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
            $query = $this->db->query("select SubStatus from dialog_sl_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
        }
        else if ($_SERVER['HTTP_HOST']=='mtoonapp.com') {
            $query = $this->db->query("select SubStatus from pal_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
        }
        else if ($_SERVER['HTTP_HOST']=='ku.mcomics.club') {
            $query = $this->db->query("select SubStatus from tpay_kuwait.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,2)=="20")    //tpay-egypt
        {
            $query = $this->db->query("select SubStatus from tpay_egypt.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,3)=="974")    //tpay-qatar
        {
            $query = $this->db->query("select SubStatus from tpay_qatar.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,3)=="973")    //tpay-bahrain
        {
            $query = $this->db->query("select SubStatus from tpay_bahrain.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,3)=="966")    //tpay-ksa
        {
            $query = $this->db->query("select SubStatus from tpay_ksa.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        else if(substr($mobnum,0,3)=="254")    //tpay-kenya
        {
            $query = $this->db->query("select SubStatus from tpay_kenya.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }
        //else if(substr($mobnum,0,5)=="88019")    //robi
        else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink 
        {
            
            $query = $this->db->query("select SubStatus from bg_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else if(substr($mobnum,0,3)=="880")    //robi
        {
            
            $query = $this->db->query("select amount from robi_mcomics.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803" || $mtnngcode=="234806" || $mtnngcode=="234810" ||  $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")
        { // mtn nigeria
            $query = $this->db->query("select SubStatus from mtnngria_mc.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else if(substr($mobnum,0,3)=="234")    //airtel nigeria
        {
            $query = $this->db->query("select lastchargedamt as amount from airtel_nigeria.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else if(substr($mobnum,0,3)=="962")    //zain jordan and tpay orange jordan
        {
            $query = $this->db->query("select SubStatus from zain_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
            if($query->num_rows()==0){
                $query = $this->db->query("select SubStatus from tpay_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
            }
        }else if(substr($mobnum,0,2)=="94" && substr($mobnum,2,2)=="75")    //airtel srilanka
        {
            $query = $this->db->query("select SubStatus from airtelsl_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
        }
        else if(substr($mobnum,0,3)=="964")    //robi
        {
            $query = $this->db->query("select * from zain_iraq.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else if(substr($mobnum,0,3)=="965")    //vivo kuwait
        {
            $query = $this->db->query("select * from vivo_kuwait.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if(count($query) <= 0){
                $query1 = $this->db->query("select * from vivo_kuwait.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
                if(count($query1) <= 0) redirect('vivokw_errormsg');
            }
        }else if($mobnum == "919871666000"  or $mobnum == '919873032445' or $mobnum == '919718698337')    //india
        {
            $query = $this->db->query("select productID from dubai_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }//else if(substr($mobnum,0,3)=="947")    //srilanka dialog
        else if(strlen($mobnum)>13)
        {
            $query = $this->db->query("select SubStatus from dialog.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        }else{
            $result = array();
            $query = '';
        }  
        return $query;
    }

    public function phoCartoon(){
        
    	$this->userLoged();
        $data['tmp']=1;

        ///added code by dushyant 11sep2017
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['Create_Cartoon']="කාටූන් නිර්මාණය කරන්න";
            $data['Create_Comics']="කොමියූස් කරන්න";
            $data['Create_background']="පසුබිම් බලපෑම";
            $data['createComicsTitle']='කොමියූස් කරන්න';
            $data['createButtonName']='ඔබේ විහිලු සාදන්න';
            $data['sliderimg'] = 'slidersin1.jpg' ;
            $data['sliderimg2'] = 'slidersin2.jpg' ;
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['Create_Cartoon']="কার্টুন তৈরি করুন";
            $data['Create_Comics']="কমিক্স তৈরি করুন";
            $data['Create_background']="পটভূমি প্রভাব";
            $data['createComicsTitle']='কমিক্স তৈরি করুন';
            $data['createButtonName']='আপনার কমিকস তৈরি করুন';
            $data['sliderimg'] = 'sliderbang1.jpg' ;
            $data['sliderimg2'] = 'sliderbang2.jpg' ;
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['Create_Cartoon']="Créer une bande dessinée";
            $data['Create_Comics']="Créer des BD";
            $data['Create_background']="Effet de fond";
            $data['createComicsTitle']='Créer des BD';
            $data['createButtonName']='Créez vos bandes dessinées';
            $data['sliderimg'] = 'sliderfren1.jpg' ;
            $data['sliderimg2'] = 'sliderfren2.jpg' ;
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['Create_Cartoon']="إنتاج الكاريكاتير";
            $data['Create_Comics']="إنتاج الكاريكاتير";
            $data['Create_background']="تأثير الخلفية";
            $data['createComicsTitle']='إنتاج الكاريكاتير';
            $data['createButtonName']='إبدأ';
            $data['sliderimg'] = 'sliderarab1.jpg' ;
            $data['sliderimg2'] = 'sliderarab2.jpg' ;
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['Create_Cartoon']="ကာတွန်း Create";
            $data['Create_Comics']="ကာတွန်း Create";
            $data['Create_background']="နောက်ခံ Effect";
            $data['createComicsTitle']='ကာတွန်း Create';
            $data['createButtonName']='စတင်';
            $data['sliderimg'] = 'sliderburm1.jpg' ;
            $data['sliderimg2'] = 'sliderburm2.jpg' ;
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['Create_Cartoon']="Buat Kartun";
            $data['Create_Comics']="Buat Komik";
            $data['Create_background']="Efek Latar Belakang";
            $data['createComicsTitle']='Buat Komik';
            $data['createButtonName']='Mulailah';
            $data['sliderimg'] = 'slider1.png' ;
            $data['sliderimg2'] = 'slider2.png' ;
        }else{
            $data['Create_Cartoon']="Create Cartoon";
            $data['Create_Comics']="Create Comics";
            $data['Create_background']="Background Effect";

            $data['createComicsTitle']='Create Comics';
            $data['createButtonName']='Start';
            $data['sliderimg']  = 'slider1.png' ;
            $data['sliderimg2'] = 'slider2.png' ;
        }
         $data['littledataurl']='http://littledata.in/mcom/';


        
        
        
        $mobnum = $this->session->userdata('mobnum');        
        $data['result'] = $this->comic_model->lastCreated_avatar($mobnum);
        $this->load->view('newui/template/header_1',$data);
        $this->load->view('newui/home', $data);
        $data['tabstatus'] = '1';
        $data['activeTab'] = $this->uri->segment(1);
        if(substr($mobnum,0,3)=="880")    //robi
        {
            $this->load->view('newui/template/fixedFooterMenuTab_robi', $data);
        }else{
            $this->load->view('newui/template/fixedFooterMenuTab', $data);
        }
        


    }

    public function main() {
        $data['littledataurl']='http://littledata.in/mcom/';
        $data['tmp']=1;
        $this->load->view('template/header_1',$data);
        $this->load->view('main'); 
    }

    public function index() {
       
            $data['backgroundTitle']='Choose a Background';
            $data['createcartoon']='Create<br>Cartoons';
            $data['createcomic']='Create<br/>Comics';       
            $data['backroundeffect']='Background<br/>Effect';  
            $data['maincontent']="newui/main";
        

        $data['littledataurl']='http://littledata.in/mcom/';
        $data['tmp']=1;
        $this->load->view('newui/template/header_1',$data);
        $this->load->view($data['maincontent']);
       
        
    }


    public function backgroundEffect() {
        $this->userLoged(); 
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['Create_Cartoon']="කාටූන් නිර්මාණය කරන්න";
            $data['Create_Comics']="කොමියූස් කරන්න";
            $data['Create_background']="පසුබිම් බලපෑම";
            $data['backgroundTitle']='පසුබිම තෝරන්න';            
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['Create_Cartoon']="কার্টুন তৈরি করুন";
            $data['Create_Comics']="কমিক্স তৈরি করুন";
            $data['Create_background']="পটভূমি প্রভাব";
            $data['backgroundTitle']='একটি পটভূমি চয়ন করুন';            
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['Create_Cartoon']="Créer une bande dessinée";
            $data['Create_Comics']="Créer des BD";
            $data['Create_background']="Effet de fond";
            $data['backgroundTitle']='Choisissez un arrière-plan';            
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['Create_Cartoon']="إنتاج الكاريكاتير";
            $data['Create_Comics']="إنتاج الكاريكاتير";
            $data['Create_background']="تأثير الخلفية";
            $data['backgroundTitle']='اختيار الخلفية';            
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['Create_Cartoon']="ကာတွန်း Create";
            $data['Create_Comics']="ကာတွန်း Create";
            $data['Create_background']="နောက်ခံ Effect";
            $data['backgroundTitle']='တစ်ဦးနောက်ခံသမိုင်းကိုရွေးချယ်ပါ';            
        }else  if($this->session->userdata('userlanguage') == 'BAHASA'){            
        $data['Create_Cartoon']="Lihat Kartun";
        $data['Create_Comics']="Lihat Komik";
        $data['Create_background']="Efek Latar Belakang";
        $data['backgroundTitle']='Pilih Latar Belakang';            
        }else{
            $data['Create_Cartoon']="Create Cartoon";
            $data['Create_Comics']="Create Comics";
            $data['Create_background']="Background Effect";
            $data['backgroundTitle']='Choose a Background';
        }
        $data['littledataurl']='http://littledata.in/mcom/';
        $data['tmp']=1;
        $this->load->view('newui/template/header_1',$data);
        $data['result'] = $this->comic_model->selCartoonAjaxAllRecord(0);
        $this->load->view('newui/phocartoon3',$data);
        $data['tabstatus'] = '1';
        $data['activeTab'] = $this->uri->segment(1);
        $this->load->view('newui/template/fixedFooterMenuTab', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        //redirect(base_url(), 'refresh');
        $mobnum = $this->session->userdata('mobnum');    
        /*if($mobnum == "919871666000"){
            redirect(base_url() . 'auth', 'refresh');
        }else{
            redirect(base_url(), 'refresh');
        }*/
        redirect(base_url(), 'refresh');
    }

    public function selGen() {
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['Gendertxt']='ඔබගේ ලිංගය තෝරා ගන්න';            
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['Gendertxt']='আপনার লিঙ্গ চয়ন করুন';            
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['Gendertxt']='Choisissez votre genre';            
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['Gendertxt']='يرجى اختيار جنسك';            
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['Gendertxt']='သင့်ရဲ့ကျားကိုရွေးချယ်ပါ';            
        }else{
            $data['Gendertxt']='Choose Your Gender';
        }

        if ($this->session->userdata('imageFemaleURL') != '') {
            $this->session->unset_userdata('imageFemaleURL');
        } else if ($this->session->userdata('imageMaleURL') != '') {
            $this->session->unset_userdata('imageMaleURL');
        }
        $data['forward_img'] = 'save.png';
        $data['forward'] = '';

        if(isset($_GET['tab'])){
            $tab = $_GET['tab'];
            $this->session->set_userdata('tab',$tab);
        }

        $backid     = $this->session->userdata('backid');
        $titleName  = $this->session->userdata('titleName');
        
        $data['backword'] = "createStory?id=$backid&titlename=$titleName";

       
        $data['tmp']=2;
        $this->load->view('newui/template/header_2',$data);
        $this->load->view('newui/selgender');
    }

    public function favatar() {
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['FaceShapeTxt']='මුහුණ පටි';    
            $data['facename']='name_sinhala';        
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['FaceShapeTxt']='মুখের আকৃতি';  
            $data['facename']='name_bangla';          
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['FaceShapeTxt']='Forme du visage'; 
            $data['facename']='name_french';           
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['FaceShapeTxt']='شكل الوجه'; 
            $data['facename']='name_arabic';           
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['FaceShapeTxt']='မျက်နှာ Shape'; 
            $data['facename']='name_arabic';           
        }else{
            $data['FaceShapeTxt']='Face Shape';
            $data['facename']='name';
        }

        $data['backword'] = 'selGen';
        $data['forward_img'] = 'save.png';
        $data['forward'] = 'fchooseOutfit';
        $data['type'] = '1';
        $this->load->view('newui/template/header_2', $data);
        $data['result'] = $this->comic_model->avatar_icons(1);
        $data['avatarIcons'] = $this->comic_model->requestAvatar(1, 1);
        $cropped = 'head';
        $data['img_src'] = 'https://render.bitstrips.com/render/6688424/159953823_183_s1-v1.png?';
        $data['img_src'] .= 'sex=2';
        $data['img_src'] .= '&body={%22body_type%22:9,%22breast_type%22:0}&cropped=%22' . $cropped . '%22&head_rotation=0&style=1';
        $data['img_src'] .= '&colours={%22ff9999%22:16764057,%22ffcc99%22:16764057,%22926715%22:2566954,%224f453e%22:1579802,%2236a7e9%22:5977116,%22b88eb6%22:12162955,%22ff9866%22:13442115}';
        $data['img_src'] .= '&pd2={%22eyelash_L%22:%22_blank%22,%22eyelash_R%22:%22_blank%22,%22earring_L%22:%22_blank%22,%22earring_R%22:%22_blank%22,%22detail_T%22:%22detail_T_n0%22,%22glasses%22:%22glasses_blank%22,%22hat%22:%22hat_blank%22,%22detail_E2_L%22:%22detail_E2_n1%22,%22detail_E2_R%22:%22detail_E2_n1%22,%22detail_L2_L%22:%22detail_L2_n1%22,%22detail_L2_R%22:%22detail_L2_n1%22,%22ear_L%22:%22ear_n1%22,%22ear_R%22:%22ear_n1%22,%22mouth%22:%22mouth_n9%22,%22tongue%22:%22tongue_n1_3%22,%22nose%22:%22nose_n11%22,%22pupil_L%22:%22pupil_n4%22,%22pupil_R%22:%22pupil_n4%22,%22eye_L%22:%22eye_n2%22,%22eye_R%22:%22eye_n2%22,%22eyelines_L%22:%22eye_n2%22,%22eyelines_R%22:%22eye_n2%22,%22brow_L%22:%22brow_n3%22,%22brow_R%22:%22brow_n3%22,%22jaw%22:%22jaw_n20%22,%22cranium%22:%22cranium_midpart01%22,%22forehead%22:%22forehead_standard%22,%22hair_back%22:%22hair_back_midpart01%22,%22hair_front%22:%22hair_front_midpart01%22,%22hairbottom%22:%22hairbottom_blank%22,%22detail_L%22:%22detail_L_n4%22,%22detail_R%22:%22detail_L_n4%22,%22detail_E_L%22:%22detail_E_n0%22,%22detail_E_R%22:%22detail_E_n0%22}';
        $data['img_src'] .= '&proportion=1';
        if ($this->session->userdata('imageFemaleURL') != '') {
            $data['img_src'] = $this->session->userdata('imageFemaleURL');
        } else {
            $this->session->set_userdata('imageFemaleURL', $data['img_src']);
        }

        $this->load->view('newui/avatarFemale', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function mavatar() {
        $this->userLoged();
        $data['backword'] = 'selGen';
        $data['forward_img'] = 'save.png';
        $data['forward'] = 'mchooseOutfit';
        $data['type'] = '2';
        $this->load->view('newui/template/header_2', $data);
        $data['result'] = $this->comic_model->avatar_icons(2);
        $data['avatarIcons'] = $this->comic_model->requestAvatar(1, 2);
        $cropped = 'head';

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['FaceShapeTxt']='මුහුණ පටි';    
            $data['facename']='name_sinhala';        
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['FaceShapeTxt']='মুখের আকৃতি';  
            $data['facename']='name_bangla';          
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['FaceShapeTxt']='Forme du visage'; 
            $data['facename']='name_french';           
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['FaceShapeTxt']='شكل الوجه'; 
            $data['facename']='name_arabic';           
        }else{
            $data['FaceShapeTxt']='Face Shape';
            $data['facename']='name';
        }
        

        $data['img_src'] = 'https://render.bitstrips.com/render/6688424/134174650_1_s1-v1.png?';
        $data['img_src'] .= 'body={%22body_type%22:0}&cropped=%22' . $cropped . '%22&head_rotation=0&sex=1&style=1';
        $data['img_src'] .= '&colours={%22ffcc99%22:16764057,%22926715%22:2566954,%224f453e%22:1579802,%2236a7e9%22:5977116,%226f4b4b%22:1579802}';
        $data['img_src'] .= '&pd2={%22mouth%22:%22mouth_n1%22,%22tongue%22:%22tongue_n1_3%22,%22eye_L%22:%22eye_n1%22,%22eye_R%22:%22eye_n1%22,%22eyelines_L%22:%22eye_n1%22,%22eyelines_R%22:%22eye_n1%22,%22cranium%22:%22cranium_tom%22,%22forehead%22:%22forehead_standard%22,%22hair_back%22:%22hair_back_tom%22,%22hair_front%22:%22hair_front_tom%22,%22hairbottom%22:%22hairbottom_blank%22,%22jaw%22:%22jaw_n3%22,%22beard%22:%22_blank%22,%22stachin%22:%22_blank%22,%22stachout%22:%22_blank%22,%22brow_L%22:%22brow_n19%22,%22brow_R%22:%22brow_n19%22,%22pupil_L%22:%22pupil_n8%22,%22pupil_R%22:%22pupil_n8%22,%22nose%22:%22nose_n6%22,%22ear_L%22:%22ear_n1%22,%22ear_R%22:%22ear_n1%22,%22detail_E_L%22:%22detail_E_n0%22,%22detail_E_R%22:%22detail_E_n0%22,%22detail_L%22:%22detail_L_n4%22,%22detail_R%22:%22detail_L_n4%22,%22detail_T%22:%22detail_T_n0%22,%22glasses%22:%22_blank%22,%22hat%22:%22_blank%22,%22beard%22:%22_blank%22,%22stachin%22:%22_blank%22,%22stachout%22:%22_blank%22}';
        $data['img_src'] .= '&proportion=1';

        if ($this->session->userdata('imageMaleURL') != '') {
            $data['img_src'] = $this->session->userdata('imageMaleURL');
        } else {
            $this->session->set_userdata('imageMaleURL', $data['img_src']);
        }

        $this->load->view('newui/avatarMale', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function yourFemaleAvatar() {
        $this->userLoged();
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $data['type'] = $type;
        $data['avatarIcons'] = $this->comic_model->requestAvatar($id, $type);
        $this->load->view('newui/avatar/avatarFemaleIcons', $data);
    }

    public function yourMaleAvatar() {
        $this->userLoged();
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $data['type'] = $type;
        $data['avatarIcons'] = $this->comic_model->requestAvatar($id, $type);
        $this->load->view('newui/avatar/avatarMaleIcons', $data);
    }


    public function fchooseOutfit() {
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['ChooseTxt']='ඔබගේ ඇඳුම තෝරන්න';            
            $userlanguage = 'SINHALESE';
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['ChooseTxt']='আপনার সাজসরঞ্জাম চয়ন করুন';            
            $userlanguage = 'BANGLA';
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['ChooseTxt']='Choisissez votre tenue';            
            $userlanguage = 'FRENCH';
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['ChooseTxt']='يرجى اختيار الزي الخاص بك';      
            $userlanguage = 'ARABIC';      
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['ChooseTxt']='သင့်ရဲ့မတ်မတ် Choose';      
            $userlanguage = 'ARABIC';      
        }else{
            $data['ChooseTxt']='Choose Your Outfit';
            $userlanguage = 'English';
        }

        $data['backword'] = 'favatar';
        $data['forward_img'] = 'save.png';
         $data['littledataurl']='http://littledata.in/mcom/';
        // $data['forward'] = 'saveFemale';
        $data['forward'] = 'javascript:void(0);';
        /*$data['onclickEvent'] = 'onclick="saveAvatar('."'saveFemale'".','."'fconfirmOutfit'".')"';*/
        $data['onclickEvent'] = 'onclick="saveAvatar('."'saveFemale'".','."'fconfirmOutfit'".','."'$userlanguage'".')"';
        if ($this->session->userdata('imageFemaleURL') != '') {
            $data['img_src'] = $this->setDefaultBody('female');
        }
        $data['outfit'] = $this->comic_model->outfit(1);
        $this->load->view('newui/template/header_3', $data);
        $this->load->view('newui/chooseFemaleOutfit', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function mchooseOutfit() {
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['ChooseTxt']='ඔබගේ ඇඳුම තෝරන්න';   
            $userlanguage = 'SINHALESE';         
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['ChooseTxt']='আপনার সাজসরঞ্জাম চয়ন করুন';    
            $userlanguage = 'BANGLA';        
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['ChooseTxt']='Choisissez votre tenue';    
            $userlanguage = 'FRENCH';        
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['ChooseTxt']='يرجى اختيار الزي الخاص بك';     
            $userlanguage = 'ARABIC';       
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['ChooseTxt']='သင့်ရဲ့မတ်မတ် Choose';     
            $userlanguage = 'ARABIC';       
        }else{
            $data['ChooseTxt']='Choose Your Outfit';
            $userlanguage = 'English';
        }
        

        $data['backword'] = 'mavatar';
        $data['forward_img'] = 'save.png';
        //$data['forward'] = 'saveMale';
        $data['forward'] = 'javascript:void(0);';
         $data['littledataurl']='http://littledata.in/mcom/';
        $data['onclickEvent'] = 'onclick="saveAvatar('."'saveMale'".','."'mconfirmOutfit'".','."'$userlanguage'".')"';
        if ($this->session->userdata('imageMaleURL') != '') {
            $data['img_src'] = $this->setDefaultBody('male');
        }
        $data['outfit'] = $this->comic_model->outfit(2);
        $this->load->view('newui/template/header_3', $data);
        $this->load->view('newui/chooseMaleOutfit', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function saveFemale() {
        $this->userLoged();
        error_reporting(0);
        
        $this->createMiniFemaleClips();
        
        $mobnum = $this->session->userdata('mobnum');
        $imageURL = $this->setDefaultBody('female');
        $img_src = urldecode($imageURL);


        //$url = str_replace('http', 'https', $img_src);
        $url = $img_src;
        $filename = rand(1234, 99938) . '_' . time() . '.png';
        $img = 'output/' . $filename;

        file_put_contents($img, file_get_contents($url));

        $last_id = $this->comic_model->saved_avatars($mobnum, $filename);

        $avatar_id = 159953823;
        $json = file_get_contents(base_url() . 'templates.json', true);
        $json_a = json_decode($json, true);

        $json_imoji = $json_a['imoji'];
        $i = 0;
        $num = rand(111, 999);

        $filenameArr = array();
        foreach ($json_imoji as $imoji) {
            if (stripos($imoji['tags'][count($imoji['tags']) - 1], "bitmoji") !== false)
                continue;

            $url = str_replace("%s", $avatar_id . "_$num-s1", $imoji['src']);
            $filename = rand(1234, 99938) . '_' . time() . '.png';
            $img = 'mShare/' . $filename;
            file_put_contents($img, file_get_contents($url));
            $this->comic_model->savedShared($filename, $last_id);
            if ($i > 16)
                break;
            $i++;
        }
        if($last_id!=''){
            echo '{"message":"true","data_id":"'.$last_id.'"}';
        }else{
            echo '{"message":"false","data_id":"'.$last_id.'"}';
        }
        //redirect(base_url() . 'fconfirmOutfit?id=' . $last_id, 'refresh');
    }

    public function saveMale() {
        $this->userLoged();
        error_reporting(0);
        
        $this->createMiniMaleClips();
        
        $mobnum = $this->session->userdata('mobnum');
        $imageURL = $this->setDefaultBody('male');
        $img_src = urldecode($imageURL);

        //$url = str_replace('http', 'https', $img_src);
        $url = $img_src;

        $filename = rand(1234, 99938) . '_' . time() . '.png';
        $img = 'output/' . $filename;
        file_put_contents($img, file_get_contents($url));
        $last_id = $this->comic_model->saved_avatars($mobnum, $filename);

        $avatar_id = 134174650;
        $json = file_get_contents(base_url() . 'templates.json', true);
        $json_a = json_decode($json, true);

        $json_imoji = $json_a['imoji'];
        $i = 0;
        $num = rand(111, 999);

        $filenameArr = array();
        foreach ($json_imoji as $imoji) {
            if (stripos($imoji['tags'][count($imoji['tags']) - 1], "bitmoji") !== false)
                continue;

            $url = str_replace("%s", $avatar_id . "_$num-s1", $imoji['src']);
            $filename = rand(1234, 99938) . '_' . time() . '.png';
            $img = 'mShare/' . $filename;
            file_put_contents($img, file_get_contents($url));
            $this->comic_model->savedShared($filename, $last_id);
            
            if ($i > 16)
                break;
            $i++;
        }
        if($last_id!=''){
            echo '{"message":"true","data_id":"'.$last_id.'"}';
        }else{
            echo '{"message":"false","data_id":"'.$last_id.'"}';
        }
        //redirect(base_url() . 'mconfirmOutfit?id=' . $last_id, 'refresh');
    }
    
    function createMiniMaleClips() {
        $this->userLoged();
        $url = urldecode($this->session->userdata('imageMaleURL'));
        $outfit_id = $this->session->userdata('outfitId');
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);

        $data = '{"colours":'.$query['colours'].',';
        $data .= '"pd2":'.$query['pd2'].',';
        $data .= '"body":'.$query['body'].',';
        $data .= '"cropped":'.$query['cropped'].',';
        $data .= '"head_rotation":'.$query['head_rotation'].',';
        $data .= '"style":'.$query['style'].'}';
        $dataString = 'avatar_id=134174650&char_data='.urlencode($data);
        //echo $dataString ;
       
   
        //  $dataString ='avatar_id=134174650&char_data=%7B%22colours%22%3A%7B%7D%2C%22pd2%22%3A%7B%7D%2C%22body%22%3A%7B%7D%2C%22cropped%22%3A%22body%22%2C%22head_rotation%22%3A0%2C%22body_rotation%22%3A0%2C%22outfit%22%3A'.$outfit_id.'%2C%22style%22%3A1%7D';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.bitmoji.com/user/avatar?styles=1,4&app_id=13");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: api.bitmoji.com", "Content-Type: application/x-www-form-urlencoded", "User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.4; MI 3W MIUI/V7.1.1.0.KXDMICK)", "X-GRAPH-API-VERSION: v2.3", "bitmoji-token: b5aeb71a9fa5b76aa7d27bef8875f005a7c1bb6e", "Origin:https://www.bitmoji.com"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);
        
        
        $dataString ='avatar_id=134174650&char_data=%7B%22colours%22%3A%7B%7D%2C%22pd2%22%3A%7B%7D%2C%22body%22%3A%7B%7D%2C%22cropped%22%3A%22body%22%2C%22head_rotation%22%3A0%2C%22body_rotation%22%3A0%2C%22outfit%22%3A'.$outfit_id.'%2C%22style%22%3A1%7D';
        //echo $dataString; die();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.bitmoji.com/user/avatar?styles=1,4&app_id=13");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: api.bitmoji.com", "Content-Type: application/x-www-form-urlencoded", "User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.4; MI 3W MIUI/V7.1.1.0.KXDMICK)", "X-GRAPH-API-VERSION: v2.3", "bitmoji-token: b5aeb71a9fa5b76aa7d27bef8875f005a7c1bb6e", "Origin:https://www.bitmoji.com"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);
        //echo $out; die();
    }
    
    
    function createMiniFemaleClips() {
        $this->userLoged();
        $url = urldecode($this->session->userdata('imageFemaleURL'));
        $outfit_id = $this->session->userdata('outfitId');
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);

        $data = '{"colours":'.$query['colours'].',';
        $data .= '"pd2":'.$query['pd2'].',';
        $data .= '"body":'.$query['body'].',';
        $data .= '"cropped":'.$query['cropped'].',';
        $data .= '"head_rotation":'.$query['head_rotation'].',';
        $data .= '"style":'.$query['style'].'}';
        $dataString = 'avatar_id=159953823&char_data='.urlencode($data);
       
    
        //  $dataString ='avatar_id=159953823&char_data=%7B%22colours%22%3A%7B%7D%2C%22pd2%22%3A%7B%7D%2C%22body%22%3A%7B%7D%2C%22cropped%22%3A%22body%22%2C%22head_rotation%22%3A0%2C%22body_rotation%22%3A0%2C%22outfit%22%3A'.$outfit_id.'%2C%22style%22%3A1%7D';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.bitmoji.com/user/avatar?styles=1,4&app_id=13");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: api.bitmoji.com", "Content-Type: application/x-www-form-urlencoded", "User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.4; MI 3W MIUI/V7.1.1.0.KXDMICK)", "X-GRAPH-API-VERSION: v2.3", "bitmoji-token: 36e1052c29fe3063c76e82c6c783e477ab71a189", "Origin:https://www.bitmoji.com"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);
        
        
        $dataString ='avatar_id=159953823&char_data=%7B%22colours%22%3A%7B%7D%2C%22pd2%22%3A%7B%7D%2C%22body%22%3A%7B%7D%2C%22cropped%22%3A%22body%22%2C%22head_rotation%22%3A0%2C%22body_rotation%22%3A0%2C%22outfit%22%3A'.$outfit_id.'%2C%22style%22%3A1%7D';
        //echo $dataString; die();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.bitmoji.com/user/avatar?styles=1,4&app_id=13");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: api.bitmoji.com", "Content-Type: application/x-www-form-urlencoded", "User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.4; MI 3W MIUI/V7.1.1.0.KXDMICK)", "X-GRAPH-API-VERSION: v2.3", "bitmoji-token: 36e1052c29fe3063c76e82c6c783e477ab71a189", "Origin:https://www.bitmoji.com"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);
        //echo $out; die();
    }
    
    

    public function fconfirmOutfit() {
        $this->userLoged();
        $id = $_GET['id'];
        $data['titlename'] = $this->session->userdata('titleName'); 
        $data['img_src'] = $this->comic_model->sel_saved_avatars($id);
        $data['share'] = $this->comic_model->getshare($id);
        $data['backword'] = 'fchooseOutfit';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['id'] = $id;

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['CreateComicTxt']='කොමික් නිර්මාණය කරන්න';    
            $data['ShareAvatarTxt']='ඔබේ කථාව බෙදාගන්න';        
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['CreateComicTxt']='কমিক তৈরি করুন';         
            $data['ShareAvatarTxt']='আপনার অবতার ভাগ করুন';   
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['CreateComicTxt']='Créer une bande dessinée';         
            $data['ShareAvatarTxt']='Partagez votre avatar';   
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['CreateComicTxt']='إنتاج الكاريكاتير';   
            $data['ShareAvatarTxt']='مشاركة الصورة الرمزية الخاصة بك';         
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['CreateComicTxt']='ရုပ်ပြ Create';   
            $data['ShareAvatarTxt']='သင့်ရဲ့ Avatar Share';         
        }else{
            $data['CreateComicTxt']='Create Comic';
            $data['ShareAvatarTxt']='Share Your Avatar';
        }

        $this->load->view('template/header_2', $data);
        $this->load->view('confirmOutfitF', $data);
        $this->load->view('template/footer_2');
    }

    public function mconfirmOutfit() {
        $this->userLoged();
        //error_reporting(0);
        $id = $_GET['id'];
        $data['titlename'] = $this->session->userdata('titleName');
        $data['img_src'] = $this->comic_model->sel_saved_avatars($id);
        $data['share'] = $this->comic_model->getshare($id);
        $data['backword'] = 'mchooseOutfit';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['id'] = $id;

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['CreateComicTxt']='කොමික් නිර්මාණය කරන්න';    
            $data['ShareAvatarTxt']='ඔබේ කථාව බෙදාගන්න';        
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['CreateComicTxt']='কমিক তৈরি করুন';         
            $data['ShareAvatarTxt']='আপনার অবতার ভাগ করুন';   
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['CreateComicTxt']='Créer une bande dessinée';         
            $data['ShareAvatarTxt']='Partagez votre avatar';   
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['CreateComicTxt']='إنتاج الكاريكاتير';   
            $data['ShareAvatarTxt']='مشاركة الصورة الرمزية الخاصة بك';         
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['CreateComicTxt']='ရုပ်ပြ Create';   
            $data['ShareAvatarTxt']='သင့်ရဲ့ Avatar Share';          
        }else{
            $data['CreateComicTxt']='Create Comic';
            $data['ShareAvatarTxt']='Share Your Avatar';
        }


        $this->load->view('newui/template/header_2', $data);
        $this->load->view('newui/confirmOutfitM', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function giveTitle() {
        $this->userLoged();

        $this->session->unset_userdata('tab');
         $data['littledataurl']='http://littledata.in/mcom/';
        ///added code by dushyant 11sep2017
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['titleName']='මාතෘකාවක් දෙන්න';
            $data['characterRemain']='චරිත ඉතිරිව ඇත';
            $data['placeholder']='විහිලු මාතෘකාවක් ඇතුළත් කරන්න';
            $data['userlanguage']= 'SINHALESE';
        }else  if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['titleName']='একটি শিরোনাম দিন';
            $data['characterRemain']='অক্ষর বাকি';
            $data['placeholder']='কমিক শিরোনাম লিখুন';
            $data['userlanguage']= 'BANGLA';
        }else  if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['titleName']='Donnez un titre';
            $data['characterRemain']='les caractères restants';
            $data['placeholder']='Entrez le titre de la bande dessinée';
            $data['userlanguage']= 'FRENCH';
        }else  if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['titleName']='أعط العنوان';
            $data['characterRemain']='الأحرف المتبقية';
            $data['placeholder']='يرجى إدخال عنوان الكاريكاتير';
            $data['userlanguage']= 'ARABIC';
        }else  if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['titleName']='ခေါင်းစဉ်တစ်ခုပေး';
            $data['characterRemain']='ကျန်ရှိသောဇာတ်ကောင်';
            $data['placeholder']='ရုပ်ပြခေါင်းစဉ်ကိုရိုက်ထည့်';
            $data['userlanguage']= 'BURMESE';
        }else  if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['titleName']='Beri judul';
            $data['characterRemain']='karakter tersisa';
            $data['placeholder']='Masukkan judul komik';
            $data['userlanguage']= 'BAHASA';
        }else{
            $data['titleName']='Give a title';
            $data['characterRemain']='characters remaining';
            $data['placeholder']='Enter comic title';
            $data['userlanguage']= 'ENGLISH';
        }

        //end

        $data['id'] = $_GET['id'];
        $data['backword'] = 'index';
        $data['forward_img'] = 'save.png';
        $data['forward'] = 'javascript:void(0);';
        $data['onclickEvent'] = 'onclick="validateComicTitle(\''.$data['placeholder'].'\')" ';
        $this->load->view('newui/template/header_3', $data);
        $this->load->view('newui/giveTitle');
        $this->load->view('newui/template/footer_2');
    }

    public function cc() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        $filename = 'default.png';
        $last_id = $this->comic_model->saved_avatars($mobnum, $filename);
        redirect(base_url() . 'giveTitle?id=' . $last_id, 'refresh');
    }

    public function givenTitleName() {
        $this->userLoged();
        $titleName = $this->input->post('titlename');
        $id = $this->input->post('id');
        $this->comic_model->update_saved_avatars($titleName, $id);
    }

    public function createStory() {
        $this->userLoged();
        $id = $_GET['id'];
        $titleName = $_GET['titlename'];

        $tab = $this->session->userdata('tab');
        if(isset($tab)){
            $data['tab'] = $tab;
        }else{
            $data['tab'] = 1;
        }
        $data['littledataurl']='https://littledata.in/mcom/';
        $mobnum = $this->session->userdata('mobnum');
        $this->session->set_userdata('titleName', $titleName);
        $this->session->set_userdata('backid', $id);

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['saveconfirm'] = 'ඔබට තිරය සුරැකීමට අවශ්යද?';  
            $data['continue'] ='ඉදිරියට යන්න';
            $data['cancel'] = 'අවලංගු කරන්න';  
            $data['starcontent'] = "<i>* ශෝචන අන්තර්ගතය වෙනස් කිරීම සඳහා පහතින් යන්න</i>";   
            $data['rightscreentext'] = 'ඔවුන් කියන්නේ මෙයයි.';
            $data['leftscreentext'] = 'ඒයි මේ ! මෙන්න ඔබේ බුබුල සඳහා තවත් පෙළක්.';
            $data['narrationscreentext'] = 'ඔබගේ විස්තරය මෙතැනින් එයි!';    
            $data['backgroundFooter']='පසුබිම';
            $data['userlanguage']= 'SINHALESE';  
            $data['Screentxt']= 'තිරය';    
            $data['Screenaddedtxt']= 'තිරය ​​එකතු කරන්න';  
            $data['Screenrmxt']= 'තිරය ​​ඉවත් කරන ලදි'; 
            $data['Screencntremovetxt']= "තිරය ​​ඉවත් කළ නොහැක";
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['saveconfirm'] = 'আপনি কি সব পর্দা সংরক্ষণ করতে চান?';  
            $data['continue'] ='চালিয়ে';
            $data['cancel'] = 'বাতিল';  
            $data['starcontent'] = "<i>*কমিক এর বিষয়বস্তু পরিবর্তন করতে নীচের যান</i>"; 
            $data['rightscreentext'] = 'এই তারা কি বলছে তা।';
            $data['leftscreentext'] = 'সেখানে হ্যালো! এখানে আপনার বুদ্বুদ জন্য আরো কিছু টেক্সট।';
            $data['narrationscreentext'] = 'আপনার বিবরণ এখানে যায়!';            
            $data['backgroundFooter']='পটভূমি';
            $data['userlanguage']= 'BANGLA';
            $data['Screentxt']= 'স্ক্রিন';  
            $data['Screenaddedtxt']= 'স্ক্রিন যোগ করা হয়েছে';    
            $data['Screenrmxt']= 'স্ক্রীন সরানো হয়েছে'; 
            $data['Screencntremovetxt']= "স্ক্রীনটি সরানো যাবে না";
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['saveconfirm'] = 'Voulez-vous enregistrer tout l\'écran?'; 
            $data['continue'] ='Continuer';
            $data['cancel'] = 'Annuler';    
            $data['starcontent'] = "<i>* Pour changer le contenu de la BD, allez ci-dessous</i>";
            $data['rightscreentext'] = "C'est ce qu'ils disent.";
            $data['leftscreentext'] = 'Bonjour ! Voici un peu plus de texte pour votre bulle.';
            $data['narrationscreentext'] = 'Votre Narration va ici!';
            $data['backgroundFooter']='Contexte';
            $data['userlanguage']= 'FRENCH';
            $data['Screentxt']= 'Écran';    
            $data['Screenaddedtxt']= 'Écran ajouté';  
            $data['Screenrmxt']= 'Écran supprimé'; 
            $data['Screencntremovetxt']= "L'écran ne peut pas être supprimé";
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['saveconfirm'] = 'هل تريد حفظ كل الشاشة?'; 
            $data['continue'] ='استمر';
            $data['cancel'] = 'إلغاء';    
            $data['starcontent'] = "<i>* لتغيير محتوى كوميدي إذهب إلى أدناه</i>";
            $data['rightscreentext'] = "هذا ما يقولون.";
            $data['leftscreentext'] = 'مرحبا ! إليك بعض المزيد من النص للفقاعة الخاصة بك.';
            $data['narrationscreentext'] = 'حكايتك يجري هنا!';
            $data['backgroundFooter']='خلفية';
            $data['userlanguage']= 'ARABIC';
            $data['Screentxt']= 'شاشة';   
            $data['Screenaddedtxt']= 'تمت إضافة الشاشة';  
            $data['Screenrmxt']= 'تم حذف الشاشة'; 
            $data['Screencntremovetxt']= "لا يمكن حذف الشاشة";
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['saveconfirm'] = 'သင်တို့ရှိသမျှသည်မျက်နှာပြင်ကိုကယ်တင်ချင်ပါသလား?'; 
            $data['continue'] ='ဆက်လက်';
            $data['cancel'] = 'ဖျက်သိမ်း';    
            $data['starcontent'] = "<i>*အောက်တွင်ဖော်ပြထားသောကာတွန်းသွားလာရင်းများ၏ content ကိုပြောင်းလဲရန်</i>";
            $data['rightscreentext'] = "ဤသည်ကိုသူတို့ပြောနေကြသည်အရာဖြစ်တယ်။";
            $data['leftscreentext'] = "ဟယ်လို ! ဤတွင်သင့်ရဲ့ပူဖောင်းတချို့ပိုပြီးစာသားကိုပါပဲ။";
            $data['narrationscreentext'] = 'သင့်ရဲ့ဇာတ်ကြောင်းပြောကဒီမှာသွား!';
            $data['backgroundFooter']='နောက်ခံသမိုင်း';
            $data['userlanguage']= 'BURMESE';
            $data['Screentxt']= 'ဖန်သားပြင်';   
            $data['Screenaddedtxt']= 'မျက်နှာပြင် Added';  
            $data['Screenrmxt']= 'မျက်နှာပြင် Deleted'; 
            $data['Screencntremovetxt']= "မျက်နှာပြင်မဖျက်ပစ်နိုင်";
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['saveconfirm'] = 'Apakah Anda ingin menyimpan semua layar?'; 
            $data['continue'] ='Terus';
            $data['cancel'] = 'membatalkan';    
            $data['starcontent'] = "<i>* Untuk mengubah konten komik, lihat di bawah</i>";
            $data['rightscreentext'] = "Ini yang mereka katakan.";
            $data['leftscreentext'] = "Halo yang disana ! Berikut ini beberapa teks lagi untuk balon Anda.";
            $data['narrationscreentext'] = 'Narasi Anda ada di sini!';
            $data['backgroundFooter']='Latar Belakang';
            $data['userlanguage']= 'BAHASA';
            $data['Screentxt']= 'Layar';   
            $data['Screenaddedtxt']= 'Layar Ditambahkan';  
            $data['Screenrmxt']= 'Layar Dihapus'; 
            $data['Screencntremovetxt']= "Layar tidak dapat dihapus";
        }else{
            $data['saveconfirm'] = 'Do you want to save all the screen? ';  
            $data['continue'] ='Continue';
            $data['cancel'] = 'cancel';     
            $data['starcontent'] = "<i>*To change the content of comic go below</i>"; 
            $data['rightscreentext'] = 'This is what they are saying.';
            $data['leftscreentext'] = "Hello there ! Here\'s some more text for your bubble.";
            $data['narrationscreentext'] = 'Your Narration goes here !';
            $data['backgroundFooter']='Background';
            $data['userlanguage']= 'ENGLISH';
            $data['Screentxt']= 'Screen';  
            $data['Screenaddedtxt']= 'Screen Added';  
            $data['Screenrmxt']= 'Screen Deleted'; 
            $data['Screencntremovetxt']= "The screen can not be deleted";
             
        }


        $data['backword'] = 'giveTitle?id=' . $id;
        $data['myCreatedCharacter'] = $this->comic_model->myCharacter($id);
        $data['myChar'] = $id;
        $data['titleName'] = $titleName;
        $data['forward_img'] = 'save.png';
        $data['forward'] = 'javascript:void(0);';
        $data['onclickEvent'] = 'onclick="savedComic()"';
        $this->load->view('newui/template/header_3', $data);
        $data['result'] = $this->comic_model->createStoryAllIcons();

        if($tab==3){
            $data['tab']   = 3;
            $data['backid'] = $this->session->userdata('backid');
            $data['type'] = 'left';
            $data['myAvatar'] = $this->comic_model->getMyAvatar($mobnum);
            $data['screenName'] = 'screen_1';
            $data['result2'] = $this->comic_model->storyImages(3);
        }else if ($tab==6){
            $data['tab']   = 6;
            $data['backid'] = $this->session->userdata('backid');
            $data['type'] = 'right';
            $data['myAvatar'] = $this->comic_model->getMyAvatar($mobnum);
            $data['screenName'] = 'screen_1';
            $data['result2'] = $this->comic_model->storyImages(6);
        }else{
            $data['storyImages'] = $this->comic_model->storyImages(1);
        }
        
        $this->load->view('newui/createStory', $data);
        $this->load->view('newui/template/footer_2');
    }

    public function savedMyScreens() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        $content = json_decode($this->input->get_post('content'), true);
        $countArr = sizeof($content);
        $title = $content[0]['title'];
        $insert_id = $this->comic_model->savedSrc($title);

        for ($i = 0; $i < $countArr; $i++) {
            $lastId = $content[$i]['sId'];
            $narra = $content[$i]['narra'];
            $bg = $content[$i]['bckg'];
            $lc = $content[$i]['lCom'];
            $rc = $content[$i]['rCom'];
            $li = $content[$i]['lImg'];
            $ri = $content[$i]['rImg'];
            $lB = $content[$i]['lB'];
            $rB = $content[$i]['rB'];
            $this->comic_model->saveComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $insert_id, $lB, $rB);
        }
        echo '{"last_id":"' . $insert_id . '"}';
        //echo json_encode($title);
        //echo $myHTML = urldecode($this->input->get('myHTML'));        
    }

    public function storyCharacter() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        $data['backid'] = $this->session->userdata('backid');
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['userlanguage']= 'SINHALESE';
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['userlanguage']= 'BANGLA';
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['userlanguage']= 'FRENCH';
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['userlanguage']= 'ARABIC';
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['userlanguage']= 'BURMESE';
        }else{
            $data['userlanguage']= 'ENGLISH';
        }
        $data['littledataurl']='http://littledata.in/mcom/';
        $charId = $this->input->get_post('id');
        $screenName = $this->input->get_post('screenName');
        if ($charId == 2) {
            $data['screenName'] = $screenName;
            $this->load->view('newui/storyCharacter/aNarration', $data);
        } else if ($charId == 5) {
            $data['screenName'] = $screenName;
            $this->load->view('newui/storyCharacter/rightDialogue', $data);
        } else if ($charId == 8) {
            $data['screenName'] = $screenName;
            $this->load->view('newui/storyCharacter/leftDialogue', $data);
        } else if ($charId == 3) {
            $data['type'] = 'left';
            $data['tab']   = 3;
            $data['myAvatar'] = $this->comic_model->getMyAvatar($mobnum);
            $data['screenName'] = $screenName;
            $data['result'] = $this->comic_model->storyImages($charId);
            $this->load->view('newui/storyCharacter/dispCharacter', $data);
        } else if ($charId == 6) {
            $data['type'] = 'right';
             $data['tab']   = 6;
            $data['myAvatar'] = $this->comic_model->getMyAvatar($mobnum);
            $data['screenName'] = $screenName;
            $data['result'] = $this->comic_model->storyImages($charId);
            $this->load->view('newui/storyCharacter/dispCharacter', $data);
        } else {
            $data['type'] = '';
             $data['tab']   = 1;
            $data['myAvatar'] = $this->comic_model->getMyAvatar($mobnum);
            $data['screenName'] = $screenName;
            $data['result'] = $this->comic_model->storyImages($charId);
            $this->load->view('newui/storyCharacter/dispCharacter', $data);
        }
    }

    public function getstoryCharacter() {
        $this->userLoged();
        $charId = $this->input->post('id');
        $result = $this->comic_model->getstoriesCharacter($charId);
          $data['littledataurl']='http://littledata.in/mcom/';
        echo base_url() . 'images/comics/bg/' . $result['path'];
    }

    public function sharemComic() {
        /*if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on'){
            $link  = "http://";
            $link .= $_SERVER['HTTP_HOST'];
            $link .= $_SERVER['REQUEST_URI'];
            redirect($link);
        }*/
        $this->userLoged();
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->session->unset_userdata('tab');
        $data['tmp'] = 2;
        $id = $_GET['id'];
        $titlename = $_GET['titlename'];
        $sid = $_GET['sid'];
        //id=2&titlename=testing&sid=5
        $data['titleName'] = $this->comic_model->comicTitleName($sid);
        $data['result'] = $this->comic_model->comicDetails($sid);
        $data['id'] = $id;
        $data['titlename'] = $titlename;
        $data['sid'] = $sid;
        $data['backword'] = 'createStory?id=' . $id . '&titlename=' . $titlename;
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['sharevia']= 'හරහා බෙදා ගන්න';
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['sharevia']= 'মাধ্যমে ভাগ করুন';
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['sharevia']= 'Partage via';
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
           $data['sharevia']= 'شارك عبر';
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['sharevia']= 'မှတဆင့်မျှဝေပါ';
        }else{
            $data['sharevia']= 'Share via';
        }

        //$this->load->view('template/header_4', $data);
        $this->load->view('newui/template/header_1', $data);
        $this->load->view('newui/sharemComic');
        $this->load->view('newui/template/footer_2');
    }

    public function viewComics() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        //echo $this->session->userdata('userlanguage'); die();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['view_Cartoon']="කාටූන් බලන්න";
            $data['view_Comics']="කොමික්ස් බලන්න";
            $data['view_background']="පසුබිම් බලපෑම";
			$data['pconfirm'] = 'කරුණාකර තහවුරු කරන්න';  
			$data['deletesure']   = ' ඔබට මැකීමට අවශ්ය බව ඔබට විශ්වාසද?';  
			$data['deleteyes']  = 'ඔව්';
			$data['deleteno']   = 'නැත'; 
			$data['lanmessage'] = 'පණිවුඩය';  
			$data['mcomis']     = "මගේ කොමිස්";   
			$data['userlanguage']= 'SINHALESE';  
			$data['yettocreate']="එහෙත් ඔබ කිසිදු කොමික් නිර්මාණය කර නැත.";
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['view_Cartoon']="কার্টুন দেখুন";
            $data['view_Comics']="কমিক্স দেখুন";
            $data['view_background']="পটভূমি প্রভাব";
			$data['pconfirm'] = 'দয়া করে নিশ্চিত করুন';         
			$data['deletesure']   = ' আপনি মুছে ফেলতে চান?';  
			$data['deleteyes'] = 'হাঁ';
			$data['deleteno']  = 'না';   
			$data['lanmessage'] = 'বার্তা';
			$data['mcomis']     = "আমার কমিক্স";
			$data['userlanguage']= 'BANGLA';
			$data['yettocreate']="তবুও আপনি কোনও কমিকস তৈরি করেন নি।";
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['view_Cartoon']="Voir le dessin animé";
            $data['view_Comics']="Voir les bandes dessinées";
            $data['view_background']="Effet de fond";
			$data['pconfirm'] = 'Veuillez confirmer'; 
			$data['deletesure']   = ' Etes-vous sûr que vous voulez supprimer?';  
			$data['deleteyes']  = 'Oui';
			$data['deleteno']   = 'Non';
			$data['lanmessage'] = 'Message';
			$data['mcomis']     = "Mes bandes dessinées";
			$data['userlanguage']= 'FRENCH';
			$data['yettocreate']="Pourtant, vous n'avez créé aucune bande dessinée.";
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['view_Cartoon']="عرض الكرتون";
            $data['view_Comics']="عرض الكاريكاتير";
            $data['view_background']="تأثير الخلفية";
            $data['pconfirm'] = 'يرجى تأكيد'; 
            $data['deletesure']   = ' هل أنت متأكد أنك تريد حذف؟';  
            $data['deleteyes']  = 'نعم';
            $data['deleteno']   = 'لا';
            $data['lanmessage'] = 'رسالة';
            $data['mcomis']     = "الكاريكاتير الخاص بي";
            $data['userlanguage']= 'ARABIC';
            $data['yettocreate']="حتى الآن لم تقم بإنشاء أي كاريكاتير.";
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['view_Cartoon']="ကြည့်ရန်ကာတွန်း";
            $data['view_Comics']="ကြည့်ရန်ရုပ်ပြ";
            $data['view_background']="နောက်ခံ Effect";
            $data['pconfirm'] = 'အတည်ပြုပါ ကျေးဇူးပြု.'; 
            $data['deletesure']   = ' သငျသညျကိုဖျက်ပစ်ချင်သင်သေချာပါသလား?';  
            $data['deleteyes']  = 'ဟုတ်ကဲ့';
            $data['deleteno']   = 'အဘယ်သူမျှမ';
            $data['lanmessage'] = 'မက်ဆေ့ခ်ျကို';
            $data['mcomis']     = "အကြှနျုပျ၏ရုပ်ပြ";
            $data['userlanguage']= 'BURMESE';
            $data['yettocreate']="သို့တိုင်သင်သည်မည်သည့်ရုပ်ပြဖန်တီးကြပြီမဟုတ်။";
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){ //Bahasa
            $data['view_Cartoon']="Lihat Kartun";
            $data['view_Comics']="Lihat Komik";
            $data['view_background']="Efek Latar Belakang";
            $data['pconfirm'] = 'Mohon Konfirmasi'; 
            $data['deletesure']   = ' Anda yakin ingin menghapus?';  
            $data['deleteyes']  = 'Iya';
            $data['deleteno']   = 'Tidak';
            $data['lanmessage'] = 'Pesan';
            $data['mcomis']     = "Komik Saya";
            $data['userlanguage']= 'BAHASA';
            $data['yettocreate']="Yet you have not created any Comics.";
        }else{
            $data['view_Cartoon']="View Cartoon";
            $data['view_Comics']="View Comics";
            $data['view_background']="Background Effect";
			$data['pconfirm'] = 'Please Confirm';   
			$data['deletesure']   = ' Are you sure you want to delete?';
			$data['deleteyes']  = 'Yes';
			$data['deleteno']   = 'No';
			$data['lanmessage'] = 'Message';
			$data['mcomis']     = "My Comics";
			$data['userlanguage']= 'ENGLISH';
			$data['yettocreate']="Yet you have not created any Comics.";
        }
        $data['result'] = $this->comic_model->viewAllComic($mobnum);
        $data['backword'] = 'index';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $this->load->view('newui/template/header_2', $data);
        $this->load->view('newui/viewComics');
        $this->load->view('newui/template/footer_2');
        $data['tabstatus'] = '2';
        $data['activeTab'] = $this->uri->segment(1);
        if(substr($mobnum,0,3)=="880")    //robi
        {
            $this->load->view('newui/template/fixedFooterMenuTab_robi', $data);
        }else{
            $this->load->view('newui/template/fixedFooterMenuTab', $data);
        }
    }

    public function Comics() {
        //$this->userLoged();
        /*if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on'){
            $link  = "http://";
            $link .= $_SERVER['HTTP_HOST'];
            $link .= $_SERVER['REQUEST_URI'];
            redirect($link);
        }*/
        $data['mobnum'] = '';
        if ($this->session->userdata('mobnum') != '') {
            $data['mobnum'] = $this->session->userdata('mobnum');
        }
        $data['littledataurl']='https://littledata.in/mcom/';
        $sid = $_GET['mComic_id'];
        $data['id'] = $sid;
        $data['titleName'] = $this->comic_model->comicTitleName($sid);
        $data['result'] = $this->comic_model->comicDetails($sid);
        $data['backword'] = 'viewComics';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['onclickEvent'] = 'style="display:none;"';
        $this->load->view('newui/template/header_f', $data);
        $this->load->view('newui/Comic');
        $this->load->view('newui/template/footer_2');
    }

    public function robi_faq()
    {
        
        // if ($this->session->userdata('mobnum') != '') {
        //     $data['mobnum'] = $this->session->userdata('mobnum');
        // }
        // $data['littledataurl']='http://littledata.in/mcom/';
        // $data['backword'] = 'viewComics';
        // $data['forward_img'] = 'home.png';
        // $data['forward'] = 'index';
        // $data['onclickEvent'] = 'style="display:none;"';
        $this->load->view('template/header_auth');
        $this->load->view("robi_faq");
        //echo "fine";
    }


    public function shareComics() {
        //$this->userLoged();
        $data['mobnum'] = '';
        if ($this->session->userdata('mobnum') != '') {
            $data['mobnum'] = $this->session->userdata('mobnum');
        }
        $sid = $_GET['id'];
        $data['id'] = $sid;
        $type = $_GET['type'];
        $data['backword'] = 'viewComics';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['onclickEvent'] = 'style="display:none;"';

        switch($type){
        	case 'photo'	: 
	        					$data['type']		= 'photo';
	        					$data['path']		= 'uploadPhoCartoon/';
	        					$tbl_name			= 'tbl_cartoon_res';
	        					$data['result']	 	= $this->comic_model->share($tbl_name,$sid);
	        					break;

        	case 'face' 	: 
        						$data['type']		= 'face';
	        					$data['path'] 		= 'changefacefile/downloads/';
	        					$tbl_name			= 'tbl_changeface_download';
	        					$data['result'] 	= $this->comic_model->share($tbl_name,$sid);
	        					break;
        	default 		: 
        						$data['result'] = '';
								break;
        }
        $this->load->view('share',$data);
        $this->load->view('template/footer_2');
    }


    public function makeComicsPage() {
        //$url = $_REQUEST['id'];
        $url = $this->input->get('id');
        $filePath = exec("/var/www/html/testing_d/games/toimage/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /var/www/pheuture/pheuture/mcomics/PhantomJS/site-screenshot.js $url 2>&1");
    }

     public function PrintComics() {
        $sid = $_GET['id'];
        $data['id'] = $sid;
        $data['titleName'] = $this->comic_model->comicTitleName($sid);
        $data['result'] = $this->comic_model->comicDetails($sid);

        $this->load->view('print',$data);
    }

    public function makeFemaleComic() {
        $this->userLoged();
        $p1 = $this->input->post('param1');
        $p2 = $this->input->post('param2');
        $p3 = $this->input->post('param3');
        $value = $this->input->post('value');


        $url = urldecode($this->session->userdata('imageFemaleURL'));
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);

        $json_1 = json_decode($query['colours'], true);
        $json_2 = json_decode($query['pd2'], true);
        $json_3 = json_decode($query['body'], true);

        $proportion = $query['proportion'];
        $c_ffcc99 = $json_1['ffcc99'];
        $c_926715 = $json_1['926715'];
        $c_4f453e = $json_1['4f453e'];
        $c_36a7e9 = $json_1['36a7e9'];
        $c_ff9999 = $json_1['ff9999'];
        $c_b88eb6 = $json_1['b88eb6'];
        $c_ff9866 = $json_1['ff9866'];
        $pd2_eyelash = $json_2['eyelash_L'];
        $pd2_cranium = $json_2['cranium'];
        $pd2_forehead = $json_2['forehead'];
        $pd2_hair_back = $json_2['hair_back'];
        $pd2_hair_front = $json_2['hair_front'];
        $pd2_hairbottom = $json_2['hairbottom'];
        $pd2_jaw = $json_2['jaw'];
        $pd2_eyebrow = $json_2['brow_L'];
        $pd2_eyes = $json_2['eye_L'];
        $pd2_pupil = $json_2['pupil_L'];
        $pd2_nose = $json_2['nose'];
        $pd2_mouth = $json_2['mouth'];
        $pd2_tongue = $json_2['tongue'];
        $pd2_ear = $json_2['ear_L'];
        $pd2_eyedetail = $json_2['detail_E_L'];
        $pd2_blush = $json_2['detail_E2_L'];
        $pd2_eye_shadow = $json_2['detail_L2_L'];
        $pd2_faceline = $json_2['detail_T'];
        $pd2_glasses = $json_2['glasses'];
        $pd2_hat = $json_2['hat'];
        $pd2_earring = $json_2['earring_L'];
        $pd2_cheekdetail = $json_2['detail_L'];
        
        
        $body = $json_3['body_type'];
        $breast_type = $json_3['breast_type'];
        $cropped = 'head';

        if ('proportion' == $p1) {
            $proportion = $value;
        }

        if ('colours' == $p1) {
            if ('ffcc99' == $p2) {
                $c_ffcc99 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('926715' == $p2) {
                $c_926715 = $value;
            }
        }



        if ('colours' == $p1) {
            if ('36a7e9' == $p2) {
                $c_36a7e9 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('ff9999' == $p2) {
                $c_ff9999 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('b88eb6' == $p2) {
                $c_b88eb6 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('ff9866' == $p2) {
                $c_ff9866 = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('cranium' == $p2 && 'forehead' == $p3) {
                $hairStyle = explode(',', $value);
                $pd2_cranium = $hairStyle[0];
                $pd2_forehead = $hairStyle[1];
                $pd2_hair_back = $hairStyle[2];
                $pd2_hair_front = $hairStyle[3];
                $pd2_hairbottom = $hairStyle[4];
            }
        }

        if ('pd2' == $p1) {
            if ('jaw' == $p2) {
                $pd2_jaw = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('brow_L' == $p2 && 'brow_R' == $p3) {
                $pd2_eyebrow = $value;
            }
        }

        if ('colours' == $p1) {
            if ('4f453e' == $p2) {
                $c_4f453e = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('eye_L' == $p2 && 'eye_R' == $p3) {
                $pd2_eyes = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('pupil_L' == $p2 && 'pupil_R' == $p3) {
                $pd2_pupil = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('nose' == $p2) {
                $pd2_nose = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('mouth' == $p2 && 'tongue' == $p3) {
                $mouthStyle = explode(',', $value);
                $pd2_mouth = $mouthStyle[0];
                $pd2_tongue = $mouthStyle[1];
            }
        }

        if ('pd2' == $p1) {
            if ('ear_L' == $p2 && 'ear_R' == $p3) {
                $pd2_ear = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('detail_E2_L' == $p2 && 'detail_E2_R' == $p3) {
                $pd2_blush = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('detail_L2_2' == $p2 && 'detail_L2_R' == $p3) {
                $pd2_eye_shadow = $value;
            }
        }
        
        if ('pd2' == $p1) {
            if ('detail_L' == $p2 && 'detail_R' == $p3) {
                $pd2_cheekdetail = $value;
            }
        }
        
        if ('pd2' == $p1) {
            if ('detail_E_L' == $p2 && 'detail_E_R' == $p3) {
                $pd2_eyedetail = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('detail_T' == $p2) {
                $pd2_faceline = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('glasses' == $p2) {
                $pd2_glasses = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('hat' == $p2) {
                $pd2_hat = $value;
            }
        }

        if ('body' == $p1) {
            if ('body_type' == $p2) {
                $body = $value;
                $cropped = 'body';
            }
        }

        if ('body' == $p1) {
            if ('breast_type' == $p2) {
                $breast_type = $value;
                $cropped = 'body';
            }
        }

        if ('pd2' == $p1) {
            if ('eyelash_L' == $p2 && 'eyelash_R' == $p3) {
                $pd2_eyelash = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('earring_L' == $p2 && 'earring_R' == $p3) {
                $pd2_earring = $value;
            }
        }

        $url = 'https://render.bitstrips.com/render/6688424/159953823_183_s1-v1.png?';
        $url .= 'sex=2';
        $url .= '&body={"body_type":' . $body . ',"breast_type":' . $breast_type . '}&cropped="' . $cropped . '"&head_rotation=0&style=1';
        $url .= '&colours={"ffcc99":' . $c_ffcc99 . ',"926715":' . $c_926715 . ',"4f453e":' . $c_4f453e . ',"36a7e9":' . $c_36a7e9 . ',"ff9999":' . $c_ff9999 . ',"b88eb6":' . $c_b88eb6 . ',"ff9866":' . $c_ff9866 . '}';
        $url .= '&pd2={"eyelash_L":"' . $pd2_eyelash . '","eyelash_R":"' . $pd2_eyelash . '","earring_L":"' . $pd2_earring . '","earring_R":"' . $pd2_earring . '","detail_T":"' . $pd2_faceline . '","glasses":"' . $pd2_glasses . '","hat":"' . $pd2_hat . '","detail_E2_L":"' . $pd2_blush . '","detail_E2_R":"' . $pd2_blush . '","detail_L2_L":"' . $pd2_eye_shadow . '","detail_L2_R":"' . $pd2_eye_shadow . '","ear_L":"' . $pd2_ear . '","ear_R":"' . $pd2_ear . '","mouth":"' . $pd2_mouth . '","tongue":"' . $pd2_tongue . '","nose":"' . $pd2_nose . '","pupil_L":"' . $pd2_pupil . '","pupil_R":"' . $pd2_pupil . '","eye_L":"' . $pd2_eyes . '","eye_R":"' . $pd2_eyes . '","eyelines_L":"' . $pd2_eyes . '","eyelines_R":"' . $pd2_eyes . '","brow_L":"' . $pd2_eyebrow . '","brow_R":"' . $pd2_eyebrow . '","jaw":"' . $pd2_jaw . '","cranium":"' . $pd2_cranium . '","forehead":"' . $pd2_forehead . '","hair_back":"' . $pd2_hair_back . '","hair_front":"' . $pd2_hair_front . '","hairbottom":"' . $pd2_hairbottom . '","detail_L":"' . $pd2_cheekdetail . '","detail_R":"' . $pd2_cheekdetail . '","detail_E_L":"' . $pd2_eyedetail . '","detail_E_R":"' . $pd2_eyedetail . '"}';
        $url .= '&proportion=' . $proportion;

        $this->session->set_userdata('imageFemaleURL', $url);
        echo urldecode($url);
    }

    public function makeMaleComic() {
        $this->userLoged();
        $p1 = $this->input->post('param1');
        $p2 = $this->input->post('param2');
        $p3 = $this->input->post('param3');
        $value = $this->input->post('value');

        $url = urldecode($this->session->userdata('imageMaleURL'));
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);

        $json_1 = json_decode($query['colours'], true);
        $json_2 = json_decode($query['pd2'], true);
        $json_3 = json_decode($query['body'], true);

        //print_r($json);

        $proportion = $query['proportion'];
        $c_ffcc99 = $json_1['ffcc99'];
        $c_926715 = $json_1['926715'];
        $c_6f4b4b = $json_1['6f4b4b'];
        $pd2_cranium = $json_2['cranium'];
        $pd2_forehead = $json_2['forehead'];
        $pd2_hair_back = $json_2['hair_back'];
        $pd2_hair_front = $json_2['hair_front'];
        $pd2_hairbottom = $json_2['hairbottom'];
        $pd2_jaw = $json_2['jaw'];
        $pd2_eyebrow = $json_2['brow_L'];
        $c_4f453e = $json_1['4f453e'];
        $pd2_eyes = $json_2['eye_L'];
        $pd2_pupil = $json_2['pupil_L'];
        $c_36a7e9 = $json_1['36a7e9'];
        $pd2_nose = $json_2['nose'];
        $pd2_mouth = $json_2['mouth'];
        $pd2_tongue = $json_2['tongue'];
        $pd2_ear = $json_2['ear_L'];
        $pd2_beard = $json_2['beard'];
        $pd2_stachin = $json_2['stachin'];
        $pd2_stachout = $json_2['stachout'];
        $pd2_eyedetail = $json_2['detail_E_L'];
        $pd2_cheekdetail = $json_2['detail_L'];
        $pd2_faceline = $json_2['detail_T'];
        $pd2_glasses = $json_2['glasses'];
        $pd2_hat = $json_2['hat'];
        $body = $json_3['body_type'];
        $cropped = 'head';

        if ('proportion' == $p1) {
            $proportion = $value;
        }

        if ('colours' == $p1) {
            if ('ffcc99' == $p2) {
                $c_ffcc99 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('926715' == $p2) {
                $c_926715 = $value;
            }
        }

        if ('colours' == $p1) {
            if ('6f4b4b' == $p2) {
                $c_6f4b4b = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('cranium' == $p2 && 'forehead' == $p3) {
                $hairStyle = explode(',', $value);
                $pd2_cranium = $hairStyle[0];
                $pd2_forehead = $hairStyle[1];
                $pd2_hair_back = $hairStyle[2];
                $pd2_hair_front = $hairStyle[3];
                $pd2_hairbottom = $hairStyle[4];
            }
        }

        if ('pd2' == $p1) {
            if ('jaw' == $p2) {
                $pd2_jaw = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('brow_L' == $p2 && 'brow_R' == $p3) {
                $pd2_eyebrow = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('eye_L' == $p2 && 'eye_R' == $p3) {
                $pd2_eyes = $value;
            }
        }

        if ('colours' == $p1) {
            if ('4f453e' == $p2) {
                $c_4f453e = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('pupil_L' == $p2 && 'pupil_R' == $p3) {
                $pd2_pupil = $value;
            }
        }

        if ('colours' == $p1) {
            if ('36a7e9' == $p2) {
                $c_36a7e9 = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('nose' == $p2) {
                $pd2_nose = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('mouth' == $p2 && 'tongue' == $p3) {
                $mouthStyle = explode(',', $value);
                $pd2_mouth = $mouthStyle[0];
                $pd2_tongue = $mouthStyle[1];
            }
        }

        if ('pd2' == $p1) {
            if ('ear_L' == $p2 && 'ear_R' == $p3) {
                $pd2_ear = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('beard' == $p2 && 'stachin' == $p3) {
                $breadStyle = explode(',', $value);
                $pd2_beard = $breadStyle[0];
                $pd2_stachin = $breadStyle[1];
                $pd2_stachout = $breadStyle[2];
            }
        }

        if ('pd2' == $p1) {
            if ('detail_E_L' == $p2 && 'detail_E_R' == $p3) {
                $pd2_eyedetail = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('detail_L' == $p2 && 'detail_R' == $p3) {
                $pd2_cheekdetail = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('detail_T' == $p2) {
                $pd2_faceline = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('glasses' == $p2) {
                $pd2_glasses = $value;
            }
        }

        if ('pd2' == $p1) {
            if ('hat' == $p2) {
                $pd2_hat = $value;
            }
        }

        if ('body' == $p1) {
            if ('body_type' == $p2) {
                $body = $value;
                $cropped = 'body';
            }
        }


        $url = 'https://render.bitstrips.com/render/6688424/134174650_1_s1-v1.png?';
        $url .= 'body={"body_type":' . $body . '}&cropped="' . $cropped . '"&head_rotation=0&sex=1&style=1';
        $url .= '&colours={"ffcc99":' . $c_ffcc99 . ',"926715":' . $c_926715 . ',"4f453e":' . $c_4f453e . ',"36a7e9":' . $c_36a7e9 . ',"6f4b4b":' . $c_6f4b4b . '}';
        $url .= '&pd2={"mouth":"' . $pd2_mouth . '","tongue":"' . $pd2_tongue . '","eye_L":"' . $pd2_eyes . '","eye_R":"' . $pd2_eyes . '","eyelines_L":"' . $pd2_eyes . '","eyelines_R":"' . $pd2_eyes . '","cranium":"' . $pd2_cranium . '","forehead":"' . $pd2_forehead . '","hair_back":"' . $pd2_hair_back . '","hair_front":"' . $pd2_hair_front . '","hairbottom":"' . $pd2_hairbottom . '","jaw":"' . $pd2_jaw . '","beard":"_blank","stachin":"_blank","stachout":"_blank","brow_L":"' . $pd2_eyebrow . '","brow_R":"' . $pd2_eyebrow . '","pupil_L":"' . $pd2_pupil . '","pupil_R":"' . $pd2_pupil . '","nose":"' . $pd2_nose . '","ear_L":"' . $pd2_ear . '","ear_R":"' . $pd2_ear . '","detail_E_L":"' . $pd2_eyedetail . '","detail_E_R":"' . $pd2_eyedetail . '","detail_L":"' . $pd2_cheekdetail . '","detail_R":"' . $pd2_cheekdetail . '","detail_T":"' . $pd2_faceline . '","glasses":"' . $pd2_glasses . '","hat":"' . $pd2_hat . '","beard":"' . $pd2_beard . '","stachin":"' . $pd2_stachin . '","stachout":"' . $pd2_stachout . '"}';
        $url .= '&proportion=' . $proportion;

        $this->session->set_userdata('imageMaleURL', $url);
        echo urldecode($url);
    }

    public function setDefaultBody($gender) {
        $this->userLoged();
        if ($gender == 'female') {
            $url = $this->session->userdata('imageFemaleURL');
            $parts = parse_url($url);
            parse_str($parts['query'], $query);

            if (urldecode($query['cropped']) == '"head"') {
                $from = 'head';
                $to = 'body';
                $from = '/' . preg_quote($from, '/') . '/';
                $url = preg_replace($from, $to, $url, 1);
                //$url = str_replace("head", "body", $url);
                $this->session->set_userdata('imageFemaleURL', $url);
                return $url;
            } else
                return $url;
        }

        if ($gender == 'male') {


            $url = $this->session->userdata('imageMaleURL');
            $parts = parse_url($url);
            parse_str($parts['query'], $query);

            if (urldecode($query['cropped']) == '"head"') {
                $from = 'head';
                $to = 'body';
                $from = '/' . preg_quote($from, '/') . '/';
                $url = preg_replace($from, $to, $url, 1);
                //$url = str_replace("head", "body", $url);
                $this->session->set_userdata('imageMaleURL', $url);
                return $url;
            } else
                return $url;
        }
    }

    public function changeMaleOutfit() {
        $this->userLoged();
        $param1 = $this->input->post('id');
        $param2 = $this->input->post('rand');


        //echo $param1.'_____'.$param2.'<br/>';
        $url = urldecode($this->session->userdata('imageMaleURL'));
        $arr2 = parse_url($url);
        //print_r($arr2);
        $path = explode('/', $arr2['path']);
        $oldPng = $path[3];
        $newPng = $param1 . '_' . $param2 . '_s1-v1.png';
        //print_r($path);
        $url = str_replace($oldPng, $newPng, $url);
        $this->session->set_userdata('imageMaleURL', $url);
        echo urldecode($url);
    }

    public function changeFemaleOutfit() {
        $this->userLoged();
        $param1 = $this->input->post('id');
        $param2 = $this->input->post('rand');


        //echo $param1.'_____'.$param2.'<br/>';
        $url = urldecode($this->session->userdata('imageFemaleURL'));
        $arr2 = parse_url($url);
        //print_r($arr2);
        $path = explode('/', $arr2['path']);
        $oldPng = $path[3];
        $newPng = $param1 . '_' . $param2 . '_s1-v1.png';
        //print_r($path);
        $url = str_replace($oldPng, $newPng, $url);

        $this->session->set_userdata('imageFemaleURL', $url);
        echo urldecode($url);
    }

    public function edit() {
        $this->userLoged();
        $id = $_GET['id'];
        $sid = $_GET['sid'];
        $titleName = $_GET['titlename'];
         $data['littledataurl']='http://littledata.in/mcom/';
        $data['backword'] = 'giveTitle?id=' . $id;
        $data['myCreatedCharacter'] = $this->comic_model->myCharacter($id);
        $data['myChar'] = $id;
        $data['editID'] = $sid;
        $data['titleName'] = $titleName;
        $data['forward_img'] = 'save.png';
        $data['forward'] = 'javascript:void(0);';
        $data['onclickEvent'] = 'onclick="editsavedComic()"';
        $this->load->view('template/header_3', $data);
        $data['result'] = $this->comic_model->createStoryAllIcons();
        $data['results'] = $this->comic_model->comicDetails($sid);
        $data['storyImages'] = $this->comic_model->storyImages(1);
        $this->load->view('edit_story', $data);
        $this->load->view('template/footer_2');
    }

    public function editsavedMyScreens() {
        error_reporting(1);
        ini_set('display_errors', 1);
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        $content = json_decode($this->input->get_post('content'), true);
        $countArr = sizeof($content);
        $title = $content[0]['title'];
        //$insert_id = $this->comic_model->savedSrc($title);

        for ($i = 0; $i < $countArr; $i++) {
            $removescreen = $content[$i]['removescreen'];
            if ($removescreen == '-REEMOVESCREEN') {   // if screen remove for this index
                $screenid = $content[$i]['subscreenid'];
                $this->comic_model->deleteEditComicScreen($mobnum, $screenid);
            } else {
                $lastId = $content[$i]['sId'];
                $editid = $content[$i]['editId'];
                $screenid = $content[$i]['subscreenid'];
                $narra = $content[$i]['narra'];
                $bg = $content[$i]['bckg'];
                $lc = $content[$i]['lCom'];
                $rc = $content[$i]['rCom'];
                $li = $content[$i]['lImg'];
                $ri = $content[$i]['rImg'];
                $lB = $content[$i]['lB'];
                $rB = $content[$i]['rB'];
                if ($screenid < '0') {       //new screen added
                    $this->comic_model->insertEditComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $editid, $lB, $rB, $screenid);
                } else {      //no screen added
                    $this->comic_model->updateComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $editid, $lB, $rB, $screenid);
                }
            }
        }
        echo '{"last_id":"' . $content[0]['editId'] . '"}';
        //echo json_encode($title);
        //echo $myHTML = urldecode($this->input->get('myHTML'));        
    }


    function getMaleOutfit() {
        //$outfit_id = $_POST['outfit_id'];
        $this->userLoged();
        
        
        $url = urldecode($this->session->userdata('imageMaleURL'));

        $outfit_id = $this->input->get_post('outfit_id');
        
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);
        
        $data = 'outfit='.$outfit_id;
        $data .= '&body='.$query['body'];
        $data .= '&cropped='.$query['cropped'];
        $data .= '&head_rotation='.$query['head_rotation'];
        $data .= '&sex='.$query['sex'];
     /*   $data .= '&crop_width='.$query['crop_width'];
        $data .= '&crop_height='.$query['crop_height'];*/
        $data .= '&style='.$query['style'];
        $data .= '&colours='.$query['colours'];
        $data .= '&pd2='.$query['pd2'];
        $data .= '&proportion='.$query['proportion'];
        
        
        
        $url = 'https://render.bitstrips.com/render/6688424/134174650_1_s1-v1.png?'. $data;
        $this->session->set_userdata('imageMaleURL', $url);
        $this->session->set_userdata('outfitId', $outfit_id);
        echo urldecode($url);
    }

    

    function getFemaleOutfit() {
        //$outfit_id = $_POST['outfit_id'];
        $this->userLoged();
        $url = urldecode($this->session->userdata('imageFemaleURL'));
        $outfit_id = $this->input->get_post('outfit_id');

        
        
        $arr2 = parse_url($url);
        parse_str($arr2['query'], $query);

        $data = 'outfit='.$outfit_id;
        $data .= '&sex='.$query['sex'];
        
        $data .= '&body='.$query['body'];
        $data .= '&cropped='.$query['cropped'];
        $data .= '&head_rotation='.$query['head_rotation'];
        
/*        $data .= '&crop_width='.$query['crop_width'];
        $data .= '&crop_height='.$query['crop_height'];*/
        $data .= '&style='.$query['style'];
        $data .= '&colours='.$query['colours'];
        $data .= '&pd2='.$query['pd2'];
        $data .= '&proportion='.$query['proportion'];
        
        
        $url = 'https://render.bitstrips.com/render/6688424/159953823_183_s1-v1.png?' . $data ;
        $this->session->set_userdata('imageFemaleURL', $url);
        echo urldecode($url);
    }

    function cartoon(){
        $this->userLoged();
        $data['tmp']=2;
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->load->view('template/header_1',$data);
        $this->load->helper('form');
        $this->load->library('image_lib');
        $this->load->view('cartoon');
    }

    public function value() {
        $this->userLoged();
        $data['selBg'] = $this->input->post("selBg");
            if ($this->input->post("submit")) {
            $config = array(
                'upload_path' => "upload/",
                'upload_url' => base_url() . "upload/",
                'allowed_types' => "gif|jpg|png|jpeg|pdf"
            );
                $this->load->library('upload', $config);
                if ($this->upload->do_upload()) {
            }
        }
        $data['img_src'] =  $image_data['file_name'];
        echo $response = $this->upload11($image_data['file_name']);
        //$data['img_src'] = 'Jerry_PNG_Picture_Free_Clipart7.png';
        
        //$this->load->view('cartoonRes',$data);
    }

    /*
    public function phoCartoon(){
        $this->userLoged();
        $data['tmp']=2;
        $this->load->view('template/header_1',$data);
        $data['result'] = $this->comic_model->phoCartoon(0);
        //$this->load->helper('form');
        //$this->load->library('image_lib');
        $this->load->view('phocartoon',$data);
    }
    */

    // public function phoCartoon(){
    //     $this->userLoged();
    //     $data['tmp']=2;
    //     $this->load->view('template/header_1',$data);
    //     $data['result'] = $this->comic_model->selCartoonAjaxAllRecord(0);
    //     $this->load->view('phocartoon2',$data);
    //     $data['tabstatus'] = '1';
    //     $data['activeTab'] = $this->uri->segment(1);
    //     $this->load->view('fixedFooterMenuTab', $data);
    // }

    


    public function request(){
        $limitStart = $this->input->post('limitStart');
        //$html = file_get_contents('http://lab.iamrohit.in/sload/request.php?limitstart='.$limitstart);
        if($this->input->post('status') == 'success'){
            $selCartoonAjax = $this->comic_model->selCartoonAjax2($limitStart,0);
            if(count($selCartoonAjax) > 0){
                echo json_encode($selCartoonAjax);
            }else{
                echo '[{"id":"0","msg":"No More Images"}]';
            }
        }else{
            redirect(base_url(), 'refresh');
        }
        
        
    }

    public function selCartoonAjax(){
        $this->userLoged();
        $page =  $this->input->post('page');
        $selCartoonAjax = $this->comic_model->selCartoonAjax($page,0);
        if(count($selCartoonAjax) > 0){
            foreach($selCartoonAjax as $data){
                echo '<a href="'.base_url().'welcome/selCartoon/'.$data->id.'">';
                   echo '<div class="col-md-2  col-xs-4" style="text-align:center;margin-bottom:5px;">';
                   if($data->img!=''){
                      echo '<img src="'.base_url().'phoCartoons/'.$data->img.'" style="width:100%">';
                   }else{
                      echo '<img src="'.base_url().'phoCartoons/default-bck.png" style="width:100%">';
                   }
                   
                   //echo '<div class="row"><div class="col-md-12 phoText">'.$data->name.'</div></div>';
                   echo '</div>';
                   echo '</a>';
            }
        }else{
            echo '<div></div>';
        }
        
        exit;
    }

    public function selCartoon(){
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['uploadTitle']='පින්තුරයක් උඩුගත කරන්න';
            $data['upload']='උඩුගත කරන්න';            
            $data['Selfie']='සැසි';
             $data['uploadingtxt']='රූපය උඩුගත කිරීම ....';  
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['uploadTitle']='একটি ছবি আপলোড করুন';
            $data['upload']='আপলোড';            
            $data['Selfie']='শেলফি';
            $data['uploadingtxt']='ছবি আপলোড হচ্ছে ....'; 
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['uploadTitle']='Télécharger une image';
            $data['upload']='Télécharger';            
            $data['Selfie']='Selfie';
             $data['uploadingtxt']="Téléchargement de l'image ....";
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['uploadTitle']='تحميل صورة';
            $data['upload']='تحميل صورة'; 
            $data['Selfie']='تحميل';   
            $data['uploadingtxt']='يجري تحميل صورة ....';           
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['uploadTitle']='တစ်ဦးကို Image Upload လုပ်ပါ';
            $data['upload']='အပ်လုဒ်ကို'; 
            $data['Selfie']='Selfie';   
            $data['uploadingtxt']='အပ်လုဒ်လုပ်နေသည်ပုံရိပ် ....';           
        }else{
            $data['uploadTitle']='Upload a Image';
            $data['upload']='Upload';
            $data['Selfie']='Selfie';
            $data['uploadingtxt']='Uploading image....'; 
        }

        $data['littledataurl']='http://littledata.in/mcom/';
        $data['backword'] = 'phoCartoon';
        $data['tmp'] = 1;
        $this->load->view('newui/template/header_5',$data);
        $id = $this->uri->segment(3);
        $data['id'] = $id;
        $data['result'] = $this->comic_model->selCartoon($id,0);
        $this->load->view('newui/selcartoon',$data);
    }


    public function resCartoon() {
        $this->userLoged();
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
		  $data['previmage']=  '<div class="heading_caption" style="font-size:20px">පූර්ව දර්ශනය</div>';            
		}else if($this->session->userdata('userlanguage') == 'BANGLA'){
		  $data['previmage']=  '<div class="heading_caption" style="font-size:20px">চিত্র প্রাকদর্শন</div>';           
		}else if($this->session->userdata('userlanguage') == 'FRENCH'){
		  $data['previmage']= '<div class="heading_caption" style="font-size:20px">Aperçu de l\'image</div>';           
		}else if($this->session->userdata('userlanguage') == 'ARABIC'){
          $data['previmage']= '<div class="heading_caption" style="font-size:20px">العرض المسبق للصورة</div>';           
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
          $data['previmage']= '<div class="heading_caption" style="font-size:20px">Preview ကိုပုံရိပ်</div>';           
        }else{
		  $data['previmage']=  '<div class="heading_caption" style="font-size:20px">Preview Image</div>';
		}
        $data['backword'] = 'welcome/selCartoon/'.$this->uri->segment(4);
        $data['tmp'] = 2;
        $gen_id = $this->uri->segment(3);
        $data['gen_id'] = $gen_id;
        $data['littledataurl']='http://littledata.in/mcom/';
        $mobnum = $this->session->userdata('mobnum');
        $data['result'] = $this->comic_model->finalResultCartoon($mobnum,$gen_id);
        $this->load->view('newui/template/header_5',$data);
        $this->load->view('newui/resCartoon',$data);
    }

    public function saveCartoon() {
        $this->userLoged();
        $data['backword'] = '#';
        $data['tmp'] = 3;
        $gen_id = $this->uri->segment(3);
        $data['gen_id'] = $gen_id;
        if($this->session->userdata('userlanguage') == 'SINHALESE'){   
			$data['downloadtxt'] = 'බාගත';
			$data['sharetxt'] = 'බෙදාගන්න';        
		}else if($this->session->userdata('userlanguage') == 'BANGLA'){
			$data['downloadtxt'] = 'ডাউনলোড';          
			$data['sharetxt'] = 'ভাগ';          
		}else if($this->session->userdata('userlanguage') == 'FRENCH'){
			$data['downloadtxt'] = 'Télécharger';   
			$data['sharetxt'] = 'Partager';          
		}else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['downloadtxt'] = 'تنزيل';   
            $data['sharetxt'] = 'شارك';          
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['downloadtxt'] = 'ဒေါင်းလုပ်';          
            $data['sharetxt'] = 'ဝေစု';          
        }else{
			$data['downloadtxt'] = 'Download';
			$data['sharetxt'] = 'Share';     
		}
        $mobnum = $this->session->userdata('mobnum');
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->comic_model->confrimTosaveCartoon($mobnum,$gen_id);
        $data['result'] = $this->comic_model->finalResultCartoon($mobnum,$gen_id);
        $this->load->view('newui/template/header_5',$data);
        $this->load->view('newui/saveCartoon',$data);
    }

    public function getFinalResult() {
        //$gen_id = $this->uri->segment(3);
        $this->userLoged();
        $gen_id = $this->input->post('genid');
        $data['gen_id'] = $gen_id;
        $mobnum = $this->session->userdata('mobnum');
        $result = $this->comic_model->finalResultCartoon($mobnum,$gen_id);
        //print_r($result);
        $request_id = $result['request_id'];
        $num = $result['rand_num'];

        if($result['final_img']=='' && $result['final_img_thumb']==''){
            $response = $this->level4($request_id,$num);

            $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            $fimg =  $xml->result_url;
            $simg =  $xml->thumb1_url;

            $fimg = $this->saveCartoonImg($fimg);
            $simg = $this->saveCartoonImg($simg);
            //$categoriesresult = file_get_contents($fimg);    
            //file_put_contents(base_url()."uploadPhoCartoon/$fimg", $categoriesresult);
            $this->comic_model->updateCartoon($fimg,$simg,$gen_id);

            echo '{"message":"true","fimg":"'.$fimg.'","simg":"'.$simg.'","request_id":"'.$request_id.'","num":"'.$num.'"}';
        }else if($result['final_img']!='' && $result['final_img_thumb']!='') {
            $fimg =  $result['final_img'];
            $simg =  $result['final_img_thumb'];
            echo '{"message":"true","fimg":"'.$fimg.'","simg":"'.$simg.'","request_id":"'.$request_id.'","num":"'.$num.'}';
        }else{
            echo '{"message":"false"}';
        }
    }

    function saveCartoonImg($imgPath){
        $this->userLoged();
        //echo $imgPath;
        $categoriesresult = file_get_contents($imgPath); 
        //$fimg  = 'http://worker-images.ws.pho.to/i1/A154BC5E-E2F3-48D7-A24D-15C66140D8B4.jpg';
        $imgPath = basename($imgPath);
        // echo $fimg = str_replace('http://worker-images.ws.pho.to/i1/','',$fimg);   
         file_put_contents("/var/www/pheuture/pheuture/mcomics/uploadPhoCartoon/$imgPath", $categoriesresult);
         chmod("/var/www/pheuture/pheuture/mcomics/uploadPhoCartoon/$imgPath", 0755);
        return $imgPath;
    }

    public function viewPhotoEffect() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['view_Cartoon']="කාටූන් බලන්න";
            $data['view_Comics']="කොමික්ස් බලන්න";
            $data['view_background']="පසුබිම් බලපෑම";
		 	$data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">මගේ ඡායාරූප ආචරණ</div>';  
		 	$data['userlanguage']= 'SINHALESE';    
		 	$data['yettocreate']='එහෙත් ඔබ කිසිදු ඡායාරූප බලපෑමක් නිර්මාණය කර නැත.';
		}else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['view_Cartoon']="কার্টুন দেখুন";
            $data['view_Comics']="কমিক্স দেখুন";
            $data['view_background']="পটভূমি প্রভাব";
			$data['userlanguage']= 'BANGLA';
		  	$data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">আমার ফটো প্রভাব</div>';  
		  	$data['yettocreate']='তবুও আপনি কোনও ফটো ইফেক্ট তৈরি করেন নি।';         
		}else if($this->session->userdata('userlanguage') == 'FRENCH'){
                        $data['view_Cartoon']="Voir le dessin animé";
            $data['view_Comics']="Voir les bandes dessinées";
            $data['view_background']="Effet de fond";
			$data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">Mes effets photo</div>';
			$data['userlanguage']= 'FRENCH';    
			$data['yettocreate']="Pourtant, vous n'avez créé aucun effet photo.";       
		}else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['view_Cartoon']="عرض الكرتون";
            $data['view_Comics']="عرض الكاريكاتير";
            $data['view_background']="تأثير الخلفية";
            $data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">تأثيرات الصور الخاصة بي</div>';
            $data['userlanguage']= 'ARABIC'; 
            $data['yettocreate']='حتى الآن لم تقم بإنشاء أي تأثير الصورة.';          
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['view_Cartoon']="ကြည့်ရန်ကာတွန်း";
            $data['view_Comics']="ကြည့်ရန်ရုပ်ပြ";
            $data['view_background']="နောက်ခံ Effect";
            $data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">ကဗျာအကျိုးသက်ရောက်မှု</div>';
            $data['userlanguage']= 'BURMESE';     
            $data['yettocreate']='သို့သျောလညျးသငျသညျမဆိုဓာတ်ပုံကိုအကျိုးသက်ရောက်မှုကိုမဖန်ဆင်းကြပြီ';      
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){ // Bahasa Language
            $data['view_Cartoon']="Lihat Kartun";
            $data['view_Comics']="Lihat Komik";
            $data['view_background']="Efek Latar Belakang";
            $data['photoeffects']=  '<div class="heading_caption" style="font-size:20px; color: #000000;">Efek Foto Saya</div>';
            $data['userlanguage']= 'BAHASA';     
            $data['yettocreate']='Namun Anda belum membuat efek foto apa pun.';      
        }else{
            $data['view_Cartoon']="View Cartoon";
            $data['view_Comics']="View Comics";
            $data['view_background']="Background Effect";

			$data['photoeffects']= '<div class="heading_caption" style="font-size:20px; color: #000000;">My Photo Effects</div>';
			$data['userlanguage']= 'ENGLISH';
			$data['yettocreate']='Yet you have not created any photo effect.'; 
		}

        $data['result'] = $this->comic_model->viewAllPhotoEffect($mobnum);
        $data['backword'] = 'index';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->load->view('newui/template/header_2', $data);
        $this->load->view('newui/viewPhotoEffect');
        $this->load->view('newui/template/footer_2');
        $data['tabstatus'] = '2';
        $data['activeTab'] = $this->uri->segment(1);
        $mobnum = $this->session->userdata('mobnum');    
        if(substr($mobnum,0,3)=="880")    //robi
        {
            $this->load->view('newui/template/fixedFooterMenuTab_robi', $data);
        }else{
            $this->load->view('newui/template/fixedFooterMenuTab', $data);
        }
        //$this->load->view('fixedFooterMenuTab', $data);
    }

    public function popupPhotoEffect(){
        $this->userLoged();
        $genid = $this->input->post('genid');
        $data['back'] = $this->input->post('back');
        $mobnum = $this->session->userdata('mobnum');
        $data['littledataurl']='http://littledata.in/mcom/';
        $data['result'] = $this->comic_model->finalResultCartoon($mobnum,$genid);
        $this->load->view('popupPhotoEffect',$data);
    }

    public function fileUpload(){
        $this->userLoged();
        $fileext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $fileext =   strtolower($fileext);
        if($fileext=='png' || $fileext=='PNG' || $fileext=='jpg' || $fileext=='JPG'  || $fileext=='JPEG' || $fileext=='jpeg' || $fileext=='gif' || $fileext=='gif' )
        {
        $mobnum = $this->session->userdata('mobnum');
        $url = "http://temp.ws.pho.to/upload.php"; // e.g. http://localhost/myuploader/upload.php // request URL
        $filename = $_FILES['file']['name'];
        $filedata = $_FILES['file']['tmp_name'];
        $filesize = $_FILES['file']['size'];
            if ($filedata != '')
            {

                $headers = array("Content-Type:multipart/form-data","Connection: Keep-Alive","Keep-Alive: off"); // cURL headers for file uploading
                $postfields = array("filedata" => "@$filedata", "filename" => $filename);
                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => true,
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_INFILESIZE => $filesize,
                    CURLOPT_RETURNTRANSFER => true
                ); // cURL options

                curl_setopt_array($ch, $options);
                $response = curl_exec($ch);
                //echo $response.'<br/><br/><br/>';
                //$res1 = explode('http://',$response);
                //$res2 = explode('.png',$res1[1]);
                //print_r($res2[0]);

                //$img = 'http://'.$res2[0].'.png';


                $res1 = explode('http://',$response);
                $res2 = 'http://'.$res1[1];
                $img = $res2;

                if(!curl_errno($ch))
                {
                    $info = curl_getinfo($ch);
                    if ($info['http_code'] == 200){
                        $id     = $this->input->post('id');
                        $result = $this->comic_model->selCartoon($id,0);
                        $frame_name = $result['key_val'];

                        $res = 'true';
                        $errmsg = "File uploaded successfully";

                        $img_full = str_replace('http://s3.amazonaws.com/temp.ws.pho.to/','',$img);
                        $img_thumb = 'thumb_'.$img_full;

                        $img_thumb = 'http://s3.amazonaws.com/temp.ws.pho.to/'.$img_thumb;
                        $img_full = 'http://s3.amazonaws.com/temp.ws.pho.to/'.$img_full;

                        // echo $img_full.'<br/>';
                        // echo $img_thumb.'<br/>';

                        $num = $this->level2($img_full,$img_thumb);


     $data = '   
    <image_process_call>
        <image_url order="1">'.$img.'</image_url>
        <methods_list>
            <method order="1"><name>collage</name>
            <params>template_name='.$frame_name.'</params> 
        </method>
        </methods_list>
            <result_size>1400</result_size>
            <result_quality>90</result_quality>
            <template_watermark>false</template_watermark>
            <thumb1_size>200</thumb1_size>
            <thumb1_quality>85</thumb1_quality>
            <lang>en</lang>
            <abort_methods_chain_on_error>true</abort_methods_chain_on_error>
    </image_process_call>';


                            $app_id = '9f6b07b2e3d00336b528c4a61de74f3d';

                            $key = 'cae5267f7fa0c495813a83f3ce2dfc08';

                            $sign_data = hash_hmac('sha1', $data, $key);

    //  $data = '   
    // <image_process_call>
    //     <image_url order="1">'.$img.'</image_url>
    //     <methods_list>
    //         <method order="1"><name>collage</name>
    //         <params>template_name='.$frame_name.'</params> 
    //     </method>
    //     </methods_list>
    //         <result_size>1400</result_size>
    //         <result_quality>90</result_quality>
    //         <template_watermark>false</template_watermark>
    //         <thumb1_size>200</thumb1_size>
    //         <thumb1_quality>85</thumb1_quality>
    //         <lang>en</lang>
    //         <abort_methods_chain_on_error>true</abort_methods_chain_on_error>
    // </image_process_call>';


                        $request_id = $this->level3($app_id,$key,$data,$sign_data);
                        $last_response = $this->level4($request_id,$num);

                        //echo $last_response;
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    $output_dir = "uploadPhoCartoon/";
                    $new_name = "file".date('m-d-Y-His').'.'.$ext;
                    move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$new_name);

                    $mobnum = $this->session->userdata('mobnum');
                    $insert_id = $this->comic_model->saveCartoon($mobnum,$new_name,$frame_name,$img_full,$img_thumb,$num,$app_id,$key,$data,$sign_data,$request_id);    

                    $genrated_id = $insert_id;

                    }else{
                        $res = 'false';
                        $img = '';
                        $errmsg = curl_error($ch);
                        $errmsg = "An Error occurs , Please try later";
                        $genrated_id = '';
                    }
                }
                else
                {
                    $res = 'false';
                    $img = '';
                    $errmsg = curl_error($ch);
                    $errmsg = "An Error occurs , Please try later";
                    $genrated_id = '';
                }
                curl_close($ch);
            }
            else
            {
                $res = 'false';
                $errmsg = "Please select the file";
            }

            //echo '{"response":"'.$res.'","message":"'.$errmsg.'","img":"'.$img.'"}';
            echo '{"response":"'.$res.'","message":"'.$errmsg.'","genrated_id":"'.$genrated_id.'","request_id" : "'.$request_id.'","num" : "'.$num.'","filename":"'.$new_name.'"}';

        }else{
            echo '{"response":"error","message":"Please select only png, jpg, jpeg or gif image formats."}';
        }
    }

    
    public function level2($img_full,$img_thumb) {
        $this->userLoged();
        $postUrl = 'http://funny.pho.to/ajax/user_storage/';

        $num = rand(1400000000000,1499999999999);

        $requestJson = "id=uploaded&json[]=$img_thumb&json[]=$img_full&json[]=$num";

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$postUrl);
        //curl_setopt($ch,CURLOPT_POST,$postlength);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$requestJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        //close connection
        curl_close($ch);

        return $num;
    }


    public function level3($app_id,$key,$data,$sign_data) {
        $this->userLoged();
        $postUrl = "https://opeapi.ws.pho.to/queue_url.php?service_id=7";

        $requestJson = "app_id=$app_id&data=$data&sign_data=$sign_data";

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$postUrl);
        //curl_setopt($ch,CURLOPT_POST,$postlength);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$requestJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        //close connection
        curl_close($ch);

        $xml=simplexml_load_string($response) or die("Error: Cannot create object");
        // print_r($xml);
        // echo $xml->request_id.'<br/><br/>';
        return $xml->request_id;
    }


    function level4($request_id,$num)
    {
        $this->userLoged();
        $url="http://opeapi.ws.pho.to/get-result.php?request_id=$request_id&_=$num";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if(!curl_errno($ch))
        {
            $data = $data;
        }else
        {
            $data = curl_error($ch);
        }
        curl_close($ch);
        return $data;
    }


    function level5()
    {
        $this->userLoged();
        $request_id = $this->input->post('request_id');
        $num        = $this->input->post('num');
        $url="http://opeapi.ws.pho.to/get-result.php?request_id=$request_id&_=$num";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if(!curl_errno($ch))
        {
            $data = $data;
        }else
        {
            $data = curl_error($ch);
        }
        curl_close($ch);
        $xml=simplexml_load_string($data) or die("Error: Cannot create object");
        $status         = $xml->status;
        $description    = $xml->description;
        echo '{"status" : "'.$status.'","desc" : "'.$description.'"}'; 
        return $data;
    }

    public function phoCartoonEmoji(){
        $this->userLoged();
        $data['tmp']=1;
        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['Create_Cartoon']="කාටූන් නිර්මාණය කරන්න";
            $data['Create_Comics']="කොමියූස් කරන්න";
            $data['Create_background']="පසුබිම් බලපෑම";
            $data['backgroundTitle']='කාටූන් නිර්මාණය කරන්න';
            $data['upload']='උඩුගත කරන්න'; 
            $data['Selfie']='සැසි'; 
            $data['uploadingtxt']='රූපය උඩුගත කිරීම ....';         
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['Create_Cartoon']="কার্টুন তৈরি করুন";
            $data['Create_Comics']="কমিক্স তৈরি করুন";
            $data['Create_background']="পটভূমি প্রভাব";
            $data['backgroundTitle']='কার্টুন তৈরি করুন';
            $data['upload']='আপলোড';   
            $data['Selfie']='শেলফি';
            $data['uploadingtxt']='ছবি আপলোড হচ্ছে ....';          
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['Create_Cartoon']="Créer une bande dessinée";
            $data['Create_Comics']="Créer des BD";
            $data['Create_background']="Effet de fond";
            $data['backgroundTitle']='Créer un dessin animé';
            $data['upload']='Télécharger';  
            $data['Selfie']='Selfie'; 
            $data['uploadingtxt']="Téléchargement de l'image ....";          
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['Create_Cartoon']="إنتاج الكاريكاتير";
            $data['Create_Comics']="إنتاج الكاريكاتير";
            $data['Create_background']="تأثير الخلفية";
            $data['backgroundTitle']='تأثير الخلفية';
            $data['upload']='تحميل صورة';  
            $data['Selfie']='تحميل';
            $data['uploadingtxt']='يجري تحميل صورة ....';           
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['Create_Cartoon']="ကာတွန်း Create";
            $data['Create_Comics']="ကာတွန်း Create";
            $data['Create_background']="နောက်ခံ Effect";
            $data['backgroundTitle']='ကာတွန်း Create';
            $data['upload']='အပ်လုဒ်ကို';  
            $data['Selfie']='Selfie';
            $data['uploadingtxt']='အပ်လုဒ်လုပ်နေသည်ပုံရိပ် ....';           
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['Create_Cartoon']="Buat Kartun";
            $data['Create_Comics']="Buat Komik";
            $data['Create_background']="Efek Latar Belakang";
            $data['backgroundTitle']='Buat Kartun';
            $data['upload']='Unggah';  
            $data['Selfie']='Selfie';
            $data['uploadingtxt']='Mengupload gambar ....';           
        }else{
            $data['Create_Cartoon']="Create Cartoon";
            $data['Create_Comics']="Create Comics";
            $data['Create_background']="Background Effect";

            $data['backgroundTitle']='Create Cartoons';
            $data['upload']='Upload';
            $data['Selfie']='Selfie';
            $data['uploadingtxt']='Uploading image....';  
        }
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->load->view('newui/template/header_1',$data);
        $this->load->view('newui/phoCartoonEmoji',$data);
        $data['tabstatus'] = '1';
        $data['activeTab'] = $this->uri->segment(1);
        $mobnum = $this->session->userdata('mobnum');    
        if(substr($mobnum,0,3)=="880")    //robi
        {
            $this->load->view('newui/template/fixedFooterMenuTab_robi', $data);
        }else{
            $this->load->view('newui/template/fixedFooterMenuTab', $data);
        }
        
    }

    public function selCartoonEmoji(){

    	if($this->session->userdata('userlanguage') == 'SINHALESE'){
		   	$data['applyface']=  'මුහුණේ මෝෆින් යොදන්න';    
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">පූර්ව දර්ශනය</div>';    
            $data['emojiname']    = 'emoji_name_sinahala';
            $data['facenotfound']='මුහුණ හමු නොවීය.';
		}else if($this->session->userdata('userlanguage') == 'BANGLA'){
		  	$data['applyface']=  'মুখ মোরিফিং প্রয়োগ করুন';      
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">চিত্র প্রাকদর্শন</div>';
            $data['emojiname']    = 'emoji_name_bangla';
            $data['facenotfound']='মুখ খুঁজে পাওয়া যায় নি।';
		}else if($this->session->userdata('userlanguage') == 'FRENCH'){
		  	$data['applyface']=  'Appliquer le morphing du visage';      
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">Aperçu de l\'image</div>';
            $data['emojiname']    = 'emoji_name_french';
            $data['facenotfound']='Visage non trouvé.';
		}else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['applyface']=  'تطبيق تحول الوجه';      
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">العرض المسبق للصورة</div>';
            $data['emojiname']    = 'emoji_name_arabic';
            $data['facenotfound']='لا يتم العثور على الوجه.';
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['applyface']=  'မျက်နှာ Morphing Apply';      
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">Preview ကိုပုံရိပ်</div>';
            $data['emojiname']    = 'emoji_name_arabic';
            $data['facenotfound']='မျက်နှာမတွေ့ပါ';
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['applyface']=  'Terapkan Perubahan Wajah';      
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">Pratinjau Gambar</div>';
            $data['emojiname']    = 'emoji_name';
            $data['facenotfound']='Wajah tidak ditemukan.';
        }else{
			$data['applyface']= 'Apply Face Morphing';
            $data['previewimage'] =  '<div class="heading_caption" style="font-size:20px">Preview Image</div>';
            $data['emojiname']    = 'emoji_name';
            $data['facenotfound']='Face not Found.';
		}
        
        $this->userLoged();
        $data['backword'] = 'welcome/phoCartoonEmoji/';
        $data['tmp'] = 4;
        //$gen_id = $this->uri->segment(3);
        $data['gen_id'] = '';
        $data['littledataurl']='http://littledata.in/mcom/';
        $mobnum = $this->session->userdata('mobnum');
        $data['genResult'] = $this->comic_model->finalResultCartooniest($mobnum);
        $data['result'] = $this->comic_model->phoCartoonEmoji(0);
        $this->load->view('newui/template/header_5',$data);
        $this->load->view('newui/selCartoonEmoji',$data);
    }

    function randomString($num){
        $this->userLoged();
        $string1 = "D391B035FF48EBEBC5228C051278";
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string2 = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 40; $i++) {
          $string2 .= $characters[mt_rand(0, $max)];
        }

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string3 = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
          $string3 .= $characters[mt_rand(0, $max)];
        }
        $string3 = 'm1589ac'.$string3;
        $final = $string1.'.'.$num.'.1.'.$string2.'.'.$string3;
        return $final;
    }

    public function cartooniestupload(){
        // $this->session->unset_userdata('cartooniest_img');
        // die;
        $this->userLoged();
        $fileext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
/*        $fileinfo = @getimagesize($_FILES["file"]["tmp_name"]);
        echo $width = $fileinfo[0]; echo  " : ";
        echo $height = $fileinfo[1];
        die;*/

        $fileext =   strtolower($fileext);
        if($fileext=='png' || $fileext=='PNG' || $fileext=='jpg' || $fileext=='JPG'  || $fileext=='JPEG' || $fileext=='jpeg' || $fileext=='gif' || $fileext=='gif' )
        {
            $url = "http://temp.ws.pho.to/upload.php"; // e.g. http://localhost/myuploader/upload.php // request URL
            $filename = $_FILES['file']['name'];
            $filedata = $_FILES['file']['tmp_name'];
            $filesize = $_FILES['file']['size'];
           
            /***** start add resize function for image width > 500 **/
            $imgName = "images/resize/$filename";
            list($width, $height) = getimagesize($_FILES['file']['tmp_name']);  
            if($width>65500){
                move_uploaded_file($_FILES['file']['tmp_name'],$imgName); 
                //error_reporting(E_ALL);
                $filedata= $this->resize_imagegd($filename,$imgName,250,250);
                $filesize = filesize($filedata);
            }
            /***** end add resize function for image width > 500 **/
            
            if ($filedata != '')
            {

                $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
                $postfields = array("filedata" => "@$filedata", "filename" => $filename);
                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => true,
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_INFILESIZE => $filesize,
                    CURLOPT_RETURNTRANSFER => true
                ); // cURL options

                curl_setopt_array($ch, $options);
                $response = curl_exec($ch);
                // echo $response.'<br/><br/><br/>';
                // die;
                $res1 = explode('http://',$response);
                $res2 = explode('.png',$res1[1]);
                //print_r($res2[0]);

                $res1 = explode('http://',$response);
                $res2 = 'http://'.$res1[1];
                $img = $res2;
                //$this->session->set_userdata('cartooniest_img',$img);
                //echo '{"img_link" : "'.$img.'"}';
                //echo $this->session->userdata('cartooniest_img');
                if(!curl_errno($ch))
                {
                    $info = curl_getinfo($ch);
                    if ($info['http_code'] == 200){
                        $errmsg = "File uploaded successfully";
                        $img_full = str_replace('http://s3.amazonaws.com/temp.ws.pho.to/','',$img);
                        $img_thumb = 'thumb_'.$img_full;

                        $img_thumb = 'http://s3.amazonaws.com/temp.ws.pho.to/'.$img_thumb;
                        $img_full = 'http://s3.amazonaws.com/temp.ws.pho.to/'.$img_full;
                        $num = strtotime(date('y-m-d H:i:s'));
                        //echo $num;
                        $response = $this->level22($img_full,$img_thumb,$num);

                        $name = 'cartoonist';
                        $param = 'type=-1;cartoon=1';
                        $emoji_name = 'Cartoon effect';

                        $session_id = $this->randomString($num);
                        $data = '<image_process_call>
                            <image_url>'.$img.'</image_url>
                            <methods_list>
                                <method>
                                    <name>'.$name.'</name>
                                    <params>'.$param.'</params>
                                </method>
                            </methods_list>
                            <result_size>1600</result_size>
                            <result_quality>90</result_quality>
                            <thumb1_size>82</thumb1_size>
                            <thumb1_quality>85</thumb1_quality>
                            <template_watermark>false</template_watermark>
                            <limited_image_size>700</limited_image_size>
                            <lang>en</lang>
                        </image_process_call>';

                        $app_id = '9f6b07b2e3d00336b528c4a61de74f3d';

                        $key = 'cae5267f7fa0c495813a83f3ce2dfc08';
                        $sign_data = hash_hmac('sha1', $data, $key);

                        $request_id = $this->level33($app_id,$data,$sign_data,$session_id);
                        $last_response = $this->level44($request_id,$num);

                            //echo $last_response;
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                        $output_dir = "uploadPhoCartoon/";
                        $new_name = "file".date('m-d-Y-His').'.'.$ext;
                        if($width>65500){
                            
                            if( !copy($filedata, $output_dir.$new_name) ) { 
                                $uploadmsg = 'failed';
                            } 
                            else { 
                                $uploadmsg = 'sucess';
                            } 
                           /*  $moved = move_uploaded_file($filedata,$output_dir.$new_name); //new 6april2021
                            if($moved)
                            {
                                $uploadmsg = 'sucess'; 
                            }
                            else
                            {
                                $uploadmsg = 'failed';
                            } */
                        }else{
                            $uploadmsg = 2;
                            move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$new_name); //prev 6april2021
                        }
                        //move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$new_name); //prev 6april2021
                        
                        ///////////////dushyant 10april 2019 face not found issue////
                        $img_full="http://littledata.in/mcom/".$output_dir.$new_name;
                        ///////////////dushyant 10april 2019 face not found issue////
                        $mobnum = $this->session->userdata('mobnum');
                        $insert_id = $this->comic_model->saveCartooniest($mobnum,$new_name,$emoji_name,$name,$param,$img_full,$img_thumb,$num,$app_id,$key,$data,$sign_data,$request_id);    

                        $genrated_id = $insert_id;

                        $res = 'true';
                        $errmsg = "File uploaded successfully";

                        echo '{"response":"'.$res.'","message":"'.$errmsg.'","genrated_id":"'.$genrated_id.'","request_id" : "'.$request_id.'","num" : "'.$num.'","filename":"'.$new_name.'","uploadmsg":"'.$uploadmsg.'"}';
                    }
                }
                else
                {
                    $res = 'false';
                    $img = '';
                    $errmsg = curl_error($ch);
                    //$errmsg = "An Error occurs , Please try later";
                    $genrated_id = '';
                    echo '{"response":"'.$res.'","message":"'.$errmsg.'","genrated_id":"'.$genrated_id.'","request_id" : "'.$request_id.'","num" : "'.$num.'","filename":"'.$new_name.'"}';
                }
                curl_close($ch);

            }else
                {
                    $res = 'false';
                    $errmsg = "Please select the file";
                    $genrated_id = '';
                    $request_id = '';
                    $num = '';
                    $new_name = '';
                    echo '{"response":"'.$res.'","message":"'.$errmsg.'","genrated_id":"'.$genrated_id.'","request_id" : "'.$request_id.'","num" : "'.$num.'","filename":"'.$new_name.'"}';
            }
        }else{
            echo '{"response":"error","message":"Please select only png, jpg, jpeg or gif image formats."}';
        }
    }

    public function level22($img_full,$img_thumb,$num) {

        $postUrl = 'https://cartoon.pho.to/userJsonStorageManager.php';
        $requestJson = 'id=uploaded&json[]='.$img_full.'&json[]='.$img_thumb.'&json[]='.$num;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$postUrl);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$requestJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function level33($app_id,$data,$sign_data,$session_id) {
        //$num = strtotime(date('y-m-d H:i:s'));
        
            $postUrl = "https://cartoon.pho.to/opeapi-ws-proxy/queue_url.php?service_id=3";
            $requestJson = "app_id=$app_id&data=$data&sign_data=$sign_data&session_id=$session_id";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$postUrl);
            //curl_setopt($ch,CURLOPT_POST,$postlength);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$requestJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
			//print_r($requestJson);
			//print_r($response);
            $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            $request_id = $xml->request_id;
            return $request_id;
    }

    function level44($request_id,$num)
    {
        $this->userLoged();
        $url="https://opeapi.ws.pho.to/get-result.php?request_id=$request_id&_=$num";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if(!curl_errno($ch))
        {
            $data = $data;
        }else
        {
            $data = curl_error($ch);
        }
        curl_close($ch);
        $xml=simplexml_load_string($data) or die("Error: Cannot create object");
        $status         = $xml->status;
        $description    = $xml->description;
        //echo '{"status" : "'.$status.'","url" : "https://pheuture.com/mcomics/pho_to/level5.php?a='.$request_id.'&b='.$num.'" , "desc" : "'.$description.'"}'; 
        return $data;
    }

    function level55()
    {
        $this->userLoged();
        $request_id = $this->input->post('request_id');
        $num        = $this->input->post('num');
        $url="https://opeapi.ws.pho.to/get-result.php?request_id=$request_id&_=$num";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if(!curl_errno($ch))
        {
            $data = $data;
        }else
        {
            $data = curl_error($ch);
        }
        curl_close($ch);
        $xml=simplexml_load_string($data) or die("Error: Cannot create object");
        $status         = $xml->status;
        $description    = $xml->description;
        //echo '{"status" : "'.$status.'","url" : "https://pheuture.com/mcomics/pho_to/level5.php?a='.$request_id.'&b='.$num.'" , "desc" : "'.$description.'"}'; 
        //return $data;
        echo '{"status" : "'.$status.'","desc" : "'.$description.'"}'; 
        return $data;
    }


    public function getFinalResult2() {
        //$gen_id = $this->uri->segment(3);
        $this->userLoged();
        $gen_id = $this->input->post('genid');
        $data['gen_id'] = $gen_id;
        $mobnum = $this->session->userdata('mobnum');
        $result = $this->comic_model->finalResultCartoon($mobnum,$gen_id);
        //print_r($result);
        $request_id = $result['request_id'];
        $num = $result['rand_num'];

        if($result['final_img']=='' && $result['final_img_thumb']==''){
            $response = $this->level44($request_id,$num);

            $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            $fimg =  $xml->result_url;
            $simg =  $xml->thumb1_url;

            $fimg = $this->saveCartoonImg2($fimg);
            $simg = $this->saveCartoonImg2($simg);
            //$categoriesresult = file_get_contents($fimg);    
            //file_put_contents(base_url()."uploadPhoCartoon/$fimg", $categoriesresult);
            $this->comic_model->updateCartoon($fimg,$simg,$gen_id);

            echo '{"message":"true","fimg":"'.$fimg.'","simg":"'.$simg.'","request_id":"'.$request_id.'","num":"'.$num.'"}';
        }else if($result['final_img']!='' && $result['final_img_thumb']!='') {
            $fimg =  $result['final_img'];
            $simg =  $result['final_img_thumb'];
            echo '{"message":"true","fimg":"'.$fimg.'","simg":"'.$simg.'","request_id":"'.$request_id.'","num":"'.$num.'}';
        }else{
            echo '{"message":"false"}';
        }
    }

    function saveCartoonImg2($imgPath){
        $this->userLoged();
        //echo $imgPath;
        $categoriesresult = file_get_contents($imgPath); 
        //$fimg  = 'http://worker-images.ws.pho.to/i1/A154BC5E-E2F3-48D7-A24D-15C66140D8B4.jpg';
        $imgPath = basename($imgPath);
        // echo $fimg = str_replace('http://worker-images.ws.pho.to/i1/','',$fimg);   
        file_put_contents("/var/www/pheuture/pheuture/mcomics/uploadPhoCartoon/$imgPath", $categoriesresult);
        return $imgPath;
    }

    
    public function level3333($name,$param,$emoji_name,$num) {
        //$num = strtotime(date('y-m-d H:i:s'));
        $session_id = $this->randomString($num);

        $mobnum = $this->session->userdata('mobnum');
        $cartooniest_details = $this->comic_model->finalResultCartooniest($mobnum);
        $img = $cartooniest_details['img_url'];

            $data = '<image_process_call>
                <image_url>'.$img.'</image_url>
                <methods_list>
                    <method>
                        <name>'.$name.'</name>
                        <params>'.$param.'</params>
                    </method>
                </methods_list>
                <result_size>1600</result_size>
                <result_quality>90</result_quality>
                <thumb1_size>82</thumb1_size>
                <thumb1_quality>85</thumb1_quality>
                <template_watermark>false</template_watermark>
                <limited_image_size>700</limited_image_size>
                <lang>en</lang>
            </image_process_call>';

            $app_id = '9f6b07b2e3d00336b528c4a61de74f3d';

            $key = 'cae5267f7fa0c495813a83f3ce2dfc08';
            $sign_data = hash_hmac('sha1', $data, $key);
            $postUrl = "https://cartoon.pho.to/opeapi-ws-proxy/queue_url.php?service_id=3";
            $requestJson = "app_id=$app_id&data=$data&sign_data=$sign_data&session_id=$session_id";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$postUrl);
            //curl_setopt($ch,CURLOPT_POST,$postlength);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$requestJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            $status = $xml->status;
            $request_id = $xml->request_id;
            
            // $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            // $request_id = $xml->request_id;

            $new_name   = $cartooniest_details['uploaded_img'];
            $img_full   = $img;
            $img_thumb  = $img;
            $num        = $num;

            $insert_id = $this->comic_model->saveCartooniest($mobnum,$new_name,$emoji_name,$name,$param,$img_full,$img_thumb,$num,$app_id,$key,$data,$sign_data,$request_id);    


            $genrated_id = $insert_id;

            echo '{"status":"'.$status.'" , "request_id" : "'.$request_id.'" , "num" : "'.$num.'" , "genid" : "'.$genrated_id.'"}';
    }


    function level555()
    {
        $this->userLoged();
        $request_id = $this->input->post('request_id');
        $num        = $this->input->post('num');
        $genid        = $this->input->post('genid');
        $url="https://cartoon.pho.to/opeapi-ws-proxy/get-result.php?request_id=$request_id&_=$num";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if(!curl_errno($ch))
        {
            $data = $data;
        }else
        {
            $data = curl_error($ch);
        }

        curl_close($ch);
        $xml=simplexml_load_string($data) or die("Error: Cannot create object");
        $status         = $xml->status;
        $description    = $xml->description;

        if(strtolower($status)=='ok'){
            $fimg =  $xml->result_url;
            $simg =  $xml->thumb1_url;

            $fimg = $this->saveCartoonImg2($fimg);
            $simg = $this->saveCartoonImg2($simg);
            $this->comic_model->updateCartoon($fimg,$simg,$genid);

            
        }else{
            $status = $xml->status;
            $fimg =  '';
            $simg =  '';
        }

        echo '{"status" : "'.$status.'","fimg":"'.$fimg.'","simg":"'.$simg.'","request_id":"'.$request_id.'","num":"'.$num.'","description":"'.$description.'"}';

        // echo '{"status" : "'.$status.'","url" : "https://pheuture.com/mcomics/pho_to/level5.php?a='.$request_id.'&b='.$num.'" , "desc" : "'.$description.'"}'; 
        return $data;
    }
    

    public function editimage(){
        $name = $this->input->post('name');
        $param = $this->input->post('param');
        $emoji_name = $this->input->post('emoji_name');
        $num = strtotime(date('y-m-d H:i:s'));
        $request_id = $this->level3333($name,$param,$emoji_name,$num);
    }

    public function saveEmojiCartoon() {
        $this->userLoged();
        $data['backword'] = '#';
        $data['tmp'] = 3;
        $mobnum = $this->session->userdata('mobnum');
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->comic_model->confrimTosaveEmojiCartoon($mobnum);
        $data['result'] = $this->comic_model->finalResultCartooniest($mobnum);
        $this->load->view('newui/template/header_5',$data);
        $this->load->view('newui/saveCartoonEmoji',$data);
    }

    public function viewEmojiEffect() {
        $this->userLoged();
        $mobnum = $this->session->userdata('mobnum');
		if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['view_Cartoon']="කාටූන් බලන්න";
            $data['view_Comics']="කොමික්ස් බලන්න";
            $data['view_background']="පසුබිම් බලපෑම";
			$data['mycartoons'] = '<div class="heading_caption" style="font-size:20px; color:#000;">මගේ කාටූන්</div>'; 
			$data['userlanguage']= 'SINHALESE';      
			$data['yettocreate']= 'එහෙත් ඔබ කිසිදු ඉමොජි ආචරණයක් නිර්මාණය කර නැත.';       
		}else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['view_Cartoon']="কার্টুন দেখুন";
            $data['view_Comics']="কমিক্স দেখুন";
            $data['view_background']="পটভূমি প্রভাব";
			$data['mycartoons'] =  '<div class="heading_caption" style="font-size:20px; color:#000;">আমার কার্টুন</div>';    
			$data['userlanguage']= 'BANGLA';       
			$data['yettocreate']= 'তবুও আপনি কোনও ইমোজি ইফেক্ট তৈরি করেন নি।'; 
		}else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['view_Cartoon']="Voir le dessin animé";
            $data['view_Comics']="Voir les bandes dessinées";
            $data['view_background']="Effet de fond";
			$data['mycartoons'] =  '<div class="heading_caption" style="font-size:20px; color:#000;">Mes dessins animés</div>'; 
			$data['userlanguage']= 'FRENCH';          
			$data['yettocreate']= "Pourtant, vous n'avez créé aucun effet Emoji."; 
		}else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['view_Cartoon']="عرض الكرتون";
            $data['view_Comics']="عرض الكاريكاتير";
            $data['view_background']="تأثير الخلفية";
            $data['mycartoons'] =  '<div class="heading_caption" style="font-size:20px; color:#000;">الرسوم الكاريكاتورية الخاصة بي</div>'; 
            $data['userlanguage']= 'ARABIC';        
            $data['yettocreate']= 'حتى الآن لم تقم بإنشاء أي تأثير رموز تعبيرية.';   
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['view_Cartoon']="ကြည့်ရန်ကာတွန်း";
            $data['view_Comics']="ကြည့်ရန်ရုပ်ပြ";
            $data['view_background']="နောက်ခံ Effect";
            $data['mycartoons'] =  '<div class="heading_caption" style="font-size:20px; color:#000;">ကာတွန်း</div>'; 
            $data['userlanguage']= 'BURMESE';  
            $data['yettocreate']= 'သို့သျောလညျးသငျသညျမဆို Emoji သက်ရောက်မှုကိုဖန်တီးကြပြီမဟုတ်။';         
        }else if($this->session->userdata('userlanguage') == 'BAHASA'){
            $data['view_Cartoon']="Lihat Kartun";
            $data['view_Comics']="Lihat Komik";
            $data['view_background']="Efek Latar Belakang";
            $data['mycartoons'] =  '<div class="heading_caption" style="font-size:20px; color:#000;">Kartun saya</div>'; 
            $data['userlanguage']= 'BAHASA';  
            $data['yettocreate']= 'Namun Anda belum membuat efek emoji apa pun.';         
        }else{
            $data['view_Cartoon']="View Cartoon";
            $data['view_Comics']="View Comics";
            $data['view_background']="Background Effect";
			$data['mycartoons'] = '<div class="heading_caption" style="font-size:20px; color:#000;">My Cartoons</div>';
			$data['userlanguage']= 'ENGLISH';
			$data['yettocreate']= 'Yet you have not created any emoji effect.'; 
		}

        $data['result'] = $this->comic_model->viewAllEmojiEffect($mobnum);
        $data['backword'] = 'index';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->load->view('newui/template/header_2', $data);
        $this->load->view('newui/viewEmojiEffect');
        $this->load->view('newui/template/footer_2');
        $data['tabstatus'] = '2';
        $data['activeTab'] = $this->uri->segment(1);
        if(substr($mobnum,0,3)=="880")    //robi
        {
            $this->load->view('newui/template/fixedFooterMenuTab_robi', $data);
        }else{
            $this->load->view('newui/template/fixedFooterMenuTab', $data);
        }
    }

    public function removePhoto(){
        $this->userLoged();
        //echo '{"sds":"sdfsfs"}';
        $genid = $this->input->post('content');
        $action = $this->input->post('action');
        // $data['back'] = $this->input->post('back');
        $mobnum = $this->session->userdata('mobnum');
        $this->comic_model->removePhoto($mobnum,$genid,$action);
        // $this->load->view('popupPhotoEffect',$data);
    }


    //////////////////////////dushyant ////////////////


    public function get_MDN() {
        $mobnum='';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) {
            $mobnum=$_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_X_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_X_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_X_SUB_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_X_SUB_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_X_WAP_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_X_WAP_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_X_NOKIA_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_X_NOKIA_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_X_DU_MSISDN'])) {
            $mobnum=$_SERVER['HTTP_X_DU_MSISDN'];
            $mdn=$mobnum;
            }
        else if(isset($_SERVER['HTTP_CLI'])) {
            $mobnum=$_SERVER['HTTP_CLI'];
            $mdn=$mobnum;
            }
        if(!isset($mdn))
            $mdn='1111111111';
        $this->session->set_userdata('mobnum',$mobnum);
        return $mdn;
    }

    /***********************************Country***************************/

    public function mlogin(){
        $mobnum ="";$mobnum2 ="";$slcode="";
        if($this->session->userdata('mobnum') == '' || $this->session->userdata('mobnum') == '1111111111'){
            $mdn = $this->get_MDN();
        }else{
            $mdn = $this->session->userdata('mobnum');
        }
        
        $id              = $this->session->userdata('mobnum');
        $data['id']      = $id;
        if($mdn=='1111111111'){
            if ($_SERVER['HTTP_HOST']=='vk.mcomics.club') {
                //redirect("vkuwait_failmsg");die;
                redirect(base_url().'subpackKW');die;
            }
            if ($_SERVER['HTTP_HOST']=='zj.mcomics.club' || $_SERVER['HTTP_HOST']=='zj.mooddiit.com') {
                redirect(base_url().'subscribe_ZJ');die;
            }
            if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
                redirect(base_url().'sd_sub');die;
            }
            if ($_SERVER['HTTP_HOST']=='mtoonapp.com') {
                redirect(base_url().'pal_sub');die;
            }
            if ($_SERVER['HTTP_HOST']=='u.mtoonapp.com') {
                redirect(base_url().'aue_sub');die;
            }
            if ($_SERVER['HTTP_HOST']=='eg.mcomics.club') {
                redirect(base_url().'egy_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='mc.mcomics.club') {
                redirect(base_url().'ma_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='ku.mcomics.club') {
                redirect(base_url().'ku_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='q.mcomics.club') {
                redirect(base_url().'qat_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='k.mcomics.club') {
                redirect(base_url().'ksa_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='ke.mcomics.club') {
                redirect(base_url().'ken_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='j.mcomics.club') {
                redirect(base_url().'jor_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='br.mcomics.club') {
                redirect(base_url().'bah_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='bz.mcomics.club') {
                redirect(base_url().'bah_subs_zain');die;
            }
            if ($_SERVER['HTTP_HOST']=='bb.mcomics.club') {
                redirect(base_url().'bbah_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='p.mcomics.club') {
                redirect(base_url().'pal_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='o.mcomics.club') {
                redirect(base_url().'om_subs');die;
            }
            if ($_SERVER['HTTP_HOST']=='sa.mcomics.club') {
                redirect(base_url().'mtnsa_wp_subs');die;
            }
            if($_SERVER['HTTP_X_VC_ACR']){
                redirect(base_url()."vodacom_sa");
            }
            $data['heading'] ="mComics";
            $data['content'] = 'country';
            $this->load->view('newui/template/header_auth');
            $data['country_list'] = $this->comic_model->country_list();
            $this->load->view('newui/signup_du',$data);
        }
        else{
            if ($_SERVER['HTTP_HOST']=='j.mcomics.club') {
                redirect(base_url().'jor_subs');die;
            }
            if($_SERVER['HTTP_X_VC_ACR']){
                redirect(base_url()."vodacom_sa");die;
            }
            if ($_SERVER['HTTP_HOST']=='sa.mcomics.club') {
                redirect(base_url().'mtnsa_wp_subs');die;
            }
            $mobnum     = $mdn;
            $slcode     = substr($mobnum,0,2);
            $mobnum1    = substr($mobnum,-9);
            $code       = substr($mobnum,2,2);

            $HTTP_USER_AGENT    = $_SERVER['HTTP_USER_AGENT'];
            $REMOTE_ADDR        = $_SERVER['REMOTE_ADDR'];
            $QUERY_STRING       = $_SERVER['QUERY_STRING'];
            $page               = $_SERVER['REQUEST_URI'];
            $slcode=substr($mobnum,0,2);
            $code = substr($mobnum,2,2);
            $mtnngcode =  substr($mobnum,0,6);
            if(substr($mobnum,0,3)=="971")  //dubai
            {
                $eti_series = substr($mobnum,0,5);
                if($eti_series=="97150" || $eti_series=="97154" || $eti_series=="97155" || $eti_series=="97156" || $eti_series=="97158"){
                    $result = $this->subscribe_model->checkmComics_etiuaeuser($mobnum);
                }
                else{
                    $result = $this->subscribe_model->checkmComics_user($mobnum);
                }               
            }//else if(substr($mobnum,0,5)=="88019")    //bg
            else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink 
            {
            
                $result = $this->subscribe_model->checkmComics_bguser($mobnum);
            }else if(substr($mobnum,0,3)=="880")    //robi
            {
              
                $result = $this->subscribe_model->checkmComics_robiuser($mobnum);
            }else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803"  || $mtnngcode=="234806" || $mtnngcode=="234810" || $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")    //mtn nigeria
            {
                $result = $this->subscribe_model->check_mtnnga_user($mobnum);
            }else  if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
                 $result = $this->subscribe_model->checkmComics_dialogSlMcomics($mobnum);
            }else if(substr($mobnum,0,3)=="234")    //airtel nigeria
            {
                $result = $this->subscribe_model->checkmComics_airtelngriauser($mobnum);
            }else if($slcode=="94"&&$code=="75")    //airtel SRILANKA
            {
                $result = $this->subscribe_model->checkmComics_airtelsluser($mobnum);
            }
            else if(substr($mobnum,0,3)=="964")    //zain iraq
            {
                $result = $this->subscribe_model->check_ZIuser($mobnum);
            }else if(substr($mobnum,0,3)=="965")    //vivo kuwait
            {
                $result = $this->subscribe_model->check_vivoKuwaituser($mobnum);
            }else if(substr($mobnum,0,3)=="962")    //vivo kuwait
            {
                $result = $this->subscribe_model->check_zainJordanuser($mobnum);
            }else if($mobnum=="919871666000")    //india
            {
                $result = array('mobnum' => '919871666000');
            }else if($mobnum == '919873032445'){
                $result = array('mobnum' => '919873032445');
            }else if($mobnum == '919718698337'){
                $result = array('mobnum' => '919718698337');
            }//else if(substr($mobnum,0,3)=="947")    //srilanka dialog
            else if(strlen($mobnum)>13)
            {
                $result = $this->subscribe_model->checkmComics_sridialoguser_new($mobnum);
            }else{
                $result = array();
            }

            if(count($result)>0)
            {
                if(substr($mobnum,0,3)=="971")  //dubai
                {
                    $res=$this->subscribe_model->checkEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->user_detail($mobnum);
                    }
                }//else if(substr($mobnum,0,5)=="88019")    //bg
                else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink 
                {
               
                    $res=$this->subscribe_model->bgcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->bguser_detail($mobnum);
                    }
                }else if(substr($mobnum,0,3)=="880")    //robi
                {
               
                    $res=$this->subscribe_model->robicheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->robiuser_detail($mobnum);
                    }
                }else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803" || $mtnngcode=="234806" || $mtnngcode=="234810" ||  $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")
                {
                    $res=$this->subscribe_model->mtnngcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->mtnnguser_detail($mobnum);
                    }
                }else if(substr($mobnum,0,3)=="234")    //airtel nigeria
                {
                    $res=$this->subscribe_model->airtelngriacheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->airtelngriauser_detail($mobnum);
                    }
                }else if($slcode=="94"&&$code=="75"){   //srilanka airtel
                    $res=$this->subscribe_model->airtelslcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->airtelsluser_detail($mobnum);
                    }
                }                    
                else if(substr($mobnum,0,3)=="964")    //zain iraq
                {
                    $res=$this->subscribe_model->zainiraqcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->zainiraquser_detail($mobnum);
                    }
                }else if(substr($mobnum,0,3)=="965")    //vivo kuwait
                {
                    $res=$this->subscribe_model->vivokuwaitcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->vivokuwaituser_detail($mobnum);
                    }
                }else if(substr($mobnum,0,3)=="962")    //zain jordan
                {
                    $res=$this->subscribe_model->zainjordancheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->zainjordanuser_detail($mobnum);
                    }
                }else if($mobnum=="919871666000"  or $mobnum == '919873032445'  or $mobnum == '919718698337')    //india
                {
                    $res=$this->subscribe_model->robicheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->robiuser_detail($mobnum);
                    }
                }
                //else if(substr($mobnum,0,3)=="947")    //srilanka dialog
                else if(strlen($mobnum)>13)
                {
                    $res=$this->subscribe_model->sridialogcheckEmailExists($mobnum);
                    if ($res=="1") {
                        $this->subscribe_model->sridialoguser_detail($mobnum);
                    }
                }
               
                //$this->session->userdata('subuser') == 'yes';
                $this->session->set_userdata('subuser','yes');
                //redirect('/index');
                /* New Redirection(13-04-2019) */
                redirect(base_url());
            }
            else{   
               // echo $mobnum;             
                if(substr($mobnum,0,3)=="971"){         //dubai
                    $eti_series = substr($mobnum,0,5);
                    if($eti_series=="97150" || $eti_series=="97154" || $eti_series=="97155" || $eti_series=="97156" || $eti_series=="97158"){
                        $chk_eti_uae_user = $this->subscribe_model->checkmComics_etiuaeuser($mobnum);
                        if(count($chk_eti_uae_user)>0){
                            $this->session->set_userdata('subuser','yes');
                            //redirect('/index');
                            redirect(base_url());
                        }
                        else{
                            redirect(base_url().'subscribe_eti_uae');
                        }
                    }
                    else{
                        redirect(base_url().'subPack_dubai');
                    }
                }//else if(substr($mobnum,0,5)=="88019"){    //bg banglalink
                else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink 
                {
                
                    redirect(base_url().'subPack_bg');
                }
                else if(substr($mobnum,0,3)=="880"){    //robi
                
                    redirect(base_url().'subPack_robi');
                }else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803"  || $mtnngcode=="234806" || $mtnngcode=="234810" || $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906") // mtn nigeria
                {
                    redirect(base_url().'subscribe_mtnnga');
                }else  if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
                    redirect(base_url().'sd_sub');
                }else if(substr($mobnum,0,3)=="234"){    //airtel nigeria
                    redirect(base_url().'subPack_airtelngria');
                }else if($slcode=="94"&&$code=="75"){   //srilanka airtel
                    redirect(base_url().'subPack_airtelsl');
                }
                else if(substr($mobnum,0,3)=="964"){    //zain iraq
                    redirect(base_url().'getMDNZI');
                }else if(substr($mobnum,0,3)=="965"){    //vivo kuwait
                    //redirect(base_url().'subPack_vivokuwait');
                    redirect(base_url().'subpackKW');
                }else if(substr($mobnum,0,3)=="962"){    //zain jordan
                    redirect(base_url().'subpackZJ');
                }
                //else if(substr($mobnum,0,3)=="947"){    //srilanka dialog
                else if(strlen($mobnum)>13){
                    redirect(base_url().'subPack_sridialog');
                }else{
                    //redirect(base_url().'subPack_dubai');
                    //redirect(base_url().'auth');
                    //redirect(base_url().'subPack_bg');
                    redirect(base_url());
                }
            }
        }
    }

    public function callCG_mcomic(){
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $subtype;
        $ran = rand(11111,99999);
         if($mdn=='1111111111' or $mdn==''){
           // redirect('/index');
            redirect(base_url());
        }
        else{
            //$this->info_model->du_insert_subtype($mobnum,$subtype);
             $this->db->query("insert into dubai_mcomics.mobnum_subtype(mobnum,subtype,date) values('$mdn','$subtype',now())");

             if($subtype=="weekly"){
               //echo "monthly$subtype";
                //var_dump($subtype);
               // redirect("http://182.74.46.88:8093/API/CGRequest?MSISDN=".substr($mdn,-9)."&productID=9001&pPrice=700&pVal=7&CpId=PHEUT_2&CpPwd=PHEUT@2&CpName=PHEUTRE&reqMode=WAP&reqType=Subscription&ismID=17&transID=".$ran."&Wap_mdata=%23F0F8FF|http%3a%2f%2f111.118.180.237%2fdubai_du%2fmcomics%2fimage-icon.png|NA|NA|150X150");  
                $url = file_get_contents("http://158.69.203.108/du/enccom.php?mdn=$mdn&price=700&val=7&tid=$ran");
                redirect($url);
            }
            else if($subtype=="daily"){
                //  echo "daily$subtype";
                //redirect("http://182.74.46.88:8093/API/CGRequest?MSISDN=".substr($mdn,-9)."&productID=9001&pPrice=200&pVal=1&CpId=PHEUT_2&CpPwd=PHEUT@2&CpName=PHEUTRE&reqMode=WAP&reqType=Subscription&ismID=17&transID=".$ran."&Wap_mdata=%23F0F8FF|http%3a%2f%2f111.118.180.237%2fdubai_du%2fmcomics%2fimage-icon.png|NA|NA|150X150"); 

                $url = file_get_contents("http://158.69.203.108/du/enccom.php?mdn=$mdn&price=200&val=1&tid=$ran");
                redirect($url); 
            }
            else if($subtype=="dailyft"){
                //  echo "ft$subtype";
                //redirect("http://182.74.46.88:8093/API/CGRequest?MSISDN=".substr($mdn,-9)."&productID=9001&pPrice=0&pVal=1&CpId=PHEUT_2&CpPwd=PHEUT@2&CpName=PHEUTRE&reqMode=WAP&reqType=Subscription&ismID=17&transID=".$ran."&Wap_mdata=%23F0F8FF|http%3a%2f%2f111.118.180.237%2fdubai_du%2fmcomics%2fimage-icon.png|NA|NA|150X150"); 

                $url = file_get_contents("http://158.69.203.108/du/enccom.php?mdn=$mdn&price=0&val=1&tid=$ran");
                redirect($url);   
            }
            else{
                echo "none$subtype";
                redirect(base_url()."subPack_dubai");
            }
        }
    }

    /* public function callCG_robimcomic(){
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $subtype;
        $ran = rand(11111,99999);
         if($mdn=='1111111111' or $mdn==''){
            redirect('/index');
        }
        else{
            
            if($subtype=="daily"){
               // $url = file_get_contents("http://158.69.203.108/du/enccom.php?mdn=$mdn&price=200&val=1&tid=$ran");
                redirect($url); 
            }           
            else{
                echo "none$subtype";
                redirect(base_url()."subPack_robi");
            }
        }
    }
*/
    public function callCG_robimcomic(){
        
        $mdn=$this->session->userdata('mobnum');
        $mobnum= $this->get_MDN();
        if($mobnum=="1111111111"){
            $mobnum="";
        }
        $chkDND = $this->subscribe_model->check_blocked_robi_1($mobnum);
        if($chkDND==0){
            $package= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            //$data['redirect_url']= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            

            date_default_timezone_set("Asia/Kolkata");

            $nonce = rand(1111111,9999999).rand(111111,9999999);
            $created = date('Y-m-d\TH:i:s\Z');
            //$password = "Robi1234";
            $password = 'p|-|eUI$tD0_o72O2O';

            //$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);
            $transactionId = date("YmdHis").rand(1111,9999).rand(111,999);

            $hash = $nonce . $created . $password;
            //$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));
            $password_coded = base64_encode(hash( 'sha256', $hash ));

            /*$nonce = "4758958672854";
        $created = "2021-03-18T18:31:37Z";
        $password = 'p|-|eUI$tD0_o72O2O';
        $transactionId = "202103181831376779618";
        $hash = $nonce . $created . $password;
        $password_coded = base64_encode(hash( 'sha256', $hash ));
echo $password_coded;die;*/

            if($package=="regular"){
                //$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");
                $url = "https://d-consent.robi.com.bd/Robi/asecure?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");
            }
            else{
                redirect(base_url()."subPack_robi");
            }

            $this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

            redirect($url);
            //header("Location: ".$url);
            die();
        }
        else{
            echo "DND_BLOCKED";die;
        }
    }



    public function callCG_robimcomic_test(){
        
        $mobnum='8801878866878';
        
        date_default_timezone_set("Asia/Kolkata");

        $nonce = rand(1111111,9999999).rand(111111,9999999);
        $created = date('Y-m-d\TH:i:s\Z');
        $password = "Robi1234";

        $transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


        $hash = $nonce . $created . $password;
        $password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));

        $url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");
        echo $url;
        //redirect($url);
        die();
    }
/*
    public function callCG_robimcomic(){
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $chkDND = $this->subscribe_model->check_blocked_robi_1($mobnum);
        if($chkDND==0){
            $package= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $substatus = $this->subscribe_model->subscribe_robi($mdn,$package);
            if($substatus=="SUBSCRIBED"){
                $data['msg'] = "Dear customer, Your subscription is under process.<br>Please complete your subscription via USSD.And then Wait for 1 Minute";
                $this->load->view('template/header_auth');
                $this->load->view('unsubmessage',$data);
            }
            elseif($substatus=="EXISTING"){
                $this->session->set_userdata('subuser','yes');
                redirect(base_url());
            }
            else{
                $data['msg'] = "Dear customer, Your subscription is failed.Please Try Later.";
                $this->load->view('template/header_auth');
                $this->load->view('unsubmessage',$data);
            }
        }
        else{
            echo "DND_BLOCKED";die;
        }
    }
*/
    public function robi_dc_lp(){
        $resultCode = isset($_GET['resultCode']) ? $_GET['resultCode'] : NULL;
        $msisdn = isset($_GET['msisdn']) ? $_GET['msisdn'] : NULL;
        $d = $_GET;
        $this->db->query("insert into robi_mcomics.cg_response(data,date) values('".serialize($d)."',now())");
        if(strlen($msisdn)==13){
            $mobnum = $msisdn;
        }
        else{
            if($this->session->userdata('mobnum') == '' || $this->session->userdata('mobnum') == '1111111111'){
                $mobnum = $this->get_MDN();
            }else{
                $mobnum = $this->session->userdata('mobnum');
            }
        }
        $data['littledataurl']='http://littledata.in/mcom/';
        $query = $this->db->query("select * from robi_mcomics.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        if($query->num_rows()>0 && $resultCode==0){
            $this->session->set_userdata('subuser','yes');
            $this->session->set_userdata('mobnum',$mobnum);
            //redirect('/index');
            redirect(base_url());
        }
        elseif($resultCode==4){
            $data['msg'] ="Insufficient Balance";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
        elseif($resultCode==-1){
            $data['msg'] ="You have not opted for the product.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
        elseif($resultCode==6){
            $this->session->set_userdata('subuser','yes');
            //redirect('/index');
            redirect(base_url());
        }
        else{
            $data['msg'] ="Failure. Please try after sometime.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
    }


    /**************Robi Camp****************/
    public function robi_consent_camp(){
        
        $mobnum=$this->get_MDN();
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;

        $this->db->query("insert into robi_mcomics.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$mobnum','$QUERY_STRING','$REMOTE_ADDR','$HTTP_USER_AGENT',now(),'internal','$referrer');");
        $hid = $this->db->insert_id();
        if($mobnum=="1111111111"){
            $redirectUrl="http://mcomics.club/welcome/robi_camp_int?Msisdn=2222222222&Response=FAIL";
            redirect($redirectUrl);die;
        }

        $query = $this->db->query("select * from robi_mcomics.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        if($query->num_rows()>0){
            $redirectUrl="http://mcomics.club/welcome/robi_camp_int?Msisdn=$mobnum&Response=SUCCESS";
            redirect($redirectUrl);die;
        }

        $chkDND = $this->subscribe_model->check_blocked_robi_1($mobnum);
        if($chkDND==0){
            $package= "premium";        

            date_default_timezone_set("Asia/Kolkata");

            $nonce = rand(1111111,9999999).rand(111111,9999999);
            $created = date('Y-m-d\TH:i:s\Z');
            $password = 'p|-|eUI$tD0_o72O2O';

            //$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);
            $transactionId = date("YmdHis").rand(1111,9999).rand(111,999);

            $hash = $nonce . $created . $password;
            $password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));

            if($package=="regular"){
                $url = "https://d-consent.robi.com.bd/Robi/asecure?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dclp");
            }
            else{
                $url = "https://d-consent.robi.com.bd/Robi/asecure?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dclp");
            }
            $this->db->query("insert into robi_mcomics.tokenlogs(mdn,token,adnet,ip,date) values('$mobnum','$transactionId','internal','".$_SERVER['REMOTE_ADDR']."',now())");
            $this->db->query("insert into robi_mcomics.cg_request_camp(data,date) values('".serialize($url)."',now())");
            $this->db->query("insert into robi_mcomics.reporo_charge(mdn,status,date,adnet,hid) values('$mobnum','',now(),'internal',$hid);");

            redirect($url);
            //header("Location: ".$url);
            die();
        }
        else{
            echo "blocked";
        }
    }


    public function robi_dclp(){
        $resultCode = $_GET['resultCode'];
        $tid = $_GET['transactionId'];
        $cnfmResult = $_GET['cnfmResult'];
        $isDoubleConfrim = $_GET['isDoubleConfrim'];
        $d = $_GET;
        $this->db->query("insert into robi_mcomics.cg_response_camp(data,date) values('".serialize($d)."',now())");
        $this->db->query("insert into robi_mcomics.consent_res_logs(cnfmResult,resultCode,isDoubleConfrim,transactionId,date) values('$cnfmResult','$resultCode','$isDoubleConfrim','$tid',now())");

        $getmdn = $this->db->query("select * from robi_mcomics.tokenlogs where token='$tid' order by id desc limit 1");
        $mobnum='';
        if($getmdn->num_rows()>0){
            $mobnum = $getmdn->row()->mdn;
        }

        if($resultCode==0){
            $redirectUrl="http://mcomics.club/welcome/robi_camp_int?Msisdn=$mobnum&Response=SUCCESS";
            redirect($redirectUrl);die;
        }
        else{
            $redirectUrl="http://mcomics.club/welcome/robi_camp_int?Msisdn=$mobnum&Response=FAIL";
            redirect($redirectUrl);die;
        }
    }

    public function robi_camp_int(){
        //print_r($_GET);die;
        $status = $this->input->get_post('Response');
        if($status=='SUCCESS'){
            redirect("http://mcomics.club/index");
        }
        else{
            redirect("http://mcomics.club");
        }
    }

    public function robi_home(){
        $mobnum = $this->input->get_post('msisdn');
        $this->session->set_userdata('mobnum',$mobnum);
        redirect("welcome/home");
    }



    public function robi_unauth(){       
            $data['msg'] ="You are not authorized to use this service";
            $data['heading'] ="MSISDN Failure";
            $data['mdn']     = "";
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/subfail_robimcomics',$data);
       
    }

    public function chkDubaiSub(){
        $mobnum = $this->session->userdata('mobnum');
        $query = $this->db->query("select productID from dubai_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");                          
                
        if($query->num_rows()>0){                    
            echo "1";
        }else{
            echo "0";
        }

    }

    public function mdnfail_dialog(){
        die();
            $data['msg'] ="Please access the service using Dialog Mobile Data.";
            $data['heading'] ="MSISDN Failure";
            $data['mdn']     = "";
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/subfail_robimcomics',$data);
       
    }

   


    /********************** sri lanka diaolg **************************/

    public function subscribe_sridialog(){
        die();
       // $mobnum     = $this->session->userdata('mobnum');
        $mdn = $this->get_MDN();
        if($mdn=='' or $mdn=='1111111111'){
            redirect(base_url().'mdnfail_dialog');
            
        }else{
             $mobnum     = $this->session->userdata('mobnum');
            $package    = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $amt='0';
            if($package == 'monthly'){
                $pckid = '711296';
                $amt='90';
            }else if($package == 'weekly'){
                $pckid = '711295';
                $amt='30';
            }else{
                $pckid = '711294';
                $amt='5';
            }
            $requestid='';
            $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
            for ($i = 0; $i < 25; $i++) {
                $requestid .= $characters[rand(0, strlen($characters) - 1)];
            }

            $txid='';
            $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
            for ($i = 0; $i < 30; $i++) {
                $txid .= $characters[rand(0, strlen($characters) - 1)];
            }
            $resultdnd = $this->subscribe_model->checkmComics_dialogdnd($mobnum);
            if($resultdnd>0){
                redirect("http://mcomics.club");
                die;
            }
            $result = $this->subscribe_model->checkmComics_sridialoguser($mobnum);
            if($result==0){

                $result1 = $this->subscribe_model->checkmComics_dialoguser($mobnum);
                if($result1>0){
                    $this->db->query("delete from dialog.sub_users where SubStatus='' and Mobnum='$mobnum' and Unsub=0");
                }

                $query = $this->db->query("insert into dialog.sub_users(mobnum,subdate,substatus,productID) values('$mobnum',now(),'','$pckid')");
                $this->db->query("insert into dialog.SubLogs(Mobnum,Action,Request,Date,Type,Mode,Amount) values('$mobnum','SUB','',now(),'$package','WEB','$amt')");
                $this->db->query("insert into dialog.SubLogs_archive(Mobnum,Action,Request,Date,Type,Mode,Amount) values('$mobnum','SUB','',now(),'$package','WEB','$amt')");

                //commented for testing
                //$url = "http://consent.dialog.lk/conssys/index.php?r=chrg/index&pkgid=$pckid&action=subscribe&redirecturl=".urlencode('http://mcomics.club/welcome/redirectdialog')."&validateurl=".urlencode("http://111.118.180.237/dialog/ak_validate.php")."&authkey=$requestid";

                //$url = "http://consentnew.dialog.lk/dev/bizlogic/index.php?r=chrg/index&pkgid=$pckid&action=subscribe&redirecturl=".urlencode('http://mcomics.club/welcome/redirectdialog')."&validateurl=".urlencode("http://111.118.180.237/dialog/ak_validate.php")."&authkey=$requestid";

                //$url = "http://consentnew.dialog.lk/conssys/index.php?r=chrg/index&pkgid=$pckid&action=subscribe&redirecturl=".urlencode('http://mcomics.club/welcome/redirectdialog')."&validateurl=".urlencode("http://111.118.180.237/dialog/ak_validate.php")."&authkey=$requestid";
               
                $url = "http://securedconsent.dialog.lk/pheuture/mcomicsdaily-1-en-soi-web?trxId=$txid&pkgId=$pckid&serviceName=mcomicsdaily&serviceProvider=pheuture&validity=1&price=5&lang=en&redirectUrl=".urlencode('http://mcomics.club/welcome/redirectdialog_secure')."&validateUrl=".urlencode("http://111.118.180.237/dialog/secure_d/ak_validate.php")."&authKey=$requestid&trafficSource=CDN";
                $query = $this->db->query("INSERT INTO  callback_request(`response`,`request`,`date`) VALUES('url','".serialize($url)."',now()) ");
               //echo $url;die;
               //header('Location: http://consent.dialog.lk/conssys/index.php?pkgid=711296&action=subscribe&redirecturl=http%3A%2F%2Fmcomics.club%2Fwelcome%2Fredirectdialog&validateurl=http%3A%2F%2F111.118.180.237%2Fdialog%2Fak_validate.php&authkey=6d4SQkCgDeWhZI5GB5OBG3EDO');
               redirect($url);
            }
            else{
		      redirect("http://mcomics.club");
            }
        }
       
    }


    public function redirectdialog() {
        die();
        $mdn = $this->get_MDN();
        $mobnum     = $this->session->userdata('mobnum');
        $query = $this->db->query("insert into dialog.cgresponse(response,date) values('".serialize($_GET)."',now())");
        $action = $this->input->get('status');
        $message = $this->input->get('message');
        $refid = $this->input->get('refid');
	    $url = "http://mcomics.club/";
        $resultdnd = $this->subscribe_model->checkmComics_dialogdnd($mobnum);
        if($resultdnd>0){
            redirect("http://mcomics.club");
            die;
        }
        if($message == 'Transaction successful'){
            $result = $this->subscribe_model->checkmComics_dialoguser($mobnum);
            if(count($result)>0){
                $query = $this->db->query("update dialog.sub_users set substatus='SUCCESS',SubExpire=date_add(now(),INTERVAL 2 Day) where mobnum='$mobnum' and unsub=0");               
            }else{
                $query = $this->db->query("insert into dialog.sub_users(mobnum,subdate,substatus,SubExpire) values('$mobnum',now(),'SUCCESS',date_add(now(),INTERVAL 2 Day))");
            }
             $this->db->query("update dialog.SubLogs set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1");
            $this->db->query("update dialog.SubLogs_archive set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1");          

            redirect($url);
        }
    	else {
    		redirect("http://mcomics.club");
    	}
        $data['msg'] = "$message";
        $this->load->view('template/header_fbpix');
        $this->load->view('unsubmessage',$data);
        
	
        //echo "Status: $action :: RefID: $refid :: Message: $message :: done";
    }

    public function redirectdialog_secure() {
        die();
        $mdn = $this->get_MDN();
        $mobnum     = $this->session->userdata('mobnum');
        $query = $this->db->query("insert into dialog.cgresponse(response,date) values('".serialize($_GET)."',now())");
        $result = $this->input->get('result');
        $message = $this->input->get('description');
        $refid = $this->input->get('trxId');
        $url = "http://mcomics.club/";
        $resultdnd = $this->subscribe_model->checkmComics_dialogdnd($mobnum);
        if($resultdnd>0){
            redirect("http://mcomics.club");
            die;
        }
        if($result == 'SUCCESS'){
            $result = $this->subscribe_model->checkmComics_dialoguser($mobnum);
            if(count($result)>0){
                $query = $this->db->query("update dialog.sub_users set substatus='SUCCESS',SubExpire=date_add(now(),INTERVAL 2 Day) where mobnum='$mobnum' and unsub=0");               
            }else{
                $query = $this->db->query("insert into dialog.sub_users(mobnum,subdate,substatus,SubExpire) values('$mobnum',now(),'SUCCESS',date_add(now(),INTERVAL 2 Day))");
            }
            $cmpCheck = $this->subscribe_model->checkmComics_dialogcmpCheck($mobnum);
            // check mdn sub from internal camp
            if($cmpCheck){
                $url='http://mcomics.club/sticker_maker';
            }
            $this->db->query("update dialog.SubLogs set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1");
            $this->db->query("update dialog.SubLogs_archive set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1");          

            redirect($url);
        }
        else {
            redirect("http://mcomics.club");
        }
        $data['msg'] = "$message";
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
        
    
        //echo "Status: $action :: RefID: $refid :: Message: $message :: done";
    }

    public function subscribe_eti_uae() {      
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;

        $chk_eti_uae_user = $this->subscribe_model->checkmComics_etiuaeuser($mobnum);
        if(count($chk_eti_uae_user)>0){
            $this->session->set_userdata('subuser','yes');
            //redirect('/index');
            redirect(base_url());
        }
        
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $data['littledataurl']='http://littledata.in/mcom/';
        $this->load->view('template/header_auth');
        $this->load->view('subscribepack_eti_uae',$data);
    }

    public function subPack_dubai() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack',$data);
    }


    ///////////////////////bg banglalink ////////////////////

    public function subPack_bg() {
        
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        
        $result = $this->subscribe_model->checkmComics_bguser($mobnum);
        if(count($result)>0){
            $this->session->set_userdata('subuser','yes');
            redirect(base_url());
        }
        $mdn=$mobnum;
        $data['mobnum']     = $mdn;
        $data['id']      = "";

        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack_bg',$data);
    }

    public function bg_otp(){
        
        /*error_reporting(E_ALL);
        ini_set("display_errors", 1);*/

        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        /*$result = $this->db->query("select * from bg_mcomics.otp where mobnum='$mobnum' and date>date_sub(now(), interval 5 minute) order by id desc limit 1");
        if($result->num_rows()>0){
            $fetchOtp = $result->row();
            $otp = $fetchOtp->otp;
            $otp_id = $fetchOtp->id;
            $this->db->query("update bg_mcomics.otp set date=now() where id=$otp_id");
            $msg = "Please enter the OTP ".$otp." to subscribe.";
            file_get_contents("http://202.164.209.130/bg/sendsms.php?mobnum=$mobnum&msg=".urlencode($msg));
            $this->db->query("insert into bg_mcomics.otp_logs(msisdn,msg) values('$mobnum','$msg')");
        }
        else{*/
            $otp = rand(1111,9999);
            $this->db->query("insert into bg_mcomics.otp(mobnum,otp) values('$mobnum','$otp')");
            $msg = "Please enter the OTP ".$otp." to subscribe.";
            file_get_contents("http://202.164.209.130/bg/sendsms.php?mobnum=$mobnum&msg=".urlencode($msg));
            $this->db->query("insert into bg_mcomics.otp_logs(msisdn,msg) values('$mobnum','$msg')");
        //}
        $mdn=$mobnum;
        $data['mobnum']     = $mdn;
        $data['id']      = "";

        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/bg_otp',$data);
    }

    public function bg_otp_verify(){
       
        $mobnum = $this->input->get_post('mobnum');
        $otp = $this->input->get_post('otp');

        $this->db->query("insert into bg_mcomics.otp_entered(mobnum,otp) values('$mobnum','$otp')");

        $result = $this->db->query("select * from bg_mcomics.otp where mobnum='$mobnum' and date>date_sub(now(), interval 5 minute) and otp='$otp' order by id desc limit 1");
        if($result->num_rows()>0){
            echo "success";
        }
        else{
            echo "failure";
        }
    }

    public function sub_bg()
    {
        
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $serviceid;
        $mobnum              = $this->session->userdata('mobnum'); //echo $mobnum;exit;
        $result = $this->subscribe_model->checkmComics_bguser($mobnum); //var_dump($result);
        if(count($result)>0){ // not happens usually
            $this->session->set_userdata('subuser','yes');
            redirect(base_url());
        }else{
            $result = $this->subscribe_model->sub_bg_user($mobnum,$subtype); //var_dump($result);
            redirect(base_url());
        }         

    }
    public function sub_bgfail()
    {
        $this->subfail_dumcomics(); 

    }

    public function api()
    {
        $mobnum = $this->input->get('mdn');
        $subtype = $this->input->get('type');
        $isChargeSuccess = $this->input->get('isChargeSuccess');

        $this->db->query("insert into bg.service_block(mobnum,ser) values('$mobnum','mc')");
        if($isChargeSuccess=="0") {
          $result = $this->subscribe_model->checkmComics_bguser($mobnum); //var_dump($result);
          if(count($result)>0){ // not happens usually
          }else{
            $result = $this->subscribe_model->sub_bg_user($mobnum,$subtype); 
            $this->session->set_userdata('subuser','yes');
            redirect(base_url());
          }
        }
        else{
            $this->subscribe_model->bg_logs_failure($mobnum,$subtype,$isChargeSuccess);
        }
    }

    ////////////bg robi/////////////////////////////////////

    public function subPack_robi() {
        
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        if($this->session->userdata('mobnum') == '' || $this->session->userdata('mobnum') == '1111111111'){
            $mdn = $this->get_MDN();
            if($this->session->userdata('mobnum') == '' || $this->session->userdata('mobnum') == '1111111111'){
                //redirect("mlogin");
                redirect(base_url() . 'mlogin', 'refresh');   die;
            }
        }
        $mobnum=$this->session->userdata('mobnum');
        $result = $this->subscribe_model->checkmComics_robiuser($mobnum);
        if(count($result)>0){
        $this->session->set_userdata('subuser','yes');
        redirect(base_url());
        }
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack_robi',$data);
    }

    public function subPack_airtelngria() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack_airtelngria',$data);
    }

    public function confirm_airtelngria() {
        $subtype= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['type']     = $subtype;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('template/header_auth');
        $this->load->view('confirm_airtelngria',$data);
    }

    public function subPack_sridialog() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subPack_sridialog',$data);
    }

    public function subfail_dumcomics() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('template/header_auth');
        $this->load->view('subfail_dmcomics',$data);
    }

    public function viewprofile() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        //$mtnngcode = substr($mdn,0,6);
        $data['changelanguage']   = "<select class='classic' name='changelanguage' id='changelanguage'>";
        $mtnngcode = substr($mobnum,0,6);  
       
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$mobnum' ");
        /*if($query->num_rows()>0){
            $result         = $query->result();
            $language       = $result[0]->language;
            $this->session->set_userdata('userlanguage', $language);
            if($language == 'english'){
                $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='sinhalese'>SINHALESE</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option>";
            }else if($language == 'bangla'){
                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='sinhalese'>SINHALESE</option><option value='bangla' selected='selected'>BANGLA</option><option value='arabic'>ARABIC</option>";
            }else if($language == 'arabic'){
                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='sinhalese'>SINHALESE</option><option value='bangla' >BANGLA</option><option value='arabic' selected='selected'>ARABIC</option>";
            }else{
                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='sinhalese' selected='selected'>SINHALESE</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option>";
            }
                       
        }else{
            $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='sinhalese'>SINHALESE</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option>";
            $this->session->set_userdata('userlanguage', 'ENGLISH');
        }*/
        if($query->num_rows()>0){
            $result         = $query->result();
            $language       = $result[0]->language;
            $this->session->set_userdata('userlanguage', $language);
            if ($_SERVER['HTTP_HOST']=='zj.mcomics.club' || $_SERVER['HTTP_HOST']=='zj.mooddiit.com') {
            	if($language == 'english'){
	                $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='arabic'>ARABIC</option>";
	            }else if($language == 'arabic'){
	                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='arabic' selected='selected'>ARABIC</option>";
	            }else{
	                $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='arabic'>ARABIC</option>";
	            }
            }else{
            	if($language == 'english'){
	                $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option><option value='bahasa'>BAHASA</option>";
	            }else if($language == 'bangla'){
	                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='bangla' selected='selected'>BANGLA</option><option value='arabic'>ARABIC</option><option value='bahasa'>BAHASA</option>";
	            }else if($language == 'arabic'){
	                $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='bangla' >BANGLA</option><option value='arabic' selected='selected'>ARABIC</option><option value='bahasa'>BAHASA</option>";
	            }else if($language == 'bahasa'){
                    $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='bangla' >BANGLA</option><option value='arabic'>ARABIC</option><option selected='selected' value='bahasa'>BAHASA</option>";
                }else{
	                $data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option><option value='bahasa'>BAHASA</option>";
	            }
            }            
                       
        }else{
        	if ($_SERVER['HTTP_HOST']=='zj.mcomics.club' || $_SERVER['HTTP_HOST']=='zj.mooddiit.com') {
	            $data['changelanguage']   .= "<option value='english'>ENGLISH</option><option value='arabic'  selected='selected'>ARABIC</option>";
           		$this->session->set_userdata('userlanguage', 'ARABIC');

	        }else{
	        	$data['changelanguage']   .= "<option value='english' selected='selected'>ENGLISH</option><option value='sinhalese'>SINHALESE</option><option value='bangla'>BANGLA</option><option value='arabic'>ARABIC</option>";
           		$this->session->set_userdata('userlanguage', 'ENGLISH');
	        }            
        }

        if ($_SERVER['HTTP_HOST']=='a.mcomics.club' || $_SERVER['HTTP_HOST']=='z.mcomics.club') {
            $this->session->set_userdata('userlanguage', 'ARABIC');
             $this->session->set_userdata('userlanguage', 'arabic');
              $data['changelanguage']   = "<select class='classic' name='changelanguage' id='changelanguage'><option value='arabic' selected='selected'>ARABIC</option>";

        }else if ($_SERVER['HTTP_HOST']=='b.mcomics.club') {
            $this->session->set_userdata('userlanguage', 'BURMESE');
             $this->session->set_userdata('userlanguage', 'burmese');
              $data['changelanguage']   = "<select class='classic' name='changelanguage' id='changelanguage'><option value='burmese' selected='selected'>BURMESE</option>";

        }

        $data['changelanguage'] .= "</select>";
        /*if(substr($mdn,0,3) == '880'){

        }*/
        if ($_SERVER['HTTP_HOST']=='u.mtoonapp.com') {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select date(SubDate) as date,SubType from anest_eti_uae_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                $data['pack'] = 'Daily';
                $data['amount'] = '3.25 AED';
            }
            else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select date(SubDate) as date,SubType from dialog_sl_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['SubDate']   = $result[0]->date;
                $data['pack'] = 'Daily';
                $data['amount'] = '5 LKR';
            }
            else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,3)=="971")  //dubai
        {
            $eti_series = substr($mobnum,0,5);
            if($eti_series=="97150" || $eti_series=="97154" || $eti_series=="97155" || $eti_series=="97156" || $eti_series=="97158"){
                $data['mdn']     = substr($mdn,-9);
                $query = $this->db->query("select productID,date(SubDate) as date from etisalat_uae_mcom.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
                if($query->num_rows()>0){
                    $result         = $query->result();
                    $data['pack']   = $result[0]->productID;
                    $data['SubDate']   = $result[0]->date;
                    if($data['pack'] == '1326'){
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '32 AED';                    
                    }else if($data['pack'] == '1325'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '11 AED';
                    }else{
                        $data['pack'] = 'Daily';
                        $data['amount'] = '2.25 AED';
                    }
                    
                }else{
                     $this->session->sess_destroy();
                    redirect(base_url() . 'index', 'refresh');
                }
            }else{
                $data['mdn']     = substr($mdn,-9);
                $query = $this->db->query("select productID,date(SubDate) as date from dubai_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                if($query->num_rows()>0){
                    $result         = $query->result();
                    $data['pack']   = $result[0]->productID;
                    $data['SubDate']   = $result[0]->date;
                    if($data['pack'] == 'MCOMICS_02'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '2 AED';                    
                    }else if($data['pack'] == 'MCOMICS_07'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '7 AED';
                    }else{
                        $data['pack'] = 'Daily Free Trial';
                        $data['amount'] = 'Free';
                    }
                    
                }else{
                     $this->session->sess_destroy();
                    redirect(base_url() . 'index', 'refresh');
                }
            }
        }else if ($_SERVER['HTTP_HOST']=='mtoonapp.com') {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select date(SubDate) as date,SubType from pal_mcomics.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                $data['pack'] = 'Daily';
                $data['amount'] = '1.16 ILS';
            }
            else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if ($_SERVER['HTTP_HOST']=='ku.mcomics.club') {
            $data['mdn']     = substr($mdn,-8);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_kuwait.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '0.2 KWD';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '1 KWD';
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '4 KWD';
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '0.2 دينار كويتي';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '1 دينار كويتي';
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '4 دينار كويتي';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if ($_SERVER['HTTP_HOST']=='p.mcomics.club') {
            $data['mdn']     = substr($mdn,-9);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_palestine_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    $data['pack'] = 'Daily';
                    $data['amount'] = '1 ILS';
                }
                else{
                    $data['pack'] = 'اليومي';
                    $data['amount'] = '1 شيكل يوميا';
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if ($_SERVER['HTTP_HOST']=='o.mcomics.club') {
            $data['mdn']     = substr($mdn,-8);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_oman.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '0.2 OMR';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '1 OMR';
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '4 OMR';
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '0.2 ريال عماني';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '1 ريال عماني';
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '4 ريال عماني';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if ($_SERVER['HTTP_HOST']=='mc.mcomics.club') {
            $data['mdn']     = substr($mdn,-8);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_mtania.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '5 MRU';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '20 MRU';
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '50 MRU';
                    }
                }
                else if($language=="french"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'du quotidien';
                        $data['amount'] = '5 MRU';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Hebdomadaire';
                        $data['amount'] = '20 MRU';
                    }
                    else{
                        $data['pack'] = 'Mensuel';
                        $data['amount'] = '50 MRU';
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '5 MRU';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '20 MRU';
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '50 MRU';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,2)=="20")    //egypt
        {
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_egypt.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '3 Egyptian Pounds';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '12.5 Egyptian Pounds';
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '50 Egyptian Pounds';
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '3 جنيه مصري';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '12.5 جنيه مصري';
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '50 جنيه مصري';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,3)=="254")    //kenya
        {
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_kenya.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($data['pack'] == 'daily'){
                    $data['pack'] = 'Daily';
                    $data['amount'] = '10 KES';
                }
                else if($data['pack'] == 'weekly'){
                    $data['pack'] = 'Weekly';
                    $data['amount'] = '50 KES';
                }
                else{
                    $data['pack'] = 'Monthly';
                    $data['amount'] = '200 KES';
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,3)=="966")    //tpay ksa
        {
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType from tpay_ksa.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '1 SAR';
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '1 ريال سعودي';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,3)=="973")    //bahrain
        {
            $data['mdn']     = substr($mdn,-11);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType,date(SubExpire) as se,oper from tpay_bahrain.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $lang = $fetLang->lang;
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                $data['subend']   = $result[0]->se;
                $data['oper'] = $result[0]->oper;
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '0.15 BHD';
                        $data['submsg'] = "Your subscription is currently active and will automatically renew daily until you unsubscribe";
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '0.7 BHD';
                        $data['submsg'] = "Your subscription is currently active and will automatically renew weekly until you unsubscribe";
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '2.5 BHD';
                        $data['submsg'] = "Your subscription is currently active and will automatically renew monthly until you unsubscribe";
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '0.15 دينار بحريني';
                        $data['submsg'] = "اشتراكك نشط حاليًا وسيتم تجديده تلقائيًا يوميًا حتى تقوم بإلغاء الاشتراك";
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '0.7 دينار بحريني';
                        $data['submsg'] = "اشتراكك نشط حاليًا وسيتم تجديده أسبوعيًا تلقائيًا حتى تقوم بإلغاء الاشتراك";
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '2.5 دينار بحريني';
                        $data['submsg'] = "اشتراكك نشط حاليًا وسيتم تجديده تلقائيًا شهريًا حتى تقوم بإلغاء الاشتراك";
                    }
                }
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(substr($mobnum,0,3)=="974")    //qatar
        {
            $data['mdn']     = substr($mdn,-11);
            $query = $this->db->query("select productID,date(SubDate) as date,SubType,oper from tpay_qatar.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->SubType;
                $data['SubDate']   = $result[0]->date;
                $oper   = $result[0]->oper;

                if($oper=="42701"){
                    $currency = "QAR";
                }
                else{
                    $currency = "QAT"; 
                }
                
                if($language=="english"){
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '1 '.$currency;
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'Weekly';
                        $data['amount'] = '7 '.$currency;
                    }
                    else{
                        $data['pack'] = 'Monthly';
                        $data['amount'] = '40 '.$currency;
                    }
                }
                else{
                    if($data['pack'] == 'daily'){
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '1 ريال قطري';
                    }
                    else if($data['pack'] == 'weekly'){
                        $data['pack'] = 'أسبوعي';
                        $data['amount'] = '7 ريال قطري';
                    }
                    else{
                        $data['pack'] = 'شهريا';
                        $data['amount'] = '40 ريال قطري';
                    }
                }
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        //else if(substr($mobnum,0,5)=="88019")    //bg banglalink
        else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink 
        {
            
            $data['mdn']     = substr($mdn,-11);
            $query = $this->db->query("select productID,date(SubDate) as date from bg_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = "2.67";
                $data['SubDate']   = $result[0]->date;
                if($data['pack'] == '2.67'){
                    $data['pack'] = 'Daily';
                    $data['amount'] = '2.67 Tk (inclusive VAT+SD+SC)';
                }
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }else if(substr($mobnum,0,3)=="880")    //robi
        {
            
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select amount,date(SubDate) as date from robi_mcomics.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->amount;
                $data['SubDate']   = $result[0]->date;
                if($data['pack'] == '2.44'){
                    $data['pack'] = 'Daily';
                    $data['amount'] = '2.44 TK';
                }else if($data['pack'] == '2.55'){
                    $data['pack'] = 'Daily';
                    $data['amount'] = '2.55 TK';
                }
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803" || $mtnngcode=="234806" || $mtnngcode=="234810" ||  $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")    //mtn nigeria
        {
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select productID,date(SubDate) as date from mtnngria_mc.sub_users_sdp where mobnum='$mobnum' and Unsub=0 and productID<>'' ");

            if($query->num_rows()>0){
                $result         = $query->result();
                $productid = $result[0]->productID;
                if($productid == '23401220000027993'){
                    $pack = "daily";
                    $amount = "10";
                }else if($productid == '23401220000027994'){
                    $pack = "weekly";
                    $amount = "50";
                }else{
                    $pack = "monthly";
                    $amount = "100";
                }

                $data['pack']   = strtoupper($pack);
                $data['amount'] = $amount;
                $data['SubDate']   = $result[0]->date;
                
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }else if(substr($mobnum,0,3)=="234")    //airtel nigeria
        {
            $data['mdn']     = substr($mdn,-10);
            $query = $this->db->query("select lastchargedamt as amount,date(SubDate) as date,subtype from airtel_nigeria.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = strtoupper(($result[0]->subtype));
                $data['amount'] = $result[0]->amount;
                $data['SubDate']   = $result[0]->date;
                
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }else if(substr($mobnum,0,3)=="964")    //zain iraq
        {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select lastchargedamt as amount,date(SubDate) as date,subtype from zain_iraq.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = strtoupper(($result[0]->subtype));
                $data['amount'] = $result[0]->amount;
                $data['SubDate']   = $result[0]->date;
                
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(strlen($mobnum)>400)    //vodacom sa
        {
            $data['mdn']     = $mdn;
            
            $query = $this->db->query("select date(SubDate) as date,subtype from vodacom_sa.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = strtoupper(($result[0]->subtype));
                //$data['amount'] = $result[0]->amount;
                $data['amount'] = '';
                $data['SubDate']   = $result[0]->date;
            }
            else{
                $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
            
        }
        else if(substr($mobnum,0,2)=="27")    //cellc south africa
        {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select date(SubDate) as date,subtype from cellc_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = 'Daily';
                //$data['amount'] = $result[0]->amount;
                $data['amount'] = 'R 5';
                $data['SubDate']   = $result[0]->date;   
            }
            else{
                $query = $this->db->query("select date(SubDate) as date,subtype from mtnsa_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                if($query->num_rows()>0){
                    $result         = $query->result();
                    $data['pack']   = strtoupper(($result[0]->subtype));
                    //$data['amount'] = $result[0]->amount;
                    $data['amount'] = '';
                    $data['SubDate']   = $result[0]->date;
                }
                else{
                    $this->session->sess_destroy();
                    redirect(base_url() . 'index', 'refresh');
                }
            }
        }
        else if(substr($mobnum,0,3)=="965")    //vivo kuwait
        {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select lastchargedamt as amount,date(SubDate) as date,subtype from vivo_kuwait.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = strtoupper(($result[0]->subtype));
                $data['amount'] = $result[0]->amount;
                $data['SubDate']   = $result[0]->date;
                
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url(), 'refresh');
            }
        }else if(substr($mobnum,0,3)=="962")    //zain jordan
        {
            $data['mdn']     = $mdn;
            $query = $this->db->query("select productID,date(SubDate) as date from zain_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = 'DAILY';
                $data['amount'] = 'JOD 0.2';
                $data['SubDate']   = $result[0]->date;   
            }else{
                
                $query = $this->db->query("select productID,date(SubDate) as date,SubType,oper from tpay_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
                if($query->num_rows()>0){
                    $result         = $query->result();
                    $data['pack']   = $result[0]->SubType;
                    $data['SubDate']   = $result[0]->date;
                    $oper   = $result[0]->oper;
                    
                    if($language=="english"){
                        $data['pack'] = 'Daily';
                        $data['amount'] = '0.15 JOD';
                    }
                    else{
                        $data['pack'] = 'اليومي';
                        $data['amount'] = '0.15 دينار';
                    }

                }
                else{
                    $this->session->sess_destroy();
                    redirect(base_url(), 'refresh');
                }
                
            }
        }else if(substr($mobnum,0,2)=="94" && substr($mobnum,2,2)=="75")    //srilanka airtel
        {
            $data['mdn']     = "+".substr($mdn,-11);
            $query = $this->db->query("SELECT productID,date(SubDate) as date from airtelsl_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->productID;
                $data['SubDate']   = $result[0]->date;
                if($data['pack']=="Pheuture6875_1"){
                    $data['pack']      = "Daily Pack";
                    $data['amount']      = 2.40;
                }
                elseif($data['pack']=="Pheuture6877_1"){
                    $data['pack']      = "Weekly Pack";
                    $data['amount']      =11.97;
                }
                else{
                    $data['pack']      = "Monthly Pack";
                    $data['amount']      = 35.92;
                }
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }
        else if(strlen($mobnum)>13)    //dialog
        //else if(substr($mobnum,0,3)=="947")    //dialog
        {
            $data['mdn']     = "+".substr($mdn,-11);
            $query = $this->db->query("select productID,date(SubDate) as date from dialog.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $result         = $query->result();
                $data['pack']   = $result[0]->productID;
                $data['SubDate']   = $result[0]->date;
                if($data['pack'] == ' 711294'){
                    $data['pack'] = 'Daily';
                    $data['amount'] = 'LKR 5 + tax';                    
                }else if($data['pack'] == ' 711295'){
                    $data['pack'] = 'Weekly';
                    $data['amount'] = 'LKR 30 + tax';  
                }else{
                    $data['pack'] = 'Monthly';
                    $data['amount'] = 'LKR 99 + tax';  
                }
                
            }else{
                 $this->session->sess_destroy();
                redirect(base_url() . 'index', 'refresh');
            }
        }else if($mobnum == "919871666000" or $mobnum == "919873032445"  or $mobnum == '919718698337'  or $mobnum == '919997970414')    //robi
        {
            $data['mdn']        = substr($mdn,-10);
            $data['SubDate']    = '2017-10-16';
            $data['pack']       = 'Daily';
            $data['amount']     = 'Rs 100';
                
           
        }else{
            $result = array();
            redirect(base_url() . 'index', 'refresh');
        }
        $data['mobnum']=$mobnum;
        if($this->session->userdata('userlanguage') == 'sinhalese'){
            $data['subscription'] = 'දායකත්ව විස්තර';
            $data['Mobile'] = 'ජංගම දූරකථන අංකය';          
            $data['Pack'] = 'ඇසුරුම් වර්ගය';   
            $data['PackAmount'] = 'පැකට්ටුව';
            $data['subdate'] = 'දායක වීමේ දිනය';
            $data['clang'] = 'භාෂාව වෙනස් කරන්න';
            $data['unsub'] = 'නොපවතියි'; 
        }else if($this->session->userdata('userlanguage') == 'bangla'){
            $data['subscription'] = 'সাবস্ক্রিপশন বিবরণ';   
            $data['Mobile'] = 'মোবাইল নম্বর';              
            $data['Pack'] = 'প্যাক প্রকার';
            $data['PackAmount'] = 'প্যাক পরিমাণ';
            $data['subdate']= 'সাবস্ক্রিপশন তারিখ';
            $data['clang'] = 'ভাষা পরিবর্তন করুন';
            $data['unsub'] = 'সদস্যতা ত্যাগ করুন'; 
        }else if($this->session->userdata('userlanguage') == 'french'){
            $data['subscription'] = 'Détails de l\'abonnement';   
            $data['Mobile'] = 'Numéro de portable';   
            $data['Pack'] = 'Type de paquet';  
            $data['PackAmount'] = 'Quantité de pack';
            $data['subdate'] = 'Date d\'inscription';
            $data['clang'] = 'Changer de langue';
            $data['unsub'] = 'Se désabonner';                       
        }else if($this->session->userdata('userlanguage') == 'arabic'){
            $data['subscription'] = 'تفاصيل الاشتراك';   
            $data['Mobile'] = 'رقم الموبايل.';   
            $data['Pack'] = 'نوع الحزمة';  
            $data['PackAmount'] = 'مبلغ الحزمة';
            $data['servicestatus']= 'حالة الخدمة';
            $data['subdate'] = 'تاريخ الاشتراك';
            $data['clang'] = 'تغيير اللغة';
            $data['unsub'] = 'إلغاء الاشتراك';             
            $data['active']= 'فعال';
            $data['SubExpire']= 'ميعاد تجديد الأشتراك';          
        }else if($this->session->userdata('userlanguage') == 'burmese'){
            $data['subscription'] = 'Subscription ကိုအသေးစိတျ';   
            $data['Mobile'] = 'ဖုန်းနံပါတ်။';   
            $data['Pack'] = 'ဗူးအမျိုးအစား';  
            $data['PackAmount'] = 'Pack ကိုငွေပမာဏ';
            $data['subdate'] = 'ကြေးပေးသွင်းသည့်ရက်စွဲ';
            $data['servicestatus']= 'ဝန်ဆောင်မှုအခြေအနေ';
            $data['active']= 'တက်ကြွ';
            $data['clang'] = 'ပြောင်းလဲမှုဘာသာစကားများ';
            $data['unsub'] = 'စာရင်းဖျက်ရန်';                       
        }else if($this->session->userdata('userlanguage') == 'bahasa'){
            $data['subscription'] = 'Detail Langganan';   
            $data['Mobile'] = 'Nomor ponsel.';   
            $data['Pack'] = 'Jenis Paket';  
            $data['PackAmount'] = 'Jumlah Paket';
            $data['subdate'] = 'Tanggal Berlangganan';
            $data['servicestatus']= 'Status pelayanan';
            $data['active']= 'Aktif';
            $data['clang'] = 'Ganti BAHASA';
            $data['unsub'] = 'Berhenti berlangganan';                       
        }else{
            $data['subscription'] = 'Subscription Details';                       
            $data['Mobile'] = 'Mobile No.';
            $data['Pack'] = 'Pack Type';
            $data['PackAmount'] = 'Pack Amount';
            $data['subdate']= 'Subscription Date';
            $data['servicestatus']= 'Service Status';
            $data['active']= 'Active';
            $data['clang'] = 'Change Language';
            $data['unsub'] = 'Unsubscribe';
            $data['SubExpire'] = 'Service End Date'; 
        }

       
        $data['id']      = "";
       
        $data['backword'] = 'index';
        $data['forward_img'] = 'home.png';
        $data['forward'] = 'index';
        $this->load->view('newui/template/header_2', $data);
        $this->load->view('newui/viewprofile',$data);
    }

    public function unsubscribe_mcomics(){
       
        $mobnum ="";
        $mobnum = $this->session->userdata('mobnum');
        $mdn=$mobnum;
        $mtnngcode = substr($mdn,0,6);
        if ($_SERVER['HTTP_HOST']=='u.mtoonapp.com') {
            redirect(base_url()."welcome/aue_unsub");
        }
        else if(substr($mobnum,0,3)=="971")  //dubai
        {
            @file_get_contents("http://111.118.180.237/dubai_du/mcomics/unsub.php?msisdn=$mobnum&mode=WEB");
        }else if((substr($mobnum,0,5)=="88019") || (substr($mobnum,0,5)=="88014"))    //banglalink
        {
            
            $this->subscribe_model->bg_mc_unsub($mobnum);
            redirect("http://103.239.252.108/blsdp_wap/deregister.php?appid=OyeIM&apppass=oyeim&subscriptionroot=21270MComicPortal&msisdn=$mobnum");
        }else if(substr($mobnum,0,3)=="880")    //robi
        {
           
            @file_get_contents("http://111.118.180.237/robi/sdpmcomics/unsub.php?msisdn=$mobnum&mode=WEB");
        }
        else if ($_SERVER['HTTP_HOST']=='mtoonapp.com') {
            redirect(base_url()."welcome/pal_unsub");
        }
        else if ($_SERVER['HTTP_HOST']=='p.mcomics.club') {
            redirect(base_url()."pales_unsub");
        }
        else if ($_SERVER['HTTP_HOST']=='o.mcomics.club') {
            redirect(base_url()."om_unsub");
        }
        else if ($_SERVER['HTTP_HOST']=='mc.mcomics.club') {
            redirect(base_url()."ma_unsub");
        }
        else if(substr($mobnum,0,2)=="20")    //egypt
        {
            redirect(base_url()."welcome/eg_unsub");
        }
        else if ($_SERVER['HTTP_HOST']=='ku.mcomics.club') {
            redirect(base_url()."welcome/ku_unsub");
        } 
        else if ($_SERVER['HTTP_HOST']=='sd.mcomics.club') {
            redirect(base_url()."welcome/sd_unsub");
        }
        else if(substr($mobnum,0,3)=="966")    //tpay ksa
        {
            redirect(base_url()."welcome/ks_unsub");
        }
        else if(substr($mobnum,0,3)=="254")    //tpay kenya
        {
            redirect(base_url()."welcome/ke_unsub");
        }
        else if(substr($mobnum,0,3)=="973")    //bahrain
        {
            redirect(base_url()."welcome/ba_unsub");
        }
        else if(substr($mobnum,0,3)=="974")    //qatar
        {
            redirect(base_url()."welcome/qa_unsub");
        }
        else if($mtnngcode=="234703" || $mtnngcode=="234706" || $mtnngcode=="234803" || $mtnngcode=="234806" || $mtnngcode=="234810" || $mtnngcode=="234813" || $mtnngcode=="234814" || $mtnngcode=="234816" || $mtnngcode=="234903" || $mtnngcode=="234906")    //mtn nigeria
        {
            
            $this->subscribe_model->mtnnga_unsub($mobnum);
        }else if(substr($mobnum,0,3)=="234")    //airtel nigeria
        {
            @file_get_contents("http://111.118.180.237/airtelng/unsub.php?msisdn=$mobnum&mode=WEB");
        }else if(substr($mobnum,0,3)=="962")    //zain jordan
        {
            $query = $this->db->query("select * from tpay_jordan.sub_users where mobnum='$mobnum' and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                redirect(base_url()."welcome/jo_unsub");
            }
            @file_get_contents("http://111.118.180.237/zain_jordan/unsubscribe.php?mdn=$mobnum");
        }else if(substr($mobnum,0,2)=="94" && substr($mobnum,2,2)=="75")    
        {   
            
            redirect("unsub_airtelsl_confirm");
            //@file_get_contents("http://111.118.180.237/airtelsl/mcomics/unsub.php?mobnum=$mobnum&mode=WEB");
        }
        else if(substr($mobnum,0,3)=="964")    //zain iraq
        {
            file_get_contents("http://111.118.180.237/comviva/iraq/unsubscribe.php?mobnum=$mobnum");
            //@file_get_contents("http://zainirq.mcomviva.com/api/unsubscribe?key=iQ4Mh8/=&comp_id=61&con_type=757&msisdn=$mobnum&op_id=4");
        }
        else if(substr($mobnum,0,2)=="27")    //cellc south africa
        {
            //MTNSA UNSUB
            $query = $this->db->query("select * from mtnsa_mcomics.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                //$pid = $query->row()->productID;
                file_get_contents("http://111.118.180.237/mtnsa_wp/unsub.php?mdn=$mobnum&ser=SP_MM_mComics");
            }
            else{
                file_get_contents("http://111.118.180.237/cellc_sa/unsubscribe.php?mobnum=$mobnum&service=mc");
            }
        }
        else if(substr($mobnum,0,3)=="965")    //vivo kuwait
        {
           //echo "http://vascld-afl.mcomviva.com:8112/AFL/deactService?key=mT3NM3/=&comp_id=208&con_type=749&msisdn=$mobnum&op_id=3";
            //@file_get_contents("http://111.118.180.237/comviva/vivo_kuwait/mcomics/unsub.php?msisdn=$mobnum&mode=WEB");
            $unsub_status=file_get_contents("http://172.31.8.32:8112/AFL/deactService?key=mT3NM3/=&comp_id=208&con_type=749&msisdn=$mobnum&op_id=3");
        	/*	$url="http://vascld-afl.mcomviva.com:8112/AFL/deactService?key=mT3NM3/=&comp_id=208&con_type=749&msisdn=$mobnum&op_id=3";
                    $curlh = curl_init($url);
        		curl_setopt($curlh, CURLOPT_RETURNTRANSFER, true);
        		curl_setopt($curlh, CURLOPT_INTERFACE, "111.118.180.238");
        		$unsub_status = curl_exec($curlh);*/
                    /*if($unsub_status=='%0%'){
                @file_get_contents("http://111.118.180.237/comviva/vivo_kuwait/mcomics/unsub.php?msisdn=$mobnum&mode=WEB");
            }*/
        }
        else if(substr($mobnum,0,3)=="947")    //dialog
        {
            //@file_get_contents("http://111.118.180.237/dialog/unsub.php?msisdn=$mobnum&mode=WEB");
            $query = $this->db->query("select productID from dialog.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $pack   = $result[0]->productID;
                redirect(base_url()."dialog_unsub?pid=$pack");
            }
	
           
        }
        else if(strpos( $mobnum, '=' ) !== false ) {
            //@file_get_contents("http://111.118.180.237/dialog/unsub.php?msisdn=$mobnum&mode=WEB");
            $query = $this->db->query("select productID from dialog.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $pack   = $result[0]->productID;
                redirect(base_url()."dialog_unsub?pid=$pack");
            }
    
           
        }
        else if(strlen($mobnum)>400){
            $query = $this->db->query("select * from vodacom_sa.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            if($query->num_rows()>0){
                $pid = $query->row()->productID;
                $vodacom_unsub_res = file_get_contents("http://111.118.180.237/vodacom_sa/unsub.php?mdn=$mobnum&pid=$pid&ser=mc");
            }
            else{
                $this->session->sess_destroy();
            }
        }
        else{
            $result = array();
            $query = '';
        } 

        if($mobnum == "919871666000"){

        }else{

         $this->session->sess_destroy();
        }

        if($this->session->userdata('userlanguage') == 'SINHALESE'){
            $data['msg'] = "ඔබ mComics වෙතින් සාර්ථකව දායකත්වයෙන් ඉවත් වී ඇත. නැවත දායක වීමට, පිවිසෙන්න ".base_url(); 
        }else if($this->session->userdata('userlanguage') == 'BANGLA'){
            $data['msg'] = "আপনি সফলভাবে এমকমিকস থেকে সাবস্ক্রাইব করেছেন। আবার সাবস্ক্রাইব করতে, দেখুন ".base_url(); 
        }else if($this->session->userdata('userlanguage') == 'FRENCH'){
            $data['msg'] = "Vous avez été désabonné de mComics avec succès. Pour vous abonner à nouveau, visitez ".base_url();                      
        }else if($this->session->userdata('userlanguage') == 'ARABIC'){
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();                     
        }else if($this->session->userdata('userlanguage') == 'BURMESE'){
            $data['msg'] = "ရုပ်ပြထံမှအောင်မြင်စွာစာရင်းသွင်းပြီးပါပြီ။ တဖန်စာရင်းသွင်းရန်, သွားရောက်ကြည့်ရှု ".base_url();                      
        }
        else if(strlen($mobnum)>400){
            if($vodacom_unsub_res == "1"){
                $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
            }
            else{
                $data['msg'] = "Sorry, your unsubscribe request was unsuccessful, please try again or dial *135*997# to unsubscribe.";
            }
        }
        else{
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }       
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    public function dialog_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $package = $this->input->get_post('pid');
        /*echo "<html>
                <body onload='document.frm1.submit()'>
                    <form action='http://consent.dialog.lk/dev/bizlogic/index.php?r=api/unsubs' name='frm1' method='post'>
                        <input type='hidden' name='username' value='' />
                        <input type='hidden' name='password' value='' />
                        <input type='hidden' name='msisdn' value='$mobnum' />
                        <input type='hidden' name='pkgid' value='$package' />
                    </form>
                </body>
            </html>";
	*/
		$requestid='';
        $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        for ($i = 0; $i < 25; $i++) {
            $requestid .= $characters[rand(0, strlen($characters) - 1)];
        }
		file_get_contents("http://localhost/dialog/unsubscribe.php?mobnum=$mobnum&prodid=$package&mode=WEB");
        $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
		//$url = "http://consentnew.dialog.lk/conssys/index.php?r=chrg/index&pkgid=$package&action=unsubscribe&redirecturl=".urlencode('http://mcomics.club/welcome/unsubdialog')."&validateurl=".urlencode("http://111.118.180.237/dialog/ak_validate.php")."&authkey=$requestid";
        //redirect($url);

    }

    public function unsubdialog() {
        $mdn = $this->get_MDN();
        $mobnum     = $this->session->userdata('mobnum');
        $query = $this->db->query("insert into dialog.cgresponse(response,date) values('".serialize($_GET)."',now())");
        $action = $this->input->get('status');
        $message = $this->input->get('message');
        $refid = $this->input->get('refid');
        $url = "http://mcomics.club/";
        if($message == 'Transaction successful'){
                $status=@file_get_contents("http://111.118.180.237/dialog/unsub.php?msisdn=$mobnum&mode=WEB");
                $this->session->sess_destroy();
                $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();       

        }else{
            $data['msg'] = "There is some error occured in Unsubscription.Plz. Try Later.";        
        }
        //$this->load->view('template/header_auth');
        //$this->load->view('unsubmessage',$data);
	redirect("http://mcomics.club");
        
     
       // echo "Status: $action :: RefID: $refid :: Message: $message :: done";
    }

    public function dusub(){ //dialog unsub direct for auto cc
        $mobnum = $this->get_MDN();
        if(substr($mobnum,0,3)=="947"){
            $query = $this->db->query("select productID from dialog.sub_users where mobnum='$mobnum' and Unsub=0");
            if($query->num_rows()>0){
                $result         = $query->result();
                $pack   = $result[0]->productID;
                $this->session->set_userdata('mobnum',$mobnum);
                redirect(base_url()."dialog_unsub?pid=$pack");
            }
        }else{
            redirect(base_url().'mdnfail_dialog');
        }
    }

    //*************************************airtel nigeria *************************************//

    public function sub_airtelngria(){
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $subtype;
        $ran = rand(11111,99999);
         if($mdn=='1111111111' or $mdn=='' or $mdn=='234'){
            //redirect('/index');
            redirect(base_url());
        }
        else{
            
            if($subtype=="daily" || $subtype=="weekly" || $subtype=="monthly"){
                $url = file_get_contents("http://localhost/airtelng/submcomics.php?MSISDN=$mdn&subtype=$subtype&mode=WEB&adv=0");
                if($url == 'SUBSCRIBED'){
                    redirect(base_url());
                }
                //redirect($url); 
            }           
            else{
                echo "none$subtype";
                redirect(base_url()."subPack_airtelngria");
            }
        }
    }

    public function languageChange(){
        $languageChange = $this->input->get_post('option');
        $mobnum         = $this->session->userdata('mobnum');
        $langmessage = '';

        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$mobnum'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$mobnum'");
            if($languageChange=="arabic"){
                $this->db->query("update tpay_bahrain.tidlogs set lang='ar' where mobnum='$mobnum'");
                $this->db->query("update tpay_qatar.tidlogs set lang='ar' where mobnum='$mobnum'");
                $this->db->query("update tpay_egypt.tidlogs set lang='ar' where mobnum='$mobnum'");
                $this->db->query("update tpay_jordan.tidlogs set lang='ar' where mobnum='$mobnum'");
                $this->db->query("update tpay_ksa.tidlogs set lang='ar' where mobnum='$mobnum'");
            }
            else{
                $this->db->query("update tpay_bahrain.tidlogs set lang='en' where mobnum='$mobnum'");
                $this->db->query("update tpay_qatar.tidlogs set lang='en' where mobnum='$mobnum'");
                $this->db->query("update tpay_egypt.tidlogs set lang='en' where mobnum='$mobnum'");
                $this->db->query("update tpay_jordan.tidlogs set lang='en' where mobnum='$mobnum'");
                $this->db->query("update tpay_ksa.tidlogs set lang='en' where mobnum='$mobnum'");
            }
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$mobnum',now()) ");
        }

        if($languageChange == "english"){ //bangladesh
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'ENGLISH');
            $langmessage = "English Language Changed Successfully.";            
        }else if($languageChange == "bangla"){ //bangladesh
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'BANGLA');
            $langmessage = "BANGLA Language Changed Successfully.";            
        }else if($languageChange == "french"){ //bangladesh
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'FRENCH');
            $langmessage = "FRENCH Language Changed Successfully.";            
        }else if($languageChange == "arabic"){ //bangladesh
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'ARABIC');
            $langmessage = "ARABIC Language Changed Successfully.";            
        }else if($languageChange == "burmese"){ //bangladesh
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'BURMESE');
            $langmessage = "BURMESE Language Changed Successfully.";            
        }else if($languageChange == "bahasa"){ //Bahasa
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'BAHASA');
            $langmessage = "BAHASA Language Changed Successfully.";            
        }else{
            $this->session->unset_userdata('userlanguage');
            $this->session->set_userdata('userlanguage', 'SINHALESE');
            $langmessage = "Sinhalese Language Changed Successfully.";
        }
        echo  $langmessage;
    } 

    public function changeFrenchConvert(){
        $title = $this->input->get_post('title');
        $frenchdesc = file_get_contents("https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20180227T073600Z.597ba82555be1e11.32a28281a10e7fe267af05ab0acbc5eadb38a1ae&lang=en-fr&text=".urlencode($title));
        $desctext = json_decode($frenchdesc,true);
        echo $descrussion = trim($desctext['text'][0]);

    }


    /************************** etisalat uae blazon *****************/

    function confirmEU(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->session->set_userdata('subtype',$subtype);
        $mobnum = $this->session->userdata('mobnum');

        switch ($subtype) {
            case "monthly":
                $data['pack'] = 'Monthly Pack @ AED 32/month';
                break;
            case "weekly":
                $data['pack'] = 'Weekly Pack @ AED 11/week';
                break;
            case "daily":
                $data['pack'] = 'Daily Pack @ AED 2.25/day';
                break;
        }
        $data['heading'] = "Subscription";
        $data['content']  = 'confirm_etisalat_uae';
        $data['subtype']   = $subtype;
        $data['mobnum']   = $mobnum;
        //$this->load->vars($data);
        $this->load->view('template/header_auth');
        $this->load->view('confirm_etisalat_uae',$data);
    }

    function sendEUOTP(){
        $subtype =$this->session->userdata('subtype');
        $mobnum = $this->session->userdata('mobnum');
        $token = $this->subscribe_model->send_eti_uae_otp($mobnum,$subtype);

        $this->session->set_userdata('eu_token',$token);

        $data['content']  = 'enterotp_etisalat_uae';
        $data['subtype']   = $subtype;
        $data['mobnum']   = $mobnum;
        $data['token']   = $token;
        $this->load->view('template/header_auth');
        $this->load->view('enterotp_etisalat_uae',$data);
    }

    function validateEUOTP(){
        $otp  = $this->input->get_post('otp');
        $mobnum  = $this->input->get_post('mobnum');
        $subtype  = $this->input->get_post('subtype');
        $token  = $this->input->get_post('token');
        $validate_otp = $this->subscribe_model->verify_eti_uae_otp($mobnum,$subtype,$otp,$token);
        
        if($validate_otp=="1"){
            redirect("/index");
        }else{
            
            $mobnum  = $this->session->userdata('mobnum');
            $subtype  = $this->session->userdata('subtype');
            $token  = $this->session->userdata('token');

            $data['content']  = 'enterotp_etisalat_uae';
            $data['mobnum']   = $mobnum;
            $data['subtype']   = $subtype;
            $data['token']   = $token;
            $data['errorOtp']= "Enter Correct OTP";
            $this->load->view('template/header_auth');
            $this->load->view('enterotp_etisalat_uae',$data);
        }
    }


    /***********************************Zain Iraq************************************************/
    public function getMDNZI(){
        $ran1 = rand(11,999);
        $ran2 = date('YmdHis');
        $ran = $ran1.$ran2;
        $key = "iQ4Mh8/=";
        $comp_id = "61";
        $con_type = "757";
        $op_id = "4";
        redirect("http://zainirq.mcomviva.com/common/MSISDN.jsp?key=$key&comp_id=$comp_id&con_type=$con_type&op_id=$op_id&tid=$ran");
    }

    function mdnZI(){
        $msisdn = $this->input->get_post('msisdn');
        $key = $this->input->get_post('key');
        $comp_id = $this->input->get_post('comp_id');
        $con_type = $this->input->get_post('con_type');
        $op_id = $this->input->get_post('op_id');
        $tid = $this->input->get_post('tid');

        $this->session->set_userdata('mobnum',$msisdn);
        $this->session->set_userdata('tid',$tid);

        $tid_query = $this->db->query("select * from zain_iraq.reporo_hits where tid='$tid' order by id desc limit 1");
        if($tid_query->num_rows() > 0){
            /*$this->db->query("update zain_iraq.reporo_hits set mdn='$msisdn' where tid='$tid'");
            $this->db->query("update zain_iraq.reporo_clickid set mdn='$msisdn' where tid='$tid'");

            $date = date('dmYHis');

            $this->db->query("insert into zain_iraq.tidlogs(date,mobnum,tid,subtype) values(now(),'$msisdn','$tid','daily');");

            $ran1 = rand(1,99);
            $ran2 = date('YmdHis');
            $ran = $ran1.$ran2;
            $ip = $_SERVER['REMOTE_ADDR'];

            $url = "http://zainirq.mcomviva.com/common/CPRequest.jsp?key=$key&comp_id=$comp_id&con_type=$con_type&op_id=$op_id&price=200&charg_cycle=1&date=$date&tid=$tid&ssid=$ran&clientip=$ip&msisdn=$msisdn";

            $adnetFetch = $tid_query->row();
            $adnet = $adnetFetch->adnet;
            if($adnet=="s2s" || $adnet=="trafficcompany" || $adnet=="skytech"){
                redirect($url);die;   
            }

            $data['url']  = $url;
            $this->load->view('zainiraq_adnet.php',$data);*/
            redirect("http://mcomics.club/caign/zi_camp_redirect.php?m=$msisdn&t=$tid");
        }
        else{
            $query = $this->db->query("select * from zain_iraq.sub_users where mobnum='$msisdn' and Unsub=0");
            if($query->num_rows() > 0){
                $row = $query->row();
                $substatus = $row->SubStatus;
                if($substatus == 'SUCCESS'){
                    $this->session->set_userdata("showlogo","yes");
                    $this->session->set_userdata("subuser","yes");
                    //redirect('/index');
                    redirect(base_url());die;
                }
            }

            $data['content']  = 'Subscription';
            $data['mobnum']   = $msisdn;
            $this->load->view('template/header_auth');
            $this->load->view('sub_zain_iraq',$data);
        }
    }

    function subscribe_ZI(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $msisdn = $this->session->userdata('mobnum');
        $tid = $this->session->userdata('tid');
        $key = "iQ4Mh8/=";
        $comp_id = "61";
        $con_type = "757";
        $op_id = "4";
        $date = date('dmYHis');

        $this->db->query("insert into zain_iraq.tidlogs(date,mobnum,tid,subtype) values(now(),'$msisdn','$tid','$subtype');");

        $ran1 = rand(1,99);
        $ran2 = date('YmdHis');
        $ran = $ran1.$ran2;

        $ip = $_SERVER['REMOTE_ADDR'];

        if($subtype=="weekly"){
            $price = 1000;
            $val = 7;
        }
        else{
            $price = 200;
            $val = 1;
        }

        redirect("http://zainirq.mcomviva.com/common/CPRequest.jsp?key=$key&comp_id=$comp_id&con_type=$con_type&op_id=$op_id&price=$price&charg_cycle=$val&date=$date&tid=$tid&ssid=$ran&clientip=$ip&msisdn=$msisdn&TrackId=1001");
    }

    public function sub_ZI(){
        $d1 = serialize($_GET);

        //$d1 = mysql_real_escape_string($d1);

        $this->db->query("insert into zain_iraq.callbackSms(data,cbackfrom) values('".$d1."','redirect');");
        $tid = isset($_GET['tid']) ? $_GET['tid'] : '';
        $mobnum ='';
        $pageType = isset($_GET['pageType']) ? $_GET['pageType'] : '';
        if($pageType=="error"){
            redirect("http://google.com");die;
        }

        $query = $this->db->query("select * from zain_iraq.tidlogs where tid='$tid'");
        if($query->num_rows() > 0){
            $row = $query->row();
            $mobnum = $row->mobnum;
        }
        /*if($this->session->userdata('subuser') != 'yes'){
                $this->session->set_userdata('subuser','no');
                $this->userLoged();
            }else{
                $query = $this->checkSubUser();                
                $mobnum = $this->session->userdata('mobnum');
                
                if(count($query) and $query->num_rows()>0){                    
                }else if($mobnum=="919871666000"   or $mobnum == '919873032445' or $mobnum == '919718698337'){
                }else{
                    $this->session->sess_destroy();
                    redirect('/index');
                }
            }   

        redirect("http://mcomics.club/index");
        echo "reach";*/

        $data['msg'] ="Please wait your subscription is under process.<br>You will redirect to home page after a minute";
        $data['heading'] ="Subscription Failure";
        $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->session->set_userdata("showlogo","yes");
        $this->load->view('template/header_auth');
        $this->load->view('zainirag_msg',$data);
    }

    function zi_home(){
        $MSISDN = $this->input->get_post('msisdn');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes"); 
        redirect(base_url()."welcome/index");
    }



    function test_deep(){
        /*$this->session->set_userdata("mobnum","9647801099823");
        $this->session->set_userdata("subuser","yes");*/
        /*$data['content']  = 'Subscription';
        $this->load->view('template/header_auth');
        $this->load->view('sub_zain_iraq',$data);*/
        //redirect("/index");
        $data['url']   = "http://google.com";
        $this->load->view('zainiraq_adnet.php',$data);
    }
    function testdushyant(){
       
    }

    function checkuser_zainiraq(){
        $mobnum = $this->input->post_get('mobnum');
        $query = $this->db->query("select * from zain_iraq.sub_users where mobnum='$mobnum' and Unsub=0");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = $row->SubStatus;
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");
                echo '{"response":"done"}';
            }else if($SubStatus == 'LOWBAL'){
                echo '{"response":"LOWBAL"}';
            }else if($SubStatus == ''){
                echo '{"response":"2"}';
            }else{
                echo '{"response":"3"}';
            }
        }else{
            echo '{"response":"4"}';
        }
    }

    public function srilanka_failmsg(){
        die();
        $data['msg'] ="This service is only accessible from Dialog's Mobile Data. Please switch on mobile data and try again.";
        $data['heading'] ="Subscription Failure";
       // $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subfail_robimcomics',$data);
    }

    /********************************* SRILANKA AIRTEL *************************/
    public function subPack_airtelsl() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack_airtelsl',$data);
    }

    public function subPack_airtelsl_confirm() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $serviceid;
        $data['subtype'] = $subtype;
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('template/header_auth');
        $this->load->view('subscribepack_airtelslconfirm',$data);
    }

    public function subscribe_airtelsl(){

        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $serviceid;
        
        $mobnum              = $this->session->userdata('mobnum'); //echo $mobnum;exit;
        $result = $this->subscribe_model->checkmComics_airtelsluser($mobnum); //var_dump($result);
        if(count($result)>0){ // not happens usually
            $this->session->set_userdata("subuser","yes");
            //redirect('/index');
            redirect(base_url());die;
        }else{
            if($subtype == "monthly" || $subtype =="weekly" || $subtype == "daily"){
                
                $sublogid   = $this->subscribe_model->airtelsl_subscribe($mobnum,$subtype);
                
                $data['id']      = "";

                $data['msg'] ="Hello, we have received your request for activation.<br> Please wait for a minute your subscription under process.";
                $data['heading'] ="Subscription";
               // $data['mdn']     = $mobnum;
                $data['id']      = "";
                $this->load->view('template/header_auth');
                $this->load->view('subfail_robimcomics',$data);
            }else{
                $data['msg'] ="There is some error in subscription.Please try later.";
                $data['heading'] ="Subscription Failure";
               // $data['mdn']     = $mobnum;
                $data['id']      = "";
                $this->load->view('template/header_auth');
                $this->load->view('subfail_robimcomics',$data);
            }           
            
        }
    }

    public function unsub_airtelsl_confirm(){
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');          
        $data['subtype'] = "";
        $mdn=$mobnum;
        $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->load->view('template/header_auth');
        $this->load->view('unsubpack_airtelslconfirm',$data);
    }
    public function unsub_airtelsl() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        @file_get_contents("http://111.118.180.237/airtelsl/mcomics/unsub.php?mobnum=$mobnum&mode=WEB");
        
        $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    ////////////////////////////// vivo kuwait ////////////////////////////////

    public function subPack_vivokuwait() {
        $mobnum ="";
        $mobnum=$this->session->userdata('mobnum');
        $mdn=$mobnum;
        $data['mdn']     = $mdn;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subPack_vivokuwait',$data);
    }
    public function subscribe_vkuwait(){
        $requestid='';
        $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        for ($i = 0; $i < 25; $i++) {
            $requestid .= $characters[rand(0, strlen($characters) - 1)];
        }
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $msisdn = $this->session->userdata('mobnum');        
        $date = date('dmYHis');
        $pname = urlencode("M Comics");
        $imageurl = urlencode("http://littledata.in/images/mobile/gamefactory.jpg");

        $ip = $_SERVER['REMOTE_ADDR'];

        if($subtype=="monthly"){
            $price = 2500;
            $val = 30;
        }else if($subtype=="weekly"){
            $price = 700;
            $val = 7;
        }
        else{
            $price = 150;
            $val = 1;
        }
        $this->subscribe_model->vivokuwait_insert_subtype($msisdn,$subtype);
        
        redirect("http://182.74.46.89:8093/API/CGRequest?MSISDN=$msisdn&productID=mComics&pName=$pname&pPrice=$price&pVal=$val&CpId=PheutureD#0121&CpPwd=PheutureD$0121&CpName=Pheuture&reqMode=WAP&reqType=Subscription&ismID=17&transID=$requestid&sRenewalPrice=$price&sRenewalValidity=$val&Wap_mdata=$imageurl");
    }

    public function loginmc_vk(){ //login vivo kuwait
        echo $password =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $query = $this->subscribe_model->vivokuwait_password($password);
        if($query->num_rows()>0){
            $result = $query->result();
            $mobnum = $result[0]->Mobnum;
            $this->session->set_userdata("mobnum",$mobnum);
            $this->session->set_userdata("sub_users","yes");
            
        }
        redirect(base_url());die();
    } 

    public function vkuwait_failmsg(){
        $data['msg'] ="This service is only accessible from Vivo Mobile Data. Please switch on mobile data and try again.";
        $data['heading'] ="Subscription Failure";
       // $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subfail_robimcomics',$data);
    }

    public function loginmc_zj(){ //login vivo kuwait
        echo $password =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $query = $this->subscribe_model->zainjordan_password($password);
        if($query->num_rows()>0){
            $result = $query->result();
            $mobnum = $result[0]->Mobnum;
            $this->session->set_userdata("mobnum",$mobnum);
            $this->session->set_userdata("sub_users","yes");
            
        }
        redirect(base_url());die();
    } 

    function ip_in_range( $ip, $range ) {
        if ( strpos( $range, '/' ) == false ) {
            $range .= '/32';
        }
        // $range is in IP/CIDR format eg 127.0.0.1/24
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }

    public function fblikes()
    {
        $mob=$this->input->get_post("mobnum");
        $id=$this->input->get_post("cid");
        //$likes=$this->input->get_post("fblikes");
        //echo $mob."--".$id."--".$likes; die;
        if($mob!="" && $id!="")
        {
              $check=$this->db->query("SELECT * from fblikes where mobnum='".$mob."' and pageid='".$id."'");
              $total=$check->num_rows();
              //echo $total; die();
              if($total==0)
              {
              $query=$this->db->query("INSERT into fblikes(mobnum, pageid, date) values('$mob', '$id', now())");
              return true;
              }
              else
              {
                //$query2=$this->db->query("UPDATE fblikes set fbcount='".$likes."' where mobnum='".$mob."' and pageid='".$id."'");
                return true; 
              }
        }
        else
        {
            return false;
        }
    }

    public function updatelikes()
    {
        $id=$this->input->get_post("cid");
        $likes=$this->input->get_post("fblikes");
        //echo $id."--".$likes; die;
        if($likes!="" && $id!="" && $likes>0)
        {
            $query2=$this->db->query("UPDATE fblikes set fbcount='".$likes."' where pageid='".$id."'");
            return true;
        }
        else
        {
            return false;
        }
    }

    public function iprangerobi($ip){
        $i=1;
        $exp = explode(".", $ip);
        $iprange='';
        foreach($exp as $value){
            if($i<4){
                $iprange .= $value;
                $i++;
            }else{
                break;
            }
            
        }
        return $iprange;
    }
    public function robifaq(){
        $this->load->view('template/header_auth');
        $this->load->view("newui/robicamp_faq");
    }
    public function directmcomics(){
        $this->session->set_userdata('subuser','yes');
        $this->session->set_userdata('mobnum', "96550565748");
        redirect(base_url());
    }

    /***********************************vivo kuwait************************************************/
    
    function subpackKW(){
        $msisdn='';
        $msisdn = $this->session->userdata('mobnum');
        $query = $this->db->query("select * from vivo_kuwait.sub_users where mobnum='$msisdn' and Unsub=0");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = $row->SubStatus;
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("subuser","yes");
                redirect(base_url());die;
            }
        }
        redirect('welcome/subscribe_KW/monthly');

        $data['content']  = 'Subscription';
        $data['mobnum']   = $msisdn;
        $this->load->view('newui/template/header_auth');
        $this->load->view('sub_packkuwait',$data);
    }

    function subscribe_KW(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $msisdn = '';
        
        $key = "mT3NM3/=";
        $comp_id = "208";
        $con_type = "749";
        $op_id = "3";
        $date = date('dmYHis');
        $ran1 = rand(1,99);
        $ran2 = date('YmdHis');
        $ran = $ran1.$ran2;
        $this->session->set_userdata('tid',$ran);
        $tid = $this->session->userdata('tid');
        $this->db->query("insert into vivo_kuwait.tidlogs(date,mobnum,tid,subtype) values(now(),'$msisdn','$tid','$subtype');");

       

        $ip = $_SERVER['REMOTE_ADDR'];

        if($subtype=="monthly"){
            $price = 3000;
            $val = 30;
        }else if($subtype=="weekly"){
            $price = 700;
            $val = 7;
        }
        else{
            $price = 100;
            $val = 1;
        }
        
        redirect("http://vascld-afl.mcomviva.com:8112/AFL/MPPRedirect?key=$key&comp_id=$comp_id&con_type=$con_type&op_id=$op_id&price=$price&charg_cycle=$val&date=$date&tid=$tid&ssid=$ran&clientip=$ip&msisdn=$msisdn");
    }

    public function sub_KW(){
        $d1 = serialize($_GET);

        //$d1 = mysql_real_escape_string($d1);
        $mobnum ='';
        $this->db->query("insert into vivo_kuwait.callbackSms(data,cbackfrom) values('".$d1."','redirect');");
        $tid        = isset($_GET['tid'])       ? $_GET['tid'] : '';
        $mobnum     = isset($_GET['msisdn'])    ? $_GET['msisdn'] : '';
        $key        = isset($_GET['key'])       ? $_GET['key'] : '';
        $comp_id    = isset($_GET['comp_id'])   ? $_GET['comp_id'] : '';
        $charg_cycle= isset($_GET['charg_cycle']) ? $_GET['charg_cycle'] : '';
        $price      = isset($_GET['price'])     ? $_GET['price'] : '';
        $op_id      = isset($_GET['op_id'])     ? $_GET['op_id'] : '';
        $substat    = isset($_GET['substat'])   ? $_GET['substat'] : '';

        if($substat=="0"){
            $this->session->set_userdata("mobnum",$mobnum);
            $data['msg'] ="Please wait your subscription is under process.<br>You will redirect to home page after a minute";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('vivokw_succmsg',$data);
        }else if($substat=="5"){
            $this->session->set_userdata("mobnum",$mobnum);
            $this->session->set_userdata("subuser","yes");
            redirect(base_url());die;
        }else if($substat=="6"){
            $data['msg'] ="Your are not allowed to access the portal.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }else if($substat=="1"){
            $data['msg'] ="You have cancelled the subsciption. To access the portal pls. try subscription again.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }else{
            $data['msg'] ="Failure. Please try after sometime.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
    }
    

    function checkuser_vivokuwait(){
        $mobnum = $this->input->post_get('mobnum');
        $query = $this->db->query("select * from vivo_kuwait.sub_users where mobnum='$mobnum' and Unsub=0");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = strtoupper($row->SubStatus);
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");
                echo '{"response":"done"}';
            }else if($substatus == 'LOWBAL'){
                echo '{"response":"LOWBAL"}';
            }else if($substatus == ''){
                echo '{"response":"2"}';
            }else{
                echo '{"response":"3"}';
            }
        }else{
            echo '{"response":"4"}';
        }
    }

    function vivokuwait_checkuserstatus(){
        $mobnum = $this->session->userdata('mobnum');
        $query = $this->subscribe_model->check_vivoKuwaituserStatus($mobnum);
        if($query->num_rows()>0){
            $result = $query->row();
            //print_r($result);//die;
            $Substatus = $result->SubStatus;//die;
            if(strtoupper($Substatus)=='LOWBAL'){                
                redirect(base_url() . 'vivokw_errormsg', 'refresh');
               
                //die;
            }else if(strtoupper($Substatus)!='SUCCESS'){
                $this->session->sess_destroy();
                redirect(base_url() . 'subpackKW', 'refresh');
            }
        }else{
            $this->session->sess_destroy();
            redirect(base_url() . 'subpackKW', 'refresh');
        }   
    }
    public function vivokw_errormsg(){
        $data['msg'] ="Dear Customer ! Please recharge your line to continue the services.<br/>For Unsubscribe click <a style='color:#000' href='unsubscribe_mcomics'>here</a>";
        $data['heading'] ="Subscription Failure";
        $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('subfail_robimcomics',$data);
    }

    public function subkw_user(){
        $mobnum = $this->input->post_get('msisdn');
        $query = $this->db->query("select * from vivo_kuwait.sub_users where mobnum='$mobnum' and Unsub=0");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = strtoupper($row->SubStatus);
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");
                redirect(base_url());
            }else if($substatus == 'LOWBAL'){
                redirect(base_url() . 'vivokw_errormsg', 'refresh');
            }else{
                //$this->session->sess_destroy();
                redirect(base_url() . 'subpackKW', 'refresh');
            }
        }else{
            redirect(base_url() . 'subpackKW', 'refresh');
        }
    }

    /***********************************zain jordan************************************************/
    
    function subpackZJ(){
        $msisdn='';
        $msisdn = $this->session->userdata('mobnum');
        $query = $this->db->query("select * from zain_jordan.sub_users where mobnum='$msisdn' and Unsub=0 and SubStatus<>''");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = $row->SubStatus;
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("subuser","yes");
                //$this->session->set_userdata("showlogo","yes");
                redirect(base_url());die;
            }
        }

        $data['content']  = 'Subscription';
        $data['mobnum']   = $msisdn;
        $this->load->view('newui/template/header_auth');
        $this->load->view('sub_packzjordan',$data);
    }

    function subscribe_ZJ(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $msisdn = $this->session->userdata('mobnum');

        $ran = rand(1,99999).time();

        //$url = "http://cgw-comviva.jo.zain.com:8093/API/CGRequest?CpId=WHYFI&MSISDN=$msisdn&productID=18000&pName=mComics&pPrice=0.2&pVal=1&CpPwd=WHYFI@123&CpName=WHYFI&reqMode=WAP&reqType=Subscription&ismID=17&transID=$ran&sRenewalPrice=0.2&sRenewalValidity=1&Wap_mdata=%23F0F8FF%7Chttp%3a%2f%2f111.118.180.237%2fooredoo%2fimage-icon.png%7CNA%7CNA%7C150X150&request_locale=en&serviceType=DOB_JORDAN&planId=WH_18000_PID&opId=1";

        $url = "http://cgw-comviva.jo.zain.com:8093/API/CGRequest?MSISDN=$msisdn&productID=18000&pName=mComics&pPrice=0.2&pVal=1&CpId=WHYFI&CpPwd=WHYFI@123&CpName=WHYFI&reqMode=WAP&reqType=Subscription&ismID=17&sRenewalPrice=0&sRenewalValidity=0&serviceDesc=mComics&Songname=de&serviceType=DOB_JORDAN&planId=WH_18000_PID&request_locale=ar&Wap_mdata=http://111.118.180.237/zain_jordan/img_icon.png&opId=1&transID=$ran";

        $this->db->query("insert into zain_jordan.temp(server,date) values('".serialize($url)."',now())");

        redirect($url);
    }

    public function sub_ZJ(){
        $substat    = isset($_GET['substat'])   ? $_GET['substat'] : '';
        //$mobnum ='962799719799';
        //$this->session->set_userdata('mobnum',$mobnum);
        $mobnum = $this->session->userdata("mobnum");
        $d1 = serialize($_GET);

        //$d1 = mysql_real_escape_string($d1);
        
        $this->db->query("insert into zain_jordan.callbackSms(data,cbackfrom) values('".$d1."','redirect');");
        if($substat=="0"){
            $data['msg'] ="يرجى الانتظار اشتراكك قيد العملية. سيتم توجيهك إلى الصفحة الرئيسية بعد دقيقة.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('zainJordan_succmsg',$data);
        }else if($substat=="5"){
            redirect(base_url());die;
        }else if($substat=="6"){
            $data['msg'] ="غير مسموح لك بالوصول إلى البوابة.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }else if($substat=="1"){
            $data['msg'] ="لقد ألغيت الاشتراك. للوصول إلى البوابة من فضلك. حاول الاشتراك مرة أخرى.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }else{
            $data['msg'] ="بالفشل. يرجى المحاولة بعد دقائق";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
    }
    

    function checkuser_zainJordan(){
        $mobnum = $this->input->post_get('mobnum');
        $query = $this->db->query("select * from zain_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus<>''");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = strtoupper($row->SubStatus);
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");
                //$this->session->set_userdata("showlogo","yes");
                echo '{"response":"done"}';
            }else if($substatus == 'LOWBAL'){
                echo '{"response":"LOWBAL"}';
            }else if($substatus == ''){
                echo '{"response":"2"}';
            }else{
                echo '{"response":"3"}';
            }
        }else{
            echo '{"response":"4"}';
        }
    }

    function zainjordan_checkuserstatus(){
        $mobnum = $this->session->userdata('mobnum');
        $query = $this->subscribe_model->check_zainJordanuserStatus($mobnum);
        if($query->num_rows()>0){
            $result = $query->row();
            //print_r($result);//die;
            $Substatus = $result->SubStatus;//die;
            if(strtoupper($Substatus)=='LOWBAL'){                
                redirect(base_url() . 'zainjordan_errormsg', 'refresh');
               
                //die;
            }else if(strtoupper($Substatus)!='SUCCESS'){
                $this->session->sess_destroy();
                redirect(base_url() . 'subpackZJ', 'refresh');
            }
        }else{
            $this->session->sess_destroy();
            redirect(base_url() . 'subpackZJ', 'refresh');
        }   
    }
    public function zainjordan_errormsg(){
        $data['msg'] ="Dear Customer ! Please recharge your line to continue the services.<br/>";
        $data['heading'] ="Subscription Failure";
        $data['mdn']     = $mobnum;
        $data['id']      = "";
        $this->load->view('newui/template/header_auth');
        $this->load->view('subfail_robimcomics',$data);
    }

    public function callback_ZJ(){
        $mobnum = $this->input->post_get('mobnum');
        $this->session->set_userdata("mobnum",$mobnum);
        
        //redirect(base_url().'index');
        $this->db->query("insert into zain_jordan.callbackSms(data,cbackfrom) values('".$mobnum."','redirect1');");
        $query = $this->db->query("select * from zain_jordan.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
        if($query->num_rows() > 0){
            $this->session->set_userdata("subuser","yes");
            redirect(base_url().'index');
        }
        redirect(base_url().'sub_ZJ?substat=0');
    }

    /************** mtn nigeria *********************/

    public function subscribe_mtnnga()
    {
        $data['id']      = "";
        $data['heading'] = "Subscription";
        $data['content']='subs_mtnnga';
        $this->load->view('newui/template/header_auth');
        $this->load->view('subs_mtnnga',$data);
    }

    public function sub_mtnnga(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $serviceid;
        
        $mobnum              = $this->session->userdata('mobnum'); //echo $mobnum;exit;
        //$mobnum = '2348036729440';
        $result = $this->subscribe_model->check_mtnnga_user($mobnum); 
        
        if(count($result)>0){ // not happens usually
            redirect('/home');
        }else{
            if($subtype == 'monthly'){
                $serviceid='234012000024090';
                $productID='23401220000027995';
                $spid='2340110011541';                
            }else{
                $serviceid='234012000024090';
                $productID='23401220000027993';
                $spid='2340110011541';
            }

            $status = $this->subscribe_model->mtnnga_subscribe($mobnum,$subtype,'',$productID);
            if($status=="SUBSCRIBED" || $status=="EXISTING"){
                $this->session->set_userdata("mobnum",$mobnum);
                $data['msg'] ="Please confirm your subscription on ussd.<br>You will redirect to home page after this.";
                $data['heading'] ="Subscription Failure";
                $data['mdn']     = $mobnum;
                $data['id']      = "";
                $this->load->view('newui/template/header_auth');
                $this->load->view('mtnnga_succmsg',$data);
            }else{
                //$data['error']      = "please try again.";
                redirect('welcome/subscribe_mtnnga');
            }
        }
    }

    /*public function sub_mtnnga()
    {
        echo $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;//echo $serviceid;
        
        $mobnum              = $this->session->userdata('mobnum'); //echo $mobnum;exit;
        $mobnum = '2348036729440';
        $result = $this->subscribe_model->check_mtnnga_user($mobnum); 
        
        if(count($result)>0){ // not happens usually
            redirect('/home');
        }else{
            if($subtype == 'monthly'){
                $serviceid='234012000024090';
                $productID='23401220000027995';
                $spid='2340110011541';                
            }else if($subtype == 'weekly'){
                $serviceid='234012000024090';
                $productID='23401220000027994';
                $spid='2340110011541';
            }else{
                $serviceid='234012000024090';
                $productID='23401220000027993';
                $spid='2340110011541';
            }
            
            $nonce = rand(1111111,9999999).rand(111111,9999999);
            $created = date('Y-m-d\TH:i:s\Z');
            $password = "bmeB500";

            $hash = $nonce . $created . $password;
            $password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));

            $tid= "0110000"."0646".date("YmdHis").rand(1111,9999).rand(111,999);
            $this->session->set_userdata('mtnnga_subtype',$subtype);
            $url ="http://41.206.4.159:80/portalone/otp/nigeria?notificationURL=".urlencode("http://mcomics.club/welcome/mtnnga_notify")."&spAccount=$spid&spPasswd=".urlencode($password_coded)."&endUserIdentifier=".$mobnum."&scope=99&transactionId=".$tid."&productID=$productID&nonce=".$nonce."&created=".$created."&serviceid=$serviceid";
            $this->subscribe_model->mtnnga_cgurl($mobnum,$url); 
            //echo $url;die();
            redirect($url);
            die();                                
        }
    }

    public function mtnnga_notify(){
        $mobnum = $this->get_MDN();
        $QUERY_STRING=$_SERVER['QUERY_STRING'];
        $d = $_REQUEST;
        $d = serialize($d);
        $result=$this->input->get_post('result');
        $this->subscribe_model->mtnnga_cgLogs($mobnum,$QUERY_STRING);
        $result=0;
        if($result=="0"){
            $this->subscribe_model->mtnnga_cgLogs_details($mobnum,$QUERY_STRING);
            $subtype=$this->session->userdata('mtnnga_subtype');
            /* if($mobnum=="2348036729440"){
                
            }
            else{
                $status = $this->info_model->mtnnga_subscribe($mobnum,$subtype);
            } *
        
            $ex = explode('&',$QUERY_STRING);
            $a_arr = explode('=',$ex[2]);
            if($a_arr[0] == 'authToken'){
                $atoken = $a_arr[1];
            }else{
                $characters = '0123456789';
                $string2 = '';
                $max = strlen($characters) - 1;
                for ($i = 0; $i < 14; $i++) {
                  $string2 .= $characters[mt_rand(0, $max)];
                }
                $atoken = "010000".$string2;
            }
            

            if($subtype == 'monthly'){
                $serviceid='234012000024090';
                $productID='23401220000027995';
                $spid='2340110011541';                
            }else if($subtype == 'weekly'){
                $serviceid='234012000024090';
                $productID='23401220000027994';
                $spid='2340110011541';
            }else{
                $serviceid='234012000024090';
                $productID='23401220000027993';
                $spid='2340110011541';
            }

            $status = $this->subscribe_model->mtnnga_subscribe($mobnum,$subtype,$atoken,$productID);
            if($status=="SUBSCRIBED" || $status=="EXISTING"){
                $this->session->set_userdata("mobnum",$mobnum);
                $data['msg'] ="Please wait your subscription is under process.<br>You will redirect to home page after a minute";
                $data['heading'] ="Subscription Failure";
                $data['mdn']     = $mobnum;
                $data['id']      = "";
                $this->load->view('newui/template/header_auth');
                $this->load->view('mtnnga_succmsg',$data);
            }else{
                //$data['error']      = "please try again.";
                redirect('welcome/subscribe_mtnnga');
            }
        }
        else{
            $data['msg'] ="Failure. Please try after sometime.";
            $data['heading'] ="Subscription Failure";
            $data['mdn']     = $mobnum;
            $data['id']      = "";
            $this->load->view('newui/template/header_auth');
            $this->load->view('subfail_robimcomics',$data);
        }
    }*/

    function checkuser_mtnnga(){
        $mobnum = $this->input->post_get('mobnum');
        $query = $this->db->query("select * from mtnngria_mc.sub_users_sdp where mobnum='$mobnum' and Unsub=0");
        if($query->num_rows() > 0){
            $row = $query->row();
            $substatus = $row->SubStatus;
            if($substatus == 'SUCCESS'){
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");
                echo '{"response":"done"}';
            }else if($SubStatus == 'LOWBAL'){
                echo '{"response":"LOWBAL"}';
            }else if($SubStatus == ''){
                echo '{"response":"2"}';
            }else{
                echo '{"response":"3"}';
            }
        }else{
            echo '{"response":"4"}';
        }
    }

    public function testing(){
        //$this->session->set_userdata("mobnum",'96550565748');//vivo kuwait
        $this->session->set_userdata("mobnum",'962799719799');//jordan
        $this->session->set_userdata("subuser","yes");
    }

    function getmdn_kw(){
        echo "OK";
    }
    /************************************T-Pay Egypt*********************************/
    function egy_subs(){
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/subscribepack_egy',$data);
    }

    function egy_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        //echo $subtype." : ".$lang;die;
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://eg.mcomics.club/welcome/egy_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_egypt.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=60201&msisdn=201286438693";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function egy_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_egypt.tidlogs set stoken='$stoken' where tid='".$tid."'");

        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_egypt.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1030;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1029;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1028;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://eg.mcomics.club/welcome/egy_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_egypt.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="ar"){
                if($subtype=="daily"){
                    $data['subtype'] = "3 جنيهات مصرية يومياً";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "12.5 جنيه اسبوعيا";    
                }
                else{
                    $data['subtype'] = "50 جنيه شهريا";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "3 Egyptian Pounds daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "12.5 Egyptian Pounds weekly";    
                }
                else{
                    $data['subtype'] = "50 Egyptian Pounds monthly";    
                }
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/egy_mobnum',$data);
            /*$tid = $this->input->get_post('OrderId');
            redirect(base_url().'welcome/egy_consent_callback?ReferenceCode=&OrderId='.$tid);die;*/
        }
    }

    function egy_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_egypt.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_egypt.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_egypt.tidlogs where tid='".$tid."'");
                $subtype = $getSubType->row()->subtype;
                $lang = $getSubType->row()->lang;

                $data['subtype'] = $subtype;
                $data['lang'] = $lang;

                $this->load->view('newui/template/header_auth');
                $this->load->view('newui/egy_pass',$data);
            }
            else{
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");    
                redirect(base_url()."welcome/index");
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_egypt.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_egypt.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1030;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1029;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1028;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteInitialPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";
            $ExecuteRecurringPaymentNow = false;        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $allowMultipleFreeStartPeriods = false;
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;
            $this->db->query("insert into tpay_egypt.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp_resend');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    redirect(base_url()."welcome/egy_otp?sid=".$sid."&tid=".$tid);
                }
                redirect(base_url()."welcome/egy_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/egy_subs");die;    
                }
                redirect(base_url()."welcome/egy_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/egy_subs");
            }
        }
    }

    function eg_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select subtype,lang from tpay_egypt.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/egy_unsub',$data);
    }

    function egy_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $query = $this->db->query("select * from tpay_egypt.sub_users where mobnum='$mobnum'");
        $getSubType = $this->db->query("select subtype,lang from tpay_egypt.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;

        $sid = $query->row()->productID;
        $this->egy_unsubscription($sid);

        $this->session->sess_destroy();

        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }
        
        //$data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function egy_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function egy_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $data['sid'] = $sid;
        //$data['sid'] = 613348;
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_egypt.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="ar"){
            if($subtype=="daily"){
                $data['subtype'] = "3 جنيهات مصرية يومياً";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "12.5 جنيه اسبوعيا";    
            }
            else{
                $data['subtype'] = "50 جنيه شهريا";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "3 Egyptian Pounds daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "12.5 Egyptian Pounds weekly";    
            }
            else{
                $data['subtype'] = "50 Egyptian Pounds monthly";    
            }
        }
        $data['lang'] = $lang;
        $data['mobnum'] = $mobnum;
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/pin_egy',$data);        
    }

    function egy_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function egy_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid.$otp;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "pinCode": "'.$otp.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function egy_home(){
	error_log("EGYPT".serialize($_SERVER));
        $MSISDN = $this->input->get_post('mobnum');
        if($MSISDN==""){
            $MSISDN = $this->input->get_post('msisdn');    
        }
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_egypt.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        $lang="ar";
        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }
        redirect(base_url()."welcome/index");
    }

    function egy_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function egy_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_egypt.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function egy_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_egypt.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function egLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_egypt.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $qry_lang = $this->db->query("select * from tpay_egypt.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        $subtype = $qry_lang->row()->subtype;
        $data['lang'] = $lang;
        $data['oper'] = $oper;


        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/egy_login',$data);
    }

    function egy_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_egypt.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function eg_test(){
        redirect("http://eg.mcomics.club/welcome/eg_test1");
    }

    function eg_test1(){
	ob_start(); 
        file_get_contents("http://connectexpress.in/api/v3/index.php?method=sms&api_key=A9635d568b4d0c86f992f8f836bd3758d&to=8586076390&sender=SOLODG&message=".urlencode("123456")."&format=json");
	ob_flush();
sleep(1);
        $this->load->view('newui/otp_test');
	ob_end_flush();
    }

    function eg_test2(){
        $ran = rand(1,2);
        if($ran==1){
            $res = "SUCCESS";
        }
        else{
            $res = "FAIL";
        }
        redirect("http://eg.mcomics.club/welcome/eg_test3?Msisdn=8586076390&Response=$res");
    }

    function eg_test3(){
        print_r($_GET);die;
    }


    function egy_camp1(){

        $adnet = 'internal';
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    
        $subtype ="daily";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);

        $this->db->query("insert into tpay_egypt.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$tid','$QUERY_STRING','$REMOTE_ADDR','$HTTP_USER_AGENT',now(),'$adnet','$referrer');");
        $this->db->query("insert into tpay_egypt.tokenlogs(mdn,token,date,adnet,ip) values('','$tid',now(),'internal','$REMOTE_ADDR')");

        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://eg.mcomics.club/welcome/egy_camp2";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_egypt.tidlogs(tid,subtype,date) values('".$tid."','".$subtype."',now())");


        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        redirect($url);die;
    }

    function egy_camp2(){
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');

        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype from tpay_egypt.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1030;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1029;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1028;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://eg.mcomics.club/welcome/egy_camp4";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.'en';

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=en&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $url="http://eg.mcomics.club/welcome/egy_camp1";
            redirect($url);die;
        }
    }

    function egy_camp3(){
        print_r($_GET);die;
    }

    function egy_camp4(){
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_egypt.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            //echo '{"response":"SUCCESS","errorMessage":""} ';die;
            $redirectUrl="http://eg.mcomics.club/welcome/egy_camp3?Msisdn=$mobnum&Response=SUCCESS";
            redirect($redirectUrl);die;
        }
        else{
            $this->db->query("update tpay_egypt.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $this->db->query("update tpay_egypt.tokenlogs set mdn='".$mobnum."' where token='".$tid."'");
            $getSubType = $this->db->query("select subtype from tpay_egypt.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1030;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1029;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1028;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteInitialPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";
            $ExecuteRecurringPaymentNow = false;        
            $AutoRenewContract = true;
            $Language = 1;
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $allowMultipleFreeStartPeriods = false;
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": ""
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            $this->db->query("insert into tpay_egypt.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            //print_r($jd);die;

            if($jd->operationStatusCode=="0"){
                //echo '{"response":"SUCCESS","errorMessage":""} ';die;
                $this->db->query("INSERT INTO tpay_egypt.appverify_logs_4g(mobnum,`date`,response,adnet) VALUES('$mobnum',now(),'".$out."','internal'");
                $redirectUrl="http://eg.mcomics.club/welcome/egy_camp3?Msisdn=$mobnum&Response=SUCCESS";
                redirect($redirectUrl);die;
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    //echo '{"response":"FAIL","errorMessage":""} ';die;
                    $redirectUrl="http://eg.mcomics.club/welcome/egy_camp3?Msisdn=$mobnum&Response=FAIL";
                    redirect($redirectUrl);die;
                }
                //echo '{"response":"SUCCESS","errorMessage":""} ';die;
                $redirectUrl="http://eg.mcomics.club/welcome/egy_camp3?Msisdn=$mobnum&Response=SUCCESS";
                redirect($redirectUrl);die;
            }
            else{
                //echo '{"response":"FAIL","errorMessage":""} ';die;  
                $redirectUrl="http://eg.mcomics.club/welcome/egy_camp3?Msisdn=$mobnum&Response=FAIL";
                redirect($redirectUrl);die;
            }
        }
    }


    function egy_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://eg.mcomics.club/welcome/egy_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_egypt.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function egy_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_egypt.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_egypt.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    }



    /*************************Tpay Egypt End*****************************************/

    /************************************T-Pay Bahrain*********************************/
    function bbah_subs(){
        $this->load->view('newui/template/header_bbah');
        $this->load->view('newui/bbah_subs',$data);
    }

    function bah_subs_zain(){
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/bah_subscribepack_zain',$data);
    }

    function bah_mobnum_zain(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $tid = date('YmdHms').rand(11,999);
        $this->db->query("insert into tpay_bahrain.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.15 BHD daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "0.7 BHD weekly";    
            }
            else{
                $data['subtype'] = "2.5 BHD monthly";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.15 دينار بحريني اليومي";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "0.7 دينار بحريني أسبوعي";    
            }
            else{
                $data['subtype'] = "2.5 دينار بحريني شهريا";    
            }
        }
        $data['tid'] = $tid;
        $data['lang'] = $lang;
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/bah_mobnum_zain',$data);
    }

    function bbah_mobnum(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        if($lang=="css"){
            die;
        }
        //echo $subtype." : ".$lang;die;

        $tid = date('YmdHms').rand(11,999);
        $this->db->query("insert into tpay_bahrain.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.15 BHD daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "0.7 BHD weekly";    
            }
            else{
                $data['subtype'] = "2.5 BHD monthly";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
            }
            else{
                $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
            }
        }
        $data['tid'] = $tid;
        $data['lang'] = $lang;
        $this->load->view('newui/template/header_bbah');
        $this->load->view('newui/bbah_mobnum',$data);
    }


    function bah_subs(){
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/bah_subscribepack',$data);
    }

    function bah_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://br.mcomics.club/welcome/bah_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_bahrain.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42602&msisdn=97336345549";

        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";


        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function bah_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_bahrain.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_bahrain.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1143;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1142;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1141;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://br.mcomics.club/welcome/bah_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_bahrain.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            $this->load->view('newui/template/header_bbah');
            $this->load->view('newui/bbah_mobnum',$data);


            /*if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily (5% VAT excluded)";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly (5% VAT excluded)";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly (5% VAT excluded)";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/bah_mobnum',$data);*/
        }
    }

    function bah_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid    = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper   = $this->input->get_post('OperatorCode');
        $Status = $this->input->get_post('Status');
       
        
        $checkSub = $this->db->query("select * from tpay_bahrain.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/bahrain/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_bahrain.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang,oper from tpay_bahrain.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $data['lang'] = $lang;
                $subtype = $getSubType->row()->subtype;
                $data['oper'] = $oper;

                if($oper=="42601"){
                    if($lang=="en"){
                        if($subtype=="daily"){
                            $data['subtype'] = "0.15 BHD daily";    
                        }
                        else if($subtype=="weekly"){
                            $data['subtype'] = "0.7 BHD weekly";    
                        }
                        else{
                            $data['subtype'] = "2.5 BHD monthly";    
                        }
                    }
                    else{
                        if($subtype=="daily"){
                            $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                        }
                        else if($subtype=="weekly"){
                            $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                        }
                        else{
                            $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                            $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                        }
                    }
                    $this->load->view('newui/template/header_bbah');
                    $this->load->view('newui/bbah_pass',$data);
                }
                else{
                    $this->load->view('newui/template/header_auth');
                    $this->load->view('newui/bah_pass',$data);
                }
            }
            else{
                redirect(base_url()."welcome/bah_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_bahrain.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_bahrain.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1143;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1142;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1141;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";
            $ExecuteRecurringPaymentNow = false;        
            $AutoRenewContract = true;
            //if($oper=="42602" || $oper=="42601"){
            if($oper=="42602"){
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }
            /*else if($oper=="42601"){
                $checkSub = $this->db->query("select * from tpay_bahrain.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
                if($checkSub->num_rows()==0){
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                    $ExecuteInitialPaymentNow = false;
                    $allowMultipleFreeStartPeriods = true;
                }
                else{
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                    $ExecuteInitialPaymentNow = true;
                    $allowMultipleFreeStartPeriods = false;
                }
            }*/
            else{
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }

            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $out = curl_exec($ch);
            //$info = curl_getinfo($ch);
            curl_close($ch);
            $data = serialize($out);
            
            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;*/
            $this->db->query("insert into tpay_bahrain.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/bah_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/bah_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    if($oper=="42602"){
                        redirect("http://bz.mcomics.club/welcome/bah_subs_zain");die;
                    }
                    elseif($oper=="42601"){
                        redirect("http://bb.mcomics.club/welcome/bbah_subs");die;
                    }
                    else{
                        redirect(base_url()."welcome/bah_subs");die;
                    }
                }
                redirect(base_url()."welcome/bah_home?mobnum=".$MSISDN);die;
            }
            else{
                if($oper=="42602"){
                    redirect("http://bz.mcomics.club/welcome/bah_subs_zain");die;
                }
                else if($oper=="42601"){
                    redirect("http://bb.mcomics.club/welcome/bbah_subs");die;
                }
                else{
                    redirect(base_url()."welcome/bah_subs");die;
                }
            }
        }
        //}
    }

    function ba_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_bahrain.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $oper = $getSubType->row()->oper;
        $subtype = $getSubType->row()->subtype;
        $data['lang'] = $lang;
        $data['oper'] = $oper;

        if($oper=="42601"){
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
            }
            $this->load->view('newui/template/header_bbah');
            $this->load->view('newui/bbah_unsub',$data);
        }
        else{
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/bah_unsub',$data);
        }
    }

    function bah_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_bahrain.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['oper'] = $oper;
        $query = $this->db->query("select * from tpay_bahrain.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->bah_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function bah_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function bah_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['tid'] = $tid;
        $data['transactionId'] = $transactionId;
        //$data['sid'] = 613348;
        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_bahrain.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        $oper = $getSubType->row()->oper;

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['oper'] = $oper;

        if($oper=="42604"){
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily (5% VAT excluded)";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly (5% VAT excluded)";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly (5% VAT excluded)";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (5٪ ضريبة القيمة المضافة مستبعدة)";    
                }
            }
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/bah_pin',$data);
        }
        else if($oper=="42601"){
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
            }
            $this->load->view('newui/template/header_bbah');
            $this->load->view('newui/bbah_pin',$data);
        }
        else{
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly";
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly";
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي";    
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا";    
                }
            }
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/bah_pin',$data);
        }        
    }

    function bah_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;
        $this->db->query("insert into tpay_bahrain.callback_request(req,data,cbackfrom) values('".$dataString."','".$out."','otp_resend');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function bah_otp_verify(){
        $oper = $this->input->get_post('oper');
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $otp = $this->input->get_post('otp');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        //if($oper=="42602" || $oper=="42601"){
        if($oper=="42602"){
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }
        /*else if($oper=="42601"){
            $checkSub = $this->db->query("select * from tpay_bahrain.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $Message = $sid.$otp;
                $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
                $dataString = '{
                    "signature": "'.$Digest.'",
                    "pinCode": "'.$otp.'",
                    "subscriptionContractId": "'.$sid.'"
                }';
            }
            else{
                $charge = 'true';
                $Message = $sid.$otp.$tid.$charge;
                $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
                $dataString = '{
                    "signature": "'.$Digest.'",
                    "pinCode": "'.$otp.'",
                    "subscriptionContractId": "'.$sid.'",
                    "charge": true,
                    "transactionId": "'.$tid.'"
                }';
            }
        }*/
        else{
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;
        $this->db->query("insert into tpay_bahrain.callback_request(req,data,cbackfrom) values('".$dataString."','".$out."','otp_verify');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function bah_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_bahrain.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }
        redirect(base_url()."welcome/index");
    }

    function bah_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/bahrain/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function bah_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_bahrain.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/bahrain/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function bah_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_bahrain.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function baLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_bahrain.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $qry_lang = $this->db->query("select * from tpay_bahrain.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        $subtype = $qry_lang->row()->subtype;
        $data['lang'] = $lang;
        $data['oper'] = $oper;

        if($data['oper']=="42601"){
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 BHD daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 BHD weekly";    
                }
                else{
                    $data['subtype'] = "2.5 BHD monthly";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.15 دينار بحريني اليومي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.15 / يوم (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "0.7 دينار بحريني أسبوعي (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 0.7 / أسبوع (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
                else{
                    $data['subtype'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['pack'] = "2.5 دينار بحريني شهريا (خاضعة لضريبة القيمة المضافة بنسبة 10%)";
                    $data['disclaimer'] = "بالاشتراك في الخدمة, فانت توافق علي الشروط والاحكام للخدمة, و تفوض Batelco  لمشاركة رقم هاتفك مع مقدم الخدمة mComics و هو من يدير الاشتراك فالخدمة.<br/>الاشتراك سوف يتم تجديده تلقائيا و سوف يتم خصم BHD 2.5 / شهريا (subject to 10% VAT) حتي يتم الغاء الاشتراك<br/>الغاء الاشتراك ننمكت يتم عن طريق My Account او ارسال UNSUB MCOMICS الي رقم 94466<br/>سيتم تطبيق قوالب تصفح البيانات المعيارية. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أن تكون قد حصلت على إذن من والديك أو الشخص المفوض بدفع فاتورتك.<br/>لأية استفسارات يرجى الاتصال بنا على support@pheuture.com";
                }
            }
            $this->load->view('newui/template/header_bbah');
            $this->load->view('newui/bbah_login',$data);
        }
        else{
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/bah_login',$data);
        }
    }

    function bah_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_bahrain.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }


    function bah_demo(){
        $MSISDN = '919871666000';
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        redirect(base_url()."welcome/index");
    } 


    function bah_he_camp(){
        $subtype = "monthly";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://br.mcomics.club/welcome/bah_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_bahrain.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function bah_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_bahrain.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_bahrain.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    }
    /*************************Tpay Bahrain End*****************************************/

    /************************************T-Pay Qatar*********************************/
    function qat_subs(){
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_subscribepack',$data);*/

        $this->load->view('newui/template/header_qao');
        $this->load->view('newui/qao_subscribepack',$data);
    }

    function qat_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://q.mcomics.club/welcome/qat_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_qatar.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function qat_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_qatar.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_qatar.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://q.mcomics.club/welcome/qat_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_qatar.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "1 QAR daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "7 QAR weekly";    
                }
                else{
                    $data['subtype'] = "30 QAR monthly";    
                }
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "1 ريال قطري اليومي";
                    $data['pack'] = "مجانا لمدة يوم واحد ثم 1 ريال يوميا (تجديد تلقائي)";
                    $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 1 ريال قطري يوميًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "7 ريال قطري أسبوعي";    
                    $data['pack'] = "مجانا لمدة يوم واحد ثم 7 ريال اسبوعيا (تجديد تلقائي)";
                    $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 7 ريال قطري أسبوعيًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                }
                else{
                    $data['subtype'] = "30 ريال قطري شهريا";    
                    $data['pack'] = "مجاناً ليوم واحد ثم 40 ريال شهريا (تجديد تلقائي)";
                    $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم تحصيل 40 ريال قطري شهريًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                }
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            /*$this->load->view('newui/template/header_auth');
            $this->load->view('newui/qat_mobnum',$data);*/

            $this->load->view('newui/template/header_qao');
            $this->load->view('newui/qao_mobnum',$data);

            /*$tid = $this->input->get_post('OrderId');
            redirect(base_url().'welcome/egy_consent_callback?ReferenceCode=&OrderId='.$tid);die;*/
        }
    }

    function qat_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_qatar.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/qatar/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_qatar.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_qatar.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "1 QAR daily";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "7 QAR weekly";    
                    }
                    else{
                        $data['subtype'] = "30 QAR monthly";    
                    }
                }
                else{
                    if($subtype=="daily"){
                        $data['subtype'] = "1 ريال قطري اليومي";
                        $data['pack'] = "مجانا لمدة يوم واحد ثم 1 ريال يوميا (تجديد تلقائي)";
                        $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 1 ريال قطري يوميًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "7 ريال قطري أسبوعي";    
                        $data['pack'] = "مجانا لمدة يوم واحد ثم 7 ريال اسبوعيا (تجديد تلقائي)";
                        $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 7 ريال قطري أسبوعيًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                    }
                    else{
                        $data['subtype'] = "30 ريال قطري شهريا";    
                        $data['pack'] = "مجاناً ليوم واحد ثم 40 ريال شهريا (تجديد تلقائي)";
                        $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم تحصيل 40 ريال قطري شهريًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
                    }
                }
                $data['lang'] = $lang;
                /*$this->load->view('newui/template/header_auth');
                $this->load->view('newui/qat_pass',$data);*/

                $this->load->view('newui/template/header_qao');
                $this->load->view('newui/qao_pass',$data);
            }
            else{
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");    
                redirect(base_url()."welcome/index");
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_qatar.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_qatar.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            //if($oper=="42701"){
            $checkSub = $this->db->query("select * from tpay_qatar.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else if($oper=="42701"){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = false;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            /*}
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/qat_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/qat_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/qat_subs");die;    
                }
                redirect(base_url()."welcome/qat_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/qat_subs");
            }
        }
    }

    function qa_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_qatar.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_qatar.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 QAR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 QAR weekly";    
            }
            else{
                $data['subtype'] = "30 QAR monthly";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 ريال قطري اليومي";
                $data['pack'] = "مجانا لمدة يوم واحد ثم 1 ريال يوميا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 1 ريال قطري يوميًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 ريال قطري أسبوعي";    
                $data['pack'] = "مجانا لمدة يوم واحد ثم 7 ريال اسبوعيا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 7 ريال قطري أسبوعيًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else{
                $data['subtype'] = "30 ريال قطري شهريا";    
                $data['pack'] = "مجاناً ليوم واحد ثم 40 ريال شهريا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم تحصيل 40 ريال قطري شهريًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_unsub',$data);*/

        $this->load->view('newui/template/header_qao');
        $this->load->view('newui/qao_unsub',$data);
    }

    function qat_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_qatar.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_qatar.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->qat_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function qat_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function qat_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_qatar.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 QAR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 QAR weekly";    
            }
            else{
                $data['subtype'] = "30 QAR monthly";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 ريال قطري اليومي";
                $data['pack'] = "مجانا لمدة يوم واحد ثم 1 ريال يوميا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 1 ريال قطري يوميًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 ريال قطري أسبوعي";    
                $data['pack'] = "مجانا لمدة يوم واحد ثم 7 ريال اسبوعيا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 7 ريال قطري أسبوعيًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else{
                $data['subtype'] = "30 ريال قطري شهريا";    
                $data['pack'] = "مجاناً ليوم واحد ثم 40 ريال شهريا (تجديد تلقائي) (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم تحصيل 40 ريال قطري شهريًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_pin',$data);        */

        $this->load->view('newui/template/header_qao');
        $this->load->view('newui/qao_pin',$data);
    }

    function qat_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function qat_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_qatar.sub_users_unsub where mobnum='".$mobnum."' and productID<>'' order by id desc limit 1");
        if($checkSub->num_rows()>0){
            $oper = $checkSub->row()->oper;
        }
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else if($oper=="42701"){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_qatar.callback_request(req,data) values('".$dataString."','".$out."');");
        //echo "insert into tpay_qatar.callback_request(req,data) values('".$dataString."'','".$out."');";die;

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function qat_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        $this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_qatar.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        $this->session->set_userdata("qatarlang",$lang);

        redirect(base_url()."welcome/index");
    }

    function qat_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/qatar/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function qat_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_qatar.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/qatar/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function qat_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_qatar.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function qaLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_qatar.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_qatar.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 QAR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 QAR weekly";    
            }
            else{
                $data['subtype'] = "30 QAR monthly";    
            }
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 ريال قطري اليومي";
                $data['pack'] = "مجانا لمدة يوم واحد ثم 1 ريال يوميا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 1 ريال قطري يوميًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "7 ريال قطري أسبوعي";    
                $data['pack'] = "مجانا لمدة يوم واحد ثم 7 ريال اسبوعيا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم خصم 7 ريال قطري أسبوعيًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
            else{
                $data['subtype'] = "30 ريال قطري شهريا";    
                $data['pack'] = "مجاناً ليوم واحد ثم 40 ريال شهريا (تجديد تلقائي)";
                $data['disclaimer'] = "إذا كنت عميلاً جديدًا ، فستكون مشتركًا في mComics لفترة تجريبية مجانية ليوم واحد ، وبعد ذلك سيتم تحصيل 40 ريال قطري شهريًا ، ويمكنك إلغاء الاشتراك في أي وقت. للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر فوق إلغاء الاشتراك أو عملاء Ooredoo لإلغاء الاشتراك عن طريق إرسال Unsub MC إلى 92413 أو إلى الرقم 97814 لمشتركي فودافون مجانًا وسيتم تجديد اشتراكك تلقائيًا كل يوم حتى تقوم بإلغاء الاشتراك. سيتم تطبيق تكاليف التصفح المعتادة. لاستخدام هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو أنك حصلت على إذن من والديك أو الشخص المسؤول عن دفع فاتورتك. لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
            }
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_login',$data);*/

        $this->load->view('newui/template/header_qao');
        $this->load->view('newui/qao_login',$data);
    }

    function qat_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_qatar.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function qat_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://q.mcomics.club/welcome/qat_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_qatar.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function qat_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_qatar.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_qatar.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /************************************T-Pay Qatar End*********************************/

    /************************************T-Pay Jordan*********************************/
    function jor_subs(){
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_subscribepack',$data);*/

        $this->load->view('newui/template/header_jor');
        $this->load->view('newui/jor_subscribepack',$data);
    }

    function jor_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://j.mcomics.club/welcome/jor_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_jordan.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function jor_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_jordan.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_jordan.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            /*if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }*/
            $stype = "mcomics_daily";
            $pid = 1318;

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://j.mcomics.club/welcome/jor_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_jordan.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="en"){
                $data['subtype'] = "0.15 JOD daily";    
                $data['disclaimer'] = "1 Day free trial for new subscribers only for Orange and Umniah, no free trial for Zain, then you will be charged 0.15 JOD daily.<br/>You will start the paid subscription after the free period automatically for Orange, Umniah and Zain.<br/>You can unsubscribe any time, to cancel from mComics portal, please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange users, to 91825 for Umniah users and to 97970 for Zain users for free.<br>For any inquires please contact us on support@pheuture.com";
            }
            else{
                $data['subtype'] = "0.15 دينار يوميا";
                $data['disclaimer'] = "درب مجاني ليوم واحد للمشتركين الجدد فقط في Orange و Umniah  ، بعد ذلك سيتم تحصيل 0.15 دينار أردني في اليوم ، بدون نسخة تجريبية مجانية لـ Zain، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية لـ Orange و Umniah.<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            /*$this->load->view('newui/template/header_auth');
            $this->load->view('newui/qat_mobnum',$data);*/

            $this->load->view('newui/template/header_jor');
            $this->load->view('newui/jor_mobnum',$data);

            /*$tid = $this->input->get_post('OrderId');
            redirect(base_url().'welcome/egy_consent_callback?ReferenceCode=&OrderId='.$tid);die;*/
        }
    }

    function jor_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_jordan.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/jordan/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_jordan.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_jordan.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    $data['subtype'] = "0.15 JOD daily";
                    if($oper=="41601"){
                        $data['disclaimer'] = "You can unsubscribe any time, to cancel from mComics portal, please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange users, to 91825 for Umniah users and to 97970 for Zain users for free.<br>For any inquires please contact us on support@pheuture.com";
                    }
                    else{
                        $data['disclaimer'] = "1 Day free trial for new subscribers only for Orange and Umniah, no free trial for Zain, then you will be charged 0.15 JOD daily, You will start the paid subscription after the free period automatically for Orange and Umniah and Zain.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange customer and to 91825 for Umniah customer and to 97970 for Zain customer for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more than 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com";
                    }
                }
                else{
                    $data['subtype'] = "0.15 دينار يوميا";
                    if($oper=="41601"){
                        $data['disclaimer'] = "يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                    }
                    else{
                        $data['disclaimer'] = "مجاني ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم محاسبتك 0.15 دينار أردني في اليوم ، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                    }
                }
                $data['lang'] = $lang;
                /*$this->load->view('newui/template/header_auth');
                $this->load->view('newui/qat_pass',$data);*/

                $this->load->view('newui/template/header_jor');
                $this->load->view('newui/jor_pass',$data);
            }
            else{
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");    
                redirect(base_url()."welcome/index");
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_jordan.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_jordan.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            /*if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }*/
            $stype = "mcomics_daily";
            $pid = 1318;

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            if($oper=="41601"){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes')); 
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            else{
                $checkSub = $this->db->query("select * from tpay_jordan.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
                if($checkSub->num_rows()==0){
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                    $ExecuteInitialPaymentNow = false;
                    $allowMultipleFreeStartPeriods = true;
                }
                else{
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes')); 
                    $ExecuteInitialPaymentNow = true;
                    $allowMultipleFreeStartPeriods = false;
                }
            }
            /*else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_jordan.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/jor_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/jor_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/jor_subs");die;    
                }
                redirect(base_url()."welcome/jor_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/jor_subs");
            }
        }
    }

    function jo_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_jordan.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_jordan.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $oper = $getSubType->row()->oper;
        $lang = $getSubType->row()->lang;

        if($lang=="en"){
            $data['subtype'] = "0.15 JOD daily";
            if($oper=="41601"){
                $data['disclaimer'] = "For any inquires please contact us on support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "1 Day free trial for new subscribers only for Orange and Umniah, no free trial for Zain, then you will be charged 0.15 JOD per day , You will start the paid subscription after the free period automatically for Orange and Umniah and Zain.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange customer and to 91825 for Umniah customer and to 97970 for Zain customer for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com";
            }
        }
        else{
            $data['subtype'] = "0.15 دينار يوميا";
            if($oper=="41601"){
                $data['disclaimer'] = "لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "مجاني ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم محاسبتك 0.15 دينار أردني في اليوم ، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_unsub',$data);*/

        $this->load->view('newui/template/header_jor');
        $this->load->view('newui/jor_unsub',$data);
    }

    function jor_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_jordan.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_jordan.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->jor_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function jor_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function jor_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_jordan.tidlogs where tid='".$tid."'");
        $oper = $getSubType->row()->oper;
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            $data['subtype'] = "0.15 JOD daily";
            if($oper=="41601"){
                $data['disclaimer'] = "You will be charged 0.15 JOD daily.<br/>You can unsubscribe any time, to cancel from mComics portal, please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange users, to 91825 for Umniah users and to 97970 for Zain users for free.<br>For any inquires please contact us on support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "1 Day free trial for new subscribers only for Orange and Umniah, no free trial for Zain, then you will be charged 0.15 JOD per day , You will start the paid subscription after the free period automatically for Orange and Umniah and Zain.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange customer and to 91825 for Umniah customer and to 97970 for Zain customer for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com";
            }
        }
        else{
            $data['subtype'] = "0.15 دينار يوميا";
            if($oper=="41601"){
                $data['disclaimer'] = "سيتم محاسبتك 0.15 دينار أردني في اليوم<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "مجاني ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم محاسبتك 0.15 دينار أردني في اليوم ، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_pin',$data);        */

        $this->load->view('newui/template/header_jor');
        $this->load->view('newui/jor_pin',$data);
    }

    function jor_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function jor_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_jordan.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        $getSubType = $this->db->query("select mobnum,subtype,lang,oper from tpay_jordan.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
        $oper = $getSubType->row()->oper;
        if($oper=="41601"){
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }
        else if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_jordan.callback_request(req,data) values('".$dataString."','".$out."');");
        //echo "insert into tpay_qatar.callback_request(req,data) values('".$dataString."'','".$out."');";die;

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function jor_home(){
        $MSISDN = '962'.substr($this->input->get_post('mobnum'),-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_jordan.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        //$this->session->set_userdata("jordanlang",$lang);

        redirect(base_url()."welcome/index");
    }

    function jor_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/jordan/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function jor_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_jordan.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/jordan/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function jor_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_jordan.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function joLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_jordan.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_jordan.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $oper = $qry_lang->row()->oper;
        $lang = $qry_lang->row()->lang;
        if($lang=="en"){
            $data['subtype'] = "0.15 JOD daily";
            if($oper=="41601"){
                $data['disclaimer'] = "You can unsubscribe any time, to cancel from mComics portal, please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange users, to 91825 for Umniah users and to 97970 for Zain users for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe.<br>For any inquires please contact us on support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "1 Day free trial for new subscribers only for Orange and Umniah, no free trial for Zain, then you will be charged 0.15 JOD per day , You will start the paid subscription after the free period automatically for Orange and Umniah and Zain.<br/>You can unsubscribe any time, to cancel from site please go to Profile and click on [Unsubscribe] OR send Unsub MC to 99222 for Orange customer and to 91825 for Umniah customer and to 97970 for Zain customer for free.<br>Your subscription will be automatically renewed every day untill you unsubscribe, Standered data browsing casts will be applied. To make use of this service you must be more then 18 year old or have received permission from your parent or persion who is authorized to pay your bill.<br>For any inquires please contact us on support@pheuture.com";
            }
        }
        else{
            $data['subtype'] = "0.15 دينار يوميا";
            if($oper=="41601"){
                $data['disclaimer'] = "يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            else{
                $data['disclaimer'] = "مجاني ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم محاسبتك 0.15 دينار أردني في اليوم ، ستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية<br/>يمكنك إلغاء الاشتراك في أي وقت ، وللإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [Unsubscribe] أو إرسال Unsub MC إلى 99222 لعملاء Orange ، وإلى 91825 لعملاء Umniah ، وإلى 97970 لعملاء Zain مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_login',$data);*/

        $this->load->view('newui/template/header_jor');
        $this->load->view('newui/jor_login',$data);
    }

    function jor_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_jordan.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function jor_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://j.mcomics.club/welcome/jor_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_jordan.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function jor_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_jordan.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_jordan.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /**********************************T-Pay Jordan End*********************************/

    /************************************T-Pay KSA*********************************/
    function ksa_subs(){
        $this->load->view('newui/template/header_ksa');
        $this->load->view('newui/ksa_subs',$data);
    }

    function ksa_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang =($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://k.mcomics.club/welcome/ksa_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_ksa.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ksa_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_ksa.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_ksa.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            /*if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }*/
            $stype = "mcomics_daily";
            $pid = 1430;

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://k.mcomics.club/welcome/ksa_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_ksa.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="en"){
                $data['subtype'] = "1 SAR daily";    
            }
            else{
                $data['subtype'] = "1 ريال يومياً";
                $data['disclaimer'] = "سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            /*$this->load->view('newui/template/header_auth');
            $this->load->view('newui/qat_mobnum',$data);*/

            $this->load->view('newui/template/header_ksa');
            $this->load->view('newui/ksa_mobnum',$data);

            /*$tid = $this->input->get_post('OrderId');
            redirect(base_url().'welcome/egy_consent_callback?ReferenceCode=&OrderId='.$tid);die;*/
        }
    }

    function ksa_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_ksa.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            if($oper=="42003"){
                header('Location: http://k.mcomics.club/ksa_home?mobnum='.$MSISDN);die();
            }
            file_get_contents("http://111.118.180.237/tpay/ksa/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_ksa.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_ksa.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    $data['subtype'] = "1 SAR daily";    
                }
                else{
                    $data['subtype'] = "1 ريال يومياً";
                    $data['disclaimer'] = "سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;
                /*$this->load->view('newui/template/header_auth');
                $this->load->view('newui/qat_pass',$data);*/

                $this->load->view('newui/template/header_ksa');
                $this->load->view('newui/ksa_pass',$data);
            }
            else{
                $this->session->set_userdata("mobnum",$mobnum);
                $this->session->set_userdata("subuser","yes");    
                redirect(base_url()."welcome/index");
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_ksa.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_ksa.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            /*if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1174;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1173;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1172;
            }*/
            $stype = "mcomics_daily";
            $pid = 1430;

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            //if($oper=="42701"){
            $checkSub = $this->db->query("select * from tpay_ksa.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                if($oper=="42004")
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                else
                    $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+72 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else if($oper=="42003"){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            /*}
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_ksa.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/ksa_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/ksa_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/ksa_subs");die;    
                }
                redirect(base_url()."welcome/ksa_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/ksa_subs");
            }
        }
    }

    function ks_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_ksa.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_ksa.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="en"){
            $data['subtype'] = "1 SAR daily";    
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_unsub',$data);*/

        $this->load->view('newui/template/header_ksa');
        $this->load->view('newui/ksa_unsub',$data);
    }

    function ksa_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_ksa.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_ksa.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ksa_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function ksa_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function ksa_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_ksa.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            $data['subtype'] = "1 SAR daily";    
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_pin',$data);        */

        $this->load->view('newui/template/header_ksa');
        $this->load->view('newui/ksa_pin',$data);
    }

    function ksa_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ksa_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $get_oper = $this->db->query("select * from tpay_ksa.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $oper = $get_oper->row()->oper;

        $checkSub = $this->db->query("select * from tpay_ksa.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else if($oper=="42003"){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_ksa.callback_request(req,data) values('".$dataString."','".$out."');");
        //echo "insert into tpay_qatar.callback_request(req,data) values('".$dataString."'','".$out."');";die;

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ksa_home(){
        $MSISDN = '966'.substr($this->input->get_post('mobnum'),-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_ksa.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        //$this->session->set_userdata("jordanlang",$lang);

        redirect(base_url()."welcome/index");
    }

    function ksa_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/ksa/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function ksa_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_ksa.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/ksa/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function ksa_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_ksa.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function kLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_ksa.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_ksa.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        if($lang=="en"){
            $data['subtype'] = "1 SAR daily";    
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "سيتم تحصيل 1 ريال سعودي في اليوم ، وسيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والضغط على [Unsubscribe] أو إرسال UNSUBMC إلى 708900 لعملاء Zain وإرسال U98 إلى 600990  لعملاء Mobily مجانًا.<br/>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_login',$data);*/

        $this->load->view('newui/template/header_ksa');
        $this->load->view('newui/ksa_login',$data);
    }

    function ksa_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_ksa.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function ksa_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://k.mcomics.club/welcome/ksa_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_jordan.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ksa_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_jordan.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_ksa.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /**********************************T-Pay KSA End*********************************/

    /************************************T-Pay Kenya*********************************/
    function ken_subs(){
        $this->load->view('newui/template/header_ken');
        $this->load->view('newui/ken_subs',$data);
    }

    function ken_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://ke.mcomics.club/welcome/ken_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_kenya.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ken_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_kenya.tidlogs set stoken='$stoken' where tid='".$tid."'");
        /*if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_kenya.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1538;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1537;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1536;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://ke.mcomics.club/welcome/ken_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{*/
            $getSubType = $this->db->query("select subtype,lang from tpay_kenya.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = "en";
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "10 KES daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "50 KES weekly";    
                }
                else{
                    $data['subtype'] = "200 KES monthly";    
                }
            }
            else{
                $data['subtype'] = "1 ريال يومياً";
                $data['disclaimer'] = "تجربة مجانية ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم تحصيل 1 ريال سعودي في اليوم ، وستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [إلغاء الاشتراك] أو إرسال UNSUBMC إلى 708900 لعملاء زين مجانًا.<br>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;
            /*$this->load->view('newui/template/header_auth');
            $this->load->view('newui/qat_mobnum',$data);*/

            $this->load->view('newui/template/header_ken');
            $this->load->view('newui/ken_mobnum',$data);

            /*$tid = $this->input->get_post('OrderId');
            redirect(base_url().'welcome/egy_consent_callback?ReferenceCode=&OrderId='.$tid);die;*/
        //}
    }

    function ken_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_kenya.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/kenya/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_kenya.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_kenya.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = "en";
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "10 KES daily";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "50 KES weekly";    
                    }
                    else{
                        $data['subtype'] = "200 KES monthly";    
                    }
                }
                else{
                    $data['subtype'] = "1 ريال يومياً";
                    $data['disclaimer'] = "تجربة مجانية ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم تحصيل 1 ريال سعودي في اليوم ، وستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [إلغاء الاشتراك] أو إرسال UNSUBMC إلى 708900 لعملاء زين مجانًا.<br>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;
                /*$this->load->view('newui/template/header_auth');
                $this->load->view('newui/qat_pass',$data);*/

                $this->load->view('newui/template/header_ken');
                $this->load->view('newui/ken_pass',$data);
            }
            else{
                redirect(base_url()."welcome/ken_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_kenya.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_kenya.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1538;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1537;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1536;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            //if($oper=="42701"){
            $checkSub = $this->db->query("select * from tpay_kenya.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            /*}
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_kenya.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/ken_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/ken_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/ken_subs");die;    
                }
                redirect(base_url()."welcome/ken_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/ken_subs");
            }
        }
    }

    function ke_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_kenya.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        //$getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kenya.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = "en";

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "10 KES daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "50 KES weekly";    
            }
            else{
                $data['subtype'] = "200 KES monthly";    
            }
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "تجربة مجانية ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم تحصيل 1 ريال سعودي في اليوم ، وستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [إلغاء الاشتراك] أو إرسال UNSUBMC إلى 708900 لعملاء زين مجانًا.<br>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_unsub',$data);*/

        $this->load->view('newui/template/header_ken');
        $this->load->view('newui/ken_unsub',$data);
    }

    function ken_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        //$getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kenya.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = "en";
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_kenya.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ken_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function ken_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function ken_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kenya.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        //$lang = $getSubType->row()->lang;
        $lang = "en";
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "10 KES daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "50 KES weekly";    
            }
            else{
                $data['subtype'] = "200 KES monthly";    
            }
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "تجربة مجانية ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم تحصيل 1 ريال سعودي في اليوم ، وستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [إلغاء الاشتراك] أو إرسال UNSUBMC إلى 708900 لعملاء زين مجانًا.<br>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_pin',$data);        */

        $this->load->view('newui/template/header_ken');
        $this->load->view('newui/ken_pin',$data);
    }

    function ken_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ken_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_kenya.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_kenya.callback_request(req,data) values('".$dataString."','".$out."');");
        //echo "insert into tpay_qatar.callback_request(req,data) values('".$dataString."'','".$out."');";die;

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ken_home(){
        $MSISDN = '254'.substr($this->input->get_post('mobnum'),-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        //$getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kenya.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = "en";

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        //$this->session->set_userdata("jordanlang",$lang);

        redirect(base_url()."welcome/index");
    }

    function ken_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/kenya/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function ken_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_kenya.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/kenya/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function ken_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_kenya.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function keLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_kenya.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        //$qry_lang = $this->db->query("select * from tpay_kenya.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = "en";
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "10 KES daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "50 KES weekly";    
            }
            else{
                $data['subtype'] = "200 KES monthly";    
            }
        }
        else{
            $data['subtype'] = "1 ريال يومياً";
            $data['disclaimer'] = "تجربة مجانية ليوم واحد للمشتركين الجدد فقط ، وبعد ذلك سيتم تحصيل 1 ريال سعودي في اليوم ، وستبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية.<br/>يمكنك إلغاء الاشتراك في أي وقت ، للإلغاء من الموقع ، يرجى الانتقال إلى الملف الشخصي والنقر على [إلغاء الاشتراك] أو إرسال UNSUBMC إلى 708900 لعملاء زين مجانًا.<br>سيتم تجديد اشتراكك تلقائيًا كل يوم حتى يتم إلغاء الاشتراك ، سيتم تطبيق قوائم تصفح البيانات المستقلة. للاستفادة من هذه الخدمة ، يجب أن يكون عمرك أكثر من 18 عامًا أو حصلت على إذن من والديك أو الشخص المخول بدفع فاتورتك.<br>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;
        /*$this->load->view('newui/template/header_auth');
        $this->load->view('newui/qat_login',$data);*/

        $this->load->view('newui/template/header_ken');
        $this->load->view('newui/ken_login',$data);
    }

    function ken_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_kenya.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function ken_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://ke.mcomics.club/welcome/ken_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        //$this->db->query("insert into tpay_jordan.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'$lang')");

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ken_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        //$this->db->query("update tpay_jordan.tidlogs set stoken='$stoken' where tid='".$tid."'");
        $this->db->query("insert into tpay_kenya.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /**********************************T-Pay Kenya End*********************************/


    /******************************T-Pay Kuwait*****************************/
    function ku_subs(){
        $this->load->view('newui/template/header_ku');
        $this->load->view('newui/ku_subs',$data);
    }

    function ku_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://ku.mcomics.club/welcome/ku_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_kuwait.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ku_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_kuwait.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_kuwait.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1801;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1800;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1799;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://ku.mcomics.club/welcome/ku_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_kuwait.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = "en";
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.2 KWD daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "1 KWD weekly";    
                }
                else{
                    $data['subtype'] = "4 KWD monthly";    
                }
                $data['disclaimer'] = "You will subscribe in mComics for ".$data['subtype'].".<br/>To cancel your subscription, for Zain subscribers please send UNSUB MCOMICS to xxxx.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.2 دينار كويتي";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "1 د.ك أسبوعياً";    
                }
                else{
                    $data['subtype'] = "4 د.ك شهريا";    
                }
                $data['disclaimer'] = "سوف تشترك في mComics لـ ".$data['subtype']."<br/>لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى xxxx.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;

            $this->load->view('newui/template/header_ku');
            $this->load->view('newui/ku_mobnum',$data);
        }
    }

    function ku_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_kuwait.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/kuwait/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_kuwait.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_kuwait.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = "en";
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "0.2 KWD daily";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "1 KWD weekly";    
                    }
                    else{
                        $data['subtype'] = "4 KWD monthly";    
                    }
                    $data['disclaimer'] = "To cancel your subscription, for Zain subscribers please send UNSUB MCOMICS to xxxx.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
                }
                else{
                    if($subtype=="daily"){
                        $data['subtype'] = "0.2 دينار كويتي";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "1 د.ك أسبوعياً";    
                    }
                    else{
                        $data['subtype'] = "4 د.ك شهريا";    
                    }
                    $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى xxxx.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;

                $this->load->view('newui/template/header_ku');
                $this->load->view('newui/ku_pass',$data);
            }
            else{
                redirect(base_url()."welcome/ku_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_kuwait.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_kuwait.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 1801;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 1800;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 1799;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            //if($oper=="42701"){
            $checkSub = $this->db->query("select * from tpay_kuwait.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            /*}
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_kuwait.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/ku_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/ku_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/ku_subs");die;    
                }
                redirect(base_url()."welcome/ku_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/ku_subs");
            }
        }
    }

    function ku_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_kuwait.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kuwait.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 KWD daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 KWD weekly";    
            }
            else{
                $data['subtype'] = "4 KWD monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, for Zain subscribers please send UNSUB MCOMICS to xxxx.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 دينار كويتي";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 د.ك أسبوعياً";    
            }
            else{
                $data['subtype'] = "4 د.ك شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى xxxx.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_ku');
        $this->load->view('newui/ku_unsub',$data);
    }

    function kuw_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kuwait.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_kuwait.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ku_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function ku_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function ku_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kuwait.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 KWD daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 KWD weekly";    
            }
            else{
                $data['subtype'] = "4 KWD monthly";    
            }
            $data['disclaimer'] = "You will subscribe in mComics for ".$data['subtype'].".<br/>To cancel your subscription, for Zain subscribers please send UNSUB MCOMICS to xxxx.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 دينار كويتي";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 د.ك أسبوعياً";    
            }
            else{
                $data['subtype'] = "4 د.ك شهريا";    
            }
            $data['disclaimer'] = "سوف تشترك في mComics لـ ".$data['subtype']."<br/>لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى xxxx.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;

        $this->load->view('newui/template/header_ku');
        $this->load->view('newui/ku_pin',$data);
    }

    function ku_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ku_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_kuwait.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_kuwait.callback_request(req,data) values('".$dataString."','".$out."');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ku_home(){
        $MSISDN = '965'.substr($this->input->get_post('mobnum'),-8,8);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_kuwait.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        redirect(base_url()."welcome/index");
    }

    function ku_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/kuwait/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function ku_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_kuwait.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/kuwait/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function ku_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_kuwait.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function kuLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_kuwait.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_kenya.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 KWD daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 KWD weekly";    
            }
            else{
                $data['subtype'] = "4 KWD monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, for Zain subscribers please send UNSUB MCOMICS to xxxx.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 دينار كويتي";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 د.ك أسبوعياً";    
            }
            else{
                $data['subtype'] = "4 د.ك شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى xxxx.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_ku');
        $this->load->view('newui/ku_login',$data);
    }

    function ku_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_kuwait.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function ku_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://ku.mcomics.club/welcome/ku_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ku_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        $this->db->query("insert into tpay_kuwait.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /*************************T-Pay Kuwait End************************/


    /**************************T-Pay Palestine*************************/
    function pal_subs(){
        $this->load->view('newui/template/header_pal');
        $this->load->view('newui/pal_subs',$data);
    }

    function pal_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://p.mcomics.club/welcome/pal_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_palestine_mcomics.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function pal_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_palestine_mcomics.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_palestine_mcomics.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
        
            $stype = "mcomics_daily";
            $pid = 2071;
        

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://p.mcomics.club/welcome/pal_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_palestine_mcomics.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "1 ILS daily";    
                }
                
                $data['disclaimer'] = "You will subscribe in mComics for 1 ILS / day VAT Excluded.<br/>Renewal will be automatic as per your pack.<br/>No commitment, you can cancel your subscription at any time by sending UNSUB MC to 7825 for Ooredoo subscribers for free. To cancel from the site please go to your profile then press Unsubscribe button.<br/>For any inquires please contact us on support@pheuture.com";
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "1 شيكل يوميا";    
                }
                
                $data['disclaimer'] = "سوف تشترك في mComics يوم / مقابل 1 شيكل غير شامل ضريبة القيمة المضافة.<br/>سيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية. لا يوجد أي التزام ، يمكنك إلغاء اشتراكك في أي وقت عن طريق إرسال UNSUB MC إلى 7825 لمشتركي Ooredoo. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك والضغط على زر إلغاء الاشتراك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;

            $this->load->view('newui/template/header_pal');
            $this->load->view('newui/pal_mobnum',$data);
        }
    }

    function pal_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_palestine_mcomics.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/palestine/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_palestine_mcomics.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_palestine_mcomics.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "1 ILS daily";    
                    }
                    
                    $data['disclaimer'] = "No commitment, you can cancel your subscription at any time by sending UNSUB MC to 7825 for Ooredoo subscribers for free. To cancel from the site please go to your profile then press Unsubscribe button.<br/>For any inquires please contact us on support@pheuture.com";
                }
                else{
                    if($subtype=="daily"){
                        $data['subtype'] = "1 شيكل يوميا";    
                    }
                    
                    $data['disclaimer'] = "سيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية. لا يوجد أي التزام ، يمكنك إلغاء اشتراكك في أي وقت عن طريق إرسال UNSUB MC إلى 7825 لمشتركي Ooredoo. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك والضغط على زر إلغاء الاشتراك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;

                $this->load->view('newui/template/header_pal');
                $this->load->view('newui/pal_pass',$data);
            }
            else{
                redirect(base_url()."welcome/pa_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_palestine_mcomics.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_palestine_mcomics.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
        
            $stype = "mcomics_daily";
            $pid = 2071;
        

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            //if($oper=="42701"){
            $checkSub = $this->db->query("select * from tpay_palestine_mcomics.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }
            /*}
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));
            }*/
            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_palestine_mcomics.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/pales_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/pa_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/pal_subs");die;    
                }
                redirect(base_url()."welcome/pa_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/pal_subs");
            }
        }
    }

    function pales_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_palestine_mcomics.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_palestine_mcomics.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 ILS daily";    
            }
            
            $data['disclaimer'] = "No commitment, you can cancel your subscription at any time by sending UNSUB MC to 7825 for Ooredoo subscribers for free. To cancel from the site please go to your profile then press Unsubscribe button.<br/>For any inquires please contact us on support@pheuture.com";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 شيكل يوميا";    
            }
            
            $data['disclaimer'] = "سيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية. لا يوجد أي التزام ، يمكنك إلغاء اشتراكك في أي وقت عن طريق إرسال UNSUB MC إلى 7825 لمشتركي Ooredoo. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك والضغط على زر إلغاء الاشتراك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_pal');
        $this->load->view('newui/pal_unsub',$data);
    }

    function pa_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_palestine_mcomics.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_palestine_mcomics.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ku_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function pal_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function pales_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_palestine_mcomics.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 ILS daily";    
            }
            
            $data['disclaimer'] = "You will subscribe in mComics for 1 ILS / day VAT Excluded.<br/>Renewal will be automatic as per your pack.<br/>No commitment, you can cancel your subscription at any time by sending UNSUB MC to 7825 for Ooredoo subscribers for free. To cancel from the site please go to your profile then press Unsubscribe button.<br/>For any inquires please contact us on support@pheuture.com";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 شيكل يوميا";    
            }
            
            $data['disclaimer'] = "سوف تشترك في mComics يوم / مقابل 1 شيكل غير شامل ضريبة القيمة المضافة.<br/>سيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية. لا يوجد أي التزام ، يمكنك إلغاء اشتراكك في أي وقت عن طريق إرسال UNSUB MC إلى 7825 لمشتركي Ooredoo. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك والضغط على زر إلغاء الاشتراك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;

        $this->load->view('newui/template/header_pal');
        $this->load->view('newui/pal_pin',$data);
    }

    function pal_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function pal_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_palestine_mcomics.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_palestine_mcomics.callback_request(req,data) values('".$dataString."','".$out."');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function pa_home(){
        $MSISDN = '972'.substr($this->input->get_post('mobnum'),-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_palestine_mcomics.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        redirect(base_url()."welcome/index");
    }

    function pal_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/palestine/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function pal_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_palestine_mcomics.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/palestine/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function pal_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_palestine_mcomics.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function palLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_palestine_mcomics.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_palestine_mcomics.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "1 ILS daily";    
            }
            
            $data['disclaimer'] = "No commitment, you can cancel your subscription at any time by sending UNSUB MC to 7825 for Ooredoo subscribers for free. To cancel from the site please go to your profile then press Unsubscribe button.<br/>For any inquires please contact us on support@pheuture.com";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "1 شيكل يوميا";    
            }
            
            $data['disclaimer'] = "سيبدأ الاشتراك المدفوع تلقائيًا بعد الفترة المجانية. لا يوجد أي التزام ، يمكنك إلغاء اشتراكك في أي وقت عن طريق إرسال UNSUB MC إلى 7825 لمشتركي Ooredoo. للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك والضغط على زر إلغاء الاشتراك.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_pal');
        $this->load->view('newui/pal_login',$data);
    }

    function pal_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_palestine_mcomics.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function pal_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://p.mcomics.club/welcome/pal_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function pal_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        $this->db->query("insert into tpay_palestine_mcomics.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /*************************T-Pay Palestine End************************/


    /******************************T-Pay Oman*****************************/
    function om_subs(){
        $this->load->view('newui/template/header_om');
        $this->load->view('newui/om_subs',$data);
    }

    function om_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://o.mcomics.club/welcome/om_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_oman.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function om_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_oman.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_oman.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 2070;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 2069;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 2068;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://o.mcomics.club/welcome/om_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_oman.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = "en";
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "0.2 OMR daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "1 OMR weekly";    
                }
                else{
                    $data['subtype'] = "4 OMR monthly";    
                }
                $data['disclaimer'] = "<b>You will subscribe in mComics for ".$data['subtype'].". This is an auto-renewal service.</b><br/>To cancel your subscription, for Ooredoo subscribers please send UNSUB MCOMICS to 91230.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "0.2 ريال عماني يوميا";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "1 ريال عماني أسبوعيا";    
                }
                else{
                    $data['subtype'] = "4 ريال عماني شهريا";    
                }
                $data['disclaimer'] = "<b>سوف تشترك في mComics ".$data['subtype'].". هذه خدمة تجديد تلقائي.</b><br/>لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى 91230.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;

            $this->load->view('newui/template/header_om');
            $this->load->view('newui/om_mobnum',$data);
        }
    }

    function om_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_oman.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/oman/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_oman.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_oman.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = "en";
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "0.2 OMR daily";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "1 OMR weekly";    
                    }
                    else{
                        $data['subtype'] = "4 OMR monthly";    
                    }
                    $data['disclaimer'] = "To cancel your subscription, for Ooredoo subscribers please send UNSUB MCOMICS to 91230.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
                }
                else{
                    if($subtype=="daily"){
                        $data['subtype'] = "0.2 ريال عماني يوميا";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "1 ريال عماني أسبوعيا";    
                    }
                    else{
                        $data['subtype'] = "4 ريال عماني شهريا";    
                    }
                    $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى 91230.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;

                $this->load->view('newui/template/header_om');
                $this->load->view('newui/om_pass',$data);
            }
            else{
                redirect(base_url()."welcome/om_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_oman.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_oman.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 2070;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 2069;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 2068;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            
            /*$checkSub = $this->db->query("select * from tpay_oman.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }*/
            $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
            $ExecuteInitialPaymentNow = true;
            $allowMultipleFreeStartPeriods = false;

            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_oman.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/om_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/om_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/om_subs");die;    
                }
                redirect(base_url()."welcome/om_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/om_subs");
            }
        }
    }

    function om_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_oman.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_oman.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 OMR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 OMR weekly";    
            }
            else{
                $data['subtype'] = "4 OMR monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, for Ooredoo subscribers please send UNSUB MCOMICS to 91230.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 ريال عماني يوميا";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 ريال عماني أسبوعيا";    
            }
            else{
                $data['subtype'] = "4 ريال عماني شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى 91230.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_om');
        $this->load->view('newui/om_unsub',$data);
    }

    function oman_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_oman.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_oman.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ku_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function om_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function om_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_oman.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 OMR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 OMR weekly";    
            }
            else{
                $data['subtype'] = "4 OMR monthly";    
            }
            $data['disclaimer'] = "<b>You will subscribe in mComics for ".$data['subtype'].". This is an auto-renewal service.</b><br/>To cancel your subscription, for Ooredoo subscribers please send UNSUB MCOMICS to 91230.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 ريال عماني يوميا";
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 ريال عماني أسبوعيا";
            }
            else{
                $data['subtype'] = "4 ريال عماني شهريا";    
            }
            $data['disclaimer'] = "<b>سوف تشترك في mComics ".$data['subtype'].". هذه خدمة تجديد تلقائي.</b><br/>لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى 91230.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;

        $this->load->view('newui/template/header_om');
        $this->load->view('newui/om_pin',$data);
    }

    function om_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function om_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid.$otp;
        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
        $dataString = '{
            "signature": "'.$Digest.'",
            "pinCode": "'.$otp.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        /*$checkSub = $this->db->query("select * from tpay_oman.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }*/

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_oman.callback_request(req,data) values('".$dataString."','".$out."');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function om_home(){
        $MSISDN = '968'.substr($this->input->get_post('mobnum'),-8,8);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_oman.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        redirect(base_url()."welcome/index");
    }

    function om_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/oman/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function om_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_oman.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/oman/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function om_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_oman.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function omLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_oman.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_oman.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "0.2 OMR daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 OMR weekly";    
            }
            else{
                $data['subtype'] = "4 OMR monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, for Ooredoo subscribers please send UNSUB MCOMICS to 91230.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "0.2 ريال عماني يوميا";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "1 ريال عماني أسبوعيا";    
            }
            else{
                $data['subtype'] = "4 ريال عماني شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، لمشتركي زين ، يرجى إرسال UNSUB MCOMICS إلى 91230.<br/>للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك.<br/>الانترنت سوف يخصم من الباقة الخاصة بك مع العلم ان الخدمة تجدد تلقائيا.<br/>لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_om');
        $this->load->view('newui/om_login',$data);
    }

    function om_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_oman.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function om_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://o.mcomics.club/welcome/om_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function om_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        $this->db->query("insert into tpay_oman.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /**************************T-Pay Oman End************************/




    /******************************T-Pay Mtania*****************************/
    function ma_subs(){
        $this->load->view('newui/template/header_ma');
        $this->load->view('newui/ma_subs',$data);
    }

    function ma_he(){
        $subtype =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $lang = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(11,999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://mc.mcomics.club/welcome/ma_he_callback";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $this->db->query("insert into tpay_mtania.tidlogs(tid,subtype,date,lang) values('".$tid."','".$subtype."',now(),'".$lang."')");

        
        //$url = "http://enrichment-staging.tpay.me/IDXML.ashx/rdr-staging/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=42702&msisdn=97470372349";

        //$url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature."&simulate=true&operatorcode=&msisdn=";

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ma_he_callback(){
        //print_r($_GET);die;
        $Status = $this->input->get_post('Status');
        $SessionId = $this->input->get_post('SessionId');
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $this->db->query("update tpay_mtania.tidlogs set stoken='$stoken' where tid='".$tid."'");
        if($Status=="Success"){
            $getSubType = $this->db->query("select subtype,lang from tpay_mtania.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 2086;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 2085;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 2084;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            
            $redirectUrl="http://mc.mcomics.club/welcome/ma_consent_callback";
            $Message= $pid.'mComics'.$stype.$SessionId.$redirectUrl.$tid.$lang;

            $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            //$url = "http://enrichment-staging.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            $url = "http://lookup.tpay.me/headerenrichment/rdr/consent?subscriptionPlanId=".$pid."&productCatalog=mComics&productSku=".$stype."&sessionId=".$SessionId."&redirectURL=".$redirectUrl."&orderId=".$tid."&lang=".$lang."&signature=".$signature;
            //echo $url;die;

            redirect($url);die;
        }
        else{
            $getSubType = $this->db->query("select subtype,lang from tpay_mtania.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            if($lang=="en"){
                if($subtype=="daily"){
                    $data['subtype'] = "5 MRU daily";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "20 MRU weekly";    
                }
                else{
                    $data['subtype'] = "50 MRU monthly";    
                }
                $data['disclaimer'] = "<b>You will subscribe in mComics for ".$data['subtype'].". This is an auto-renewal service.</b><br/>To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com";
            }
            else if($lang=="fr"){
                if($subtype=="daily"){
                    $data['subtype'] = "5 MRU par jour";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "20 MRU par semaine";    
                }
                else{
                    $data['subtype'] = "50 MRU par mois";    
                }
                $data['disclaimer'] = "<b>Vous vous abonnerez à mComics pour ".$data['subtype'].". Il s'agit d'un service de renouvellement automatique.</b><br/>Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com.";
            }
            else{
                if($subtype=="daily"){
                    $data['subtype'] = "5 MRU عماني يوميا";    
                }
                else if($subtype=="weekly"){
                    $data['subtype'] = "20 MRU عماني أسبوعيا";    
                }
                else{
                    $data['subtype'] = "50 MRU عماني شهريا";    
                }
                $data['disclaimer'] = "<b>سوف تشترك في mComics ".$data['subtype'].". هذه خدمة تجديد تلقائي.</b><br/>لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
            }
            $data['tid'] = $tid;
            $data['lang'] = $lang;

            $this->load->view('newui/template/header_ma');
            $this->load->view('newui/ma_mobnum',$data);
        }
    }

    function ma_consent_callback(){
        //print_r($_GET);die;
        $headerEnrichmentReferenceCode = $this->input->get_post('ReferenceCode');
        $tid = $this->input->get_post('OrderId');
        $mobnum = $this->input->get_post('Msisdn');
        $oper = $this->input->get_post('OperatorCode');

        $checkSub = $this->db->query("select * from tpay_mtania.sub_users where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()>0){
            file_get_contents("http://111.118.180.237/tpay/mtania/sendsms.php?mobnum=$mobnum&oper=$oper");
            if($headerEnrichmentReferenceCode==''){
                $data['id'] = $checkSub->row()->Id;
                $chkSMSStatus = $this->db->query("select * from tpay_mtania.sms_status where msisdn='".$mobnum."' and date>date_sub(curdate(), interval 1 second)");
                $data['sms_sts'] = $chkSMSStatus->num_rows();
                $data['mobnum'] = $mobnum;
                $data['oper'] = $oper;

                $getSubType = $this->db->query("select subtype,lang from tpay_mtania.tidlogs where mobnum='".$mobnum."' order by id desc limit 1");
                $lang = $getSubType->row()->lang;
                $subtype = $getSubType->row()->subtype;
                if($lang=="en"){
                    if($subtype=="daily"){
                        $data['subtype'] = "5 MRU daily";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "20 MRU weekly";    
                    }
                    else{
                        $data['subtype'] = "50 MRU monthly";    
                    }
                    $data['disclaimer'] = "To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
                }
                else if($lang=="fr"){
                    if($subtype=="daily"){
                        $data['subtype'] = "5 MRU par jour";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "20 MRU par semaine";    
                    }
                    else{
                        $data['subtype'] = "50 MRU par mois";    
                    }
                    $data['disclaimer'] = "Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com.";
                }
                else{
                    if($subtype=="daily"){
                        $data['subtype'] = "5 MRU عماني يوميا";    
                    }
                    else if($subtype=="weekly"){
                        $data['subtype'] = "20 MRU عماني أسبوعيا";    
                    }
                    else{
                        $data['subtype'] = "50 MRU عماني شهريا";    
                    }
                    $data['disclaimer'] = "لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
                }
                $data['lang'] = $lang;

                $this->load->view('newui/template/header_ma');
                $this->load->view('newui/ma_pass',$data);
            }
            else{
                redirect(base_url()."welcome/ma_home?mobnum=".$mobnum);
                die;
            }
        }
        else{
        

//echo $tid." : ".$mobnum." : ".$oper." : ".$headerEnrichmentReferenceCode;die;

            $this->db->query("update tpay_mtania.tidlogs set mobnum='".$mobnum."',oper='".$oper."' where tid='".$tid."'");
            $getSubType = $this->db->query("select subtype,lang,stoken from tpay_mtania.tidlogs where tid='".$tid."'");
            $subtype = $getSubType->row()->subtype;
            $lang = $getSubType->row()->lang;
            $stoken = $getSubType->row()->stoken;
            if($subtype=="monthly"){
                $stype = "mcomics_monthly";
                $pid = 2086;
            }
            else if($subtype=="weekly"){
                $stype = "mcomics_weekly";
                $pid = 2085;
            }
            else{
                $stype = "mcomics_daily";
                $pid = 2084;
            }

            $PublicKey = "THJoDINxZc9ruCEZtyeO";
            $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";
            $CustomerAccountNumber = "$mobnum";
            $MSISDN = "$mobnum";
            $OperatorCode = "$oper";
            $SubscriptionPlanId = $pid;
            $InitialPaymentProductId = $stype;
            $ExecuteRecurringPaymentNow = false;
            $RecurringPaymentProductId = $stype;
            $ProductCatalogName = "mComics";        
            $AutoRenewContract = true;
            if($lang=="en"){
                $Language = 1;
            }
            else if($lang=="fr"){
                $Language = 3;
            }
            else{
                $Language = 2;
            }
            if($headerEnrichmentReferenceCode==''){
                $SendVerificationSMS = true;
            }
            else{
                $SendVerificationSMS = false;
            }
            $HeaderEnrichmentReferenceCode = $headerEnrichmentReferenceCode;
            
            $checkSub = $this->db->query("select * from tpay_mtania.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
            if($checkSub->num_rows()==0){
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+24 hours'));
                $ExecuteInitialPaymentNow = false;
                $allowMultipleFreeStartPeriods = true;
            }
            else{
                $InitialPaymentDate = gmdate("Y-m-d H:i:s\Z", strtotime('+7 minutes'));   
                $ExecuteInitialPaymentNow = true;
                $allowMultipleFreeStartPeriods = false;
            }

            $SmsId = "";

            $date = gmdate('Y-m-d H:i:s');
            $ContractStartDate = gmdate("Y-m-d H:i:s\Z", strtotime('+5 minutes'));
            $ContractEndDate = gmdate("Y-m-d H:i:s\Z", strtotime('+10 years'));
            
            $ExecuteInitialPaymentNow_c = $ExecuteInitialPaymentNow ? 'true' : 'false';
            $ExecuteRecurringPaymentNow_c = $ExecuteRecurringPaymentNow ? 'true' : 'false';
            $AutoRenewContract_c = $AutoRenewContract ? 'true' : 'false';
            $SendVerificationSMS_c = $SendVerificationSMS ? 'true' : 'false';
            $allowMultipleFreeStartPeriods_c = $allowMultipleFreeStartPeriods ? 'true' : 'false';

            $Message=$CustomerAccountNumber.$MSISDN.$OperatorCode.$SubscriptionPlanId.$InitialPaymentProductId.$InitialPaymentDate.$ExecuteInitialPaymentNow_c.$RecurringPaymentProductId.$ProductCatalogName.$ExecuteRecurringPaymentNow_c.$ContractStartDate.$ContractEndDate.$AutoRenewContract_c.$Language.$SendVerificationSMS_c.$allowMultipleFreeStartPeriods_c.$HeaderEnrichmentReferenceCode.$SmsId;

            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

            $dataString = '{
                "signature": "'.$Digest.'",
                "customerAccountNumber": "'.$MSISDN.'",
                "msisdn": "'.$MSISDN.'",
                "operatorCode": "'.$OperatorCode.'",
                "subscriptionPlanId": '.$SubscriptionPlanId.',
                "initialPaymentproductId": "'.$InitialPaymentProductId.'",
                "initialPaymentDate": "'.$InitialPaymentDate.'",
                "executeInitialPaymentNow": '.$ExecuteInitialPaymentNow_c.',
                "executeRecurringPaymentNow": '.$ExecuteRecurringPaymentNow_c.',
                "recurringPaymentproductId": "'.$InitialPaymentProductId.'",
                "productCatalogName": "'.$ProductCatalogName.'",
                "autoRenewContract": '.$AutoRenewContract_c.',
                "sendVerificationSMS": '.$SendVerificationSMS_c.',
                "allowMultipleFreeStartPeriods": '.$allowMultipleFreeStartPeriods_c.',
                "contractStartDate": "'.$ContractStartDate.'",
                "contractEndDate": "'.$ContractEndDate.'",
                "language": '.$Language.',
                "headerEnrichmentReferenceCode": "'.$headerEnrichmentReferenceCode.'",
                "smsId": "",
                "sessionToken": "'.$stoken.'"
            }';
            //echo $dataString;die;
            $headers = array(             
                "Content-type: application/json",
                "Connection: Keep-Alive",
                "Content-length: ".strlen($dataString)
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
            //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/AddSubscriptionContractRequest");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt ($ch, CURLOPT_VERBOSE, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($ch);
            curl_close($ch);

            $jd = json_decode($out);

            //print_r($jd);die;

            /*echo "DS : ".$dataString;
            echo "<br/><br/>";
            echo "Out : ".$out;die;
            echo "<br/><br/>";
            print_r($info);die;
            $this->db->query("insert into tpay_qatar.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");*/

            $this->db->query("insert into tpay_mtania.callback_request(data,req,cbackfrom) values('".$dataString."','".$out."','otp');");

            if($jd->operationStatusCode=="0"){
                if($headerEnrichmentReferenceCode==''){
                    $sid = $jd->subscriptionContractId;
                    $transactionId = $jd->transactionId;
                    redirect(base_url()."welcome/ma_otp?sid=".$sid."&tid=".$tid."&transactionId=".$transactionId);
                }
                redirect(base_url()."welcome/ma_home?mobnum=".$MSISDN);
            }
            else if($jd->operationStatusCode=="51"){
                if($jd->errorMessage!=''){
                    redirect(base_url()."welcome/ma_subs");die;    
                }
                redirect(base_url()."welcome/ma_home?mobnum=".$MSISDN);
            }
            else{
                redirect(base_url()."welcome/ma_subs");
            }
        }
    }

    function ma_unsub(){
        $MSISDN = $this->session->userdata("mobnum");
        $qry_mdn = $this->db->query("select * from tpay_mtania.sub_users where mobnum='$MSISDN'");
        $subtype = $qry_mdn->row()->SubType;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_mtania.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU weekly";    
            }
            else{
                $data['subtype'] = "50 MRU monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else if($lang=="fr"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU par jour";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU par semaine";    
            }
            else{
                $data['subtype'] = "50 MRU par mois";    
            }
            $data['disclaimer'] = "Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU عماني يوميا";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU عماني أسبوعيا";    
            }
            else{
                $data['subtype'] = "50 MRU عماني شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_ma');
        $this->load->view('newui/ma_unsub',$data);
    }

    function mt_unsub(){
        $mobnum = $this->session->userdata('mobnum');
        $MSISDN = $this->session->userdata("mobnum");
        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_mtania.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;
        $data['lang'] = $lang;
        $query = $this->db->query("select * from tpay_mtania.sub_users where mobnum='$mobnum'");
        $sid = $query->row()->productID;
        $this->ma_unsubscription($sid);

        $this->session->sess_destroy();
        
        if($lang=="en"){
            $data['msg'] = "You have been successfully unsubscribed from mComics. To subscribe again, visit ".base_url();
        }
        else if($lang=="fr"){
            $data['msg'] = "Vous avez été désabonné avec succès de mComics. Pour vous abonner à nouveau, visitez ".base_url();
        }
        else{
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mComics. للاشتراك مرة أخرى ، تفضل بزيارة".base_url();
        }              
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function ma_unsubscription($subscriptionContractId){
        //$subscriptionContractId = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $subscriptionContractId;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$subscriptionContractId.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_URL, "http://live.tpay.me/api/TPAYSubscription.svc/json/CancelSubscriptionContractRequest");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        //print_r($out);die;
    }

    function ma_otp(){
        $sid = $this->input->get_post('sid');
        $tid = $this->input->get_post('tid');
        $transactionId = $this->input->get_post('transactionId');
        $data['sid'] = $sid;
        $data['transactionId'] = $transactionId;

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_mtania.tidlogs where tid='".$tid."'");
        $mobnum = $getSubType->row()->mobnum;
        $subtype = $getSubType->row()->subtype;
        $lang = $getSubType->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU weekly";    
            }
            else{
                $data['subtype'] = "50 MRU monthly";    
            }
            $data['disclaimer'] = "<b>You will subscribe in mComics for ".$data['subtype'].". This is an auto-renewal service.</b><br/>To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com";
        }
        else if($lang=="fr"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU par jour";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU par semaine";    
            }
            else{
                $data['subtype'] = "50 MRU par mois";    
            }
            $data['disclaimer'] = "<b>Vous vous abonnerez à mComics pour ".$data['subtype'].". Il s'agit d'un service de renouvellement automatique.</b><br/>Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU عماني يوميا";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU عماني أسبوعيا";    
            }
            else{
                $data['subtype'] = "50 MRU عماني شهريا";    
            }
            $data['disclaimer'] = "<b>سوف تشترك في mComics ".$data['subtype'].". هذه خدمة تجديد تلقائي.</b><br/>لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com";
        }

        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $data['tid'] = $tid;

        $this->load->view('newui/template/header_ma');
        $this->load->view('newui/ma_pin',$data);
    }

    function ma_otp_resend(){
        $sid = $this->input->get_post('sid');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $Message = $sid;

        $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $dataString = '{
            "signature": "'.$Digest.'",
            "subscriptionContractId": "'.$sid.'"
        }';
        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);        
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/SendSubscriptionContractVerificationSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        //print_r($jd);die;

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Send Verification Code Limit Exceeded"){
                echo $jd->errorMessage;
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ma_otp_verify(){
        $sid = $this->input->get_post('sid');
        $otp = $this->input->get_post('otp');
        $tid = $this->input->get_post('tid');
        $mobnum = $this->input->get_post('mobnum');

        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $checkSub = $this->db->query("select * from tpay_mtania.sub_users_unsub where mobnum='".$mobnum."' and productID<>''");
        if($checkSub->num_rows()==0){
            $Message = $sid.$otp;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'"
            }';
        }
        else{
            $charge = 'true';
            $Message = $sid.$otp.$tid.$charge;
            $Digest=$PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);
            $dataString = '{
                "signature": "'.$Digest.'",
                "pinCode": "'.$otp.'",
                "subscriptionContractId": "'.$sid.'",
                "charge": true,
                "transactionId": "'.$tid.'"
            }';
        }

        //echo $dataString;die;
        $headers = array(             
            "Content-type: application/json",
            "Connection: Keep-Alive",
            "Content-length: ".strlen($dataString)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
        //curl_setopt($ch, CURLOPT_URL, "http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_URL, "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $jd = json_decode($out);

        $this->db->query("insert into tpay_mtania.callback_request(req,data) values('".$dataString."','".$out."');");

        if($jd->operationStatusCode=="0"){
            echo "success";
        }
        else{
            if($jd->errorMessage=="Invalid Pincode"){
                echo $jd->errorMessage;
            }
            else if(substr($jd->errorMessage, 0, 14)=="request to api"){
                echo "dr";
            }
            else if($jd->errorMessage=="InitialPaymentFailed"){
                echo "ipf";
            }
            else{
                echo "There was some error. Please try again.";
            }
        }
    }

    function ma_home(){
        $MSISDN = '222'.substr($this->input->get_post('mobnum'),-8,8);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        //$this->session->set_userdata("qataruser","yes");

        $getSubType = $this->db->query("select mobnum,subtype,lang from tpay_mtania.tidlogs where mobnum='".$MSISDN."' order by id desc limit 1");
        $lang = $getSubType->row()->lang;

        if($lang=="ar"){
            $languageChange = "arabic";
        }
        else if($lang=="fr"){
            $languageChange = "french";
        }
        else{
            $languageChange = "english";
        }
        $query = $this->db->query("SELECT * FROM mComics.user_lang where mobnum='$MSISDN'");
        if($query->num_rows()>0){
            $query = $this->db->query("UPDATE mComics.user_lang SET language='$languageChange',updatetime=now() WHERE mobnum='$MSISDN'");
        }else{
            $query = $this->db->query("INSERT INTO mComics.user_lang(language,mobnum,`date`) VALUES('$languageChange','$MSISDN',now()) ");
        }

        redirect(base_url()."welcome/index");
    }

    function ma_password_resend(){
        $mobnum = $this->input->get_post('mobnum');
        $oper = $this->input->get_post('oper');

        file_get_contents("http://111.118.180.237/tpay/mtania/sendsms.php?mobnum=$mobnum&oper=$oper");
        echo "success";
    }

    function ma_password_resend_login(){
        $id = $this->input->get_post('id');
        
        $query = $this->db->query("select * from tpay_mtania.sub_users where Id='$id'");
        if($query->num_rows()>0){
            $mobnum =  $query->row()->Mobnum;
            $oper =  $query->row()->oper;
            file_get_contents("http://111.118.180.237/tpay/mtania/sendsms.php?mobnum=$mobnum&oper=$oper");
            echo "success";
        }
        else{
            echo "This username does not exist. Please check";
        }        
    }

    function ma_password_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $pass = $this->input->get_post('pass');

        $query = $this->db->query("select * from tpay_mtania.sub_users where Id='$mobnum' and pass='$pass'");
        if($query->num_rows()>0){
            echo "success";
        }
        else{
            echo "false";
        }
    }

    function maLogin(){
        $i = $this->input->get_post("i");
        $id = isset($i) ? $i : '0' ;
        if($i==''){
            $id = 0;
        }
        $qry_mdn = $this->db->query("select * from tpay_mtania.sub_users where id=$id");
        $mobnum = $qry_mdn->row()->Mobnum;
        $subtype = $qry_mdn->row()->SubType;
        $qry_lang = $this->db->query("select * from tpay_mtania.tidlogs where mobnum='$mobnum' order by id desc limit 1");
        $lang = $qry_lang->row()->lang;
        if($lang=="en"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU daily";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU weekly";    
            }
            else{
                $data['subtype'] = "50 MRU monthly";    
            }
            $data['disclaimer'] = "To cancel your subscription, please send '04' to 1260.<br/>To cancel from the site please go to your profile then press Unsubscribe button.<br/>The internet usage will be deducted from your package knowing that the service will be renewed automatically.<br/>For any inquires please contact us on support@pheuture.com.";
        }
        else if($lang=="fr"){
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU par jour";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU par semaine";    
            }
            else{
                $data['subtype'] = "50 MRU par mois";    
            }
            $data['disclaimer'] = "Pour annuler votre abonnement, veuillez envoyer '04' au 1260.<br/>Pour annuler depuis le site veuillez vous rendre sur votre profil puis appuyez sur le bouton Se désinscrire.<br/>L'utilisation d'internet sera déduite de votre forfait sachant que le service sera renouvelé automatiquement.<br/>Pour toute demande, veuillez nous contacter sur support@pheuture.com.";
        }
        else{
            if($subtype=="daily"){
                $data['subtype'] = "5 MRU عماني يوميا";    
            }
            else if($subtype=="weekly"){
                $data['subtype'] = "20 MRU عماني أسبوعيا";    
            }
            else{
                $data['subtype'] = "50 MRU عماني شهريا";    
            }
            $data['disclaimer'] = "لإلغاء اشتراكك ، يرجى إرسال '04' إلى 1260. <br/> للإلغاء من الموقع ، يرجى الانتقال إلى ملف التعريف الخاص بك ثم الضغط على زر إلغاء الاشتراك. <br/> سيتم خصم استخدام الإنترنت من باقتك مع العلم أن الخدمة ستكون كذلك. يتم تجديده تلقائيًا. <br/> لأية استفسارات ، يرجى الاتصال بنا على support@pheuture.com.";
        }
        $data['lang'] = $lang;

        $this->load->view('newui/template/header_ma');
        $this->load->view('newui/ma_login',$data);
    }

    function ma_get_mobnum(){
        $id = $this->input->get_post('id');

        $query = $this->db->query("select * from tpay_mtania.sub_users where Id='$id'");
        if($query->num_rows()>0){
            echo $query->row()->Mobnum;
        }
        else{
            echo "false";
        }
    }

    function ma_he_camp(){
        $subtype = "daily";
        $lang = "en";
        
        $PublicKey = "THJoDINxZc9ruCEZtyeO";
        $PrivateKey = "Qx1f73tPp1gcuhnYTLrg";

        $tid = date('YmdHms').rand(1111,99999);
        $created = gmdate('Y-m-d H:i:s\Z');
        $TimeStamp =  gmdate('Y-m-d H:i:s\Z').'en1';
        
        $redirectUrl="http://mc.mcomics.club/welcome/ma_token";
        
        $Message= $created.$redirectUrl.'true'.$tid;
        
        $signature = $PublicKey.":".hash_hmac("sha256",$Message,$PrivateKey);

        $url = "http://lookup.tpay.me/IDXML.ashx/rdr/enriched?date=".$created."&redirectUrl=".$redirectUrl."&autoRedirect=true&orderId=".$tid."&signature=".$signature;

        //echo $url;die;

        redirect($url);die;
    }

    function ma_token(){
        $tid = $this->input->get_post('OrderId');
        $stoken = $this->input->get_post('SessionToken');
        $subtype = "daily";
        $lang = "en";
        $this->db->query("insert into tpay_mtania.tidlogs(tid,subtype,date,lang,stoken) values('".$tid."','".$subtype."',now(),'$lang','$stoken')");
        echo $tid;
    } 

    /**************************T-Pay mtania End************************/


    function cellc_home(){
        /*error_reporting(E_ALL);
        ini_set("display_errors", 1);*/
        
        $this->session->set_userdata('cellc_sa','cellc');
        $MSISDN = '919871666000';
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");

        redirect(base_url()."welcome/index");
    }

    function cellc_msg(){
        $data['msg'] = "Please click on the url received in the sms and enjoy mComics!";           
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    function cellc_home_user(){
        $this->session->set_userdata('cellc_sa_user','cellc');
        $MSISDN = $this->input->get_post('mobnum');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");

        redirect(base_url()."welcome/index");
    }

    function cellc_msg_user(){
        $data['msg'] = "Service is currently suspended. Please recharge and click on the url received in the sms and enjoy mComics!";           
        $this->load->view('template/header_auth');
        $this->load->view('unsubmessage',$data);
    }

    /***************************************Vodacom Sa***************/
    public function get_MDN_sa() {
        $mobnum='';
        /*if(isset($_SERVER['HTTP_X_VC_ACR'])) {
            $mobnum=$_SERVER['HTTP_X_VC_ACR'];
            $mdn=$mobnum;
        }
        else */
        if(isset($_SERVER['HTTP_X_VCZA_ACR'])) {
            $mobnum=$_SERVER['HTTP_X_VCZA_ACR'];
            $mdn=$mobnum;
        }
        if(!isset($mdn))
            $mdn='1111111111';
        $this->session->set_userdata('mobnum',$mobnum);
        return $mdn;
    }

    function vodacom_sa(){
        $mdn = $this->get_MDN_sa();

        $ret = file_get_contents("http://111.118.180.237/vodacom_sa/getPacks.php?mdn_e=".$mdn);
        $arr = explode("$", $ret);
        $chk = $arr[0];
        $pid = $arr[1];
        $mobnum = $arr[2];

        if($chk=="ASUB"){
            $this->db->query("update vodacom_sa.sub_users set mobnum='$mobnum' where productID='$pid'");
            $this->db->query("insert into vodacom_sa.callbackSms(data,cbackfrom) values('".$mobnum." : ".$pid."','mobnum')");
            redirect(base_url()."vodacom_home?mobnum=$mobnum");die;  
        }

        $transactionId = 'ym_why_p0_'.date("YmdHis").rand(1111,9999).rand(111,999);

        //test url
        //$url = "http://fusion-test.vodacom.co.za:8080/enterprise-services/ppd/service/partner/verify/v1?partner-id=DCB_YOLAMEDIA&token=".$mdn."&package-id=".urlencode($pid)."&client-txn-id=".$transactionId."&partner-redirect-url=".urlencode("http://mcomics.club/welcome/vodacom_sa_lp");

        //production
        $url = "https://verify.quickpay.mobi/api/v1/web/ci?partner-id=DCB_YOLAMEDIA&token=".$mdn."&package-id=".urlencode($pid)."&client-txn-id=".$transactionId."&partner-redirect-url=".urlencode("http://mcomics.club/welcome/vodacom_sa_lp");

        $this->db->query("insert into vodacom_sa.callbackSms(data,cbackfrom) values('$url','cg')");
        $this->db->query("insert into vodacom_sa.tokenlogs(token,date,mdn) values('$transactionId',now(),'$mobnum')");
        redirect($url);
    }

    function vodacom_sa_lp(){
        $this->db->query("INSERT INTO vodacom_sa.callback_request_renew(`response`,`phpinput`,`request`,`date`) VALUES('','','".serialize($_GET)."',now()) ");

        $date = date('Y-m-d H:i:s');
        $data_log = "\n".$date." ; ".serialize($_GET);

        //file_put_contents('/tmp/vodacom_sa_test.txt', $data_log, FILE_APPEND);

        //print_r($_GET);die;
        $status_code = $this->input->get_post('status-code');
        if($status_code==0){
            $tid = $this->input->get_post('client-txn-id');
            $getToken = $this->db->query("select mdn from vodacom_sa.tokenlogs where token='$tid'");
            $mobnum = $getToken->row()->mdn;
            $pid = file_get_contents("http://111.118.180.237/vodacom_sa/getSubInfo.php?mdn=$mobnum");

            $this->db->query("insert into vodacom_sa.sub_users(Mobnum,SubType,SubDate,SubStatus,lastcharged,productID) values('$mobnum','daily',now(),'SUCCESS',now(),'$pid')");
            $this->db->query("insert into vodacom_sa.SubLogs(Mobnum,Action,Status,Date,Type,Amount,Mode) values('$mobnum','SUB','SUCCESS',now(),'daily','7','WEB')");
            $this->db->query("insert into vodacom_sa.SubLogs_archive(Mobnum,Action,Status,Date,Type,Amount,Mode) values('$mobnum','SUB','SUCCESS',now(),'daily','7','WEB')");
            redirect(base_url()."vodacom_home?mobnum=$mobnum");die;
        }
        else if($status_code==6){
            $msg = "Session timed out. Please try again.";
            $re_func = "vsa_subs";
        }
        else if($status_code==1){
            $msg = "You have declined the subscription. Please click below to subscribe.";
            $re_func = "vsa_subs";
        }
        else if($status_code==5){
            $msg = "You have insufficient balance. Please recharge and try again.";
            $re_func = "vsa_subs";
        }
        else if($status_code==4){
            $msg = "Sorry, you have a content block flag set. Dial *135*997# to unblock.";
            $re_func = "vsa_subs";
        }
        else{
            $msg = "There was some error. Please try again.";
            $re_func = "vsa_subs";
        }

        $data['msg'] = $msg;
        $data['re_func'] = $re_func;
        $this->load->view('template/header_auth');
        $this->load->view('vodacom_sa_msg',$data);
    }

    function vodacom_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");    
        redirect(base_url()."welcome/index");        
    }

    function vsa_subs(){
        $msg = "Daily pack @ R7. Click Subscribe to proceed.";
        $re_func = "vodacom_sa";
        $data['msg'] = $msg;
        $data['re_func'] = $re_func;
        $this->load->view('template/header_auth');
        $this->load->view('vodacom_sa_msg',$data);
    }

    function vodacom_test(){
        $status_code = $this->input->get_post('s');
        if($status_code==6){
            $msg = "Session timed out. Please try again.";
        }
        else if($status_code==1){
            $re_func = "vsa_subs";
            $msg = "You have declined the subscription. Please click below to subscribe.";
        }
        else if($status_code==5){
            $re_func = "vsa_subs";
            $msg = "You have insufficient balance. Please recharge and try again.";
        }
        else{
            $msg = "There was some error. Please try again.";
            $re_func = "vsa_subs";
        }

        $data['msg'] = $msg;
        $data['re_func'] = $re_func;
        $this->load->view('template/header_auth');
        $this->load->view('vodacom_sa_msg',$data);
    }

    //////////////////////////////MTN SA WP//////////////////////////////////////
    function mtnsa_wp_subs(){
        //$mdn = $this->get_MDN_sa();
        $mdn = '';
        $cl = 'SP_MM_mComics';
        $sp = "500";
        $bf = "Day";
        $do = "s";
        $pass = "52617f89";
        //$transactionId = date("YmdHis").rand(1111,9999).rand(111,999);
        $transactionId = rand(11,9999999).rand(1,99);
        $bl = "http://sa.mcomics.club/images/zi_mcomics_new.jpg";

        $hash = md5($cl.$mdn.$do.$sp.$bf.$pass);

        $url = "http://wap.zero9.co.za/mesh/WapDoiRequest.php?cl=$cl&tn=$mdn&do=$do&sp=$sp&bf=$bf&ex=".$transactionId."&nt=mComics&ru=".urlencode("http://sa.mcomics.club/welcome/mtn_sa_lp")."&bl=".urlencode($bl)."&mh=".$hash;

        $this->db->query("insert into mtnsa_mcomics.callbackSms(data,cbackfrom) values('$url','cg')");
        //$this->db->query("insert into vodacom_sa.tokenlogs(token,date,mdn) values('$transactionId',now(),'$mobnum')");
        redirect($url);
    }

    function mtn_sa_lp(){
        $this->db->query("INSERT INTO mtnsa_mcomics.callback_request_renew(`response`,`phpinput`,`request`,`date`) VALUES('','','".serialize($_GET)."',now()) ");

        //print_r($_GET);die;
        $status_code = $this->input->get_post('ei');
        if($status_code==0){
            $mobnum = $this->input->get_post('tn');
            $chk_user = $this->db->query("select * from mtnsa_mcomics.sub_users where mobnum='$mobnum'");
            if($chk_user->num_rows()==0){
                $this->db->query("insert into mtnsa_mcomics.sub_users(mobnum,subtype,subdate,substatus) values('$mobnum','daily',now(),'SUCCESS')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs_archive(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
            }
            redirect(base_url()."mtnsa_home?mobnum=$mobnum");die;
        }
        else if(($status_code==41) || ($status_code==041)){
            $mobnum = $this->input->get_post('tn');
            $chk_user = $this->db->query("select * from mtnsa_mcomics.sub_users where mobnum='$mobnum'");
            if($chk_user->num_rows()==0){
                $this->db->query("insert into mtnsa_mcomics.sub_users(mobnum,subtype,subdate,substatus) values('$mobnum','daily',now(),'SUCCESS')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs_archive(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
            }
            redirect(base_url()."mtnsa_home?mobnum=$mobnum");die;
        }
        else{
            $msg = "There was some error. Please try again.";
        }

        $data['msg'] = $msg;
        $this->load->view('template/header_auth');
        $this->load->view('mtn_sa_msg',$data);
    }

    function mtnsa_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");    
        redirect(base_url()."welcome/index");        
    }

    function mtnsa_lp_cb(){
        $this->db->query("INSERT INTO mtnsa_mcomics.callback_request_renew(`response`,`phpinput`,`request`,`date`) VALUES('wp_camp','','".serialize($_GET)."',now()) ");

        //print_r($_GET);die;
        $status_code = $this->input->get_post('ei');
        if($status_code==0){
            $mobnum = $this->input->get_post('tn');
            $chk_user = $this->db->query("select * from mtnsa_mcomics.sub_users where mobnum='$mobnum'");
            if($chk_user->num_rows()==0){
                $this->db->query("insert into mtnsa_mcomics.sub_users(mobnum,subtype,subdate,substatus) values('$mobnum','daily',now(),'SUCCESS')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs_archive(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
            }
            redirect(base_url()."mtnsa_home?mobnum=$mobnum");die;
        }
        else if(($status_code==41) || ($status_code==041)){
            $mobnum = $this->input->get_post('tn');
            $chk_user = $this->db->query("select * from mtnsa_mcomics.sub_users where mobnum='$mobnum'");
            if($chk_user->num_rows()==0){
                $this->db->query("insert into mtnsa_mcomics.sub_users(mobnum,subtype,subdate,substatus) values('$mobnum','daily',now(),'SUCCESS')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
                $this->db->query("insert into mtnsa_mcomics.SubLogs_archive(mobnum,action,status,date,type,amount) values('$mobnum','SUB','SUCCESS',now(),'daily','')");
            }
            redirect(base_url()."mtnsa_home?mobnum=$mobnum");die;
        }
        else{
            $msg = "There was some error. Please try again.";
        }

        $data['msg'] = $msg;
        $this->load->view('template/header_auth');
        $this->load->view('mtn_sa_msg',$data);
    }

    ////////////////////////Palestine Jawwal///////////////////////////////////////
    function pal_sub(){
        $data['msg'] = $msg;
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/pal_sub',$data);
    }

    function pal_otp(){
        $mobnum = $this->input->get_post('mobnum');
        $mobnum = '972'.substr($mobnum,-9,9);
        $data['mobnum'] = $mobnum;
        $chk_user = $this->db->query("select * from pal_mcomics.sub_users where mobnum='$mobnum'");
        if($chk_user->num_rows()){
            redirect('http://mtoonapp.com/pal_home?mobnum='.$mobnum);die;
        }
        else{
            /*$res = file_get_contents("http://51.89.210.96/jawwal/migration/otp.php?mdn=".$mobnum."&ser=10896");
            
            $url ="/jawwal/otp.php?mdn=".$mobnum."&ser=10896";

            $fp = fsockopen("51.89.210.96", 80, $errno, $errstr, 30);
            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            }
            else{
                $out = "GET $url HTTP/1.0\r\n";
                fputs($fp, $out);
                $out = "Connection: close\r\n\r\n";
                fputs($fp, $out);
                fclose($fp);
            }
            $data['msg']='';
            // $this->load->view('newui/template/header_auth');
            $this->load->view('newui/meme/header');
            $this->load->view('newui/pj_otp_verify',$data);*/

            $res = file_get_contents("http://51.89.210.96/jawwal/migration/otp.php?mdn=$mobnum&ser=10896");
            if($res=="Success"){
                $data['msg']='';
                $this->load->view('newui/template/header_auth');
                $this->load->view('newui/pj_otp_verify',$data);
            }
            else{
                $data['msg']="كان هناك خطأ ما. <a href='http://mtoonapp.com/pal_sub'>اضغط هنا للمحاولة مرة أخرى.</a>";
                $this->load->view('newui/template/header_auth');
                $this->load->view('newui/pal_msg',$data);
            }
        }
    }

    function pal_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $otp = $this->input->get_post('otp');
        $data['mobnum'] = $mobnum;
        $res = file_get_contents("http://51.89.210.96/jawwal/migration/otp_verify.php?mdn=$mobnum&ser=10896&otp=$otp");
        if($res=="Success"){
            file_get_contents("http://51.89.210.96/jawwal/sub.php?mdn=$mobnum&ser=10896&otp=$otp");
            $data['msg']="لقد اشتركت بنجاح في Mtoon. <a href='http://mtoonapp.com/pal_home?mobnum=$mobnum'>انقر هنا لاستخدام الخدمة.</a>";
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/pal_msg',$data);
        }
        else{
            $data['msg']="OTP غير صحيح. الرجاء إدخال OTP الصحيح.";
            // $this->load->view('newui/template/header_auth');
            $this->load->view('newui/meme/header');
            $this->load->view('newui/pj_otp_verify',$data);
        }
    }

    function pal_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $MSISDN = '972'.substr($MSISDN,-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");    
        redirect(base_url()."welcome/index");        
    }

    function pal_unsub(){
        $mobnum = $this->session->userdata("mobnum");
        $res = file_get_contents("http://51.89.210.96/jawwal/migration/unsubscribe.php?mdn=$mobnum&ser=10896");
        if($res=="Success"){
            $data['msg'] = "لقد تم إلغاء اشتراكك بنجاح من mToon. للاشتراك مرة أخرى ، قم بزيارة <a href='http://mtoonapp.com'>".base_url()."</a>";     
        }
        else{
            $data['msg'] = "حدث خطأ أثناء الاشتراك. من فضلك حاول مرة أخرى بعد بعض من الوقت.";
        }
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/pal_msg',$data);   
    }


    /*************************Anest UAE Etisalat Start****************/
    function aue_sub(){
        $data['msg'] = $msg;
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/aue_sub',$data);
    }

    function aue_otp(){
        $lang = $this->input->get_post('lang');
        $mobnum = $this->input->get_post('mobnum');
        $mobnum = '971'.substr($mobnum,-9,9);
        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $chk_user = $this->db->query("select * from anest_eti_uae_mcomics.sub_users where mobnum='$mobnum'");
        if($chk_user->num_rows()){
            redirect('http://u.mtoonapp.com/aue_home?mobnum='.$mobnum);die;
        }
        else{
            $res = file_get_contents("http://51.89.210.96/anest/etisalat_uae/otp.php?mdn=$mobnum");
            if($res=="Success"){
                $data['msg']='';
                //$this->load->view('newui/template/header_auth');
                $this->load->view('newui/meme/header');
                $this->load->view('newui/aue_otp_verify',$data);
            }
            else{
                if($lang=="ar"){
                    $data['msg']="كان هناك خطأ.<a href='http://u.mtoonapp.com/aue_sub'>اضغط هنا للمحاولة مرة أخرى.</a>";
                }
                else{
                    $data['msg']="There was an error. <a href='http://u.mtoonapp.com/aue_sub'>Click here to try again.</a>";    
                }
                
                //$this->load->view('newui/template/header_auth');
                $this->load->view('newui/meme/header');
                $this->load->view('newui/aue_msg',$data);
            }
        }
    }

    function aue_verify(){
        $lang = $this->input->get_post('lang');
        $mobnum = $this->input->get_post('mobnum');
        $otp = $this->input->get_post('otp');
        $data['mobnum'] = $mobnum;
        $data['lang'] = $lang;
        $res = file_get_contents("http://51.89.210.96/anest/etisalat_uae/otp_val.php?mdn=$mobnum&otp=$otp");
        if($res=="Success"){
            if($lang=="ar"){
                $data['msg']="لقد اشتركت بنجاح في mToon. <a href='http://u.mtoonapp.com/aue_home?mobnum=$mobnum'>انقر هنا لاستخدام الخدمة.</a>";
            }
            else{
                $data['msg']="You have successfully subscribed to mToon. <a href='http://u.mtoonapp.com/aue_home?mobnum=$mobnum'>Click here to use the service.</a>";
            }
            
            //$this->load->view('newui/template/header_auth');
            $this->load->view('newui/meme/header');
            $this->load->view('newui/aue_msg',$data);
        }
        else{
            if($lang=="ar"){
                $data['msg']="OTP غير صحيح. الرجاء إدخال كلمة المرور لمرة واحدة الصحيحة.";
            }
            else{
                $data['msg']="Invalid OTP. Please enter the correct OTP.";
            }
            
            // $this->load->view('newui/template/header_auth');
            $this->load->view('newui/meme/header');
            $this->load->view('newui/aue_otp_verify',$data);
        }
    }

    function aue_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $MSISDN = '971'.substr($MSISDN,-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");    
        redirect(base_url()."welcome/index");        
    }

    function aue_home_login(){
        $id = $this->input->get_post('id');
        $chk_user = $this->db->query("select * from anest_eti_uae_mcomics.sub_users where id='$id'");
        if($chk_user->num_rows()){
            $MSISDN = $chk_user->row()->Mobnum;
            $this->session->set_userdata("mobnum",$MSISDN);
            $this->session->set_userdata("subuser","yes");    
            redirect(base_url()."welcome/index");
        }
        else{
            redirect(base_url()."aue_sub");
        }        
    }

    function aue_unsub(){
        $mobnum = $this->session->userdata("mobnum");
        file_get_contents("http://51.89.210.96/anest/etisalat_uae/unsubscribe.php?mdn=$mobnum");
        $data['msg'] = "You have been successfully unsubscribed from mToon. To subscribe again, visit <a href='http://u.mtoonapp.com'>".base_url()."</a>";     
        //$this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/aue_msg',$data);
    }

    function aue_test(){
        $data['lang'] = "ar";
        $data['msg']="لقد اشتركت بنجاح في mToon. <a href='http://u.mcomics.club/aue_home?mobnum=$mobnum'>انقر هنا لاستخدام الخدمة.</a>";
        
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/aue_msg',$data);
    }

    /*************************Anest UAE Etisalat END****************/



    // MamaMeo 04jan2021
    function new_meme()
    {
        //echo "Fine here"; die();
        $this->load->view('newui/meme/index');
    }

    // Sticker Maker 01feb2021
    function sticker_maker()
    {
        //echo "Fine here"; die();
        $this->load->view('sticker_maker/index');
    }

    // Drawit 11feb2021
    function drawit()
    {
        //echo "Fine here"; die();
        $this->load->view('sticker_maker/drawit');
    }
    function newlink()
    {
        $this->session->set_userdata('cellc_sa','cellc');
        $MSISDN = '919871666000';
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");
        $this->load->view('sticker_maker/newlink');
    }



    //mondia sa MTN drawit 
    function mondia_sa_int(){
        $mdn = '';
        $mobnum = '';
        
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
        if($referrer=="") 
            $referrer     = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
        $recid = $this->mondiasacamp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'internal');
        
        $res = $this->mondia_sub_new($mobnum,'2',$recid,'internal');
                die;
        /*$ip = $REMOTE_ADDR;
        if((substr($ip,0,6)=="41.113") || (substr($ip,0,6)=="41.114") || (substr($ip,0,6)=="41.115")){

            $res = $this->mondia_sub_new($mobnum,'2',$recid,'internal');
                die;
        }
        else{
            header("Location: https://ms.drawitpuzzle.com/dlogin1.php");die;
        }*/
    }

    function mondiasacamp_hits($mobnum,$url,$ip,$useragent,$HTTP_REFERER,$adnet) {
        $query = $this->db->query("INSERT into mondia_sa.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$mobnum','$url','$ip','$useragent',now(),'$adnet','$HTTP_REFERER');");
        return $this->db->insert_id();
    }


    function mondiasa_camp_charge($mobnum,$status,$hid,$adnet,$requestid) {
        $query = $this->db->query("INSERT into mondia_sa.reporo_charge(mdn,hid,status,date,adnet,reqid) values('$mobnum','$hid','$status',now(),'$adnet','$requestid');");
    }

    function mondia_sub_new($mobnum,$status,$hid,$adnet) {
        $rand = "MONDIASA".rand(10,2000);
        $requestid="MONDIASA_".
        $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        for ($i = 0; $i < 15; $i++) {
            $requestid .= $characters[rand(0, strlen($characters) - 1)];
        }
        $requestid .= rand(10,2000);
        $this->mondiasa_camp_charge($mobnum,$status,$hid,$adnet,$requestid);
        //mondiasa_cpa_clickid($requestid,$clickid,'249media',$cons);
        $res = file_get_contents("http://51.68.207.190/mondia/sa/subscribe_new.php?id=$requestid");
        $res = preg_replace('/\s+/', '', $res); 
        if($res=="NOTOTOKEN"){
            header('location: http://ms.drawitpuzzle.com/subfail_mondia.php?error=1');
        }else if($res=="BAD_REQUEST" || $res=='' || strpos($res,'http') === false){
            header('location: http://ms.drawitpuzzle.com/subfail_mondia.php');
        }       
        else{
            header("location:".$res);           
        }
        die;
    }



    ////////////////////////Srilanka Dialog Ideabiz//////////////////////////////
    function sd_sub(){
        $data['msg'] = $msg;
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/template/header_auth');
        $this->load->view('newui/sd_sub',$data);
    }

    function sd_url(){
        $requestid="";
        $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        for ($i = 0; $i < 20; $i++) {
            $requestid .= $characters[rand(0, strlen($characters) - 1)];
        }
        $requestid .= rand(10,2000);
        $this->db->query("insert into dialog_sl_mcomics.req_id_details(request,date,page) values('$requestid',now(),'sd_url')");
        redirect("https://widget.ideabiz.lk/web/reg/initiate/cffbd20abf23b8d724045938237c13a9?request-ref=$requestid");
    }


    function sd_redirect(){

        $mobnum = $this->session->userdata("mobnum");
        $ref    = $this->input->get_post('ref');
        if($ref == '') {
            redirect(base_url());  
            die;
        }
        $request = file_get_contents("http://111.118.180.237/dialog_sl_mcomics/chk_status.php?ref=$ref");
        $res    = json_decode($request,true);
        $mobnum = isset($res['msisdn']) ? $res['msisdn'] : '';
        $action = isset($res['status']) ? $res['status'] : '';
        $mode   = isset($res['doneBy']) ? $res['doneBy'] : '';
        $tid    = isset($res['serverRef']) ? $res['serverRef'] : '';
        $type   = isset($res['type']) ? $res['type'] : '';
        $mobnum = '94'.substr($mobnum,-9,9);
        $this->db->query("insert into dialog_sl_mcomics.req_id_details(request,date,page) values('".serialize($request)."',now(),'sd_red')");
       // $result = $this->subscribe_model->ideabiz_dialCallback($mobnum,$action,$mode,$type,$tid);
        /*if($action == 'ALREADY_SUBSCRIBED'){

        }*/
        redirect('http://sd.mcomics.club/sd_home?mobnum='.$mobnum);die;
        $this->session->set_userdata("mobnum",$mobnum);
        $this->session->set_userdata("subuser","yes");    
        
        $data['msg'] = "You have been successfully subscribed from mComics. Visit <a href='http://sd.mcomics.club'>".base_url()."</a>";     
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/sd_msg',$data);        
    }

    function sd_unsub(){
        $mobnum = $this->session->userdata("mobnum");
        file_get_contents("http://111.118.180.237/dialog_sl_mcomics/unsub.php?msisdn=$mobnum&mode=WEB");
        $this->session->sess_destroy();
        $data['msg'] = "You have been successfully cancelled from mComics. To subscribe again, visit <a href='http://sd.mcomics.club'>".base_url()."</a>";     
        // $this->load->view('newui/template/header_auth');
        $this->load->view('newui/meme/header');
        $this->load->view('newui/sd_msg',$data);   
    }


    function sd_callback(){
        $request = file_get_contents("php://input");
        $res = json_decode($d,true);
        $mobnum = isset($res['msisdn']) ? $res['msisdn'] : '';
        $action = isset($res['status']) ? $res['status'] : '';
        $mode   = isset($res['doneBy']) ? $res['doneBy'] : '';
        $tid    = isset($res['serverRef']) ? $res['serverRef'] : '';
        $type   = isset($res['type']) ? $res['type'] : '';
        $mobnum = '94'.substr($mobnum,-9,9);
        $this->db->query("insert into dialog_sl_mcomics.req_id_details(request,date,page) values('$request',now(),'sd_cbk')");
        $result = $this->subscribe_model->ideabiz_dialCallback($mobnum,$action,$mode,$type,$tid);
        echo "done";
    }

    function sd_otp(){
        $mobnum = $this->input->get_post('mobnum');
        $mobnum = '94'.substr($mobnum,-9,9);
        $data['mobnum'] = $mobnum;
        $chk_user = $this->db->query("select * from dialog_sl_mcomics.sub_users where mobnum='$mobnum'");
        if($chk_user->num_rows()){
            redirect('http://sd.mcomics.club/sd_home?mobnum='.$mobnum);die;
        }
        else{
            $res = file_get_contents("http://sd.mcomics.club/dialog/send_otp.php?msisdn=$mobnum");

            if($res=="SUCCESS"){
                $data['msg']='';
                $this->load->view('newui/template/header_auth');
                $this->load->view('newui/sd_otp_verify',$data);
            }
            else{
                $data['msg']="Something went wrong. <a href='http://sd.mcomics.club/sd_sub'>Click here to try again.</a>";
                $this->load->view('newui/template/header_auth');
                $this->load->view('newui/sd_msg',$data);
            }
        }
    }

    function sd_verify(){
        $mobnum = $this->input->get_post('mobnum');
        $otp = $this->input->get_post('otp');
        $data['mobnum'] = $mobnum;
        $res = file_get_contents("http://sd.mcomics.club/dialog/verify_otp.php?msisdn=$mobnum&pin=$otp");
        $arr=explode(":",$res);
        if($arr["0"]=="SUCCESS"){
            $get_detail=file_get_contents("http://111.118.180.237/dialog_sl_mcomics/submcomics.php?MSISDN=$mobnum");


            if($status=="EXISTING"){
                redirect('http://sd.mcomics.club/sd_home?mobnum='.$mobnum);die;
            }else if($status=="NOMSISDN"){
                $msg = "Something went wrong. Please Try again.";
            }else{
                $data['msg']="You have successfully subscribed to mComics. <a href='http://sd.mcomics.club/sd_home?mobnum=$mobnum'>Click here to use the service.</a>";
            }
            $this->load->view('newui/template/header_auth');
            $this->load->view('newui/sd_msg',$data);
        }
        else{
            $data['msg']="Invalid OTP. Please enter the correct OTP.";
            // $this->load->view('newui/template/header_auth');
            $this->load->view('newui/meme/header');
            $this->load->view('newui/sd_otp_verify',$data);
        }
    }

    function sd_home(){
        $MSISDN = $this->input->get_post('mobnum');
        $MSISDN = '94'.substr($MSISDN,-9,9);
        $this->session->set_userdata("mobnum",$MSISDN);
        $this->session->set_userdata("subuser","yes");    
        redirect(base_url()."welcome/index");        
    }


    function sd_url_home(){
        $requestid="";
        $characters  = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        for ($i = 0; $i < 20; $i++) {
            $requestid .= $characters[rand(0, strlen($characters) - 1)];
        }
        $requestid .= rand(10,2000);
        $this->db->query("insert into dialog_sl_mcomics.req_id_details(request,date,page) values('$requestid',now(),'sd_url_home')");
        echo '{"url":"https://widget.ideabiz.lk/web/reg/initiate/cffbd20abf23b8d724045938237c13a9?request-ref='.$requestid.'"}';
    }

    /******************************************/
    function rmcu(){
        $mdn = get_MDN();
        if($mdn!='1111111111'){
            $query = $this->db->query("SELECT * FROM robi_mcomics.sub_users_sdp where mobnum='$mobnum' and productid<>''");
            if($query->num_rows()){
                @file_get_contents("http://111.118.180.237/robi/sdpmcomics/unsub.php?msisdn=$mobnum&mode=WEB");
                @file_get_contents("http://111.118.180.237/robi/sdpmcomics/unsub_backend_new.php?msisdn=$mobnum&sms=0");
            }
            else{
                @file_get_contents("http://111.118.180.237/robi/sdpmcomics/unsub_backend_new.php?msisdn=$mobnum&sms=1");
            }
        }
        else{
            echo "To unsubscribe, send UNSUB MC to 21270.";
        }
    }
    /*************************************************/

    function resize_imagegd($filename,$file,$w,$h) {
       /* $file="images/wallpapter_mc.jpg";
        $filename="testdushyant.jpg";
        $test = getimagesize($file);
        //print_r($test);
        $w=100;
        $h=120;*/
        $crop=FALSE;
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $imgName = "images/resize/$filename";
        imagejpeg($dst, $imgName, 100);
        return  $imgName;
    }
    public function testimagesize(){
        error_reporting(E_ALL);
        phpinfo();
        
        $imgName = "testdushyant".date('m-d-Y-His').'.png';
        echo  $imgName;
        $ct = new Imagick("images/wallpapter_mc.jpg");
        $mask = new Imagick("changefacefile/masking/mask75.png");

        $ct->setImageMatte(1); 

        $ct->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
        $ct->resizeImage(125, 175, Imagick::FILTER_LANCZOS, 1);
        
        // Write image to a file.
        $ct->writeImage("output/".$imgName);
        
         
        /*$width=100;
        $height=100;

        //$imagick = new Imagick();

        $imgPath = "images/wallpapter_mc.jpg"; // set your image file
        $testBlurs = [0, 0.1, 0.2, 0.5]; // test these blur values
        $im = new IMagick();
        $im->readImage($imgPath);
       
        foreach ((new ReflectionClass('IMagick'))->getConstants() as $n => $f) {
            if (strncmp($n, 'FILTER_', 7) === 0) { // get available IMagick filters
                $filterName = strtolower(substr($n, 7)); // extract filter name from constant
                foreach ($testBlurs as $blur) {
                    $imSize = clone $im;
                    $imSize->resizeImage(500, 500, $f, $blur, true);
                    $imSize->writeImage(sprintf("images/testdushyant.jpg", $imgPath, $filterName, $blur));
                    $imSize->destroy();
                }
            }
        }*/
    }


}

?>
