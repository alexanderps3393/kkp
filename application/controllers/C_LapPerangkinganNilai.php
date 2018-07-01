<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_LapPerangkinganNilai extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->library('pdf');
        $this->load->model('M_LapPerangkinganNilai');
    }

	public function index()
	{
		$this->load->view('template/V_Header');
		$this->load->view('template/V_Sidebar');
		$this->load->view('laporan/V_LapPerangkinganNilai');
		$this->load->view('template/V_Footer');
	}

    public function periode(){
        $periode = $this->input->get('periode_masuk');
        $getLapPerangkinganNilai = $this->M_LapPerangkinganNilai->getLapPerangkinganNilai($periode);
        
        $data = ['getLapPerangkinganNilai' => $getLapPerangkinganNilai,
                'periode' => $periode
    
    ];
       
        $this->load->view('template/V_Header',$data);
		$this->load->view('template/V_Sidebar');
		$this->load->view('laporan/V_LapPerangkinganNilai');
		$this->load->view('template/V_Footer');
    }

	function cetaklaporanrank(){
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
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,15,'LAPORAN HASIL PERANGKINGAN ',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,1,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(50,6,'ID CALON',1,0);
        $pdf->Cell(43,6,'NAMA',1,0);
        $pdf->Cell(43,6,'HASIL',1,0);
        $pdf->Cell(55,6,'KETERANGAN',1,0);
        
        
		$pdf->SetFont('Arial','',10);
        $periode = $this->input->get('periode');
		$hasil = $this->M_LapPerangkinganNilai->getLapPerangkinganNilai($periode);
		
        foreach ($hasil as $row){
			$pdf->Cell(10,7,'',0,1);
            $pdf->Cell(50,6,$row->id_calon,1,0);
            $pdf->Cell(43,6,$row->nm_calon,1,0);
            $pdf->Cell(43,6,$row->hasil_akhir,1,0);
            $pdf->Cell(55,6,$row->keterangan,1,0);
            
          
        }
        $pdf->Output();
    }

}
