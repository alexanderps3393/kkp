<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_LapPenilaian extends CI_Controller {

	function __construct() 
	{
        parent::__construct();
        $this->load->library('pdf');
        $this->load->model(['M_LapPenilaianCalonKaryawan']);
        $this->load->library('Excel_generator');
    }

    //halaman awal
	public function index()
	{
		$this->load->view('template/V_Header');
		$this->load->view('template/V_Sidebar');
		$this->load->view('laporan/V_LapPenilaian');
		$this->load->view('template/V_Footer');
	}

    //tampil data per-periode
	public function periode()
    {
		$awal  = $this->input->get('awal');
        $akhir = $this->input->get('akhir');
    	$getLapPenilaianCalonKaryawan = $this->M_LapPenilaianCalonKaryawan->getLapPenilaianCalonKaryawan($awal,$akhir);
        
        $data = [
        	'getLapPenilaianCalonKaryawan' => $getLapPenilaianCalonKaryawan,
            'awal' => $awal,
            'akhir' => $akhir
    	];
       
        $this->load->view('template/V_Header',$data);
		$this->load->view('template/V_Sidebar');
		$this->load->view('laporan/V_LapPenilaian');
		$this->load->view('template/V_Footer');
    }

    //cetak laporan penilaian dalam bentuk pdf
    public function cetaklaporanPenilaian($awal,$akhir,$id_calon)
    {
    	$pdf = new FPDF('P','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        //cetak gambar
        $image1 = "assets/img/logo2.png";
        $pdf->Cell(1, 0, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 20.10), 0, 0, 'L', false );
        // mencetak string
        $pdf->Cell(186,10,'PT. BIYA MAESTRO HARDSCAPE',0,1,'C');
        $pdf->Cell(9,1,'',0,1);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(186,1,'Jl. BPKP No. 37 Sudimara Pinang, Kota Tangerang',0,1,'C');
        $pdf->Cell(186,7,'Telp / Fax : 021-22927310',0,1,'C');
        $pdf->Cell(186,1,'E-mail : maestro_hardscape@yahoo.com',0,1,'C');

        $pdf->Line(10, 35, 210-11, 35); 
        $pdf->SetLineWidth(0.5); 
        $pdf->Line(10, 35, 210-11, 35);
        $pdf->SetLineWidth(0);     
            
        $pdf->ln(6);        
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,10,'Laporan Penilaian Calon Karyawan',0,1,'C');
        //periode masuk
        $pdf->SetFont('Arial','',9);
        $periode_masuk = $this->M_LapPenilaianCalonKaryawan->periode_masuk($id_calon);
        $masuk = $periode_masuk->periode_masuk;
        $pdf->Cell(1,2,"Periode Masuk : ".tanggal($masuk),0,1,'L');
        $pdf->Cell(190,5,' ',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,1,'',0,1);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(9,6,'No.',1,0,'C');
        $pdf->Cell(15,6,'ID Calon',1,0,'C');
        $pdf->Cell(30,6,'Nama Calon',1,0,'C');
        $pdf->Cell(15,6,'Jurusan',1,0,'C');
        $pdf->Cell(10,6,'Skill',1,0,'C');
        $pdf->Cell(25,6,'Tanggung Jawab',1,0,'C');
        $pdf->Cell(20,6,'Kesiapan Kerja',1,0,'C');
        $pdf->Cell(15,6,'Perilaku',1,0,'C');
        $pdf->Cell(15,6,'Ketelitian',1,0,'C');
        $pdf->Cell(15,6,'Kejujuran',1,0,'C');
        $pdf->Cell(20,6,'Hasil',1,0,'C');
        $pdf->SetFont('Arial','',7);

        $hasil = $this->M_LapPenilaianCalonKaryawan->getLapPenilaianCalonKaryawanDetail($awal,$akhir,$id_calon);

        $no = 1;
        foreach ($hasil as $row)
        {
            $pdf->Cell(10,6,'',0,1);
            $pdf->Cell(9,6,$no++.".",1,0,'C');
            $pdf->Cell(15,6,$row->calon_id,1,0,'C');
            $pdf->Cell(30,6,ucwords($row->nm_calon),1,0);
            $pdf->Cell(15,6,$row->jurusan,1,0,'C');
            $pdf->Cell(10,6,$row->skill,1,0,'C');
            $pdf->Cell(25,6,$row->tanggung_jawab,1,0,'C');
            $pdf->Cell(20,6,$row->kesiapan_kerja,1,0,'C');
            $pdf->Cell(15,6,$row->perilaku,1,0,'C');
            $pdf->Cell(15,6,$row->ketelitian,1,0,'C');
            $pdf->Cell(15,6,$row->kejujuran,1,0,'C');
            $pdf->Cell(20,6,$row->hasil,1,0,'C');   
        }
        $pdf->Output();
    }

    //cetak laporan penilaian dalam bentuk .xlsx
    public function Excel($awal,$akhir,$id)
    {
        $query = $this->M_LapPenilaianCalonKaryawan->ExportExcel($awal,$akhir,$id);
        $this->excel_generator->set_query($query);
        $this->excel_generator->set_header(array('ID CALON', 'NAMA CALON','JURUSAN','SKILL','TANGGUNG JAWAB','KESIAPAN KERJA','PERILAKU','KETELITIAN','KEJUJURAN','HASIL'));
        $this->excel_generator->set_column(array('calon_id', 'nm_calon', 'jurusan','skill','tanggung_jawab','kesiapan_kerja','perilaku','ketelitian','kejujuran','hasil'));
        $this->excel_generator->set_width(array(10, 20, 10, 15,15,15,15,15,15,15,));
        $this->excel_generator->exportTo2007('Rekapitulasi Nilai Periode '.tanggal($awal). ' Sampai '.tanggal($akhir));
    }

    //cetak laporan penilaian dalam bentuk .doc
    public function Word($awal,$akhir,$id)
    {
        $hasil = $this->M_LapPenilaianCalonKaryawan->getLapPenilaianCalonKaryawanDetail($awal,$akhir,$id);
        $data = [
            'id' => $id,
            'awal' => $awal,
            'akhir' => $akhir,
            'word' => $hasil
        ];
        $this->load->view('laporan/word_LapPenilaianKaryawan',$data);
    }
}
