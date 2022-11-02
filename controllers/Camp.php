<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Camp extends CI_Controller
{

    function __construct()
    {
        parent::__construct();   
        $this->load->model('subscribe_model');
    }

    function encrypt($mdn){
		return ($mdn*13)+13579;
	}

	function decrypt($mdn){
		return ($mdn-13579)/13;
	}

	function opal(){
		redirect(base_url()."welcome/pal_he/daily/en");
	}

    function robi_ccamp(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'cygnus');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'cygnus');

    	/*$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}*/

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if($blocked==0 && $mdn<>''){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'cygnus');
				//$this->subscribe_model->robi_camp_backend($mdn,'cygnus');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }
	
	
	function rair_ccamp_old(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
		$mdn='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'cygnus');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'cygnus');

    	$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}
		
		$mobnum_blocked_int2=$this->subscribe_model->check_blocked_robi($mdn);
		if($mobnum_blocked_int2>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if(($blocked==0) && ($mdn<>'') && (substr($mdn,0,5)=="88016")){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'cygnus');
				//$this->subscribe_model->robi_camp_backend($mdn,'cygnus');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }

    function rair_ccamp(){
        die();
        $mobnum='';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
            $mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mobnum = substr($mobnum,-11);
            $mdn='880'.substr($mobnum,-10);
        }
        else if (isset($_SERVER['HTTP_MSISDN'])){
            $mobnum = $_SERVER['HTTP_MSISDN'];
            $mobnum = substr($mobnum,-11);
            $mdn='880'.substr($mobnum,-10);
        }

        $encrypted_mdn = $this->encrypt($mdn);

        $clickid=$this->input->get_post('clickid');

        if($mobnum==''){
            redirect("http://google.co.in");
            die();
        }

        echo "<script>
                top.window.location.href='http://111.118.180.249/mcomics/cyg_rair_1.php?id=$encrypted_mdn&clickid=$clickid';    
        </script>";
        die();
    }

    function rair_s2scamp(){
        die();
    	$mobnum='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}

    	$encrypted_mdn = $this->encrypt($mdn);

    	$clickid=$this->input->get_post('clickid');

    	if($mobnum==''){
    		redirect("http://google.co.in");
    		die();
    	}

    	/*echo "<script src='http://littledata.in/mcom/js/jquery-1.12.0.min.js'></script>";
		echo "<script>$(document).ready(function(){				
				top.window.location.href='http://111.118.180.249/mcomics/s2s_rair_1.php?id=$encrypted_mdn&clickid=$clickid';	
			});
		</script>";*/
        echo "<script>
                top.window.location.href='http://111.118.180.249/mcomics/s2s_rair_1.php?id=$encrypted_mdn&clickid=$clickid';    
        </script>";
		die();
    }

    function rair_s2scamp_old(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
		$mdn='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'s2s');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'s2s');

    	echo "<script src='http://localhost/application/create_cartoon/mcomics.club/js/jquery-1.12.0.min.js'></script>";
		//http://littledata.in/mcom/js/jquery-1.12.0.min.js
		
		/*echo "<script>$(document).ready(function(){				
			    if(self==top) {
					top.window.location.href='http://mcomics.club/camp/rair_s2scamp_new?recid=$recid';	
				}
				else{
					top.window.location.href='http://mcomics.club/camp/rair_s2scamp_new?recid=$recid';	
				}
			});
		</script>";*/
		echo "<script>$(document).ready(function(){				
				//top.window.location.href='http://mcomics.club/camp/rair_s2scamp_new?recid=$recid';	
				top.window.location.href='http://111.118.180.249/mcomics/s2s_rair_2.php?recid=$recid';	
			});
		</script>";
		//sleep(5);
    	die();    			
    }

    public function rair_s2scamp_new(){
        die();
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}

    	$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}
		
		$mobnum_blocked_int2=$this->subscribe_model->check_blocked_robi($mdn);
		if($mobnum_blocked_int2>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}
		$recid=$this->input->get_post('recid');

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if(($blocked==0) && ($mdn<>'') && (substr($mdn,0,5)=="88016")){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'s2s');
				//$this->subscribe_model->robi_camp_backend($mdn,'s2s');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}
    }


    function robi_s2scamp(){
        die();
        //if((date('d')==20) && (date('H')<23)){
        	$mobnum='';
        	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
        		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
        		$mobnum = substr($mobnum,-11);
        		$mdn='880'.substr($mobnum,-10);
        	}
        	else if (isset($_SERVER['HTTP_MSISDN'])){
        		$mobnum = $_SERVER['HTTP_MSISDN'];
        		$mobnum = substr($mobnum,-11);
        		$mdn='880'.substr($mobnum,-10);
        	}

        	$encrypted_mdn = $this->encrypt($mdn);

        	$clickid=$this->input->get_post('clickid');

        	if($mobnum==''){
        		redirect("http://google.co.in");
        		die();
        	}

        	/*echo "<script src='http://littledata.in/mcom/js/jquery-1.12.0.min.js'></script>";
    		echo "<script>$(document).ready(function(){				
    				top.window.location.href='http://111.118.180.249/mcomics/s2s_robi_1.php?id=$encrypted_mdn&clickid=$clickid';	
    			});
    		</script>";*/
            echo "<script>
                    top.window.location.href='http://111.118.180.249/mcomics/s2s_robi_1.php?id=$encrypted_mdn&clickid=$clickid';    
            </script>";
    		die();
        /*}
        else{
            redirect("http://google.com");
            die();
        }*/
    }

    function robi_s2scamp_old(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
		$mdn='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'s2s');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'s2s');

    	$mobnum_blocked_mc=$this->subscribe_model->check_dnd_block_robi_mc($mdn);
		if($mobnum_blocked_mc>0){
			//redirect('http://mcomics.club/robi_unauth');
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	echo "<script src='http://littledata.in/mcom/js/jquery-1.12.0.min.js'></script>";
		echo "<script>$(document).ready(function(){				
			    top.window.location.href='http://111.118.180.249/mcomics/s2s_robi_2.php?recid=$recid';	
			});
		</script>";
		//sleep(5);
    	die();    			
    }

    public function robi_s2scamp_new(){
        die();
        if((date('d')==15) && (date('H')<23)){
        	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
        		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
        		$mobnum = substr($mobnum,-11);
        		$mdn='880'.substr($mobnum,-10);
        	}
        	else if (isset($_SERVER['HTTP_MSISDN'])){
        		$mobnum = $_SERVER['HTTP_MSISDN'];
        		$mobnum = substr($mobnum,-11);
        		$mdn='880'.substr($mobnum,-10);
        	}

        	$mobnum_blocked_mc=$this->subscribe_model->check_dnd_block_robi_mc($mdn);
    		if($mobnum_blocked_mc>0){
    			//redirect('http://mcomics.club/robi_unauth');
    			redirect('http://www.google.com', 'refresh');
    			die();
    		}

        	$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
    		if($mobnum_blocked_int>0){
    			redirect('http://www.google.com', 'refresh');
    			die();
    		}

    		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
    		if($mobnum_blocked_int1>0){
    			redirect('http://www.google.com', 'refresh');
    			die();
    		}
    		
    		$mobnum_blocked_int2=$this->subscribe_model->check_blocked_robi($mdn);
    		if($mobnum_blocked_int2>0){
    			redirect('http://www.google.com', 'refresh');
    			die();
    		}
    		$recid=$this->input->get_post('recid');

        	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
    		if(($blocked==0) && ($mdn<>'') && (substr($mdn,0,5)=="88018")){				
    			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

    				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'s2s');
    				//$this->subscribe_model->robi_camp_backend($mdn,'s2s');
    				date_default_timezone_set("Asia/Kolkata");

    				$nonce = rand(1111111,9999999).rand(111111,9999999);
    				$created = date('Y-m-d\TH:i:s\Z');
    				$password = "Robi1234";

    				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


    				$hash = $nonce . $created . $password;
    				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


    				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

    				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

    				redirect($url);
    			}
    			else{
    				redirect("http://google.com");
    			}	
    		}
    		else{
    			redirect("http://google.com");
    		}
        }
        else{
            redirect("http://google.com");
            die();
        }
    }



    function rair_zgcamp(){
        die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
		$mdn='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'zerogravity');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('tid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'zerogravity');

    	echo "<script src='http://littledata.in/mcom/js/jquery-1.12.0.min.js'></script>";
		echo "<script>$(document).ready(function(){				
			    top.window.location.href='http://111.118.180.249/mcomics/zg_rair_2.php?recid=$recid';	
			});
		</script>";
		//sleep(5);
    	die();		
    }

    /*function rair_zgcamp(){
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
		$mdn='';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'zerogravity');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('tid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'zerogravity');

    	$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}
		
		$mobnum_blocked_int2=$this->subscribe_model->check_blocked_robi($mdn);
		if($mobnum_blocked_int2>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if(($blocked==0) && ($mdn<>'') && (substr($mdn,0,5)=="88016")){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'zerogravity');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }*/

    function robi_micamp(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'mobidea');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'mobidea');

    	/*$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}*/

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if($blocked==0 && $mdn<>''){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'mobidea');
				//$this->subscribe_model->robi_camp_backend($mdn,'mobidea');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }

    function robi_tccamp(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'trafficcompany');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'trafficcompany');

    	/*$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}*/

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if($blocked==0 && $mdn<>''){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'trafficcompany');
				//$this->subscribe_model->robi_camp_backend($mdn,'trafficcompany');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }

    function robi_ditcamp(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'dit');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$this->subscribe_model->robi_cpa_clickid($mdn,$clickid,'dit');

    	/*$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}*/

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if($blocked==0 && $mdn<>''){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'dit');
				//$this->subscribe_model->robi_camp_backend($mdn,'dit');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }

    function robi_drcamp(){
    	die();
    	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    	$QUERY_STRING = $_SERVER['QUERY_STRING'];
    	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$mobnum = '';
    	if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
    		$mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	else if (isset($_SERVER['HTTP_MSISDN'])){
    		$mobnum = $_SERVER['HTTP_MSISDN'];
    		$mobnum = substr($mobnum,-11);
    		$mdn='880'.substr($mobnum,-10);
    	}
    	$recid = $this->subscribe_model->robi_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'digitalraves');
    	$amt = 0;
    	$status = 0;

    	$clickid=$this->input->get_post('clickid');
    	$aff_id=$this->input->get_post('aff_id');
    	$this->subscribe_model->robi_cpa_clickid1($mdn,$clickid,'digitalraves',$aff_id);

    	/*$mobnum_blocked_int=$this->subscribe_model->check_dnd_block_robi_int($mdn);
		if($mobnum_blocked_int>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}*/

		$mobnum_blocked_int1=$this->subscribe_model->check_blocked_robi_1($mdn);
		if($mobnum_blocked_int1>0){
			redirect('http://www.google.com', 'refresh');
			die();
		}

    	$blocked=$this->subscribe_model->check_blocked_robi($mdn);
		if($blocked==0 && $mdn<>''){				
			if($this->subscribe_model->checkRobi_user_camp($mdn) ==0){ //---

				$recid = $this->subscribe_model->robi_camp_charge1($mdn,'',$recid,'digitalraves');
				//$this->subscribe_model->robi_camp_backend($mdn,'digitalraves');
				date_default_timezone_set("Asia/Kolkata");

				$nonce = rand(1111111,9999999).rand(111111,9999999);
				$created = date('Y-m-d\TH:i:s\Z');
				$password = "Robi1234";

				$transactionId = rand(1111111,9999999).rand(111111,9999999).rand(1111111,9999999);


				$hash = $nonce . $created . $password;
				$password_coded = base64_encode(pack('H*',hash( 'sha256', $hash )));


				$url = "https://consent.robi.com.bd/store/wapconfirm?spid=200029&passwordDigest=".urlencode($password_coded)."&msisdn=$mobnum&language=en&transactionId=$transactionId&productID=0300409221&nonce=$nonce&created=$created&callbackURL=".urlencode(base_url()."robi_dc_lp");

				$this->db->query("insert into robi_mcomics.cg_request(data,date) values('".serialize($url)."',now())");

				redirect($url);
			}
			else{
				redirect("http://google.com");
			}	
		}
		else{
			redirect("http://google.com");
		}		
    }

    /********************************************Airtel Nigeria**********************************************************/
    function angria_s2s(){
	die();
	$clickid=$this->input->get_post('clickid');
        //header('X-Frame-Options: DENY');

        echo "<script>
                top.window.location.href='http://mcomics.club/camp/angria_s2s_main?clickid=$clickid';    
        </script>";
        die();
    }

    public function angria_s2s_main(){
	die();
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
        $mobnum = '';
        $mdn='';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
            $mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }
        else if (isset($_SERVER['HTTP_MSISDN'])){
            $mobnum = $_SERVER['HTTP_MSISDN'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }

        $recid = $this->subscribe_model->angria_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'s2s');
        /*$block_rand= rand(1,2);
	if($block_rand==1) {
		die();
	}*/
        $clickid=$this->input->get_post('clickid');
        $this->subscribe_model->angria_cpa_clickid($mdn,$clickid,'s2s');

        $this->session->set_userdata('angria_mobnum',$mobnum);
        $this->session->set_userdata('angria_adnet',"s2s");
        $this->session->set_userdata('angria_clickid',$clickid);
        $this->session->set_userdata('angria_recid',$recid);
	
	/*
        $angria_check_cnt_success = $this->subscribe_model->angria_check_cnt_success();
        if($angria_check_cnt_success==1){
            $this->subscribe_model->angria_cbsent_adnet($clickid,"s2s");
            redirect('http://www.google.com', 'refresh');
            die();
        }
	*/
        $mobnum_blocked_int=$this->subscribe_model->check_blocked_angria_int($mdn);
        if($mobnum_blocked_int>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        $mobnum_blocked=$this->subscribe_model->check_blocked_angria($mdn);
        if($mobnum_blocked>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        if(substr($mdn,0,3)=='234'){                
            if(count($this->subscribe_model->checkmComics_airtelngriauser($mdn))==0){ //---

                $data['id']      = "";
                $this->load->view('template/header_auth');
                $this->load->view('angria_camp_consent',$data);             
            }
            else{
                redirect("http://google.com");
            }   
        }
        else{
            redirect("http://google.com");
        }
    }

    public function ngria_cconsent(){
        $mobnum = $this->session->userdata('angria_mobnum');
        $mdn=$mobnum;
        $adnet = $this->session->userdata('angria_adnet');
        $clickid = $this->session->userdata('angria_clickid');
        $recid = $this->session->userdata('angria_recid');
        
        $consent = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $pack = "monthly";

        $this->subscribe_model->angria_camp_logs($mobnum,$adnet,$pack,$consent);
        
        $series=substr($mobnum,0,3);
        if(($series=="234") && (($consent=="YES") || ($consent=="YES1"))) {                
            if(count($this->subscribe_model->checkmComics_airtelngriauser($mdn))==0){ //---

                $status =$this->subscribe_model->angria_charge_camp($mobnum,$pack);
                if($status==1){
                    $get_amt=$this->subscribe_model->angria_getamount($mobnum);
                    $amt=$get_amt->amt;
					
                    //if($amt==100){
                    if(($amt==100) || (($amt==50) && ($adnet=="cygnusnew"))){
                        $this->subscribe_model->angria_send_callback($adnet,$mobnum,$clickid);
                    }
					$recid = $this->subscribe_model->angria_camp_charge($mdn,'',$amt,$recid,$adnet);
					redirect("http://mcomics.club/");die;
                }
                else if($status==9){
                    redirect("http://google.com");
                    die;
                }
                else{
                    redirect("http://google.com");
                    die;
                }                
            }
            else{
                redirect("http://google.com");
            }   
        }
        else{
            redirect("http://google.com");
        }                
    }

    function angria_micamp(){
	die();
        $clickid=$this->input->get_post('clickid');

        echo "<script>
                top.window.location.href='http://mcomics.club/camp/angria_micamp_main?clickid=$clickid';    
        </script>";
        die();
    }

    public function angria_micamp_main(){
        die();
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
        $mobnum = '';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
            $mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }
        else if (isset($_SERVER['HTTP_MSISDN'])){
            $mobnum = $_SERVER['HTTP_MSISDN'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }

        $recid = $this->subscribe_model->angria_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'mobidea');
        
        $clickid=$this->input->get_post('clickid');
        $this->subscribe_model->angria_cpa_clickid($mdn,$clickid,'mobidea');

        $this->session->set_userdata('angria_mobnum',$mobnum);
        $this->session->set_userdata('angria_adnet',"mobidea");
        $this->session->set_userdata('angria_clickid',$clickid);
        $this->session->set_userdata('angria_recid',$recid);

        $mobnum_blocked_int=$this->subscribe_model->check_blocked_angria_int($mdn);
        if($mobnum_blocked_int>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        $mobnum_blocked=$this->subscribe_model->check_blocked_angria($mdn);
        if($mobnum_blocked>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        if(substr($mdn,0,3)=='234'){                
            if(count($this->subscribe_model->checkmComics_airtelngriauser($mdn))==0){ //---
                $ran = rand(1,10);
                if($ran==1){
                    redirect(base_url()."camp/ngria_cconsent/YES1");die;
                }
                else{
                    $data['id']      = "";
                    $this->load->view('template/header_auth');
                    $this->load->view('angria_camp_consent',$data);             
                }
            }
            else{
                redirect("http://google.com");
            }   
        }
        else{
            redirect("http://google.com");
        }
    }

    function angria_ccamp(){
        die();
        $clickid=$this->input->get_post('clickid');

        echo "<script>
                top.window.location.href='http://mcomics.club/camp/angria_ccamp_main?clickid=$clickid';    
        </script>";
        die();
    }

    public function angria_ccamp_main(){
        die();
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
        $mobnum = '';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
            $mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }
        else if (isset($_SERVER['HTTP_MSISDN'])){
            $mobnum = $_SERVER['HTTP_MSISDN'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }

        $recid = $this->subscribe_model->angria_camp_hits($mdn,$QUERY_STRING,$REMOTE_ADDR,$HTTP_USER_AGENT,$referrer,'cygnusnew');
        
        $clickid=$this->input->get_post('clickid');
        $this->subscribe_model->angria_cpa_clickid($mdn,$clickid,'cygnusnew');

        $this->session->set_userdata('angria_mobnum',$mobnum);
        $this->session->set_userdata('angria_adnet',"cygnusnew");
        $this->session->set_userdata('angria_clickid',$clickid);
        $this->session->set_userdata('angria_recid',$recid);

        $mobnum_blocked_int=$this->subscribe_model->check_blocked_angria_int($mdn);
        if($mobnum_blocked_int>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        $mobnum_blocked=$this->subscribe_model->check_blocked_angria($mdn);
        if($mobnum_blocked>0){
            redirect('http://www.google.com', 'refresh');
            die();
        }

        if(substr($mdn,0,3)=='234'){                
            if(count($this->subscribe_model->checkmComics_airtelngriauser($mdn))==0){ //---
                $ran = rand(1,10);
                if($ran==1){
                    redirect(base_url()."camp/ngria_cconsent/YES1");die;
                }
                else{
                    $data['id']      = "";
                    $this->load->view('template/header_auth');
                    $this->load->view('angria_camp_consent',$data);  
                }           
            }
            else{
                redirect("http://google.com");
            }   
        }
        else{
            redirect("http://google.com");
        }
    }



    /*************************Airtel Nigeria CG Test ************************/
    function angria_cg_test(){
        $mobnum = '';
        $mdn='';
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
            $mobnum = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }
        else if (isset($_SERVER['HTTP_MSISDN'])){
            $mobnum = $_SERVER['HTTP_MSISDN'];
            $mdn='234'.substr($mobnum,-10);
            $mobnum=$mdn;
        }

    }

    function angria_cg_callback(){
        echo "ok";
    }


    function test(){
        //print_r($this->input->get_post());die;
        print_r($_GET);die;
    }
}
?>
