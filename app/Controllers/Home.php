<?php

namespace App\Controllers;

use App\Models\M_Data;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    public function __construct()
    {
        $this->M_Data = new M_Data();
        $this->session = \Config\Services::session();

        if ($this->session->get('is_login')) {
            return redirect()->to('login');
        }
    }

    public function index()
    {
        return view('welcome_message');
    }

    public function lhp_view($bulan = null)
    {
        if ($bulan == null) {
            $bulan = date('Y-m');
        } else {
            $bulan = date('Y-m', strtotime($bulan));
        }

        $model = new M_Data();
        $data['data_lhp'] = $model->get_all_lhp($bulan);
        $data['data_line'] = $model->get_line();
        $data['data_grup'] = $model->get_grup();
        $data['data_kasubsie'] = $model->get_kasubsie();

        return view('pages/lhp_view', $data);
    }

    public function lhp_add_view()
    {
        return view('pages/add_lhp');
    }

    public function add_lhp()
    {
        $tanggal_produksi = $this->request->getPost('tanggal_produksi');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');
        $grup = $this->request->getPost('grup');
        $mp = $this->request->getPost('mp');
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
            'kasubsie' => $kasubsie
        ];

        $data_lhp = [
            'tanggal_produksi' => $tanggal_produksi,
            'line' => $line,
            'shift' => $shift,
            'grup' => $grup,
            'mp' => $mp,
            'kasubsie' => $kasubsie
        ];

        $cek = $model->cek_lhp($tanggal_produksi, $line, $shift, $grup);
        if (count($cek) > 0) {
            $id_lhp = $cek[0]['id_lhp_2'];
            return redirect()->to(base_url('lhp/detail_lhp/' . $id_lhp));
        } else {
            $save_data = $model->save_lhp($data_lhp);

            return redirect()->to(base_url('lhp/detail_lhp/' . $save_data));
        }
    }

    public function delete_lhp($id)
    {
        $id = $this->request->getPost('id');

        $model = new M_Data();

        $delete = $model->delete_lhp($id);

        if ($delete > 0) {
            $this->lhp_view();
        }
    }

    public function getPartNo()
    {
        $no_wo = $this->request->getPost('no_wo');

        $model = new M_Data();
        echo json_encode($model->getPartNo($no_wo));
    }

    public function getCT()
    {
        $part_no = $this->request->getPost('part_number');
        $line = $this->request->getPost('line');
        // Split the string into an array using "-"
        $arr = explode("-", $part_no);

        // Remove the first two elements from the array
        $arr = array_slice($arr, 2);

        // Join the remaining elements back into a string using "-"
        $part_no = implode("-", $arr);

        $model = new M_Data();
        echo json_encode($model->getCT($part_no, $line));
    }

    public function get_proses_breakdown()
    {
        $jenis_breakdown = $this->request->getPost('jenis_breakdown');

        $model = new M_Data();
        echo json_encode($model->getProsesBreakdown($jenis_breakdown));
    }

    public function get_kategori_reject()
    {
        $jenis_reject = $this->request->getPost('jenis_reject');

        $model = new M_Data();
        echo json_encode($model->getKategoriReject($jenis_reject));
    }

    public function get_kategori_pending()
    {
        $jenis_pending = $this->request->getPost('jenis_pending');

        $model = new M_Data();
        echo json_encode($model->getKategoriPending($jenis_pending));
    }

    public function detail_lhp($id)
    {
        $model = new M_Data();
        $data['id_lhp'] = $id;
        $data['data_lhp'] = $model->get_lhp_by_id($id);
        $data['data_detail_lhp'] = $model->get_detail_lhp_by_id($id);
        $data['data_detail_breakdown'] = $model->get_detail_breakdown_by_id($id);
        $data['data_detail_reject'] = $model->get_detail_reject_by_id($id);
        $data['data_detail_pending'] = $model->get_detail_pending_by_id($id);

        $data['data_line'] = $model->get_data_line($data['data_lhp'][0]['line']);
        $data['data_grup'] = $model->get_data_grup_pic($data['data_lhp'][0]['grup']);

        $data['data_all_line'] = $model->get_line();
        $data['data_all_grup'] = $model->get_grup();
        $data['data_all_kasubsie'] = $model->get_kasubsie();

        $data['total_menit_breakdown'] = $model->get_total_menit_breakdown($id);

        $data['data_wo'] = $model->getDataWO();
        if ($data['data_lhp'][0]['line'] <= 7) {
            $data['data_breakdown'] = $model->getListBreakdown('AMB');
            $data['data_reject'] = $model->getListReject('AMB');
        } else if ($data['data_lhp'][0]['line'] > 7 && $data['data_lhp'][0]['line'] < 10) {
            $data['data_breakdown'] = $model->getListBreakdown('WET');
            $data['data_reject'] = $model->getListReject('AMB');
        } else {
            $data['data_breakdown'] = $model->getListBreakdown('MCB');
            $data['data_reject'] = $model->getListReject('AMB');
        }
        $data['data_pending'] = $model->getListPending();
        return view('pages/lhp_detail_view', $data);
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
                        $id_detail_lhp = $this->request->getPost('id_detail_lhp')[$i];
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
                    if ($arr[2] == 'DANDORI' or $arr[2] == 'DT' or $arr[2] == 'NDT') {
                        $kategori_andon = $arr[2];
                    } else {
                        $kategori_andon = '';
                    }
                    $proses_breakdown = implode('-', array_slice($arr, 2));
                } else {
                    $ticket = '';
                    $kategori_andon = '';
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
                    'kategori_andon' => $kategori_andon,
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

                $total_reject += ($this->request->getPost('qty_reject')[$i] != '' ? $this->request->getPost('qty_reject')[$i] : 0);

                $model->save_detail_reject($id_reject, $data_detail_reject);
            }
        }

        $total_data_pending = $this->request->getPost('no_wo_pending');

        if (!empty($total_data_pending)) {
            for ($i = 0; $i < count($total_data_pending); $i++) {
                $id_pending = $this->request->getPost('id_pending')[$i];

                $data_detail_pending = [
                    'id_lhp' => $id_lhp,
                    'no_wo' => $this->request->getPost('no_wo_pending')[$i],
                    'type_battery' => $this->request->getPost('part_number_pending')[$i],
                    'qty_wo_pending' => $this->request->getPost('qty_wo_pending')[$i],
                    'qty_pending' => ($this->request->getPost('qty_pending')[$i] != '' ? $this->request->getPost('qty_pending')[$i] : 0),
                    'status_battery' => $this->request->getPost('status_battery')[$i],
                    'jenis_pending' => $this->request->getPost('jenis_pending')[$i],
                    'kategori_pending' => $this->request->getPost('kategori_pending')[$i],
                    'remark_pending' => $this->request->getPost('remark_pending')[$i],
                    'status' => 'open'
                ];

                $total_pending += ($this->request->getPost('qty_pending')[$i] != '' ? $this->request->getPost('qty_pending')[$i] : 0);

                $model->save_detail_pending($id_pending, $data_detail_pending);
            }
        }

        $data_detail = [
            'total_plan' => $total_plan,
            'total_aktual' => $total_actual,
            'total_line_stop' => $total_line_stop,
            'total_reject' => $total_reject,
            'total_pending' => $total_pending,
            'loading_time' => $loading_time
        ];

        $model->update_lhp($id_lhp, $data_detail);

        return redirect()->to(base_url('lhp/detail_lhp/' . $id_lhp));
    }

    public function get_data_andon()
    {
        $tanggal_produksi = $this->request->getPost('tanggal_produksi');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');

        $model = new M_Data();
        $data = $model->get_data_andon($tanggal_produksi, $line, $shift);
        echo json_encode($data);
    }

    public function pilih_andon()
    {
        $id_ticket = $this->request->getPost('id_ticket');

        $model = new M_Data();
        $data = $model->pilih_andon($id_ticket);
        echo json_encode($data);
    }

    public function hapus_lhp($id_lhp)
    {
        $model = new M_Data();
        $model->hapus_lhp($id_lhp);

        return redirect()->to(base_url('lhp'));
    }

    public function delete_line_stop($id_line_stop, $id_lhp)
    {
        $model = new M_Data();
        $model->delete_line_stop($id_line_stop);

        return redirect()->to(base_url('lhp/detail_lhp/' . $id_lhp));
    }

    public function delete_reject($id_reject, $id_lhp)
    {
        $model = new M_Data();
        $model->delete_reject($id_reject);

        return redirect()->to(base_url('lhp/detail_lhp/' . $id_lhp));
    }

    public function delete_pending($id_pending, $id_lhp)
    {
        $model = new M_Data();
        $model->delete_pending($id_pending);

        return redirect()->to(base_url('lhp/detail_lhp/' . $id_lhp));
    }

    public function download()
    {
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $model = new M_Data();

        //data sheet lhp
        $data_lhp = $model->get_all_lhp_by_date($start_date, $end_date);
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
                    if ($dl['id_lhp_2'] === $ddl['id_lhp_2']) {
                        $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['line'], $dl['nama_pic'], $dl['kasubsie'], $ddl['jam_start'], $ddl['jam_end'], $ddl['menit_terpakai'], $ddl['no_wo'], $ddl['type_battery'], $ddl['ct'], $ddl['plan_cap'], $ddl['actual'], $ddl['total_menit_breakdown']);
                    };
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
        header('Content-Disposition: attachment;filename="Data LHP ASSY.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function get_kategori_andon()
    {
        $model = new M_Data();
        $data_tiket = $model->get_all_tiket_andon();

        foreach ($data_tiket as $dt) {
            $kategori_andon = $model->pilih_andon($dt['tiket_andon']);
            $data = [
                'kategori_andon' => $kategori_andon[0]['kategori_perbaikan']
            ];
            $model->update_kategori_andon($dt['tiket_andon'], $data);
        }
    }
}
