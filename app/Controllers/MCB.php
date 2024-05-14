<?php

namespace App\Controllers;

use App\Models\M_MCB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MCB extends BaseController
{
  public function __construct()
  {
    $this->M_MCB = new M_MCB();
    $this->session = \Config\Services::session();

    if ($this->session->get('is_login')) {
      return redirect()->to('login');
    }
  }

  public function mcb_view($bulan = null)
  {
    if ($bulan == null) {
        $bulan = date('Y-m');
    } else {
        $bulan = date('Y-m', strtotime($bulan));
    }
    
    $model = new M_MCB();
    $data['data_lhp'] = $model->get_all_lhp_mcb($bulan);
    $data['data_line'] = $model->get_line();
    $data['data_grup'] = $model->get_grup();
    $data['data_kasubsie'] = $model->get_kasubsie();
    return view('pages/mcb/mcb_view', $data);
  }

  public function download()
    {
        // $date = $this->request->getPost('date');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        // $month = date('F_Y', strtotime($date));
        $model = new M_MCB();

        //data sheet lhp
        $data_lhp = $model->get_all_lhp_by_date($start_date, $end_date);
        // $data_lhp = $model->get_all_lhp_by_month($date);
        if($data_lhp !== NULL) {
            $dates = array_column($data_lhp, "tanggal_produksi");
            $lines = array_column($data_lhp, "line");
            $shift = array_column($data_lhp, "shift");
            array_multisort($dates, SORT_ASC, $shift, SORT_ASC, $lines, SORT_ASC,  $data_lhp);
            $data_detail_lhp = [];
            foreach ($data_lhp as $dl) {
                $temp = $model->get_all_detail_lhp_by_id_lhp($dl['id_lhp_2']);
                if($temp !== NULL) {
                    foreach ($temp as $t) {
                        array_push($data_detail_lhp, $t);
                    }
                }
            }
        }
        // dd($fix_data_detail_lhp);
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Menambahkan data ke worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LHP');
        $data = array(
            array('Date', 'Shift', 'Line', 'PIC', 'Kasubsie', 'Jam Start', 'Jam End', 'Menit Terpakai', 'No WO', 'Type Battery', 'CT', 'Plan Cap', 'Actual', 'Total Menit Line Stop'),
        );
        $isExist = [];
        if($data_lhp !== NULL) {
            foreach ($data_lhp as $dl) {
                foreach ($data_detail_lhp as $ddl) {
                    // if($ddl !== NULL) {
                    //     foreach ($ddl as $dt_ddl) {
                            if ($dl['id_lhp_2'] === $ddl['id_lhp_2']) {
                                $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie'], $ddl['jam_start'], $ddl['jam_end'], $ddl['menit_terpakai'], $ddl['no_wo'], $ddl['type_battery'], $ddl['ct'], $ddl['plan_cap'], $ddl['actual'], $ddl['total_menit_breakdown']);
                            };
                    //     }
                    // } else {
                    //     $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie']);
                    // }
                }
            }
        }

        // Memasukkan data array ke dalam worksheet
        $sheet->fromArray($data);

        //data sheet line stop
        foreach ($data_lhp as $dl) {
            $data_detail_line_stop[] = $model->get_detail_breakdown_by_id($dl['id_lhp_2']);
        }

        // Menambahkan data ke worksheet
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Line Stop');   

        $data_line_stop = array(
            array('Date', 'Shift', 'Line', 'PIC', 'Kasubsie', 'Jam Start', 'Jam End', 'Menit Terpakai', 'No WO', 'Type Battery', 'Jenis Breakdown', 'Tiket Andon', 'Proses Breakdown', 'Uraian Breakdown', 'Menit Breakdown'),
        );
        $isExist = [];
        if($data_lhp !== NULL) {
            foreach ($data_lhp as $dl) {
                foreach ($data_detail_line_stop as $ddls) {
                    if($ddls !== NULL) {
                        foreach ($ddls as $dt_ddls) {
                            if ($dl['id_lhp_2'] === $dt_ddls['id_lhp']) {
                                $data_line_stop[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie'], $dt_ddls['jam_start'], $dt_ddls['jam_end'], $dt_ddls['menit_terpakai'], $dt_ddls['no_wo'], $dt_ddls['type_battery'], $dt_ddls['jenis_breakdown'], $dt_ddls['tiket_andon'], $dt_ddls['proses_breakdown'], $dt_ddls['uraian_breakdown'], $dt_ddls['menit_breakdown']);
                            };
                        }
                    } else {
                        $data_line_stop[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie']);
                    }
                }
            }
        }

        // Memasukkan data array ke dalam worksheet
        $sheet2->fromArray($data_line_stop);

        //data sheet reject
        foreach ($data_lhp as $dl) {
            $data_detail_reject[] = $model->get_detail_reject_by_id($dl['id_lhp_2']);
        }

        // Menambahkan data ke worksheet
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Reject');   

        $data_reject = array(
            array('Date', 'Shift', 'Line', 'PIC', 'Kasubsie', 'No WO', 'Type Battery', 'QTY Reject', 'Jenis Reject', 'Kategori Reject', 'Remark Reject'),
        );
        $isExist = [];
        if($data_lhp !== NULL) {
            foreach ($data_lhp as $dl) {
                foreach ($data_detail_reject as $ddj) {
                    if($ddj !== NULL) {
                        foreach ($ddj as $dt_ddj) {
                            if ($dl['id_lhp_2'] === $dt_ddj['id_lhp']) {
                                $data_reject[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie'], $dt_ddj['no_wo'], $dt_ddj['type_battery'], $dt_ddj['qty_reject'], $dt_ddj['jenis_reject'], $dt_ddj['kategori_reject'], $dt_ddj['remark_reject']);
                            };
                        }
                    } else {
                        $data_reject[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie']);
                    }
                }
            }
        }

        // Memasukkan data array ke dalam worksheet
        $sheet3->fromArray($data_reject);


        // Mengatur header respons HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data LHP MCB.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
