<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_MatriksKriteria extends CI_Model {

    public function __construct()
    {
		parent::__construct();
	}

    //ambil data nilai target
    public function nilaiTarget($id_calon)
    {
        $result = $this->db->query("
            SELECT calon.id_calon as calon_id,periode_masuk,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K1' AND id_calon = calon_id) as K1,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K2' AND id_calon = calon_id) as K2,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K3' AND id_calon = calon_id) as K3
            FROM target2,calon WHERE calon.id_calon = target2.id_calon and calon.id_calon = '$id_calon' GROUP by calon.id_calon
        ");
        return $result->result();
    }
        
    //ambil data untuk matriks normalisasi
    public function matriksNormalisasi($tgl,$id_calon)
    {
        $result = $this->db->query("
            SELECT calon.id_calon as calon_id,periode_masuk,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K1' AND id_calon = calon_id) as K1,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K2' AND id_calon = calon_id) as K2,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K3' AND id_calon = calon_id) as K3
            FROM target2,calon WHERE calon.id_calon = target2.id_calon and periode_masuk = '$tgl' and calon.id_calon = '$id_calon' GROUP by calon.id_calon
        ");
        return $result->result();
    }

    //ambil data max
    public function max()
    {
        $result = $this->db->query("
            SELECT 
                (SELECT MAX(nilai_target) FROM target WHERE target.kd_kriteria = 'K1') as maxK1,
                (SELECT MAX(nilai_target) FROM target WHERE target.kd_kriteria = 'K2') as maxK2,
                (SELECT MAX(nilai_target) FROM target WHERE target.kd_kriteria = 'K3') as maxK3
            FROM target2 GROUP by maxK1"
        );
        return $result->result();
    }

    //ambil data eigenvector
    public function eigenvector()
    {
        $result = $this->db->query("SELECT eigenvector FROM kriteria");
        return $result->result();
    }

    //abil data untuk saw
    public function getAllSAW($tgl)
    {
        $result = $this->db->query("
            SELECT calon.id_calon as calon_id,periode_masuk,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K1' AND id_calon = calon_id) as K1,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K2' AND id_calon = calon_id) as K2,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K3' AND id_calon = calon_id) as K3
            FROM target2,calon WHERE calon.id_calon = target2.id_calon and periode_masuk = '$tgl' GROUP by calon.id_calon
        ");
        return $result->result();
    }

    //buat cegah division by zero
    public function getAllSAW_validasi($tgl)
    {
        $result = $this->db->query("
            SELECT calon.id_calon as calon_id,periode_masuk,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K1' AND id_calon = calon_id) as K1,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K2' AND id_calon = calon_id) as K2,
                (SELECT target.nilai_target FROM target WHERE target.kd_kriteria = 'K3' AND id_calon = calon_id) as K3
            FROM target2,calon WHERE calon.id_calon = target2.id_calon and periode_masuk = '$tgl' GROUP by calon.id_calon
        ");
        return $result->row();
    }

    //simpan
    public function simpanHasilSAW($data)
    {
        $status = $this->db->insert('hasil', $data);
        return $status;
    }

    //validasi
    public function hilangkanTombol($tgl)
    {
        $result = $this->db->query("SELECT * FROM hasil JOIN calon on hasil.id_calon = calon.id_calon WHERE calon.periode_masuk = '$tgl'");
        return $result->result();
    }
    
}