<?php

namespace App\Controllers;

use App\Models\M_DashboardPlateRejection;

class DashboardPlateRejection extends BaseController
{
    public function __construct()
    {
        $this->M_DashboardPlateRejection = new M_DashboardPlateRejection();
        $this->session = \Config\Services::session();
    }

    //CONTROLLER POTONG BATTERY

    public function dashboard_potong_battery()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $start1 = date('Y-m-01');
        $start2 = date('Y-m-01');
        $start3 = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');
        
        // $jenis_dashboard = $this->request->getPost('jenis_dashboard');
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $bulan = $this->request->getPost('bulan');
        $jenis_dashboard = $this->request->getPost('jenis_dashboard');

        if ($shift == null) {
            $shift = '';
        }

        if ($operator == null) {
            $operator = '';
        }

        if ($jenis_dashboard == null) {
            $jenis_dashboard = 1;
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $start1 = date('Y-m-01', strtotime($bulan));
            $start2 = date('Y-m-01', strtotime($bulan));
            $start3 = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

        $data['shift'] = $shift;
        $data['operator'] = $operator;
        $data['bulan'] = $bulan;
        $data['jenis_dashboard'] = $jenis_dashboard;

        // GET DATA PLATE NG BY DATE
        $data['data_plate_ng_by_date'] = [];
        // GET DATA ELEMENT REPAIR BY DATE
        $data['data_element_repair_by_date'] = [];

        while (strtotime($start) <= strtotime($now)) {
            $data_plate_ng_by_date = $this->M_DashboardPlateRejection->get_data_plate_ng_by_date($start, $shift, $operator);
            if (!empty($data_plate_ng_by_date)) {
                foreach ($data_plate_ng_by_date as $d_by_date) {
                    $data_plate_ng = [
                        'panel' => $d_by_date['panel'],
                        'kg' => $d_by_date['kg'],
                    ];
                    
                    $data['data_plate_ng_by_date'][] = $data_plate_ng;
                }
            } else {
                $data_plate_ng = [
                    'panel' => 0,
                    'kg' => 0,
                ];
                
                $data['data_plate_ng_by_date'][] = $data_plate_ng;
            }
            $data_element_repair_by_date = $this->M_DashboardPlateRejection->get_data_element_repair_by_date($start, $shift, $operator);
            if (!empty($data_element_repair_by_date)) {
                foreach ($data_element_repair_by_date as $d_by_date) {
                    $data_element_repair = [
                        'total' => $d_by_date['total'],
                    ];
                    
                    $data['data_element_repair_by_date'][] = $data_element_repair;
                }
            } else {
                $data_element_repair = [
                    'total' => 0,
                ];
                
                $data['data_element_repair_by_date'][] = $data_element_repair;
            }
            $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
        }

        // GET DATA PLATE NG BY MONTH
        $data['data_plate_ng_by_month'] = [];
        // GET DATA ELEMENT REPAIR BY MONTH
        $data['data_element_repair_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_plate_ng_by_month = $this->M_DashboardPlateRejection->get_data_plate_ng_by_month($i, $bulan, $shift, $operator);
            if (!empty($data_plate_ng_by_month)) {
                foreach ($data_plate_ng_by_month as $d_by_date) {
                    $data_plate_ng = [
                        'panel' => $d_by_date['panel'],
                        'kg' => $d_by_date['kg'],
                    ];
                    
                    $data['data_plate_ng_by_month'][] = $data_plate_ng;
                }
            } else {
                $data_plate_ng = [
                    'panel' => 0,
                    'kg' => 0,
                ];
                
                $data['data_plate_ng_by_month'][] = $data_plate_ng;
            }
            $data_element_repair_by_month = $this->M_DashboardPlateRejection->get_data_element_repair_by_month($i, $bulan, $shift, $operator);
            if (!empty($data_element_repair_by_month)) {
                foreach ($data_element_repair_by_month as $d_by_date) {
                    $data_element_repair = [
                        'total' => $d_by_date['total'],
                    ];
                    
                    $data['data_element_repair_by_month'][] = $data_element_repair;
                }
            } else {
                $data_element_repair = [
                    'total' => 0,
                ];
                
                $data['data_element_repair_by_month'][] = $data_element_repair;
            }
        }

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data_operator = $this->M_DashboardPlateRejection->get_data_operator_by_year($bulan);
        foreach ($data_operator as $do) {
            $data['data']['data_plate_ng_by_date_by_' . $do['operator']] = [];
            $data['data']['data_element_repair_by_date_by_' . $do['operator']] = [];
            $data['data']['data_plate_ng_by_month_by_' . $do['operator']] = [];
            $data['data']['data_element_repair_by_month_by_' . $do['operator']] = [];
        }
        $data['data_operator'] = $data_operator;

        while (strtotime($start1) <= strtotime($now)) {
            foreach ($data_operator as $do) {
                $data_plate_ng_per_operator_by_date = $this->M_DashboardPlateRejection->get_data_plate_ng_by_date($start1, $shift, $do['operator']);
                if (!empty($data_plate_ng_per_operator_by_date)) {
                    foreach ($data_plate_ng_per_operator_by_date as $d_by_date) {
                        $data['data']['data_plate_ng_by_date_by_'.$do['operator']][] = $d_by_date['panel'];
                    }
                } else {
                    $data['data']['data_plate_ng_by_date_by_'.$do['operator']][] = 0;
                }
            }

            foreach ($data_operator as $do) {
                $data_element_repair_per_operator_by_date = $this->M_DashboardPlateRejection->get_data_element_repair_by_date($start1, $shift, $do['operator']);
                if (!empty($data_element_repair_per_operator_by_date)) {
                    foreach ($data_element_repair_per_operator_by_date as $d_by_date) {
                        $data['data']['data_element_repair_by_date_by_'.$do['operator']][] = $d_by_date['total'];
                    } 
                } else {
                    $data['data']['data_element_repair_by_date_by_'.$do['operator']][] = 0;
                }
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }

        for ($i=1; $i <= 12; $i++) {
            foreach ($data_operator as $do) {
                $data_plate_ng_by_month = $this->M_DashboardPlateRejection->get_data_plate_ng_by_month($i, $bulan, $shift, $do['operator']);
                if (!empty($data_plate_ng_by_month)) {
                    foreach ($data_plate_ng_by_month as $d_by_date) {
                        $data['data']['data_plate_ng_by_month_by_' . $do['operator']][] = $d_by_date['panel'];
                    }
                } else {
                    $data['data']['data_plate_ng_by_month_by_' . $do['operator']][] = 0;
                }
                $data_element_repair_by_month = $this->M_DashboardPlateRejection->get_data_element_repair_by_month($i, $bulan, $shift, $do['operator']);
                if (!empty($data_element_repair_by_month)) {
                    foreach ($data_element_repair_by_month as $d_by_date) {
                        $data['data']['data_element_repair_by_month_by_' . $do['operator']][] = $d_by_date['total'];
                    }
                } else {
                    $data['data']['data_element_repair_by_month_by_' . $do['operator']][] = 0;
                }
            }
        }

        return view('dashboardPlateRejection/dashboard_potong_battery', $data);
    }

    public function get_data_plate_ng()
    {
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $date = $this->request->getPost('date');

        $data['data_plate_ng_type_by_month'] = $this->M_DashboardPlateRejection->get_data_plate_ng_type_by_month($date, $shift, $operator);
        $data['data_plate_ng_type_by_date'] = $this->M_DashboardPlateRejection->get_data_plate_ng_type_by_date($date, $shift, $operator);

        echo json_encode($data);    
    }

    public function get_data_element_repair()
    {
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $date = $this->request->getPost('date');

        $data['data_element_repair_type_by_month'] = $this->M_DashboardPlateRejection->get_data_element_repair_type_by_month($date, $shift, $operator);
        $data['data_element_repair_type_by_date'] = $this->M_DashboardPlateRejection->get_data_element_repair_type_by_date($date, $shift, $operator);

        echo json_encode($data);
    }

    //CONTROLLER ENVELOPE

    public function dashboard_envelope()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $start1 = date('Y-m-01');
        $start2 = date('Y-m-01');
        $start3 = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');
        
        $jenis_dashboard = $this->request->getPost('jenis_dashboard');
        $parent_filter = $this->request->getPost('parent_filter');
        $child_filter = $this->request->getPost('child_filter');
        $baby_filter = $this->request->getPost('baby_filter');
        $bulan = $this->request->getPost('bulan');

        if ($jenis_dashboard == null) {
            $jenis_dashboard = 1;
        }

        if ($parent_filter == null) {
            $parent_filter = 'line';
        }

        if ($child_filter == null) {
            $child_filter = 0;
        }

        if ($baby_filter == null) {
            $baby_filter = 'average';
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $start1 = date('Y-m-01', strtotime($bulan));
            $start2 = date('Y-m-01', strtotime($bulan));
            $start3 = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

       
        $data['jenis_dashboard'] = $jenis_dashboard;
        $data['parent_filter'] = $parent_filter;
        $data['child_filter'] = $child_filter;
        $data['baby_filter'] = $baby_filter;
        $data['bulan'] = $bulan;

        $target = $this->M_DashboardPlateRejection->get_data_target_by_year('plate_rejection', 'envelope', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardPlateRejection->get_data_target_last_year('plate_rejection', 'envelope', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardPlateRejection->get_data_target_first_year('plate_rejection', 'envelope', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        // GET DATA REJECT BY MONTH
        $data['data_envelope_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_envelope_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_panel = $d_all['total_panel'];
                    $total_produksi = $d_all['total_produksi'];
                    $eff = (!empty($total_panel) && !empty($total_produksi)) ? ($total_panel / $total_produksi) * 100 : 0;
                    array_push($data['data_envelope_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_envelope_all_month'], 0);
            }
        }
        

        // GET DATA PARETO REJECT BY LINE
        $data['data_envelope_by_line'] = [];
        $data['data_total_envelope_by_line'] = [];

        $data_line = $this->M_DashboardPlateRejection->get_data_total_envelope_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_envelope_by_line'], 'Line '.$d_line['line']);
            $data['data_total_envelope_by_line'][] = [
                'persen' => (float) number_format($d_line['persen'], 2, '.', ''),
                'kg' => $d_line['kg']
            ];
        }

        $data_year = $this->M_DashboardPlateRejection->get_year_to_date_envelope($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_panel']) && !empty($data_year[0]['total_produksi'])) ? (float) number_format(($data_year[0]['total_panel'] / $data_year[0]['total_produksi']) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_envelope_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_envelope_by_date_all_line = $this->M_DashboardPlateRejection->get_data_envelope_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_envelope_by_date_all_line)) {
                foreach ($data_average_envelope_by_date_all_line as $da) {
                    $data['data_average_envelope_by_date_all_line'][] = [
                        'persentase' => (float) number_format($da['persentase'] * 100, 2, '.', ''),
                        'kg' => $da['kg']
                    ];
                }
            } else {
                $data['data_average_envelope_by_date_all_line'][] = [
                    'persentase' => 0,
                    'kg' => 0
                ];
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_envelope_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_average_envelope_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $data['data_average_envelope_by_month'][] = [
                        'persentase' => (float) number_format($d_all['persentase'] * 100, 2, '.', ''),
                        'kg' => (float) number_format($d_all['kg'], 2, '.', '')
                    ];
                }
            } else {
                $data['data_average_envelope_by_month'][] = [
                    'persentase' => 0,
                    'kg' => 0
                ];
            }
        }

        // GET DATA AVERAGE ENVELOPE BY DATE ALL LINE
        $data['data_envelope_line_1'] = [];
        $data['data_envelope_line_2'] = [];
        $data['data_envelope_line_3'] = [];
        $data['data_envelope_line_4'] = [];
        $data['data_envelope_line_5'] = [];
        $data['data_envelope_line_6'] = [];
        $data['data_envelope_line_7'] = [];

        while (strtotime($start2) <= strtotime($now)) {
            for ($i=1; $i <= 7; $i++) { 
                $data_envelope_per_line_by_date = $this->M_DashboardPlateRejection->get_data_envelope_all_line($start2, $i);
                if (!empty($data_envelope_per_line_by_date)) {
                    foreach ($data_envelope_per_line_by_date as $d1) {
                        $total_panel = $d1['total_panel'];
                        $total_produksi = $d1['total_produksi'];
                        $eff = (!empty($total_panel) && !empty($total_produksi)) ? ($total_panel / $total_produksi) * 100 : 0;
                        array_push($data['data_envelope_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    } 
                } else {
                    array_push($data['data_envelope_line_'.$i], 0);
                }
            }

            $start2 = date ("Y-m-d", strtotime("+1 days", strtotime($start2)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_envelope_by_month_line_1'] = [];
        $data['data_envelope_by_month_line_2'] = [];
        $data['data_envelope_by_month_line_3'] = [];
        $data['data_envelope_by_month_line_4'] = [];
        $data['data_envelope_by_month_line_5'] = [];
        $data['data_envelope_by_month_line_6'] = [];
        $data['data_envelope_by_month_line_7'] = [];

        for ($h=1; $h <= 12; $h++) { 
            for ($i=1; $i <= 7; $i++) {
                $data_envelope_all_line_by_month = $this->M_DashboardPlateRejection->get_data_envelope_all_line_by_month($h, $i);
                if (!empty($data_envelope_all_line_by_month)) {
                    foreach ($data_envelope_all_line_by_month as $dalm) {
                        $total_panel = $dalm['total_panel'];
                        $total_produksi = $dalm['total_produksi'];
                        $eff = (!empty($total_panel) && !empty($total_produksi)) ? ($total_panel / $total_produksi) * 100 : 0;
                        array_push($data['data_envelope_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_envelope_by_month_line_'.$i], 0);
                }
            }
        }

        return view('dashboardPlateRejection/dashboard_envelope', $data);
    }

    public function get_detail_envelope()
    {
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');

        $data['data_qty_jenis_envelope'] = $this->M_DashboardPlateRejection->get_qty_jenis_envelope($date, $line);
        $data['data_kg_jenis_envelope'] = $this->M_DashboardPlateRejection->get_kg_jenis_envelope($date, $line);

        $data['data_qty_jenis_envelope_by_month'] = $this->M_DashboardPlateRejection->get_data_qty_envelope_by_month($date, $line);
        $data['data_kg_jenis_envelope_by_month'] = $this->M_DashboardPlateRejection->get_data_kg_envelope_by_month($date, $line);

        $data['data_jenis_envelope_by_date'] = $this->M_DashboardPlateRejection->get_jenis_envelope_by_date($date, $line);
        $data['data_jenis_envelope_by_month'] = $this->M_DashboardPlateRejection->get_jenis_envelope_by_month($date, $line);

        echo json_encode($data);    
    }

    //CONTROLLER COS

    public function dashboard_cos()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $start1 = date('Y-m-01');
        $start2 = date('Y-m-01');
        $start3 = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');
        
        $jenis_dashboard = $this->request->getPost('jenis_dashboard');
        $parent_filter = $this->request->getPost('parent_filter');
        $child_filter = $this->request->getPost('child_filter');
        $baby_filter = $this->request->getPost('baby_filter');
        $bulan = $this->request->getPost('bulan');

        if ($jenis_dashboard == null) {
            $jenis_dashboard = 1;
        }

        if ($parent_filter == null) {
            $parent_filter = 'line';
        }

        if ($child_filter == null) {
            $child_filter = 0;
        }

        if ($baby_filter == null) {
            $baby_filter = 'average';
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $start1 = date('Y-m-01', strtotime($bulan));
            $start2 = date('Y-m-01', strtotime($bulan));
            $start3 = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

       
        $data['jenis_dashboard'] = $jenis_dashboard;
        $data['parent_filter'] = $parent_filter;
        $data['child_filter'] = $child_filter;
        $data['baby_filter'] = $baby_filter;
        $data['bulan'] = $bulan;

        $target = $this->M_DashboardPlateRejection->get_data_target_by_year('plate_rejection', 'envelope', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardPlateRejection->get_data_target_last_year('plate_rejection', 'envelope', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardPlateRejection->get_data_target_first_year('plate_rejection', 'envelope', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        // GET DATA COS BY MONTH
        $data['data_cos_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_cos_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_plate = $d_all['total_plate'];
                    $total_produksi = $d_all['total_produksi'];
                    $eff = (!empty($total_plate) && !empty($total_produksi)) ? ($total_plate / $total_produksi) * 100 : 0;
                    array_push($data['data_cos_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_cos_all_month'], 0);
            }
        }
        

        // GET DATA PARETO REJECT BY LINE
        $data['data_cos_by_line'] = [];
        $data['data_total_cos_by_line'] = [];

        $data_line = $this->M_DashboardPlateRejection->get_data_total_cos_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            $data['data_total_cos_by_line'][] = [
                'persen' => (!empty($d_line['total_plate']) && !empty($d_line['total_produksi'])) ? (float) number_format(($d_line['total_plate'] / $d_line['total_produksi'] * 100), 2, '.', '') : 0,
                'panel' => $d_line['total_plate'],
                'line' => $d_line['line'],
            ];
        }
        $panelColumn = array_column($data['data_total_cos_by_line'], 'persen');
        array_multisort($panelColumn, SORT_DESC, $data['data_total_cos_by_line']);
        foreach ($data['data_total_cos_by_line'] as $dt_cos) {
            array_push($data['data_cos_by_line'], 'Line '.$dt_cos['line']);
        }

        $data_year = $this->M_DashboardPlateRejection->get_year_to_date_cos($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_plate']) && !empty($data_year[0]['total_produksi'])) ? (float) number_format(($data_year[0]['total_plate'] / $data_year[0]['total_produksi']) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_cos_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_cos_by_date_all_line = $this->M_DashboardPlateRejection->get_data_cos_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_cos_by_date_all_line)) {
                foreach ($data_average_cos_by_date_all_line as $da) {
                    $data['data_average_cos_by_date_all_line'][] = [
                        'persentase' => (!empty($da['total_plate']) && !empty($da['total_produksi'])) ? (float) number_format(($da['total_plate'] / $da['total_produksi']) * 100, 2, '.', '') : 0,
                        'panel' => $da['total_plate']
                    ];
                }
            } else {
                $data['data_average_cos_by_date_all_line'][] = [
                    'persentase' => 0,
                    'panel' => 0
                ];
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_cos_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_average_cos_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $data['data_average_cos_by_month'][] = [
                        'persentase' => (!empty($d_all['total_plate']) && !empty($d_all['total_produksi'])) ? (float) number_format(($d_all['total_plate'] / $d_all['total_produksi']) * 100, 2, '.', '') : 0,
                        'panel' => (float) number_format($d_all['total_plate'], 2, '.', '')
                    ];
                }
            } else {
                $data['data_average_cos_by_month'][] = [
                    'persentase' => 0,
                    'panel' => 0
                ];
            }
        }

        // GET DATA AVERAGE ENVELOPE BY DATE ALL LINE
        $data['data_cos_line_1'] = [];
        $data['data_cos_line_2'] = [];
        $data['data_cos_line_3'] = [];
        $data['data_cos_line_4'] = [];
        $data['data_cos_line_5'] = [];
        $data['data_cos_line_6'] = [];
        $data['data_cos_line_7'] = [];

        while (strtotime($start2) <= strtotime($now)) {
            for ($i=1; $i <= 7; $i++) { 
                $data_cos_per_line_by_date = $this->M_DashboardPlateRejection->get_data_cos_all_line($start2, $i);
                if (!empty($data_cos_per_line_by_date)) {
                    foreach ($data_cos_per_line_by_date as $d1) {
                        $total_plate = $d1['total_plate'];
                        $total_produksi = $d1['total_produksi'];
                        $eff = (!empty($total_plate) && !empty($total_produksi)) ? ($total_plate / $total_produksi) * 100 : 0;
                        array_push($data['data_cos_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    } 
                } else {
                    array_push($data['data_cos_line_'.$i], 0);
                }
            }

            $start2 = date ("Y-m-d", strtotime("+1 days", strtotime($start2)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_cos_by_month_line_1'] = [];
        $data['data_cos_by_month_line_2'] = [];
        $data['data_cos_by_month_line_3'] = [];
        $data['data_cos_by_month_line_4'] = [];
        $data['data_cos_by_month_line_5'] = [];
        $data['data_cos_by_month_line_6'] = [];
        $data['data_cos_by_month_line_7'] = [];

        for ($h=1; $h <= 12; $h++) { 
            for ($i=1; $i <= 7; $i++) {
                $data_cos_all_line_by_month = $this->M_DashboardPlateRejection->get_data_cos_all_line_by_month($h, $i);
                if (!empty($data_cos_all_line_by_month)) {
                    foreach ($data_cos_all_line_by_month as $dalm) {
                        $total_plate = $dalm['total_plate'];
                        $total_produksi = $dalm['total_produksi'];
                        $eff = (!empty($total_plate) && !empty($total_produksi)) ? ($total_plate / $total_produksi) * 100 : 0;
                        array_push($data['data_cos_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_cos_by_month_line_'.$i], 0);
                }
            }
        }

        return view('dashboardPlateRejection/dashboard_cos', $data);
    }

    public function get_detail_cos()
    {
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');

        $data['data_qty_type_battery_cos'] = $this->M_DashboardPlateRejection->get_qty_jenis_cos($date, $line);
        $data['data_qty_type_battery_cos_by_month'] = $this->M_DashboardPlateRejection->get_data_qty_cos_by_month($date, $line);

        $data['data_type_battery_cos_by_date'] = $this->M_DashboardPlateRejection->get_jenis_cos_by_date($date, $line);
        $data['data_type_battery_cos_by_month'] = $this->M_DashboardPlateRejection->get_jenis_cos_by_month($date, $line);

        echo json_encode($data);    
    }

    //CONTROLLER REJECT PLATE CUTTING

    public function dashboard_reject_plate_cutting()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $start1 = date('Y-m-01');
        $start2 = date('Y-m-01');
        $start3 = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');
        
        $jenis_dashboard = $this->request->getPost('jenis_dashboard');
        $parent_filter = $this->request->getPost('parent_filter');
        $child_filter = $this->request->getPost('child_filter');
        $baby_filter = $this->request->getPost('baby_filter');
        $bulan = $this->request->getPost('bulan');

        if ($jenis_dashboard == null) {
            $jenis_dashboard = 1;
        }

        if ($parent_filter == null) {
            $parent_filter = 'line';
        }

        if ($child_filter == null) {
            $child_filter = 0;
        }

        if ($baby_filter == null) {
            $baby_filter = 'average';
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $start1 = date('Y-m-01', strtotime($bulan));
            $start2 = date('Y-m-01', strtotime($bulan));
            $start3 = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

       
        $data['jenis_dashboard'] = $jenis_dashboard;
        $data['parent_filter'] = $parent_filter;
        $data['child_filter'] = $child_filter;
        $data['baby_filter'] = $baby_filter;
        $data['bulan'] = $bulan;

        $target = $this->M_DashboardPlateRejection->get_data_target_by_year('plate_rejection', 'plate_cutting', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardPlateRejection->get_data_target_last_year('plate_rejection', 'plate_cutting', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardPlateRejection->get_data_target_first_year('plate_rejection', 'plate_cutting', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        $data_reject_by_month = $this->M_DashboardPlateRejection->get_data_qty_reject_internal_by_month(idate('m',strtotime($bulan)), $child_filter);

        // GET DATA REJECT BY MONTH
        $data['data_reject_by_month'] = [];
        $data['data_total_reject_by_month'] = [];

        // GET DATA REJECT BY MONTH
        $data['data_reject_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_rejection_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_produksi = $d_all['total_produksi'];
                    $eff = (!empty($total_reject) && !empty($total_produksi)) ? ($total_reject / $total_produksi) * 100 : 0;
                    array_push($data['data_reject_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_reject_all_month'], 0);
            }
        }
        

        // GET DATA PARETO REJECT BY LINE
        $data['data_reject_by_line'] = [];
        $data['data_total_reject_by_line'] = [];

        $data_line = $this->M_DashboardPlateRejection->get_data_total_reject_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_reject_by_line'], 'Line '.$d_line['line']);
            $data['data_total_reject_by_line'][] = [
                'persen' => (float) number_format($d_line['persen'], 2, '.', ''),
                'kg' => $d_line['kg']
            ];
        }

        $data_year = $this->M_DashboardPlateRejection->get_year_to_date_rejection($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_reject']) && !empty($data_year[0]['total_produksi'])) ? (float) number_format(($data_year[0]['total_reject'] / $data_year[0]['total_produksi']) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_reject_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_reject_by_date_all_line = $this->M_DashboardPlateRejection->get_data_reject_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_reject_by_date_all_line)) {
                foreach ($data_average_reject_by_date_all_line as $da) {
                    $data['data_average_reject_by_date_all_line'][] = [
                        'persentase_internal' => (float) number_format($da['persentase_internal'] * 100, 2, '.', ''),
                        'persentase_eksternal' => (float) number_format($da['persentase_eksternal'] * 100, 2, '.', ''),
                        'kg' => $da['kg']
                    ];
                }
            } else {
                $data['data_average_reject_by_date_all_line'][] = [
                    'persentase_internal' => 0,
                    'persentase_eksternal' => 0,
                    'kg' => 0
                ];
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_reject_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPlateRejection->get_data_average_reject_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $data['data_average_reject_by_month'][] = [
                        'persentase_internal' => (float) number_format($d_all['persentase_internal'] * 100, 2, '.', ''),
                        'persentase_eksternal' => (float) number_format($d_all['persentase_eksternal'] * 100, 2, '.', ''),
                        'kg' => (float) number_format($d_all['kg'], 2, '.', '')
                    ];
                }
            } else {
                $data['data_average_reject_by_month'][] = [
                    'persentase_internal' => 0,
                    'persentase_eksternal' => 0,
                    'kg' => 0
                ];
            }
        }

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_reject_line_1'] = [];
        $data['data_reject_line_2'] = [];
        $data['data_reject_line_3'] = [];
        $data['data_reject_line_4'] = [];
        $data['data_reject_line_5'] = [];
        $data['data_reject_line_6'] = [];
        $data['data_reject_line_7'] = [];

        while (strtotime($start2) <= strtotime($now)) {
            for ($i=1; $i <= 7; $i++) { 
                $data_reject_per_line_by_date = $this->M_DashboardPlateRejection->get_data_reject_all_line($start2, $i);
                if (!empty($data_reject_per_line_by_date)) {
                    foreach ($data_reject_per_line_by_date as $d1) {
                        $total_reject = $d1['total_reject'];
                        $total_produksi = $d1['total_produksi'];
                        $eff = (!empty($total_reject) && !empty($total_produksi)) ? ($total_reject / $total_produksi) * 100 : 0;
                        array_push($data['data_reject_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    } 
                } else {
                    array_push($data['data_reject_line_'.$i], 0);
                }
            }

            $start2 = date ("Y-m-d", strtotime("+1 days", strtotime($start2)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_reject_by_month_line_1'] = [];
        $data['data_reject_by_month_line_2'] = [];
        $data['data_reject_by_month_line_3'] = [];
        $data['data_reject_by_month_line_4'] = [];
        $data['data_reject_by_month_line_5'] = [];
        $data['data_reject_by_month_line_6'] = [];
        $data['data_reject_by_month_line_7'] = [];

        for ($h=1; $h <= 12; $h++) { 
            for ($i=1; $i <= 7; $i++) {
                $data_reject_all_line_by_month = $this->M_DashboardPlateRejection->get_data_reject_all_line_by_month($h, $i);
                if (!empty($data_reject_all_line_by_month)) {
                    foreach ($data_reject_all_line_by_month as $dalm) {
                        $total_reject = $dalm['total_reject'];
                        $total_produksi = $dalm['total_produksi'];
                        $eff = (!empty($total_reject) && !empty($total_produksi)) ? ($total_reject / $total_produksi) * 100 : 0;
                        array_push($data['data_reject_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_reject_by_month_line_'.$i], 0);
                }
            }
        }

        return view('dashboardPlateRejection/dashboard_reject_plate_cutting', $data);
    }

    public function get_detail_rejection()
    {
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $shift = $this->request->getPost('shift');

        $data['data_qty_jenis_reject_internal'] = $this->M_DashboardPlateRejection->get_qty_jenis_reject_internal($date, $line);
        $data['data_qty_jenis_reject_eksternal'] = $this->M_DashboardPlateRejection->get_qty_jenis_reject_eksternal($date, $line);
        $data['data_kg_jenis_reject_internal'] = $this->M_DashboardPlateRejection->get_kg_jenis_reject_internal($date, $line);
        $data['data_kg_jenis_reject_eksternal'] = $this->M_DashboardPlateRejection->get_kg_jenis_reject_eksternal($date, $line);

        $data['data_qty_jenis_reject_internal_by_month'] = $this->M_DashboardPlateRejection->get_data_qty_reject_internal_by_month($date, $line);
        $data['data_qty_jenis_reject_eksternal_by_month'] = $this->M_DashboardPlateRejection->get_data_qty_reject_eksternal_by_month($date, $line);
        $data['data_kg_jenis_reject_internal_by_month'] = $this->M_DashboardPlateRejection->get_data_kg_reject_internal_by_month($date, $line);
        $data['data_kg_jenis_reject_eksternal_by_month'] = $this->M_DashboardPlateRejection->get_data_kg_reject_eksternal_by_month($date, $line);

        $data['data_jenis_reject_by_date'] = $this->M_DashboardPlateRejection->get_jenis_reject_by_date($date, $line);
        $data['data_jenis_reject_by_month'] = $this->M_DashboardPlateRejection->get_jenis_reject_by_month($date, $line);

        echo json_encode($data);    
    }
}