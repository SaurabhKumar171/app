<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comic_model extends CI_Model {

    public function userVarification($mobnum, $password) {
        $this->db->trans_start();
        $sel = "select * from tbl_user where mobnum='$mobnum' and password='$password'";
        $query = $this->db->query($sel);
        return $query->row_array();
    }

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    public function country_list() {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_country where status='0' order by country_name ASC");
        $row = $query->result();
        return $row;
    }

    public function findUser($code,$mobnum) {
        $query = $this->db->query("select * from tbl_user where mobnum='$mobnum'");
        return $query->row_array();
    }

    public function update_password($code,$mobnum,$password) {
        $updateQuery = "UPDATE tbl_user SET `password`='$password' WHERE mobnum='$mobnum'";
        $update = $this->db->query($updateQuery);
        if($update){
            return 0;
        }else{
            return 1;
        }
    }

    public function create_user($code,$mobnum,$password) {
        $this->db->query("INSERT INTO `tbl_user`(`mobnum`,`password`,`date`) VALUES ('$mobnum','$password',now())");
        $insert_id = $this->db->insert_id();
        if($insert_id!=''){
            return 0;
        }else{
            return 1;
        }
    }

    public function avatar_icons($num) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_avatars where type='0' or type='$num' order by img_order ASC");
        $row = $query->result();
        return $row;
    }

    public function requestAvatar($id, $type) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tbl_comic` WHERE avatar_id='$id' and type='$type' ");
        $row = $query->result();
        return $row;
    }

    public function getComicCharacter($id) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tbl_comic` WHERE id='$id'");
        $array = $query->row_array();
        return $array;
    }

    public function outfit($type) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tbl_outfits` WHERE type='$type'");
        $array = $query->result();
        return $array;
    }

    public function getstoriesCharacter($id) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT path FROM `tbl_story_character` WHERE id='$id'");
        $array = $query->row_array();
        return $array;
    }

    public function getMyAvatar($mobnum) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT avatarURL FROM `tbl_saved_avatars` WHERE mobnum='$mobnum' order by id DESC LIMIT 1");
        $array = $query->row_array();
        return $array;
    }

    public function saved_avatars($mobnum, $url) {
        $this->db->query("INSERT INTO `tbl_saved_avatars`(`mobnum`,`avatarURL`, `date`) VALUES ('$mobnum','$url',now())");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function savedShared($path, $lastId) {
        $this->db->query("insert INTO `tbl_shared` (`path`, `saved_avatar_id`, `date`) 
            VALUES ('$path','$lastId',now())");
    }

    public function update_saved_avatars($titleName, $id) {
        $this->db->query("UPDATE `tbl_saved_avatars` SET `title`='$titleName' WHERE id= '$id'");
    }

    public function sel_saved_avatars($id) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT avatarURL,id FROM  `tbl_saved_avatars` WHERE id='$id'");
        $array = $query->row_array();
        return $array;
    }

    public function getshare($id) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT path FROM  `tbl_shared` WHERE saved_avatar_id='$id'");
        $row = $query->result();
        return $row;
    }

    public function lastCreated_avatar($mobnum) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM  `tbl_saved_avatars` where mobnum = '$mobnum' order by id DESC LIMIT 1");
        $array = $query->row_array();
        return $array;
    }

    public function savedSrc($title) {
        $this->db->query("INSERT INTO `tbl_comicScr`(`title`) VALUES ('$title')");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function saveComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $lastId, $lB, $rB) {
        $sql = "insert INTO `tbl_comicScreens`
(`mobnum`,`narration`,`backImage`, `leftComment`, `rightComment`, `leftImg`, `rightImg`, `saved_avatars_id`, `date`,`leftBubble`,`rightBubble`) 
            VALUES ('$mobnum'," . $this->db->escape($narra) . ",'$bg'," . $this->db->escape($lc) . "," . $this->db->escape($rc) . ",'$li','$ri','$lastId',now(),'$lB','$rB')";
        $this->db->query($sql);
    }

    public function comicTitleName($id) {
        $this->db->trans_start();
        $query = $this->db->query("select title from `tbl_comicScr` where id = '$id'");
        $array = $query->row_array();
        return $array;
    }

    public function comicDetails($id) {
        $this->db->trans_start();
        $query = $this->db->query("select * from `tbl_comicScreens` where saved_avatars_id = '$id'");
        $row = $query->result();
        return $row;
    }

     public function share($tbl_name,$id) {
        $this->db->trans_start();
        $query = $this->db->query("select id,final_img from $tbl_name where id = '$id' and status='0'");
        $array = $query->row_array();
        return $array;
    }

    public function myCharacter($id) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tbl_saved_avatars`  where id = '$id'");
        $array = $query->row_array();
        return $array;
    }

    public function createStoryAllIcons() {
        $this->db->trans_start();
        $query = $this->db->query("select * from `tbl_create_story`");
        $row = $query->result();
        return $row;
    }

    public function storyImages($charId) {
        $this->db->trans_start();
        $query = $this->db->query("select * from `tbl_story_character` where create_story_id = '$charId'");
        $row = $query->result();
        return $row;
    }

    public function viewAllComic($mobnum) {
        $this->db->trans_start();
        $query = $this->db->query("SELECT t1.id as aId,title FROM `tbl_comicScr` t1 inner join `tbl_comicScreens` t2
on t1.id = t2.saved_avatars_id and t2.mobnum = $mobnum and t1.delete_status='No' group by saved_avatars_id order by aId DESC");
        $row = $query->result();
        return $row;
    }

    /*     * ******************* */

    public function getAvatarIcons($charId) {
        $this->db->trans_start();
        $query = $this->db->query("select * from `tbl_avatar` where `id_male_female_icon` = '$charId'");
        $row = $query->result();
        return $row;
    }

    public function getcreateStory() {
        $this->db->trans_start();
        $query = $this->db->query("select * from `tbl_create_story`");
        $row = $query->result();
        return $row;
    }

    public function updateComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $lastId, $lB, $rB,$screenid) {
        $sql = "update `tbl_comicScreens` set `narration` = " . $this->db->escape($narra) . ",`backImage` = '$bg', `leftComment` = " . $this->db->escape($lc) . ", `rightComment` = " . $this->db->escape($rc) . ", `leftImg` = '$li', `rightImg` = '$ri', `date` = now(),`leftBubble` = '$lB',`rightBubble` = '$rB' where `mobnum` = '$mobnum' and `saved_avatars_id` = '$lastId' and id = '$screenid' ";
        $this->db->query($sql);
    }

     public function insertEditComicScreen($mobnum, $narra, $bg, $lc, $rc, $li, $ri, $lastId, $lB, $rB,$screenid) {
         $sql = "insert INTO `tbl_comicScreens`
(`mobnum`,`narration`,`backImage`, `leftComment`, `rightComment`, `leftImg`, `rightImg`, `saved_avatars_id`, `date`,`leftBubble`,`rightBubble`) 
            VALUES ('$mobnum'," . $this->db->escape($narra) . ",'$bg'," . $this->db->escape($lc) . "," . $this->db->escape($rc) . ",'$li','$ri','$lastId',now(),'$lB','$rB')";
        $this->db->query($sql);
    }

    public function deleteEditComicScreen($mobnum,$screenid) {
        $sql = "DELETE FROM `tbl_comicScreens` WHERE  `mobnum` = '$mobnum' and id = '$screenid' " ;
        $this->db->query($sql);
    }

    public function phoCartoon($status) {
        $sql = "select * from tbl_cartoon where status='$status' limit 0,10";
        $query = $this->db->query($sql);
        $row = $query->result();
        return $row;
    }

    public function selCartoon($id,$status) {
        $sql = "select * from tbl_cartoon where id='$id' and status='$status'";
        $query = $this->db->query($sql);
        $array = $query->row_array();
        return $array;
    }

    public function selCartoonAjaxAllRecord($status){
        $sql = "select count(*) as totalRecord from tbl_cartoon where status='$status'";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }

    public function selCartoonAjax($page,$status){
        $offset = 20*$page;
        $limit = 20;
        $sql = "select * from tbl_cartoon where status='$status' limit $offset ,$limit";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    public function selCartoonAjax2($limitStart,$status){
        $limitCount = 15;
       //echo $sql = "select id,img from tbl_cartoon where status='$status' limit $offset ,$limit";

       $sql = "SELECT id,img from tbl_cartoon where lower(SUBSTRING_INDEX(img,'.',-1))!='gif' and lower(SUBSTRING_INDEX(img,'.',-1))!='' and status='$status' limit $limitStart, $limitCount";

        $result = $this->db->query($sql)->result();
        return $result;
    }

    public function saveCartoon($mobnum,$filename,$frame_name,$img_full,$img_thumb,$num,$app_id,$key,$sign_data,$data,$request_id) {

        $sql = "insert INTO `tbl_cartoon_res` (`mobnum`,`uploaded_img`,`frame_name`,`emoji_name`,`name`,`param`,`img_url`, `img_url_thumb`, `rand_num`, `app_id`, `key_value`, `data`,`sign_data`,`request_id`) 
            VALUES ('$mobnum','$filename','$frame_name','NULL','NULL','NULL','$img_full','$img_thumb','$num','$app_id','$key','$data','$sign_data','$request_id')";
        $this->db->query($sql);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function updateCartoon($fimg,$simg,$gen_id) {
        $sql = "update `tbl_cartoon_res` set `final_img`='".$fimg."',`final_img_thumb` ='".$simg."' where `id` = '$gen_id'";
        $this->db->query($sql);
    }

    public function confrimTosaveCartoon($mobnum,$gen_id) {
        $sql = "update `tbl_cartoon_res` set `status`='0' where mobnum = '$mobnum' and `id` = '$gen_id'";
        $this->db->query($sql);
    }

    public function viewAllPhotoEffect($mobnum) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_cartoon_res where mobnum= '".$mobnum."' and status='0' and delete_status='No' and effect_type='0' order by id DESC ");
        $row = $query->result();
        return $row;
    }

    public function finalResultCartoon($mobnum,$id){
        $sql = "select * from tbl_cartoon_res where mobnum='$mobnum' and id='$id'";
        $query = $this->db->query($sql);
        $array = $query->row_array();
        return $array;
    }

    public function phoCartoonEmoji($status) {
        $sql = "select * from tbl_cartoonist where status='$status'";
        $query = $this->db->query($sql);
        $row = $query->result();
        return $row;
    }

    public function saveCartooniest($mobnum,$filename,$emoji_name,$name,$param,$img_full,$img_thumb,$num,$app_id,$key,$sign_data,$data,$request_id) {

        $sql = "insert INTO `tbl_cartoon_res` (`mobnum`,`uploaded_img`,`frame_name`,`emoji_name`,`name`,`param`,`img_url`, `img_url_thumb`, `rand_num`, `app_id`, `key_value`, `data`,`sign_data`,`request_id`,`effect_type`) 
            VALUES ('$mobnum','$filename','NULL','$emoji_name','$name','$param','$img_full','$img_thumb','$num','$app_id','$key','$data','$sign_data','$request_id','1')";
        $this->db->query($sql);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function finalResultCartooniest($mobnum){
        //$sql = "select * from tbl_cartoon_res where mobnum='$mobnum' and id='$id'";
        $sql = "select * from tbl_cartoon_res where mobnum='$mobnum' order by id desc limit 1";
        $query = $this->db->query($sql);
        $array = $query->row_array();
        return $array;
    }

    public function confrimTosaveEmojiCartoon($mobnum) {
        $sql = "update `tbl_cartoon_res` set `status`='0' where mobnum = '$mobnum' and effect_type='1' order by id desc limit 1";
        $this->db->query($sql);
    }

    public function viewAllEmojiEffect($mobnum) {
        $this->db->trans_start();
        $query = $this->db->query("select * from tbl_cartoon_res where mobnum= '".$mobnum."' and status='0' and delete_status='No' and effect_type='1' order by id desc ");
        $row = $query->result();
        return $row;
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
}
?>

