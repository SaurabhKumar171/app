<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Face_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function changeFace($num) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_changeface where status='$num' order by id desc");
        $row = $query->result();
        return $row;
    }


    public function savedFacechange($mobnum,$new_name,$status) {
        $this->db->query("insert INTO `tbl_changeface_download` (`mobnum`, `final_img`,`status`) VALUES ('$mobnum','$new_name','$status')");
        $id = $this->db->insert_id();
        return $id;
    }


    public function upChangeface($id,$status){
        $updateQuery = "UPDATE tbl_changeface_download SET `status`='$status' WHERE id='$id'";
        $update = $this->db->query($updateQuery);
        if($update){
            echo '{"message":"true"}';
        }else{
            echo '{"message":"false"}';
        }
    }

    public function selectchangeFace($mobnum,$status) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_changeface_download where status='$status' and `delete_status`='No' and mobnum='$mobnum' order by id desc");
        $row = $query->result();
        return $row;
    }

    public function finalResultCartoon($mobnum,$genid) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_changeface_download  where mobnum='$mobnum' and id='$genid' ");
        $array = $query->row_array();
        return $array;
    }

    public function removePhoto($mobnum,$genid,$action){
        $table = 'tbl_'.$action;
        $updateQuery = "UPDATE $table SET `delete_status`='Yes' WHERE id='$genid'";
        $update = $this->db->query($updateQuery);
        
        //$update = true;
        if($update){
            echo '{"message":"true"}';
        }else{
            echo '{"message":"false"}';
        }
        // $sql = "select * from tbl_cartoon_res where mobnum='$mobnum' and id='$id'";
        // $query = $this->db->query($sql);
        // $array = $query->row_array();
        // return $array;
    }

    public function saved_avatars($mobnum, $url) {
        $this->db->query("INSERT INTO `tbl_saved_avatars`(`mobnum`,`avatarURL`, `date`) VALUES ('$mobnum','$url',now())");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

}
?>

