<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

   	function mcomics_insert_subtype($mobnum,$subtype){
		$this->db->query("insert into dubai_mcomics.mobnum_subtype(mobnum,subtype,date) values('$mobnum','$subtype',now())");
	}

	function checkmComics_user($mobnum)
	{
		$query = $this->db->query("SELECT * from dubai_mcomics.sub_users where productID<>'' and Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
	}

	public function user_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO dubai_mcomics.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function checkEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM dubai_mcomics.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    /*************************bangladesh bg ******************************/

    function checkmComics_bguser($mobnum)
	{
		$query = $this->db->query("SELECT * from bg_mcomics.sub_users where Mobnum='$mobnum' AND Unsub=0  ");
		return $query->row();
	}

	public function bguser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO bg_mcomics.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function bgcheckEmailExists($mdn)
	{
		$result=$this->db->query("SELECT * FROM bg_mcomics.user_info WHERE mobile='$mdn'");
		if($result->num_rows($result)>0){
			return 0;
		}
		else{
			return 1;
		}
    }

    function sub_bg_user($mobnum,$subtype)
	{
		$mobnum = '880'.substr($mobnum,-10,10);
		$dbname ="bg_mcomics";
		$query = $this->db->query("select * from $dbname.sub_users where Mobnum='$mobnum' ");
		if ($query->num_rows() == 0){
			$this->db->query("insert into $dbname.sub_users(Mobnum,SubType,SubDate,Unsub,productID,SubStatus,lastcharged) values ('$mobnum','$subtype',now(),0,'21270MComicPortal_Daily','SUCCESS',now())");

			$this->db->query("insert into $dbname.SubLogs(Mobnum,Action,Response,Mode,Type,Amount,Status) values ('$mobnum','SUB','0','WEB','$subtype','2.67','SUCCESS')");
			$this->is_adnet_bg($mobnum);
			$ret = 1;
			return($ret);
		}
	}



	function is_adnet_bg($mobnum){
		$mobnum = '880'.substr($mobnum,-10,10);
		$dbname ="bg_mcomics";

		$query = $this->db->query("select * from $dbname.reporo_hits where mdn='$mobnum' and date>date_sub(now(), interval 30 minute) order by id desc limit 1");

		if ($query->num_rows() > 0){
			$q = $query->row();
			$adnet = $q->adnet;
			$hid = $q->id;

			$this->db->query("insert into $dbname.reporo_charge(mdn,hid,amount,status,date,adnet) values('$mobnum','$hid','2.67','SUCCESS',now(),'$adnet');");

			$this->db->query("update $dbname.sub_users set campaign=1 where mobnum='$mobnum'");
			if($adnet=="cygnusnew"){
				$this->callbackCntRand_bg('cygnusnew');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='cygnusnew' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					//$getClickid = mysql_fetch_array($getClickidData);
					//$clickid = $getClickid['clickid'];
					if($mobnum == "8801954502722"){
						file_get_contents("http://viyuads.o18.click/p?&tid=$clickid");
						$this->callbackCnt_bg("cygnusnew");
						$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('cygnusnew','bangladesh','banglalink','$clickid','mcomics',now()) ");
					}else{
						$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='cygnusnew' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
						if($chkAdnetConv->num_rows()>0){
							$get_conv 	= $chkAdnetConv->row();
							$cnt 		= $get_conv->cnt;
					       /* $get_conv = mysql_fetch_array($chkAdnetConv);
					        $cnt = $get_conv['cnt'];*/
					        //$cnt=1;
					        //if($cnt==1){
							if($cnt==1){
								file_get_contents("http://viyuads.o18.click/p?&tid=$clickid");
								$this->callbackCnt_bg("cygnusnew");
								$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('cygnusnew','bangladesh','banglalink','$clickid','mcomics',now()) ");
							}
							if($cnt%10==0){
								file_get_contents("http://viyuads.o18.click/p?&tid=$clickid");
								$this->callbackCnt_bg("cygnusnew");
								$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('cygnusnew','bangladesh','banglalink','$clickid','mcomics',now()) ");
							}
						}
					}
				}
			}else if($adnet=="trackgdi"){
				$this->callbackCntRand_bg('trackgdi');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='trackgdi' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='trackgdi' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt==1){
							file_get_contents("http://trk.trackgdi.com/postback?cid=$clickid&payout=0.20");
							$this->callbackCnt_bg("trackgdi");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('trackgdi','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
						if($cnt%10==0){
							file_get_contents("http://trk.trackgdi.com/postback?cid=$clickid&payout=0.20");
							$this->callbackCnt_bg("trackgdi");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('trackgdi','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}else if($adnet=="adtriumph"){
				$this->callbackCntRand_bg('adtriumph');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='adtriumph' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='adtriumph' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://offers.advalorem.affise.com/postback?clickid=$clickid");
							$this->callbackCnt_bg("adtriumph");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('adtriumph','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}else if($adnet=="immance"){
				$this->callbackCntRand_bg('immance');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='immance' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='immance' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://imdm.o18.click/p?mid=1796&adv_sub1=&tid=$clickid");
							$this->callbackCnt_bg("immance");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('immance','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}else if($adnet=="branmark"){
				$this->callbackCntRand_bg('branmark');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='branmark' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='branmark' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://postbacka.branmark.net/acquisition?security_token=18d93e5eb79ad9092f16&click_id=$clickid");
							$this->callbackCnt_bg("branmark");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('branmark','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}else if($adnet=="intermob"){
				$this->callbackCntRand_bg('intermob');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='intermob' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='intermob' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("https://interomob.vnative.co/acquisition?security_token=56595263ab391a98eeea&click_id=$clickid");
							$this->callbackCnt_bg("intermob");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('intermob','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}else if($adnet=="skytech"){
				$this->callbackCntRand_bg('skytech');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='skytech' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='skytech' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				    if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://skytechlimited.com/callback.php?t_id=$clickid");
							$this->callbackCnt_bg("skytech");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('skytech','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="collectcent"){
				$this->callbackCntRand_bg('collectcent');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='collectcent' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='collectcent' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://162.243.217.139/dlv/track.php?p=0.25&ccuid=$clickid");
							$this->callbackCnt_bg("collectcent");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('collectcent','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="mobiarabia"){
				$this->callbackCntRand_bg('mobiarabia');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='mobiarabia' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='mobiarabia' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://tma.o18.click/p?mid=1680&tid=$clickid");
							$this->callbackCnt_bg("mobiarabia");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('mobiarabia','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="armorads"){
				$this->callbackCntRand_bg('armorads');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='armorads' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='armorads' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://secure-conversion.com/conversion_get/pixel.jpg?kp=$clickid");
							$this->callbackCnt_bg("armorads");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('armorads','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="trendqube"){
				$this->callbackCntRand_bg('trendqube');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='trendqube' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='trendqube' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt==1){
							file_get_contents("http://offers.trendqube.affise.com/postback?clickid=$clickid");
							$this->callbackCnt_bg("trendqube");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('trendqube','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
						if($cnt%5==0){
							file_get_contents("http://offers.trendqube.affise.com/postback?clickid=$clickid");
							$this->callbackCnt_bg("trendqube");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('trendqube','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="okinet"){
				$this->callbackCntRand_bg('okinet');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='okinet' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='okinet' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://offers.okinet.affise.com/postback?clickid=$clickid");
							$this->callbackCnt_bg("okinet");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('okinet','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="olimob"){
				$this->callbackCntRand_bg('olimob');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='olimob' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='olimob' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://982v5yb3ppv.net/pb/?adv=Pheuture&oliclick=$clickid&currency=USD&revenue=0.15");
							$this->callbackCnt_bg("olimob");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('olimob','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="infosystem"){
				$this->callbackCntRand_bg('infosystem');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='infosystem' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='infosystem' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://clicks9.com/pb/?amt=0.20&clickid=$clickid");
							$this->callbackCnt_bg("infosystem");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('infosystem','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="royalmobi"){
				$this->callbackCntRand_bg('royalmobi');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='royalmobi' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='royalmobi' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://offers.royalmobi.affise.com/postback?clickid=$clickid");
							$this->callbackCnt_bg("royalmobi");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('royalmobi','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="adzsmile"){
				$this->callbackCntRand_bg('adzsmile');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='adzsmile' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='adzsmile' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("https://xaxis.me/pixel?cid=$clickid");
							$this->callbackCnt_bg("adzsmile");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('adzsmile','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="mobidea"){
				$this->callbackCntRand_bg('mobidea');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='mobidea' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='mobidea' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%7==0){
							file_get_contents("http://www.securebill.mobi/bg.php?idcallback=021f06fc7d08637aa0082637fe335b21&clickID=$clickid");
							$this->callbackCnt_bg("mobidea");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('mobidea','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="bitterstrawberry"){
				$this->callbackCntRand_bg('bitterstrawberry');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='bitterstrawberry' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$uid 			= $getClickid->id;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='bitterstrawberry' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("https://callbacks.bitterstrawberry.org/?token=25ae92accdd711f8c0058c94fef52790&hash=$clickid&transaction_id=oo_bs_$uid&amount=0.15&payout_type=pps&currency=USD");
							$this->callbackCnt_bg("bitterstrawberry");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('bitterstrawberry','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="trafficcompany"){
				$this->callbackCntRand_bg('trafficcompany');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='trafficcompany' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$uid 			= $getClickid->id;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='trafficcompany' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("https://postback.level23.nl/?currency=USD&handler=10162&hash=28ba87e51f8da005c57dd5524b603247&country=BD&payout=0.2&tracker=$clickid");
							$this->callbackCnt_bg("trafficcompany");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('trafficcompany','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="adriel"){
				$this->callbackCntRand_bg('adriel');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='adriel' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$uid 			= $getClickid->id;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='adriel' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://adriel.adserverexchange.com/adsrv/postback/pixel?subid=$clickid");
							$this->callbackCnt_bg("adriel");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('adriel','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			else if($adnet=="globocom"){
				$this->callbackCntRand_bg('globocom');
				$getClickidData = $this->db->query("select * from $dbname.reporo_clickid where mdn='$mobnum' and date(date)=curdate() and adnet='globocom' order by id desc limit 1");
				if($getClickidData->num_rows()>0){
					$getClickid 	= $getClickidData->row();
					$clickid 		= $getClickid->clickid;
					$uid 			= $getClickid->id;
					$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='globocom' and date(date)=curdate() and oper='bg_mc' order by id desc limit 1");
				   if($chkAdnetConv->num_rows()>0){
						$get_conv 	= $chkAdnetConv->row();
						$cnt 		= $get_conv->cnt;
						if($cnt%5==0){
							file_get_contents("http://matrixads.in/matrix/globalpostback?clickid=$clickid");
							$this->callbackCnt_bg("globocom");
							$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('globocom','bangladesh','banglalink','$clickid','mcomics',now()) ");
						}
					}
				}
			}
			
		}
		else{
			$this->db->query("insert into $dbname.reporo_charge(mdn,hid,amount,status,date,adnet) values('$mobnum','','2.44','SUCCESS',now(),'blank');");
		}
	}


	function callbackCntRand_bg($adnet){
	    $query = $this->db->query("select * from adnet_callback_data.adnetcallrandom where date=curdate() and `adnet`='$adnet' and oper='bg_mc'");
	    if($query->num_rows()>0){
	        $this->db->query("update adnet_callback_data.adnetcallrandom set cnt=cnt+1 where date=curdate() and `adnet`='$adnet' and oper='bg_mc';");
	    }
	    else{
	        $this->db->query("insert into adnet_callback_data.adnetcallrandom(`date`,oper,cnt,`adnet`) values(curdate(),'bg_mc','1','$adnet');");
	    }
	}

	function callbackCnt_bg($adnet){
		$query = $this->db->query("select * from adnet_callback_data.bg_mc where date=curdate() and `adnet`='$adnet'");
		if($query->num_rows()>0){
			$this->db->query("update adnet_callback_data.bg_mc set cnt=cnt+1 where date=curdate() and `adnet`='$adnet';");
		}
		else{
			$this->db->query("insert into adnet_callback_data.bg_mc(`date`,cnt,`adnet`) values(curdate(),'1','$adnet');");
		}
	}



	function bg_logs_failure($mobnum,$subtype,$isChargeSuccess){
		$dbname ="bg_mcomics";
		$this->db->query("insert into $dbname.SubLogs(Mobnum,Action,Response,Mode,Type,Amount,Status) values ('$mobnum','SUB','$isChargeSuccess','WEB','$subtype','0','FAILURE')");
		$query = $this->db->query("select * from $dbname.reporo_hits where mdn='$mobnum' and date>date_sub(now(), interval 30 minute) order by id desc limit 1");

		if ($query->num_rows() > 0){
			$q = $query->row();
			$adnet = $q->adnet;
			$hid = $q->id;

			$this->db->query("insert into $dbname.reporo_charge(mdn,hid,amount,status,date,adnet) values('$mobnum','$hid','0','FAIL',now(),'$adnet');");
		}
	}

	public function bg_mc_unsub($mobnum)
	{
		$mdn = substr($mobnum,-10,10);
		$dbname ="bg_mcomics";
		$mobnum = '880'.$mdn;
		$query = $this->db->query("select * from $dbname.sub_users where Mobnum='$mobnum' ");
		if ($query->num_rows() > 0){
			$this->db->query("insert into $dbname.SubLogs(Mobnum,Action,Status,Mode) values ('$mobnum','UNSUB','SUCCESS','WEB')");
			$this->db->query("UPDATE $dbname.sub_users set Unsub=1,unsubdate=NOW() where Mobnum='$mobnum'");
			$this->db->query("insert into $dbname.sub_users_unsub(Mobnum,SubType,SubDate,Unsub,unsubdate) select Mobnum,SubType,SubDate,Unsub,unsubdate from $dbname.sub_users where Mobnum='$mobnum' and Unsub=1");
			$this->db->query("delete from $dbname.sub_users where Mobnum='$mobnum' and Unsub=1");
		}
	}


    /*************************bangladesh robi ******************************/

    function checkmComics_robiuser($mobnum)
	{
		$query = $this->db->query("SELECT * from robi_mcomics.sub_users_sdp where Mobnum='$mobnum' AND Unsub=0 and SubStatus='SUCCESS' ");
		return $query->row();
	}

	public function robiuser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO robi_mcomics.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function robicheckEmailExists($mdn)
	{
		$result=$this->db->query("SELECT * FROM robi_mcomics.user_info WHERE mobile='$mdn'");
		if($result->num_rows($result)>0){
			return 0;
		}
		else{
			return 1;
		}
    }
    function subscribe_robi($mobnum,$package)
	{
		$mobnum = '880'.substr($mobnum,-10,10);
		$ret = file_get_contents("http://localhost/robi/sdpmcomics/submc.php?mode=WAP&adv=0&type=$package&MSISDN=".$mobnum);
		return($ret);
	}

    /*************************airtel nigeria ******************************/

    function checkmComics_airtelngriauser($mobnum)
	{
		$query = $this->db->query("SELECT * from airtel_nigeria.sub_users where Mobnum='$mobnum' AND Unsub=0 and SubStatus='SUCCESS' ");
		return $query->row();
	}

	public function airtelngriauser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO airtel_nigeria.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function airtelngriacheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM airtel_nigeria.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    function angria_camp_hits($mobnum,$url,$ip,$useragent,$HTTP_REFERER,$adnet) {
        $query = $this->db->query("insert into airtel_nigeria.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$mobnum','$url','$ip','$useragent',now(),'$adnet','$HTTP_REFERER');");
        return $this->db->insert_id();
    }

    function angria_camp_charge($mobnum,$status,$amt,$hid,$adnet) {
        $query = $this->db->query("insert into airtel_nigeria.reporo_charge(mdn,status,amount,date,adnet,hid) values('$mobnum','$status','$amt',now(),'$adnet',$hid);");
        return $this->db->insert_id();
    }

    function angria_cpa_clickid($mobnum,$clickid,$adnet){
        $query = $this->db->query("insert into airtel_nigeria.reporo_clickid(mdn,adnet,clickid,date) values('$mobnum','$adnet','$clickid',now());");
    }

    function check_blocked_angria($mobnum){
    	$query = $this->db->query("SELECT * FROM airtel_nigeria.`dnd` where mobnum='$mobnum'");
        return $query->num_rows();
    }

    function check_blocked_angria_int($mobnum){
        $query = $this->db->query("SELECT * FROM airtel_nigeria.`sub_users_unsub` where mobnum='$mobnum' and date_sub(curdate(), interval 30 day)<date(unsubdate)");
        return $query->num_rows();
    }

    function angria_camp_logs($mobnum,$adnet,$pack,$consent){
		$query = $this->db->query("insert into airtel_nigeria.camp_consent_logs(mdn,adnet,pack,consent,date) values('$mobnum','$adnet','$pack','$consent',now());");
	}

	function angria_charge_camp($mobnum,$pack){
        $url = file_get_contents("http://localhost/airtelng/submcomics.php?MSISDN=$mobnum&subtype=$pack&mode=WEB&adv=1");
        if($url == 'SUBSCRIBED'){
            return 1;
        }
        else if($url == 'BLOCKED'){
            return 9;
        }
		else if($url == 'LOWBAL'){
            return 2;
        }
		else{
			return 0;
		}
	}

	function angria_getamount($mobnum){
		$query = $this->db->query("select Amount as amt from airtel_nigeria.SubLogs where Mobnum='$mobnum' and Action='SUB' and date(Date)=curdate() order by id desc limit 1;");
		return $query->row();
	}

	function angria_send_callback($adnet,$mobnum,$clickid){
		if($adnet=="s2s"){
			$this->angria_callbackCntRand('s2s');
			$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='s2s' and date(date)=curdate() and oper='angria_mcom' order by id desc limit 1");
		    if($chkAdnetConv->num_rows()>0){
		        $get_conv = $chkAdnetConv->row();
		        $cnt = $get_conv->cnt;
				if($cnt%15==0){
					file_get_contents("http://offers.s2s.affise.com/postback?clickid=$clickid");
					$this->angria_callbackCnt("s2s");
					$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('s2s','nigeria','AIRTEL','$clickid','mcomics',now()) ");
				}
			}
		}
		else if($adnet=="mobidea"){
			$this->angria_callbackCntRand('mobidea');
			$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='mobidea' and date(date)=curdate() and oper='angria_mcom' order by id desc limit 1");
		    if($chkAdnetConv->num_rows()>0){
		        $get_conv = $chkAdnetConv->row();
		        $cnt = $get_conv->cnt;
				if($cnt%35==0){
					$idcallback='021f06fc7d08637aa0082637fe335b21';
					file_get_contents("http://www.securebill.mobi/bg.php?clickID=$clickid&idcallback=$idcallback");	
					$this->angria_callbackCnt("mobidea");
					$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('mobidea','nigeria','AIRTEL','$clickid','mcomics',now()) ");
				}
			}
		}
		else if($adnet=="cygnusnew"){
			$this->angria_callbackCntRand('cygnusnew');
			$chkAdnetConv = $this->db->query("select * from adnet_callback_data.adnetcallrandom where adnet='cygnusnew' and date(date)=curdate() and oper='angria_mcom' order by id desc limit 1");
		    if($chkAdnetConv->num_rows()>0){
		        $get_conv = $chkAdnetConv->row();
		        $cnt = $get_conv->cnt;
				if($cnt%30==0){
					file_get_contents("http://viyuads.o18.click/p?&tid=$clickid");	
					$this->angria_callbackCnt("cygnusnew");
					$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('cygnusnew','nigeria','AIRTEL','$clickid','mcomics',now()) ");
				}
			}
		}
	}

	function angria_callbackCnt($adnet){
		$query = $this->db->query("select * from adnet_callback_data.angria_mcom where date=curdate() and `adnet`='$adnet'");
		if($query->num_rows()>0){
			$this->db->query("update adnet_callback_data.angria_mcom set cnt=cnt+1 where date=curdate() and `adnet`='$adnet';");
		}
		else{
			$this->db->query("insert into adnet_callback_data.angria_mcom(`date`,cnt,`adnet`) values(curdate(),'1','$adnet');");
		}
	}

	function angria_callbackCntRand($adnet){
	    $query = $this->db->query("select * from adnet_callback_data.adnetcallrandom where date=curdate() and `adnet`='$adnet' and oper='angria_mcom'");
	    if($query->num_rows()>0){
	        $this->db->query("update adnet_callback_data.adnetcallrandom set cnt=cnt+1 where date=curdate() and `adnet`='$adnet' and oper='angria_mcom';");
	    }
	    else{
	        $this->db->query("insert into adnet_callback_data.adnetcallrandom(`date`,oper,cnt,`adnet`) values(curdate(),'angria_mcom','1','$adnet');");
	    }
	}

	function angria_check_cnt_success(){
		$query = $this->db->query("select * from airtel_nigeria.reporo_charge where date(date)=curdate() and amount>0");
		if($query->num_rows()>1000){
			return 1;
		}
		else{
			return 2;
		}
	}

	function angria_cbsent_adnet($clickid,$adnet){
		$query = $this->db->query("SELECT count(*) as cnt FROM airtel_nigeria.`reporo_hits` where date(date)=curdate() and adnet='$adnet'");
		$result = $query->result();
		$cnt    = $result[0]->cnt;
		if($cnt%1000==0){
			if($adnet=="s2s"){
				file_get_contents("http://offers.s2s.affise.com/postback?clickid=$clickid");
				$this->angria_callbackCnt("s2s");
				$this->db->query("INSERT INTO adnet_callback_data.sent_clickid(adnet,country,telco,clickid,service,`date`) VALUES('s2s','nigeria','AIRTEL','$clickid','mcomics',now()) ");
			}
		}
	}
    /*************************srilanka dialog ******************************/
    function checkmComics_sridialoguser_new($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.sub_users where Mobnum='$mobnum' AND Unsub=0  AND SubStatus='SUCCESS'");
		return $query->row();
	}
    
    function checkmComics_sridialoguser($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.sub_users where Mobnum='$mobnum' AND Unsub=0  AND SubStatus='SUCCESS'");
		return $query->num_rows();
	}

	function checkmComics_dialoguser($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.sub_users where Mobnum='$mobnum' AND Unsub=0");
		return $query->num_rows();
	}
	function checkmComics_dialogcmpCheck($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.SubLogs where Mobnum='$mobnum' and action='SUB' order by id desc limit 1");
		if($query->num_rows() > 0){
			$q = $query->row();
			$request = $q->Request;
			if($request=='internal') return true;
			else return false;
		}else{
			return false;
		}
	}

	function checkmComics_dialogdnd($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.dnd where Mobnum='$mobnum'");
		return $query->num_rows();
	}

	public function sridialoguser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO dialog.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function sridialogcheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM dialog.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    /// robi campaign

    function robi_camp_hits($mobnum,$url,$ip,$useragent,$HTTP_REFERER,$adnet) {
        $query = $this->db->query("insert into robi_mcomics.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$mobnum','$url','$ip','$useragent',now(),'$adnet','$HTTP_REFERER');");
        return $this->db->insert_id();
    }

    function robi_camp_charge1($mobnum,$status,$hid,$adnet) {
        $query = $this->db->query("insert into robi_mcomics.reporo_charge(mdn,status,date,adnet,hid) values('$mobnum','$status',now(),'$adnet',$hid);");
        return $this->db->insert_id();
    }

    function robi_cpa_clickid($mobnum,$clickid,$adnet){
        $query = $this->db->query("insert into robi_mcomics.reporo_clickid(mdn,adnet,clickid,date) values('$mobnum','$adnet','$clickid',now());");
    }

    function robi_cpa_clickid1($mobnum,$clickid,$adnet,$aff_id){
        $query = $this->db->query("insert into robi_mcomics.affle_clickid(mdn,adnet,clickid,clickidr,date) values('$mobnum','$adnet','$clickid','$aff_id',now());");
    }

    function checkRobi_user_camp($mobnum)
    {
        $mdn = substr($mobnum,-10,10);
        $mobnum = '880'.$mdn;
        $query = $this->db->query("select * from robi_mcomics.sub_users_sdp where Mobnum='$mobnum' AND Unsub=0");
        return $query->num_rows();
    }

    function check_dnd_block_robi_mc($mobnum){
    	$query = $this->db->query("SELECT * FROM robi_mcomics.`dnd_mc` where mobnum='$mobnum'");
        return $query->num_rows();
    }

    function check_dnd_block_robi_int($mobnum){
        $query = $this->db->query("SELECT * FROM robi_mcomics.`sub_users_sdp_unsub` where mobnum='$mobnum' and date_sub(curdate(), interval 30 day)<date(unsubdate)");
        return $query->num_rows();
    }

    function check_blocked_robi_1($mobnum){
        $mdn = substr($mobnum,-10);
        $mobnum = '880'.$mdn;
        $query = $this->db->query("select * from robiim.dnd where Mobnum='$mobnum'");
        return $query->num_rows();
    }

    function check_blocked_robi($mobnum){
        $mdn = substr($mobnum,-10);
        $mobnum = '880'.$mdn;
        $query = $this->db->query("select * from robiim.dnd_table where Mobnum='$mobnum'");
        return $query->num_rows();
    }

    function robi_camp_backend($mobnum,$adnet){
		$query = $this->db->query("SELECT * FROM robi_mcomics.`sub_users_sdp_unsub` where mobnum='$mobnum' and date_sub(date(unsubdate), interval 30 day)>curdate()");
		$chk_user_sub = $query->num_rows();
		if($chk_user_sub==0){
			$this->db->query("insert into robi_mcomics.sub_backend_adnet(mdn,adnet,date) values('$mobnum','$adnet',now())");
		}
	}


	////////////////////////// etisalat uae blazon //////////////////////////

	function checkmComics_etiuaeuser($mobnum)
	{
		$query = $this->db->query("select productID from etisalat_uae_mcom.sub_users where mobnum='$mobnum' and Unsub=0 and date(subdate)=curdate()");
        if($query->num_rows()==0){
            $query = $this->db->query("select productID from etisalat_uae_mcom.sub_users where mobnum='$mobnum' and Unsub=0 and SubStatus='SUCCESS'");
            //echo 1;
        }
        //echo 2;die;
		//$query = $this->db->query("SELECT * from etisalat_uae_mcom.sub_users where productID<>'' and Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
	}

	function send_eti_uae_otp($mobnum,$subtype){
		$token = file_get_contents("http://111.118.180.237/etisalat_uae/mcomics/otp.php?msisdn=$mobnum&type=$subtype");
		return $token;
	}

	function verify_eti_uae_otp($mobnum,$subtype,$otp,$token){
		$validate_otp  = file_get_contents("http://111.118.180.237/etisalat_uae/mcomics/otp_verify.php?msisdn=$mobnum&otp=$otp&type=$subtype&token=$token");
		return $validate_otp;
	}


	/***********************************Zain Iraq************************************************/
	function check_ZIuser($mobnum)
	{
		$query = $this->db->query("SELECT * from zain_iraq.sub_users where SubStatus<>'' and Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
	}

	public function zainiraqcheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM zain_iraq.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    public function zainiraquser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO zain_iraq.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	/************************************** AIRTEL SRILANKA ******************************************/

	function checkmComics_airtelsluser($mobnum)
	{
		$query = $this->db->query("SELECT * from airtelsl_mcomics.sub_users where Mobnum='$mobnum' AND Unsub=0 and SubStatus<>'' ");
		return $query->row();
	}

	public function airtelsluser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO airtelsl_mcomics.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function airtelslcheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM airtelsl_mcomics.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }
    public function airtelsl_subscribe($mobnum,$subtype){		
		$sublogsid = file_get_contents("http://111.118.180.237/airtelsl/mcomics/submcomics.php?MSISDN=$mobnum&mode=WEB&subtype=$subtype&adv=0");
	}

	/////////////////////////// zain jordan //////////////////

	function check_zainJordanuser($mobnum)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where SubStatus<>'' and Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
	}

	public function zainjordancheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM vivo_kuwait.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    public function zainjordanuser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO vivo_kuwait.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function zainjordan_password($password)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where password='$password'");
		return $query;
	}

	public function check_zainJordanuserStatus($mobnum)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where Mobnum='$mobnum' AND Unsub=0");
		return $query;
	}

	/////////////////////////// vivo kuwait //////////////////

	function check_vivoKuwaituser($mobnum)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where SubStatus<>'' and Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
	}

	public function vivokuwaitcheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM vivo_kuwait.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    public function vivokuwaituser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO vivo_kuwait.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function vivokuwait_insert_subtype($mobnum,$subtype){
		$this->db->query("insert into vivo_kuwait.mobnum_subtype(mobnum,subtype,date) values('$mobnum','$subtype',now())");
	}

	public function vivokuwait_password($password)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where password='$password'");
		return $query;
	}

	public function check_vivoKuwaituserStatus($mobnum)
	{
		$query = $this->db->query("SELECT * from vivo_kuwait.sub_users where Mobnum='$mobnum' AND Unsub=0");
		return $query;
	}

	/*********************MTNNGA Start*****************************************/
	function check_mtnnga_user($mobnum)
	{
		$query = $this->db->query("select * from mtnngria_mc.sub_users_sdp where Mobnum='$mobnum' AND Unsub=0 and productID<>''");
		return $query->row();
	}

	function check_mtnnga_sub($mobnum){
		$query = $this->db->query("select * from mtnngria_mc.sub_users_sdp where Mobnum='$mobnum' AND Unsub=0 and productID<>''");
		return $query->result();
	}

	function mtnnga_subscribe($mobnum,$subtype,$atoken,$prodid){
		//$prodid ='23401220000019244';
		$ret = file_get_contents("http://111.118.180.237/mtnngdirect/sdpmcomics/submc.php?atoken=$atoken&MSISDN=$mobnum&mode=WEB&type=$subtype&adv=0&prodid=$prodid");
		return($ret);
	}

	

	function mtnnga_unsub($mobnum){
		$status = file_get_contents("http://111.118.180.237/mtnngdirect/sdpmcomics/unsub.php?mobnum=".$mobnum."&mode=WEB");
	}

	function mtnnga_cgLogs($mobnum,$url){
		$this->db->query("insert into mtnngria_mc.cgLogs(data,cbackfrom,date,mobnum) values('$url','cgLogs',now(),'$mobnum');");
	}
	
	function mtnnga_cgLogs_details($mobnum,$url){
		$data = $url;
		
		$ex = explode('&',$data);
		$r_arr = explode('=',$ex[0]);
		$d_arr = explode('=',$ex[1]);
		$a_arr = explode('=',$ex[2]);
		$t_arr = explode('=',$ex[3]);
		$tid_arr = explode('=',$ex[4]);
		
		$result = $r_arr[1];
		$desc = $d_arr[1];
		$atoken = $a_arr[1];
		$texpi = $t_arr[1];
		$tid = $tid_arr[1];
		
		$te_date = substr($texpi,0,4)."-".substr($texpi,4,2)."-".substr($texpi,6,2)." ".substr($texpi,8,2).":".substr($texpi,10,2).":".substr($texpi,12,2);
		$this->db->query("insert into mtnngria_mc.cgLogs_details(`mobnum`,`result`,`desc`,`authToken`,`tokenExpiryTime`,`transactionId`,`date`) values('$mobnum','$result','$desc','$atoken','$te_date','$tid',now());");
	}

	function mtnnga_camp_hits($mobnum,$url,$ip,$useragent,$HTTP_REFERER,$adnet) {
		$query = $this->db->query("insert into mtnngria_mc.reporo_hits(mdn,url,ip,useragent,date,adnet,HTTP_REFERER) values('$mobnum','$url','$ip','$useragent',now(),'$adnet','$HTTP_REFERER');");
		return $this->db->insert_id();
	}
	function mtnnga_camp_charge($mobnum,$status,$hid,$adnet) {
		$query = $this->db->query("insert into mtnngria_mc.reporo_charge(mdn,hid,amount,status,date,adnet) values('$mobnum','$hid','$amt','$status',now(),'$adnet');");
		return $this->db->insert_id();
	}

	function mtnnga_cpa_clickid($mobnum,$clickid,$adnet){
		$query = $this->db->query("insert into mtnngria_mc.reporo_clickid(mdn,adnet,clickid,date) values('$mobnum','$adnet','$clickid',now());");
	}

	function mtnngria_affle_clickid($mobnum,$clickid,$adnet,$clickidr){
		$query = $this->db->query("insert into mtnngria_mc.affle_clickid(mdn,adnet,clickid,date,clickidr) values('$mobnum','$adnet','$clickid',now(),'$clickidr');");
	}

	function check_dnd_block_mtnnga_int($mobnum){
		$query = $this->db->query("SELECT * FROM mtnngria_mc.`sub_users_sdp_unsub` where Mobnum='$mobnum' and date_sub(curdate(), interval 30 day)<date(unsubdate) order by id desc limit 1");
		return $query->num_rows();
	}

	function check_dnd_block_mtnnga($mobnum){
		$query = $this->db->query("SELECT * FROM mtnngria_mc.`dnd` where Mobnum='$mobnum' order by id desc limit 1");
		return $query->num_rows();
	}

	function callbackCnt_mtnngria($adnet){
		$query = $this->db->query("select * from adnet_callback_data.mtnngria_mc where date=curdate() and `adnet`='$adnet'");
		if($query->num_rows()>0){
			$this->db->query("update adnet_callback_data.mtnngria_mc set cnt=cnt+1 where date=curdate() and `adnet`='$adnet';");
		}
		else{
			$this->db->query("insert into adnet_callback_data.mtnngria_mc(`date`,cnt,`adnet`) values(curdate(),'1','$adnet');");
		}
	}

	function get_mdn_cnt_mtnngria($mobnum){
		$query = $this->db->query("select * from mtnngria_mc.reporo_hits where date(date)=curdate() and mdn='$mobnum' ");
		return $query->num_rows();
	}

	public function mtnnguser_detail($mdn)
	{
	    $query = $this->db->query("INSERT INTO mtnngria_mc.user_info(login_id,mobile) VALUES ('$mdn','$mdn')");
	}

	public function mtnngcheckEmailExists($mdn)
	{
	 $result=$this->db->query("SELECT * FROM mtnngria_mc.user_info WHERE mobile='$mdn'");
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    function mtnnga_cgurl($mobnum,$url){
		$query = $this->db->query("insert into mtnngria_mc.cgurl(mdn,url,date) values('$mobnum','$url',now());");
	}

	/***********************************************MTNNGA End******************************************************/

	/*********** ideabiz dialog **********/

	function checkmComics_dialogSlMcomics($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog_sl_mcomics.sub_users where Mobnum='$mobnum' AND Unsub=0 and SubStatus='SUCCESS' ");
		return $query->row();
	}

	function ideabiz_dialCallback($mobnum,$action,$mode,$packageid,$tid)
	{
		//$this->db->query("INSERT INTO  callback_request(`phpinput`,`request`,`date`,`ip`,response,mdn) VALUES('".serialize($d)."','".serialize($_REQUEST)."',now(),'".$REMOTE_ADDR."','callback','$mobnum') ");

		$this->db->query("INSERT into dialog_sl_mcomics.callbackData(msisdn,packageid,command,tid,date,method) values('$mobnum','$packageid','$action','$tid',now(),'$mode')");

		if($action == 'SUBSCRIBED' || $action == 'SUBSCRIBE'){

			//mysql_query("update sub_users set SubStatus='SUCCESS' where mobnum='$mobnum' order by id desc limit 1");

			$qry=$this->db->query("SELECT * from dialog_sl_mcomics.sub_users where Mobnum='$mobnum' and Unsub=0 and productID<>''");
			if($qry->num_rows()>0){
				$this->db->query("UPDATE dialog_sl_mcomics.sub_users set SubStatus='SUCCESS' where mobnum='$mobnum' order by id desc limit 1");
				$this->db->query("UPDATE dialog_sl_mcomics.SubLogs set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1");
		        $this->db->query("UPDATE dialog_sl_mcomics.SubLogs_archive set Status='SUCCESS',updateTime=now() where Mobnum='$mobnum' and Action='SUB' order by id desc limit 1"); 
			}
			else {
				$this->db->query("INSERT into dialog_sl_mcomics.sub_users(Mobnum,SubDate,SubStatus,productID) values('$mobnum',now(),'SUCCESS','$packageid')");
				$this->db->query("INSERT into dialog_sl_mcomics.SubLogs(Mobnum,Action,Request,Date,Type,Mode,Status,'SUCCESS') values('$mobnum','SUB','',now(),'$type','$mode')");
				$this->db->query("INSERT into dialog_sl_mcomics.SubLogs_archive(Mobnum,Action,Request,Date,Type,Mode,Status) values('$mobnum','SUB','',now(),'$type','$mode','SUCCESS')");
			}
			//send_callback($mobnum);

		}
		/*else if($action == 'UNSUBSCRIBED' || $action == 'UNSUBSCRIBE'){
			$qry=$this->db->query("SELECT * from dialog_sl_mcomics.sub_users where Mobnum='$mobnum' and Unsub=0 ");
			if($qry->num_rows()>0){
				$this->db->query("UPDATE dialog_sl_mcomics.sub_users set unsubdate=current_timestamp(),Unsub=1 where Mobnum='$mobnum'");
				$this->db->query("INSERT into dialog_sl_mcomics.sub_users_unsub(Mobnum,SubType,SubDate,unsubdate,Unsub,SubStatus,productID) select Mobnum,SubType,SubDate,unsubdate,Unsub,SubStatus,productID from sub_users where Mobnum='$mobnum' and Unsub=1  limit 1");
				$this->db->query("DELETE from dialog_sl_mcomics.sub_users where Mobnum='$mobnum' and Unsub=1 limit 1");
				$this->db->query("INSERT into dialog_sl_mcomics.SubLogs(Mobnum,Action,Request,Response,Status,Date,Mode) values('$mobnum','UNSUB','','SUCCESS','SUCCESS',now(),'$mode')");
				$this->db->query("INSERT into dialog_sl_mcomics.SubLogs_archive(Mobnum,Action,Request,Response,Status,Date,Mode) values('$mobnum','UNSUB','','SUCCESS','SUCCESS',now(),'$mode')");
			}

		}
		else if($action == 'suspend'){
			//$amt='2.44';
			$this->db->query("INSERT into dialog_sl_mcomics.SubLogs(Mobnum,Action,Request,Response,Status,Amount,Date,updateTime) values('$mobnum','RENEW','$packageid','$tid','FAILED','',now(),now())");
			$this->db->query("INSERT into dialog_sl_mcomics.SubLogs_archive(Mobnum,Action,Request,Response,Status,Amount,Date,updateTime) values('$mobnum','RENEW','$packageid','$tid','FAILED','',now(),now())");
		}
		else if($action == 'RENTAL_CHARGED'){
			//$amt='2.44';
			$this->db->query("INSERT into dialog_sl_mcomics.SubLogs(Mobnum,Action,Request,Response,Status,Amount,Date,updateTime) values('$mobnum','RENEW','$packageid','$tid','SUCCESS','',now(),now())");
			$this->db->query("INSERT into dialog_sl_mcomics.SubLogs_archive(Mobnum,Action,Request,Response,Status,Amount,Date,updateTime) values('$mobnum','RENEW','$packageid','$tid','SUCCESS','',now(),now())");
		}*/
	}
}
?>
