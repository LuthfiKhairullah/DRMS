<?php

namespace App\Controllers;

use App\Models\M_DashboardAssyLineStopWET;

class DashboardAssyLineStopWET extends BaseController
{
    public function __construct()
    {
        $this->M_DashboardAssyLineStopWET = new M_DashboardAssyLineStopWET();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('dashboard/home');
    }

    public function dashboard_line_stop_assy()
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

        // $total_data_line_stop_by_month = $this->M_DashboardAssyLineStopWET->get_total_data_line_stop_by_month(idate('m',strtotime($bulan)));
        // $total_aktual_by_month = $this->M_DashboardAssyLineStopWET->get_total_aktual_by_month(idate('m',strtotime($bulan)));
        $data_line_stop_by_month = $this->M_DashboardAssyLineStopWET->get_data_total_line_stop_by_month(idate('m',strtotime($bulan)), $child_filter);

        // GET DATA REJECT BY MONTH
        $data['data_line_stop_by_month'] = [];
        $data['data_jenis_line_stop_by_month'] = [];
        $data['data_total_line_stop_by_month'] = [];

        // foreach ($data_line_stop_by_month as $d_line_stop_by_month) {
        //     $data_line_stop = [
        //         'name' => $d_line_stop_by_month['jenis_breakdown'],
        //         'y' => (float) number_format(((int) $d_line_stop_by_month['qty'] / (int) $total_aktual_by_month[0]['loading_time']) * 100, 2, '.', ''),
        //     ];
            
        //     $data['data_line_stop_by_month'][] = $data_line_stop;
        // }

        foreach ($data_line_stop_by_month as $d_line_stop_by_month) {
            array_push($data['data_jenis_line_stop_by_month'], $d_line_stop_by_month['jenis_breakdown']);
            array_push($data['data_total_line_stop_by_month'], $d_line_stop_by_month['qty']);
        }

        // GET DATA REJECT BY DATE
        $data['data_line_stop_by_date'] = [];

        $data_jenis_line_stop = $this->M_DashboardAssyLineStopWET->get_jenis_line_stop_by_month($start, $child_filter);

        if (!empty($data_jenis_line_stop)) {
            while (strtotime($start) <= strtotime($now)) {
                foreach ($data_jenis_line_stop as $d_jenis_line_stop) {
                    $data_jenis_line_stop_by_date = $this->M_DashboardAssyLineStopWET->get_data_line_stop_by_date($start, $d_jenis_line_stop['jenis_breakdown'], $child_filter);
                    if (!empty($data_jenis_line_stop_by_date)) {
                        foreach ($data_jenis_line_stop_by_date as $d_jenis_line_stop_by_date) {
                            $data_line_stop = [
                                'name' => $d_jenis_line_stop_by_date['jenis_breakdown'],
                                'data' => (int) $d_jenis_line_stop_by_date['qty']
                            ];
                            
                            $data['data_line_stop_by_date'][] = $data_line_stop;
                        }
                    } else {
                        $data_line_stop = [
                            'name' => $d_jenis_line_stop['jenis_breakdown'],
                            'data' => 0
                        ];
                        
                        $data['data_line_stop_by_date'][] = $data_line_stop;
                    }
                }    
                $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
            }
        }

        // GET DATA REJECT BY MONTH
        $data['data_line_stop_all_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyLineStopWET->get_data_line_stop_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_line_stop = $d_all['total_line_stop'];
                    $loading_time = $d_all['loading_time'];
                    $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                    array_push($data['data_line_stop_all_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_line_stop_all_month'], 0);
            }
        }
        

        // GET DATA PARETO REJECT BY LINE
        $data['data_line_stop_by_line'] = [];
        $data['data_total_line_stop_by_line'] = [];

        $data_line = $this->M_DashboardAssyLineStopWET->get_data_total_line_stop_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            if ($d_line['line'] == 8) {
                array_push($data['data_line_stop_by_line'], 'WET A');
            } elseif ($d_line['line'] == 9) {
                array_push($data['data_line_stop_by_line'], 'WET F');
            }
            
            array_push($data['data_total_line_stop_by_line'], (float) number_format($d_line['persen'], 2, '.', ''));
        }

        $data_year = $this->M_DashboardAssyLineStopWET->get_year_to_date_line_stop($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_line_stop']) && !empty($data_year[0]['loading_time'])) ? (float) number_format(($data_year[0]['total_line_stop'] / $data_year[0]['loading_time']) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_line_stop_by_date_all_line'] = [];
        $data['data_menit_line_stop_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_line_stop_by_date_all_line = $this->M_DashboardAssyLineStopWET->get_data_line_stop_all_line_by_date($start1, $child_filter);
            if (!empty($data_average_line_stop_by_date_all_line)) {
                foreach ($data_average_line_stop_by_date_all_line as $da) {
                    $total_line_stop = $da['total_line_stop'];
                    $loading_time = $da['loading_time'];
                    $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                    array_push($data['data_average_line_stop_by_date_all_line'], (float) number_format($eff, 2, '.', ''));
                    array_push($data['data_menit_line_stop_by_date_all_line'], (int) $total_line_stop);
                } 
            } else {
                array_push($data['data_average_line_stop_by_date_all_line'], 0);
                array_push($data['data_menit_line_stop_by_date_all_line'], 0);
            }

            $start1 = date ("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }  
        
        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_line_stop_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyLineStopWET->get_data_average_line_stop_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_line_stop = $d_all['total_line_stop'];
                    $loading_time = $d_all['loading_time'];
                    $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                    array_push($data['data_average_line_stop_by_month'], (float) number_format($eff, 2, '.', ''));
                }
            } else {
                array_push($data['data_average_line_stop_by_month'], 0);
            }
        }

        // GET DATA QTY REJECT BY MONTH ALL LINE
        $data['data_qty_line_stop_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardAssyLineStopWET->get_data_average_line_stop_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_line_stop = $d_all['total_line_stop'];
                    $loading_time = $d_all['loading_time'];
                    // $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                    array_push($data['data_qty_line_stop_by_month'], $total_line_stop);
                }
            } else {
                array_push($data['data_qty_line_stop_by_month'], 0);
            }
        }

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_line_stop_line_8'] = [];
        $data['data_line_stop_line_9'] = [];

        while (strtotime($start2) <= strtotime($now)) {
            for ($i=8; $i <= 9; $i++) { 
                $data_line_stop_per_line_by_date = $this->M_DashboardAssyLineStopWET->get_data_line_stop_all_line($start2, $i);
                if (!empty($data_line_stop_per_line_by_date)) {
                    foreach ($data_line_stop_per_line_by_date as $d1) {
                        $total_line_stop = $d1['total_line_stop'];
                        $loading_time = $d1['loading_time'];
                        $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                        array_push($data['data_line_stop_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    } 
                } else {
                    array_push($data['data_line_stop_line_'.$i], 0);
                }
            }

            $start2 = date ("Y-m-d", strtotime("+1 days", strtotime($start2)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_line_stop_by_month_line_8'] = [];
        $data['data_line_stop_by_month_line_9'] = [];

        for ($h=1; $h <= 12; $h++) { 
            for ($i=8; $i <= 9; $i++) {
                $data_line_stop_all_line_by_month = $this->M_DashboardAssyLineStopWET->get_data_line_stop_all_line_by_month($bulan, $h, $i);
                if (!empty($data_line_stop_all_line_by_month)) {
                    foreach ($data_line_stop_all_line_by_month as $dalm) {
                        $total_line_stop = $dalm['total_line_stop'];
                        $loading_time = $dalm['loading_time'];
                        $eff = (!empty($total_line_stop) && !empty($loading_time)) ? ($total_line_stop / $loading_time) * 100 : 0;
                        array_push($data['data_line_stop_by_month_line_'.$i], (float) number_format($eff, 1, '.', ''));
                    }
                } else {
                    array_push($data['data_line_stop_by_month_line_'.$i], 0);
                }
            }
        }

        // GET DATA REJECT BY DATE PERSENTASE
        $data['data_line_stop_by_date_persentase'] = [];

        $data_jenis_line_stop_persentase = $this->M_DashboardAssyLineStopWET->get_jenis_line_stop_by_month($start3, $child_filter);

        if (!empty($data_jenis_line_stop_persentase)) {
            while (strtotime($start3) <= strtotime($now)) {
                foreach ($data_jenis_line_stop_persentase as $d_jenis_line_stop) {
                    $data_jenis_line_stop_by_date_persentase = $this->M_DashboardAssyLineStopWET->get_data_line_stop_by_date($start3, $d_jenis_line_stop['jenis_breakdown'], $child_filter);
                    $data_line_stop_per_line_by_date = $this->M_DashboardAssyLineStopWET->get_data_line_stop_all_line($start3, $child_filter);
                    if (!empty($data_jenis_line_stop_by_date_persentase)) {
                        foreach ($data_jenis_line_stop_by_date_persentase as $d_jenis_line_stop_by_date) {
                            $data_line_stop = [
                                'name' => $d_jenis_line_stop_by_date['jenis_breakdown'],
                                'data' => ($data_line_stop_per_line_by_date[0]['loading_time'] == 0) ? 0 : (float) number_format( ((int) $d_jenis_line_stop_by_date['qty'] / ((int) $data_line_stop_per_line_by_date[0]['loading_time'])) * 100, 2, '.', '')
                            ];
                            
                            $data['data_line_stop_by_date_persentase'][] = $data_line_stop;
                        }
                    } else {
                        $data_line_stop = [
                            'name' => $d_jenis_line_stop['jenis_breakdown'],
                            'data' => 0
                        ];
                        
                        $data['data_line_stop_by_date_persentase'][] = $data_line_stop;
                    }
                }    
                $start3 = date ("Y-m-d", strtotime("+1 days", strtotime($start3)));
            }
        }

        return view('dashboard/dashboard_lhp_assy_line_stop_wet', $data);
    }

    public function get_detail_line_stop()
    {
        $jenis_line_stop = $this->request->getPost('jenis_line_stop');
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $type_battery = $this->request->getPost('type_battery');
        $grup = $this->request->getPost('grup');
        $shift = $this->request->getPost('shift');

        $data['total_aktual_by_month'] = $this->M_DashboardAssyLineStopWET->get_loading_time_by_month($date, $line);
        $data['total_aktual_by_date'] = $this->M_DashboardAssyLineStopWET->get_loading_time_by_date($date, $line);

        $data['data_jenis_line_stop'] = $this->M_DashboardAssyLineStopWET->get_qty_jenis_line_stop($date, $line);
        $data['data_jenis_line_stop_by_month'] = $this->M_DashboardAssyLineStopWET->get_data_total_line_stop_by_month($date, $line);

        $data['data_line_stop_by_jenis_line_stop'] = $this->M_DashboardAssyLineStopWET->get_detail_line_stop_by_jenis($jenis_line_stop, $date, $line);
        
        echo json_encode($data);    
    }
}