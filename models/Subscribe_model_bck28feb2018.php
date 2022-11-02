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
	 if($result->num_rows($result)>0)
      {
		return 0;
	  }
	  else
	  {
		return 1;
	  }
    }

    /*************************srilanka dialog ******************************/

    function checkmComics_sridialoguser($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.sub_users where Mobnum='$mobnum' AND Unsub=0  AND SubStatus='SUCCESS'");
		return $query->row();
	}

	function checkmComics_dialoguser($mobnum)
	{
		$query = $this->db->query("SELECT * from dialog.sub_users where Mobnum='$mobnum' AND Unsub=0");
		return $query->row();
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

    function check_dnd_block_robi_int($mobnum){
        $query = $this->db->query("SELECT * FROM robi_mcomics.`sub_users_sdp_unsub` where mobnum='$mobnum' and date_sub(date(unsubdate), interval 30 day)>curdate()");
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
}
?>
