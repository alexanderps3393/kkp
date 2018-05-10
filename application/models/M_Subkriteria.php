<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Subkriteria extends CI_Model {

    public function __construct(){
		parent::__construct();
	}

	public function tambahSubkriteria($data)
	{
		$checkinsert = false;
		try{
			$this->db->insert('subkriteria',$data);
			$checkinsert = true;
		}catch (Exception $ex) {
			$checkinsert = false;
		}
		return $checkinsert;
	}

	
	public function getAllSubkriteria()
	{
		$result = $this->db->get('subkriteria');
		return $result->result();
	}

	public function getSubkriteria($id)
	{
		$result = $this->db->where('kd_subkriteria',$id)->get('subkriteria');
		return $result->row();
	}

	public function UpdateSubkriteria($id,$data)
	{
		$checkupdate = false;
		try{
			$this->db->where('kd_subkriteria',$id);
			$this->db->update('subkriteria',$data);
			$checkupdate = true;
		}catch (Exception $ex) {
			$checkupdate = false;
		}
		return $checkupdate;
	}

	public function DeleteSubkriteria($id)
	{
		$checkupdate = false;
		try{
			$this->db->where('kd_subkriteria',$id);
			$this->db->delete('subkriteria');
			$checkupdate = true;
		}catch (Exception $ex) {
			$checkupdate = false;
		}
		return $checkupdate;
	}

	public function getKdSubkriteria()
     {
       $q = $this->db->query("select MAX(RIGHT(kd_subkriteria,1)) as kd_max from subkriteria");
       $kd = "";
       if($q->num_rows() > 0)
       {
         foreach ($q->result() as $k) {
           $tmp = ((int)$k->kd_max)+1;
           $kd = sprintf("%01s",$tmp);
         }
       } else
       {
         $kd = "1";
       }
       return "SK".$kd;
     }
}