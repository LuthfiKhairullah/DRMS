<?php

namespace App\Controllers;

use App\Models\M_DashboardAssyRejection;

class DashboardAssyRejection extends BaseController
{
    public function __construct()
    {
        $this->M_DashboardAssyRejection = new M_DashboardAssyRejection();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('dashboard/home');
    }

    public function dashboard_reject_assy()
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

        $target = $this->M_DashboardAssyRejection->get_data_target_by_year('rejection', 'assembling', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardAssyRejection->get_data_target_last_year('rejection', 'assembling', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardAssyRejection->get_data_target_first_year('rejection', 'assembling', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        // $total_data_reject_by_month = $this->M_DashboardAssyRejection->get_total_data_reject_by_month(idate('m',strtotime($bulan)));
        // $total_aktual_by_month = $this->M_DashboardAssyRejection->get_total_aktual_by_month(idate('m',strtotime($bulan)));
        $data_reject_by_month = $this->M_DashboardAssyRejection->get_data_reject_by_month($bulan, $child_filter);

        // GET DATA REJECT BY MONTH
        $data['data_reject_by_month'] = [];
        $data['data_jenis_reject_by_month'] = [];
        $data['data_total_reject_by_month'] = [];

        // foreach ($data_reject_by_month as $d_reject_by_month) {
        //     $data_reject = [
        //         'name' => $d_reject_by_month['jenis_reject'],
        //         'y' => (float) number_format(((int) $d_reject_by_month['qty'] / (int) $total_aktual_by_month[0]['total_aktual']) * 100, 2, '.', ''),
        //     ];
            
        //     $data['data_reject_by_month'][] = $data_reject;
        // }

        foreach ($data_reject_by_month as $d_reject_by_month) {
            array_push($data['data_jenis_reject_by_month'], $d_reject_by_month['jenis_reject']);
            array_push($data['data_total_reject_by_month'], $d_reject_by_month['qty']);
        }

        // GET DATA REJECT BY DATE
        $data['data_reject_by_date'] = [];

        $data_jenis_reject = $this->M_DashboardAssyRejection->get_jenis_reject_by_month($start, $child_filter);

        if (!empty($data_jenis_reject)) {
            while (strtotime($start) <= strtotime($now)) {
                foreach ($data_jenis_reject as $d_jenis_reject) {
                    $data_jenis_reject_by_date = $this->M_DashboardAssyRejection->get_data_reject_by_date($start, $d_jenis_reject['jenis_reject'], $child_filter);
                    if (!empty($data_jenis_reject_by_date)) {
                        foreach ($data_jenis_reject_by_date as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_reject'],
                                'data' => (int) $d_jenis_reject_by_date['qty']
                            ];
                            
                            $data['data_reject_by_date'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_reject'],
                            'data' => 0
                        ];
                        
                        $data['data_reject_by_date'][] = $data_reject;
                    }
                }    
                $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
            }
        }

        // GET DATA REJECT BY MONTH
        $data['data_reject_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_rejection_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_reject_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_reject_all_month'], 0);
            }
        }
        

        // GET DATA PARETO REJECT BY LINE
        $data['data_reject_by_line'] = [];
        $data['data_total_reject_by_line'] = [];

        $data_line = $this->M_DashboardAssyRejection->get_data_total_reject_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_reject_by_line'], 'Line '.$d_line['line']);
            array_push($data['data_total_reject_by_line'], (!empty($d_line['total_aktual'])) ? (float) number_format(($d_line['total_reject'] / ($d_line['total_aktual']+$d_line['total_reject'])) * 100, 2, '.', '') : 0);
        }

        $data_year = $this->M_DashboardAssyRejection->get_year_to_date_rejection($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_reject']) && !empty($data_year[0]['total_aktual'])) ? (float) number_format(($data_year[0]['total_reject'] / ($data_year[0]['total_aktual']+$data_year[0]['total_reject'])) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_reject_by_date_all_line'] = [];
        $data['data_qty_reject_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_reject_by_date_all_line = $this->M_DashboardAssyRejection->get_data_reject_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_reject_by_date_all_line)) {
                foreach ($data_average_reject_by_date_all_line as $da) {
                    $total_reject = $da['total_reject'];
                    $total_aktual = $da['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_average_reject_by_date_all_line'], (float) number_format($eff, 2, '.', ''));
                    array_push($data['data_qty_reject_by_date_all_line'], (int) $total_reject);
                } 
            } else {
                array_push($data['data_average_reject_by_date_all_line'], 0);
                array_push($data['data_qty_reject_by_date_all_line'], 0);
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_reject_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_average_reject_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_average_reject_by_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_average_reject_by_month'], 0);
            }
        }

        // GET DATA QTY REJECT BY MONTH ALL LINE
        $data['data_qty_reject_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_average_reject_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    // $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / $total_aktual) * 100 : 0;
                    array_push($data['data_qty_reject_by_month'], $total_reject);
                }
            } else {
                array_push($data['data_qty_reject_by_month'], 0);
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
                $data_reject_per_line_by_date = $this->M_DashboardAssyRejection->get_data_reject_all_line($start2, $i);
                if (!empty($data_reject_per_line_by_date)) {
                    foreach ($data_reject_per_line_by_date as $d1) {
                        $total_reject = $d1['total_reject'];
                        $total_aktual = $d1['total_aktual'];
                        $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
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
                $data_reject_all_line_by_month = $this->M_DashboardAssyRejection->get_data_reject_all_line_by_month($bulan, $h, $i);
                if (!empty($data_reject_all_line_by_month)) {
                    foreach ($data_reject_all_line_by_month as $dalm) {
                        $total_reject = $dalm['total_reject'];
                        $total_aktual = $dalm['total_aktual'];
                        $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                        array_push($data['data_reject_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_reject_by_month_line_'.$i], 0);
                }
            }
        }

        // GET DATA REJECT BY DATE PERSENTASE
        $data['data_reject_by_date_persentase'] = [];

        $data_jenis_reject_persentase = $this->M_DashboardAssyRejection->get_jenis_reject_by_month($start3, $child_filter);

        if (!empty($data_jenis_reject_persentase)) {
            while (strtotime($start3) <= strtotime($now)) {
                foreach ($data_jenis_reject_persentase as $d_jenis_reject) {
                    $data_jenis_reject_by_date_persentase = $this->M_DashboardAssyRejection->get_data_reject_by_date($start3, $d_jenis_reject['jenis_reject'], $child_filter);
                    $data_reject_per_line_by_date = $this->M_DashboardAssyRejection->get_data_reject_all_line($start3, $child_filter);
                    if (!empty($data_jenis_reject_by_date_persentase)) {
                        foreach ($data_jenis_reject_by_date_persentase as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_reject'],
                                'data' => ($data_reject_per_line_by_date[0]['total_aktual'] == 0) ? 0 : (float) number_format(($d_jenis_reject_by_date['qty'] / ($data_reject_per_line_by_date[0]['total_aktual'] + $d_jenis_reject_by_date['qty'])) * 100, 2, '.', '')
                            ];
                            
                            $data['data_reject_by_date_persentase'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_reject'],
                            'data' => 0
                        ];
                        
                        $data['data_reject_by_date_persentase'][] = $data_reject;
                    }
                }    
                $start3 = date ("Y-m-d", strtotime("+1 days", strtotime($start3)));
            }
        }

        return view('dashboard/dashboard_lhp_assy_rejection', $data);
    }

    public function get_detail_rejection()
    {
        $jenis_reject = $this->request->getPost('jenis_reject');
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $type_battery = $this->request->getPost('type_battery');
        $grup = $this->request->getPost('grup');
        $shift = $this->request->getPost('shift');

        $data['total_aktual_by_month'] = $this->M_DashboardAssyRejection->get_total_aktual_by_month($date, $line);
        $data['total_aktual_by_date'] = $this->M_DashboardAssyRejection->get_total_aktual_by_date($date, $line);

        $data['data_jenis_reject'] = $this->M_DashboardAssyRejection->get_qty_jenis_reject($date, $line);
        $data['data_jenis_reject_by_month'] = $this->M_DashboardAssyRejection->get_data_reject_by_month($date, $line);

        $data['data_reject_by_jenis_reject'] = $this->M_DashboardAssyRejection->get_detail_rejection_by_jenis($jenis_reject, $date, $line);
        $data['data_reject_by_type_battery'] = $this->M_DashboardAssyRejection->get_detail_rejection_by_type_battery($jenis_reject, $date, $line);
        $data['data_reject_by_grup'] = $this->M_DashboardAssyRejection->get_detail_rejection_by_grup($jenis_reject, $date, $line);

        $data['data_jenis_reject_by_type_battery'] = $this->M_DashboardAssyRejection->get_jenis_reject_by_type_battery($type_battery, $date, $line);
        $data['data_kategori_reject_by_type_battery'] = $this->M_DashboardAssyRejection->get_kategori_reject_by_type_battery($type_battery, $date, $line);
        $data['data_grup_reject_by_type_battery'] = $this->M_DashboardAssyRejection->get_grup_reject_by_type_battery($type_battery, $date, $line);

        $data['data_jenis_reject_by_grup_shift'] = $this->M_DashboardAssyRejection->get_jenis_reject_by_grup_shift($grup, $shift, $date, $line);
        $data['data_kategori_reject_by_grup_shift'] = $this->M_DashboardAssyRejection->get_kategori_reject_by_grup_shift($grup, $shift, $date, $line);
        $data['data_battery_reject_by_grup_shift'] = $this->M_DashboardAssyRejection->get_battery_reject_by_grup_shift($grup, $shift, $date, $line);

        $data['data_all_detail_kategori_rejection_by_date'] = $this->M_DashboardAssyRejection->get_all_detail_kategori_rejection_by_date($date, $line);
        $data['data_all_detail_battery_rejection_by_date'] = $this->M_DashboardAssyRejection->get_all_detail_battery_rejection_by_date($date, $line);
        $data['data_all_detail_grup_rejection_by_date'] = $this->M_DashboardAssyRejection->get_all_detail_grup_rejection_by_date($date, $line);

        $data['data_reject_by_jenis_reject_by_month'] = $this->M_DashboardAssyRejection->get_detail_rejection_by_jenis_by_month($jenis_reject, $date, $line);
        $data['data_reject_by_type_battery_by_month'] = $this->M_DashboardAssyRejection->get_detail_rejection_by_type_battery_by_month($jenis_reject, $date, $line);

        $data['data_all_detail_kategori_rejection_by_month'] = $this->M_DashboardAssyRejection->get_all_detail_kategori_rejection_by_month($date, $line);
        $data['data_all_detail_battery_rejection_by_month'] = $this->M_DashboardAssyRejection->get_all_detail_battery_rejection_by_month($date, $line);
        $data['data_all_detail_grup_rejection_by_month'] = $this->M_DashboardAssyRejection->get_all_detail_grup_rejection_by_month($date, $line);

        $data['detail_summary_rejection'] = $this->M_DashboardAssyRejection->get_detail_summary_rejection($jenis_reject, $date, $line);

        echo json_encode($data);    
    }

    public function dashboard_reject_assy_qc()
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
            $bulan = date('Y-m-d');
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

        // $total_data_reject_by_month = $this->M_DashboardAssyRejection->get_total_data_reject_by_month(idate('m',strtotime($bulan)));
        // $total_aktual_by_month = $this->M_DashboardAssyRejection->get_total_aktual_by_month(idate('m',strtotime($bulan)));
        $data_reject_by_month = $this->M_DashboardAssyRejection->get_data_reject_by_month($bulan, $child_filter);

        // GET DATA REJECT BY MONTH
        $data['data_reject_by_month'] = [];
        $data['data_jenis_reject_by_month'] = [];
        $data['data_total_reject_by_month'] = [];

        // foreach ($data_reject_by_month as $d_reject_by_month) {
        //     $data_reject = [
        //         'name' => $d_reject_by_month['jenis_reject'],
        //         'y' => (float) number_format(((int) $d_reject_by_month['qty'] / (int) $total_aktual_by_month[0]['total_aktual']) * 100, 2, '.', ''),
        //     ];
            
        //     $data['data_reject_by_month'][] = $data_reject;
        // }

        foreach ($data_reject_by_month as $d_reject_by_month) {
            array_push($data['data_jenis_reject_by_month'], $d_reject_by_month['jenis_reject']);
            array_push($data['data_total_reject_by_month'], $d_reject_by_month['qty']);
        }

        // GET DATA REJECT BY DATE
        $data['data_reject_by_date'] = [];

        $data_jenis_reject = $this->M_DashboardAssyRejection->get_jenis_reject_by_month($start, $child_filter);

        if (!empty($data_jenis_reject)) {
            while (strtotime($start) <= strtotime($now)) {
                foreach ($data_jenis_reject as $d_jenis_reject) {
                    $data_jenis_reject_by_date = $this->M_DashboardAssyRejection->get_data_reject_by_date($start, $d_jenis_reject['jenis_reject'], $child_filter);
                    if (!empty($data_jenis_reject_by_date)) {
                        foreach ($data_jenis_reject_by_date as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_reject'],
                                'data' => (int) $d_jenis_reject_by_date['qty']
                            ];
                            
                            $data['data_reject_by_date'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_reject'],
                            'data' => 0
                        ];
                        
                        $data['data_reject_by_date'][] = $data_reject;
                    }
                }    
                $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
            }
        }

        // GET DATA REJECT BY MONTH
        $data['data_reject_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_rejection_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_reject_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_reject_all_month'], 0);
            }
        }
        
        // GET DATA PARETO REJECT BY LINE BY DATE
        $data['data_reject_by_line_by_date'] = [];
        $data['data_total_reject_by_line_by_date'] = [];

        $data_line_by_date = $this->M_DashboardAssyRejection->get_data_total_reject_line_by_date($bulan);

        foreach ($data_line_by_date as $d_line) {
            array_push($data['data_reject_by_line_by_date'], 'Line '.$d_line['line']);
            array_push($data['data_total_reject_by_line_by_date'], (!empty($d_line['total_aktual'])) ? (float) number_format(($d_line['total_reject'] / ($d_line['total_aktual']+$d_line['total_reject'])) * 100, 2, '.', '') : 0);
        }

        // GET DATA PARETO REJECT BY LINE
        $data['data_reject_by_line'] = [];
        $data['data_total_reject_by_line'] = [];

        $data_line = $this->M_DashboardAssyRejection->get_data_total_reject_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_reject_by_line'], 'Line '.$d_line['line']);
            array_push($data['data_total_reject_by_line'], (!empty($d_line['total_aktual'])) ? (float) number_format(($d_line['total_reject'] / ($d_line['total_aktual']+$d_line['total_reject'])) * 100, 2, '.', '') : 0);
        }

        $data_year = $this->M_DashboardAssyRejection->get_year_to_date_rejection($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_reject']) && !empty($data_year[0]['total_aktual'])) ? (float) number_format(($data_year[0]['total_reject'] / ($data_year[0]['total_aktual']+$data_year[0]['total_reject'])) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_reject_by_date_all_line'] = [];
        $data['data_average_reject_by_date_all_line_pcs'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_reject_by_date_all_line = $this->M_DashboardAssyRejection->get_data_reject_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_reject_by_date_all_line)) {
                foreach ($data_average_reject_by_date_all_line as $da) {
                    $total_reject = $da['total_reject'];
                    $total_aktual = $da['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_average_reject_by_date_all_line'], (float) number_format($eff, 2, '.', ''));
                    array_push($data['data_average_reject_by_date_all_line_pcs'], (int) $total_reject);
                } 
            } else {
                array_push($data['data_average_reject_by_date_all_line'], 0);
                array_push($data['data_average_reject_by_date_all_line_pcs'], 0);

            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_reject_by_month'] = [];
        $data['data_average_reject_by_month_pcs'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_average_reject_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_average_reject_by_month'], (float) number_format($eff, 2, '.', ''));
                    array_push($data['data_average_reject_by_month_pcs'], (int) $total_reject);
                }
            } else {
                array_push($data['data_average_reject_by_month'], 0);
                array_push($data['data_average_reject_by_month_pcs'], 0);
            }
        }

        // GET DATA QTY REJECT BY MONTH ALL LINE
        $data['data_qty_reject_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyRejection->get_data_average_reject_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    // $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / $total_aktual) * 100 : 0;
                    array_push($data['data_qty_reject_by_month'], $total_reject);
                }
            } else {
                array_push($data['data_qty_reject_by_month'], 0);
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
                $data_reject_per_line_by_date = $this->M_DashboardAssyRejection->get_data_reject_all_line($start2, $i);
                if (!empty($data_reject_per_line_by_date)) {
                    foreach ($data_reject_per_line_by_date as $d1) {
                        $total_reject = $d1['total_reject'];
                        $total_aktual = $d1['total_aktual'];
                        $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
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
                $data_reject_all_line_by_month = $this->M_DashboardAssyRejection->get_data_reject_all_line_by_month($bulan, $h, $i);
                if (!empty($data_reject_all_line_by_month)) {
                    foreach ($data_reject_all_line_by_month as $dalm) {
                        $total_reject = $dalm['total_reject'];
                        $total_aktual = $dalm['total_aktual'];
                        $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                        array_push($data['data_reject_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_reject_by_month_line_'.$i], 0);
                }
            }
        }

        // GET DATA REJECT BY DATE PERSENTASE
        $data['data_reject_by_date_persentase'] = [];

        $data_jenis_reject_persentase = $this->M_DashboardAssyRejection->get_jenis_reject_by_month($start3, $child_filter);

        if (!empty($data_jenis_reject_persentase)) {
            while (strtotime($start3) <= strtotime($now)) {
                foreach ($data_jenis_reject_persentase as $d_jenis_reject) {
                    $data_jenis_reject_by_date_persentase = $this->M_DashboardAssyRejection->get_data_reject_by_date($start3, $d_jenis_reject['jenis_reject'], $child_filter);
                    $data_reject_per_line_by_date = $this->M_DashboardAssyRejection->get_data_reject_all_line($start3, $child_filter);
                    if (!empty($data_jenis_reject_by_date_persentase)) {
                        foreach ($data_jenis_reject_by_date_persentase as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_reject'],
                                'data' => ($data_reject_per_line_by_date[0]['total_aktual'] == 0) ? 0 : (float) number_format( ((int) $d_jenis_reject_by_date['qty'] / (int) ($data_reject_per_line_by_date[0]['total_aktual'] + $d_jenis_reject_by_date['qty'])) * 100, 2, '.', '')
                            ];
                            
                            $data['data_reject_by_date_persentase'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_reject'],
                            'data' => 0
                        ];
                        
                        $data['data_reject_by_date_persentase'][] = $data_reject;
                    }
                }    
                $start3 = date ("Y-m-d", strtotime("+1 days", strtotime($start3)));
            }
        }

        return view('dashboard/dashboard_lhp_assy_rejection_qc', $data);
    }
}