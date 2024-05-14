<?php

namespace App\Controllers;

use App\Models\M_WET_Finishing;
use App\Models\M_Data;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WET_Finishing extends BaseController
{
    public function __construct()
    {
        $this->M_WET_Finishing = new M_WET_Finishing();
        $this->session = \Config\Services::session();

        if ($this->session->get('is_login')) {
            return redirect()->to('login');
        }
    }

    public function wet_view($bulan = null)
    {
        if ($bulan == null) {
            $bulan = date('Y-m');
        } else {
            $bulan = date('Y-m', strtotime($bulan));
        }

        $model = new M_WET_Finishing();
        $data['data_lhp'] = $model->get_all_lhp_wet($bulan);
        $data['data_line'] = $model->get_line();
        $data['data_grup'] = $model->get_grup();
        $data['data_kasubsie'] = $model->get_kasubsie();
        return view('pages/wet_finishing/wet_view', $data);
    }

    public function add_lhp()
    {
        $tanggal_produksi = $this->request->getPost('tanggal_produksi');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');
        $grup = $this->request->getPost('grup');
        $mp = $this->request->getPost('mp');
        $absen = $this->request->getPost('absen');
        $cuti = $this->request->getPost('cuti');
        $kasubsie = $this->request->getPost('kasubsie');

        $model = new M_Data();
        $data_line = $model->get_data_line($line);
        $data_grup = $model->get_data_grup_pic($grup);

        $data = [
            'tanggal_produksi' => $tanggal_produksi,
            'id_line' => $line,
            'line' => $data_line[0]['nama_line'],
            'shift' => $shift,
            'id_pic' => $grup,
            'grup' => $data_grup[0]['nama_pic'],
            'mp' => $mp,
            'absen' => $absen,
            'cuti' => $cuti,
            'kasubsie' => $kasubsie
        ];

        $data_lhp = [
            'tanggal_produksi' => $tanggal_produksi,
            'line' => $line,
            'shift' => $shift,
            'grup' => $grup,
            'mp' => $mp,
            'absen' => $absen,
            'cuti' => $cuti,
            'kasubsie' => $kasubsie
        ];

        $cek = $model->cek_lhp($tanggal_produksi, $line, $shift, $grup);
        if (count($cek) > 0) {
            $id_lhp = $cek[0]['id_lhp_2'];
            return redirect()->to(base_url('wet_finishing/detail_lhp/' . $id_lhp));
        } else {

            $save_data = $model->save_lhp($data_lhp);

            return redirect()->to(base_url('wet_finishing/detail_lhp/' . $save_data));
        }
    }

    public function detail_lhp($id)
    {
        $model = new M_Data();
        $data['id_lhp'] = $id;
        $data['data_lhp'] = $model->get_lhp_by_id($id);
        $data['data_detail_lhp'] = $model->get_detail_lhp_by_id($id);
        $data['data_detail_breakdown'] = $model->get_detail_breakdown_by_id($id);
        $data['data_detail_reject'] = $model->get_detail_reject_by_id($id);
        $data['data_detail_pending'] = $this->M_WET_Finishing->get_detail_pending_by_id($id);

        $data['data_line'] = $model->get_data_line($data['data_lhp'][0]['line']);
        $data['data_grup'] = $model->get_data_grup_pic($data['data_lhp'][0]['grup']);

        $data['data_all_line'] = $model->get_line();
        $data['data_all_grup'] = $model->get_grup();
        $data['data_all_kasubsie'] = $model->get_kasubsie();

        $data['total_menit_breakdown'] = $model->get_total_menit_breakdown($id);
        $data['total_pending'] = $this->M_WET_Finishing->get_total_pending($id);

        $data['data_wo'] = $model->getDataWO($data['data_lhp'][0]['tanggal_produksi'], $data['data_lhp'][0]['line']);
        // $data['data_wo'] = [];
        if ($data['data_lhp'][0]['line'] <= 7) {
            $data['data_breakdown'] = $model->getListBreakdown('AMB');
            $data['data_reject'] = $model->getListReject('AMB');
        } else if ($data['data_lhp'][0]['line'] > 7 && $data['data_lhp'][0]['line'] < 10) {
            $data['data_breakdown'] = $model->getListBreakdown('WET');
            $data['data_reject'] = $model->getListReject('WET');
            $data['data_pending'] = $this->M_WET_Finishing->get_pending();
        } else {
            $data['data_breakdown'] = $model->getListBreakdown('MCB');
            $data['data_reject'] = $model->getListReject('MCB');
        }

        return view('pages/wet_finishing/lhp_detail_view', $data);
    }

    public function update_lhp()
    {

        $id_lhp = $this->request->getPost('id_lhp');

        $data_lhp = [
            'tanggal_produksi' => $this->request->getPost('tanggal_produksi'),
            'line' => $this->request->getPost('line'),
            'shift' => $this->request->getPost('shift'),
            'grup' => $this->request->getPost('grup'),
            'mp' => $this->request->getPost('mp'),
            'absen' => $this->request->getPost('absen'),
            'cuti' => $this->request->getPost('cuti'),
            'kasubsie' => $this->request->getPost('kasubsie')
        ];

        $model = new M_Data();

        $update_data = $model->update_lhp($id_lhp, $data_lhp);

        $total_plan = 0;
        $total_actual = 0;
        $total_line_stop = 0;
        $total_detail_line_stop = 0;
        $total_reject = 0;
        $total_pending = 0;

        if ($this->request->getPost('shift') == 1) {
            $loading_time = 440;
        } elseif ($this->request->getPost('shift') == 2) {
            $loading_time = 410;
        } elseif ($this->request->getPost('shift') == 3) {
            $loading_time = 370;
        }

        if ($update_data > 0) {
            if (!empty($this->request->getPost('no_wo'))) {
                $total_data = count($this->request->getPost('no_wo'));
                for ($i = 0; $i < $total_data; $i++) {
                    if ($this->request->getPost('no_wo')[$i] != '') {
                        $id_detail_lhp = (!empty($this->request->getPost('id_detail_lhp')[$i])) ? $this->request->getPost('id_detail_lhp')[$i] : '';
                        $data_detail_lhp = [
                            'id_lhp_2' => $id_lhp,
                            'batch' => $this->request->getPost('batch')[$i],
                            'jam_start' => $this->request->getPost('start')[$i],
                            'jam_end' => $this->request->getPost('stop')[$i],
                            'menit_terpakai' => $this->request->getPost('menit_terpakai')[$i],
                            'no_wo' => $this->request->getPost('no_wo')[$i],
                            'type_battery' => $this->request->getPost('part_number')[$i],
                            'ct' => $this->request->getPost('ct')[$i],
                            'plan_cap' => $this->request->getPost('plan_cap')[$i],
                            'actual' => $this->request->getPost('actual')[$i],
                            'total_menit_breakdown' => $this->request->getPost('total_menit_breakdown')[$i]
                        ];

                        if ($this->request->getPost('actual')[$i] != null) {
                            $total_plan += $this->request->getPost('plan_cap')[$i];
                            $total_actual += $this->request->getPost('actual')[$i];
                        }

                        if ($this->request->getPost('total_menit_breakdown')[$i] != null) {
                            $total_line_stop += $this->request->getPost('total_menit_breakdown')[$i];
                        }

                        $update_detail = $model->update_detail_lhp($id_detail_lhp, $data_detail_lhp);
                    }
                }
            }
        }

        $total_data_breakdown = $this->request->getPost('no_wo_breakdown');
        if (!empty($total_data_breakdown)) {
            for ($i = 0; $i < count($total_data_breakdown); $i++) {
                if ($this->request->getPost('jenis_breakdown')[$i] == 'ANDON') {
                    $string_ticket = $this->request->getPost('proses_breakdown')[$i];
                    $arr = explode("-", $string_ticket);
                    $ticket = $arr[0];
                    $proses_breakdown = $string_ticket;
                } else {
                    $ticket = '';
                    $proses_breakdown = $this->request->getPost('proses_breakdown')[$i];
                }

                $id_breakdown = $this->request->getPost('id_breakdown')[$i];

                $data_detail_breakdown = [
                    'id_lhp' => $id_lhp,
                    'jam_start' => $this->request->getPost('start_breakdown')[$i],
                    'jam_end' => $this->request->getPost('stop_breakdown')[$i],
                    'no_wo' => $this->request->getPost('no_wo_breakdown')[$i],
                    'type_battery' => $this->request->getPost('part_number_breakdown')[$i],
                    'jenis_breakdown' => $this->request->getPost('jenis_breakdown')[$i],
                    'tiket_andon' => $ticket,
                    'proses_breakdown' => $this->request->getPost('proses_breakdown')[$i],
                    'uraian_breakdown' => $this->request->getPost('uraian_breakdown')[$i],
                    'menit_breakdown' => $this->request->getPost('menit_breakdown')[$i]
                ];

                if ($this->request->getPost('menit_breakdown')[$i] != '') {
                    $total_detail_line_stop += (int) $this->request->getPost('menit_breakdown')[$i];
                }

                $model->save_detail_breakdown($id_breakdown, $data_detail_breakdown);
            }
        }

        $total_data_reject = $this->request->getPost('no_wo_reject');

        if (!empty($total_data_reject)) {
            for ($i = 0; $i < count($total_data_reject); $i++) {
                $id_reject = $this->request->getPost('id_reject')[$i];

                $data_detail_reject = [
                    'id_lhp' => $id_lhp,
                    'no_wo' => $this->request->getPost('no_wo_reject')[$i],
                    'type_battery' => $this->request->getPost('part_number_reject')[$i],
                    'qty_reject' => $this->request->getPost('qty_reject')[$i],
                    'jenis_reject' => $this->request->getPost('jenis_reject')[$i],
                    'kategori_reject' => $this->request->getPost('kategori_reject')[$i],
                    'remark_reject' => $this->request->getPost('remark_reject')[$i]
                ];

                $total_reject += $this->request->getPost('qty_reject')[$i];

                $model->save_detail_reject($id_reject, $data_detail_reject);
            }
        }

        $data_detail = [
            'total_plan' => $total_plan,
            'total_aktual' => $total_actual,
            'total_line_stop' => $total_line_stop,
            'total_reject' => $total_reject,
            'loading_time' => $loading_time
        ];

        $model->update_lhp($id_lhp, $data_detail);

        $total_data_pending = $this->request->getPost('no_wo_pending');

        if (!empty($total_data_pending)) {
            for ($i = 0; $i < count($total_data_pending); $i++) {
                $id_pending = $this->request->getPost('id_pending')[$i];

                $data_detail_pending = [
                    'id_lhp' => $id_lhp,
                    'no_wo' => $this->request->getPost('no_wo_pending')[$i],
                    'type_battery' => $this->request->getPost('part_number_pending')[$i],
                    'jenis_pending' => $this->request->getPost('jenis_pending')[$i],
                    'kategori_pending' => $this->request->getPost('kategori_pending')[$i],
                    'qty_pending' => $this->request->getPost('qty_pending')[$i],
                ];

                $total_pending += $this->request->getPost('qty_pending')[$i];

                $this->M_WET_Finishing->save_detail_pending($id_pending, $data_detail_pending);
            }
        }

        return redirect()->to(base_url('wet_finishing/detail_lhp/' . $id_lhp));
    }

    public function get_kategori_pending()
    {
        $jenis_pending = $this->request->getPost('jenis_pending');

        echo json_encode($this->M_WET_Finishing->getKategoriPending($jenis_pending));
    }

    public function hapus_lhp($id_lhp)
    {
        $model = new M_Data();
        $model->hapus_lhp($id_lhp);

        return redirect()->to(base_url('wet_finishing'));
    }

    public function download()
    {
        // $date = $this->request->getPost('date');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        // $month = date('F_Y', strtotime($date));
        $model = new M_WET_Finishing();

        //data sheet lhp
        $data_lhp = $model->get_all_lhp_by_date($start_date, $end_date);
        // $data_lhp = $model->get_all_lhp_by_month($date);
        if ($data_lhp !== NULL) {
            $dates = array_column($data_lhp, "tanggal_produksi");
            $lines = array_column($data_lhp, "line");
            $shift = array_column($data_lhp, "shift");
            array_multisort($dates, SORT_ASC, $shift, SORT_ASC, $lines, SORT_ASC,  $data_lhp);
            $data_detail_lhp = [];
            foreach ($data_lhp as $dl) {
                $temp = $model->get_all_detail_lhp_by_id_lhp($dl['id_lhp_2']);
                if ($temp !== NULL) {
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
        if ($data_lhp !== NULL) {
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
        if ($data_lhp !== NULL) {
            foreach ($data_lhp as $dl) {
                foreach ($data_detail_line_stop as $ddls) {
                    if ($ddls !== NULL) {
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
        if ($data_lhp !== NULL) {
            foreach ($data_lhp as $dl) {
                foreach ($data_detail_reject as $ddj) {
                    if ($ddj !== NULL) {
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
        header('Content-Disposition: attachment;filename="Data LHP WET.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
