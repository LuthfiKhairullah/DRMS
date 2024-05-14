<?php

namespace App\Controllers;

use App\Models\M_DashboardPendingWet;

class DashboardPendingWet extends BaseController
{
    public function __construct()
    {
        $this->M_DashboardPendingWet = new M_DashboardPendingWet();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('dashboard/home');
    }

    public function dashboard_pending_wet()
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

        $target = $this->M_DashboardPendingWet->get_data_target_by_year('pending', 'wet', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardPendingWet->get_data_target_last_year('pending', 'wet', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardPendingWet->get_data_target_first_year('pending', 'wet', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        // $total_data_reject_by_month = $this->M_DashboardPendingWet->get_total_data_reject_by_month(idate('m',strtotime($bulan)));
        // $total_aktual_by_month = $this->M_DashboardPendingWet->get_total_aktual_by_month($bulan);
        $data_reject_by_month = $this->M_DashboardPendingWet->get_data_reject_by_month($bulan, $child_filter);

        // GET DATA REJECT BY MONTH
        $data['data_reject_by_month'] = [];
        $data['data_jenis_reject_by_month'] = [];
        $data['data_total_reject_by_month'] = [];

        $data['data_line_by_grup'] = [];
        $data['data_line_by_grup_month'] = [];
        $data['data_line_by_kss'] = [];
        $data['data_line_by_kss_month'] = [];

        // foreach ($data_reject_by_month as $d_reject_by_month) {
        //     $data_reject = [
        //         'name' => $d_reject_by_month['jenis_pending'],
        //         'y' => (float) number_format(((int) $d_reject_by_month['qty'] / (int) $total_aktual_by_month[0]['total_aktual']) * 100, 2, '.', ''),
        //     ];
            
        //     $data['data_reject_by_month'][] = $data_reject;
        // }

        foreach ($data_reject_by_month as $d_reject_by_month) {
            array_push($data['data_jenis_reject_by_month'], $d_reject_by_month['jenis_pending']);
            array_push($data['data_total_reject_by_month'], $d_reject_by_month['qty']);
        }

        // GET DATA REJECT BY DATE
        $data['data_reject_by_date'] = [];

        $data_jenis_reject = $this->M_DashboardPendingWet->get_jenis_reject_by_month($start, $child_filter);

        if (!empty($data_jenis_reject)) {
            while (strtotime($start) <= strtotime($now)) {
                foreach ($data_jenis_reject as $d_jenis_reject) {
                    $data_jenis_reject_by_date = $this->M_DashboardPendingWet->get_data_reject_by_date($start, $d_jenis_reject['jenis_pending'], $child_filter);
                    if (!empty($data_jenis_reject_by_date)) {
                        foreach ($data_jenis_reject_by_date as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_pending'],
                                'data' => (int) $d_jenis_reject_by_date['qty']
                            ];
                            
                            $data['data_reject_by_date'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_pending'],
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
            $data_all = $this->M_DashboardPendingWet->get_data_rejection_by_month($bulan, $i, $child_filter);
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
        $data['data_qty_reject_by_line'] = [];

        $data_line = $this->M_DashboardPendingWet->get_data_total_reject_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            if ($d_line['line'] == 8) {
                array_push($data['data_reject_by_line'], 'WET A');
            } else if ($d_line['line'] == 9) {
                array_push($data['data_reject_by_line'], 'WET F');
            }
            
            array_push($data['data_total_reject_by_line'], (!empty($d_line['total_aktual'])) ? (float) number_format(($d_line['total_reject'] / ($d_line['total_aktual']+$d_line['total_reject'])) * 100, 2, '.', '') : 0);
            array_push($data['data_qty_reject_by_line'], $d_line['total_reject']);
        }

        $data_year = $this->M_DashboardPendingWet->get_year_to_date_rejection($bulan, $child_filter);
        $data['data_all_year'] = (!empty($data_year[0]['total_reject']) && !empty($data_year[0]['total_aktual'])) ? (float) number_format(($data_year[0]['total_reject'] / ($data_year[0]['total_aktual']+$data_year[0]['total_reject'])) * 100, 2, '.', '') : 0;

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data['data_average_reject_by_date_all_line'] = [];
        $data['data_qty_reject_by_date_all_line'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_average_reject_by_date_all_line = $this->M_DashboardPendingWet->get_data_reject_all_line_by_date($start1, $child_filter);
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
        $data['data_qty_reject_by_month'] = [];
        for ($i=1; $i <= 12; $i++) { 
            $data_all = $this->M_DashboardPendingWet->get_data_average_reject_by_month($bulan, $i, $child_filter);
            if (!empty($data_all)) {
                foreach($data_all as $d_all) {
                    $total_reject = $d_all['total_reject'];
                    $total_aktual = $d_all['total_aktual'];
                    $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / ($total_aktual+$total_reject)) * 100 : 0;
                    array_push($data['data_average_reject_by_month'], (float) number_format($eff, 2, '.', ''));
                    array_push($data['data_qty_reject_by_month'], $total_reject);
                }
            } else {
                array_push($data['data_average_reject_by_month'], 0);
                array_push($data['data_qty_reject_by_month'], 0);
            }
        }

        // GET DATA QTY REJECT BY MONTH ALL LINE
        // $data['data_qty_reject_by_month'] = [];
        // for ($i=1; $i <= 12; $i++) { 
        //     $data_all = $this->M_DashboardPendingWet->get_data_average_reject_by_month($bulan, $i, $child_filter);
        //     if (!empty($data_all)) {
        //         foreach($data_all as $d_all) {
        //             $total_reject = $d_all['total_reject'];
        //             $total_aktual = $d_all['total_aktual'];
        //             // $eff = (!empty($total_reject) && !empty($total_aktual)) ? ($total_reject / $total_aktual) * 100 : 0;
        //             array_push($data['data_qty_reject_by_month'], $total_reject);
        //         }
        //     } else {
        //         array_push($data['data_qty_reject_by_month'], 0);
        //     }
        // }

        if ($jenis_dashboard == 1 AND ($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'line') {
            // GET DATA AVERAGE REJECT BY DATE ALL LINE
            $data['data_reject_line_8'] = [];
            $data['data_reject_line_9'] = [];
            while (strtotime($start2) <= strtotime($now)) {
                for ($i=8; $i <= 9; $i++) { 
                    $data_reject_per_line_by_date = $this->M_DashboardPendingWet->get_data_reject_all_line($start2, $i);
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
            $data['data_reject_by_month_line_8'] = [];
            $data['data_reject_by_month_line_9'] = [];

            for ($h=1; $h <= 12; $h++) { 
                for ($i=8; $i <= 9; $i++) {
                    $data_reject_all_line_by_month = $this->M_DashboardPendingWet->get_data_reject_all_line_by_month($bulan, $h, $i);
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
        } else if ($jenis_dashboard == 1 AND ($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'grup') {
            $data_grup_line = $this->M_DashboardPendingWet->get_data_grup_by_line($bulan, '');

            $data_grup_line_year = $this->M_DashboardPendingWet->get_data_grup_by_line_year($bulan, '');

            if (!empty($data_grup_line_year)) {
                for ($i=1; $i <= 12 ; $i++) { 
                    $total_aktual_grup_month = 0;
                    $qty_pending_grup_month = 0;
                    $eff = 0;
                    foreach ($data_grup_line_year as $d_grup_line) {
                        $grup_month = $d_grup_line['nama_pic'];
                        $data_all_grup_month = $this->M_DashboardPendingWet->get_data_line_by_grup_month($bulan, $i, '', $grup_month);

                        $total_aktual_grup_month = 0;
                        $qty_pending_grup_month = 0;
                        $eff = 0;

                        if (!empty($data_all_grup_month)) {
                            foreach ($data_all_grup_month as $d_all_grup) {
                                $total_aktual_grup_month += $d_all_grup['total_aktual'];
                                $qty_pending_grup_month += $d_all_grup['qty_pending'];
                            } 
                        } else {
                            $total_aktual_grup_month += 0;
                            $qty_pending_grup_month += 0;

                        }

                        $eff = (!empty($total_aktual_grup_month) && !empty($qty_pending_grup_month)) ? ($qty_pending_grup_month / $total_aktual_grup_month) * 100 : 0;

                        $data_grup_month = [
                            'grup' => $grup_month,
                            'data' => (float) number_format($eff, 1, '.', '')
                        ];

                        $data['data_line_by_grup_month'][] = $data_grup_month;
                    }
                }
            }
            
            if (!empty($data_grup_line)) {
                $start = date('Y-m-01', strtotime($bulan));
                while (strtotime($start) <= strtotime($now)) {
                    foreach ($data_grup_line as $d_grup_line) {
                        $grup = $d_grup_line['nama_pic'];
                        $data_all_grup = $this->M_DashboardPendingWet->get_data_line_by_grup($start, '', $grup);
                        if (!empty($data_all_grup)) {
                            foreach ($data_all_grup as $d_all_grup) {
                                $total_aktual_grup = $d_all_grup['total_aktual'];
                                $qty_pending_grup = $d_all_grup['qty_pending'];
                                $eff = (!empty($total_aktual_grup) && !empty($qty_pending_grup)) ? ($qty_pending_grup / $total_aktual_grup) * 100 : 0;

                                $data_grup = [
                                    'grup' => $grup,
                                    'data' => (float) number_format($eff, 1, '.', '')
                                ];
                                $data['data_line_by_grup'][] = $data_grup;
                            } 
                        } else {
                            $data_grup = [
                                'grup' => $grup,
                                'data' => 0
                            ];
                            $data['data_line_by_grup'][] = $data_grup;
                        }
                    }
                    $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
                }
            }
        } else if ($jenis_dashboard == 1 AND ($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter != null OR $child_filter != 0) AND $baby_filter == 'grup') {
            $data_grup_line = $this->M_DashboardPendingWet->get_data_grup_by_line($bulan, $child_filter);

            $data_grup_line_year = $this->M_DashboardPendingWet->get_data_grup_by_line_year($bulan, $child_filter);

            if (!empty($data_grup_line_year)) {
                for ($i=1; $i <= 12 ; $i++) { 
                    $total_aktual_grup_month = 0;
                    $qty_pending_grup_month = 0;
                    $eff = 0;
                    foreach ($data_grup_line_year as $d_grup_line) {
                        $grup_month = $d_grup_line['nama_pic'];
                        $data_all_grup_month = $this->M_DashboardPendingWet->get_data_line_by_grup_month($bulan, $i, '', $grup_month);

                        $total_aktual_grup_month = 0;
                        $qty_pending_grup_month = 0;
                        $eff = 0;

                        if (!empty($data_all_grup_month)) {
                            foreach ($data_all_grup_month as $d_all_grup) {
                                $total_aktual_grup_month += $d_all_grup['total_aktual'];
                                $qty_pending_grup_month += $d_all_grup['qty_pending'];
                            } 
                        } else {
                            $total_aktual_grup_month += 0;
                            $qty_pending_grup_month += 0;

                        }

                        $eff = (!empty($total_aktual_grup_month) && !empty($qty_pending_grup_month)) ? ($qty_pending_grup_month / $total_aktual_grup_month) * 100 : 0;

                        $data_grup_month = [
                            'grup' => $grup_month,
                            'data' => (float) number_format($eff, 1, '.', '')
                        ];

                        $data['data_line_by_grup_month'][] = $data_grup_month;
                    }
                }
            }
            
            if (!empty($data_grup_line)) {
                $start = date('Y-m-01', strtotime($bulan));
                while (strtotime($start) <= strtotime($now)) {
                    foreach ($data_grup_line as $d_grup_line) {
                        $grup = $d_grup_line['nama_pic'];
                        $data_all_grup = $this->M_DashboardPendingWet->get_data_line_by_grup($start, $child_filter, $grup);
                        if (!empty($data_all_grup)) {
                            foreach ($data_all_grup as $d_all_grup) {
                                $total_aktual_grup = $d_all_grup['total_aktual'];
                                $qty_pending_grup = $d_all_grup['qty_pending'];
                                $eff = (!empty($total_aktual_grup) && !empty($qty_pending_grup)) ? ($qty_pending_grup / $total_aktual_grup) * 100 : 0;

                                $data_grup = [
                                    'grup' => $grup,
                                    'data' => (float) number_format($eff, 1, '.', '')
                                ];
                                $data['data_line_by_grup'][] = $data_grup;
                            } 
                        } else {
                            $data_grup = [
                                'grup' => $grup,
                                'data' => 0
                            ];
                            $data['data_line_by_grup'][] = $data_grup;
                        }
                    }
                    $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
                }
            }
        } elseif($jenis_dashboard == 1 AND ($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter != null OR $child_filter != 0) AND $baby_filter != null AND $baby_filter == 'kasubsie') {
            $data_kss_line = $this->M_DashboardPendingWet->get_data_kss_by_line($bulan, $child_filter);

            $data_kss_line_year = $this->M_DashboardPendingWet->get_data_kss_by_line_year($bulan, $child_filter);

            if (!empty($data_kss_line_year)) {
                for ($i=1; $i <= 12 ; $i++) { 
                    $total_aktual_kss_month = 0;
                    $qty_pending_kss_month = 0;
                    $eff = 0;
                    foreach ($data_kss_line_year as $d_kss_line) {
                        $kss_month = $d_kss_line['kasubsie'];
                        $data_all_kss_month = $this->M_DashboardPendingWet->get_data_line_by_kss_month($bulan, $i, $child_filter, $kss_month);

                        $total_aktual_kss_month = 0;
                        $qty_pending_kss_month = 0;
                        $eff = 0;

                        if (!empty($data_all_kss_month)) {
                            foreach ($data_all_kss_month as $d_all_kss) {
                                $total_aktual_kss_month += $d_all_kss['total_aktual'];
                                $qty_pending_kss_month += $d_all_kss['qty_pending'];
                            } 
                        } else {
                            $total_aktual_kss_month += 0;
                            $qty_pending_kss_month += 0;
            
                        }

                        $eff = (!empty($total_aktual_kss_month) && !empty($qty_pending_kss_month)) ? ($qty_pending_kss_month / $total_aktual_kss_month) * 100 : 0;

                        $data_kss_month = [
                            'kss' => $kss_month,
                            'data' => (float) number_format($eff, 1, '.', '')
                        ];

                        $data['data_line_by_kss_month'][] = $data_kss_month;
                    }
                }
            }

            if (!empty($data_kss_line)) {
                $start = date('Y-m-01', strtotime($bulan));
                while (strtotime($start) <= strtotime($now)) {
                    foreach ($data_kss_line as $d_kss_line) {
                        $kss = $d_kss_line['kasubsie'];
                        $data_all_kss = $this->M_DashboardPendingWet->get_data_line_by_kss($start, $child_filter, $kss);
                        if (!empty($data_all_kss)) {
                            foreach ($data_all_kss as $d_all_kss) {
                                $total_aktual_kss = $d_all_kss['total_aktual'];
                                $qty_pending_kss = $d_all_kss['qty_pending'];
                                $eff = (!empty($total_aktual_kss) && !empty($qty_pending_kss)) ? ($qty_pending_kss / $total_aktual_kss) * 100 : 0;
            
                                $data_kss = [
                                    'kss' => $kss,
                                    'data' => (float) number_format($eff, 1, '.', '')
                                ];
                                $data['data_line_by_kss'][] = $data_kss;
                            } 
                        } else {
                            $data_kss = [
                                'kss' => $kss,
                                'data' => 0
                            ];
                            $data['data_line_by_kss'][] = $data_kss;
                        }
                    }
                    $start = date ("Y-m-d", strtotime("+1 days", strtotime($start)));
                }
            }
        }

        // GET DATA REJECT BY DATE PERSENTASE
        $data['data_reject_by_date_persentase'] = [];

        $data_jenis_reject_persentase = $this->M_DashboardPendingWet->get_jenis_reject_by_month($start3, $child_filter);

        if (!empty($data_jenis_reject_persentase)) {
            while (strtotime($start3) <= strtotime($now)) {
                foreach ($data_jenis_reject_persentase as $d_jenis_reject) {
                    $data_jenis_reject_by_date_persentase = $this->M_DashboardPendingWet->get_data_reject_by_date($start3, $d_jenis_reject['jenis_pending'], $child_filter);
                    $data_reject_per_line_by_date = $this->M_DashboardPendingWet->get_data_reject_all_line($start3, $child_filter);
                    if (!empty($data_jenis_reject_by_date_persentase)) {
                        foreach ($data_jenis_reject_by_date_persentase as $d_jenis_reject_by_date) {
                            $data_reject = [
                                'name' => $d_jenis_reject_by_date['jenis_pending'],
                                'data' => ($data_reject_per_line_by_date[0]['total_aktual'] == 0) ? 0 : (float) number_format(($d_jenis_reject_by_date['qty'] / ($data_reject_per_line_by_date[0]['total_aktual'] + $d_jenis_reject_by_date['qty'])) * 100, 2, '.', '')
                            ];
                            
                            $data['data_reject_by_date_persentase'][] = $data_reject;
                        }
                    } else {
                        $data_reject = [
                            'name' => $d_jenis_reject['jenis_pending'],
                            'data' => 0
                        ];
                        
                        $data['data_reject_by_date_persentase'][] = $data_reject;
                    }
                }    
                $start3 = date ("Y-m-d", strtotime("+1 days", strtotime($start3)));
            }
        }

        return view('dashboard_wet_finishing/dashboard_pending_wet', $data);
    }

    public function get_detail_pending()
    {
        $jenis_reject = $this->request->getPost('jenis_reject');
        $date = $this->request->getPost('date');
        $line = $this->request->getPost('line');
        $type_battery = $this->request->getPost('type_battery');
        $grup = $this->request->getPost('grup');
        $shift = $this->request->getPost('shift');

        $data['total_aktual_by_month'] = $this->M_DashboardPendingWet->get_total_aktual_by_month($date, $line);
        $data['total_aktual_by_date'] = $this->M_DashboardPendingWet->get_total_aktual_by_date($date, $line);

        $data['data_jenis_reject'] = $this->M_DashboardPendingWet->get_qty_jenis_reject($date, $line);
        $data['data_jenis_reject_by_month'] = $this->M_DashboardPendingWet->get_data_reject_by_month($date, $line);

        $data['data_reject_by_jenis_reject'] = $this->M_DashboardPendingWet->get_detail_rejection_by_jenis($jenis_reject, $date, $line);
        $data['data_reject_by_type_battery'] = $this->M_DashboardPendingWet->get_detail_rejection_by_type_battery($jenis_reject, $date, $line);
        $data['data_reject_by_grup'] = $this->M_DashboardPendingWet->get_detail_rejection_by_grup($jenis_reject, $date, $line);

        $data['data_jenis_reject_by_type_battery'] = $this->M_DashboardPendingWet->get_jenis_reject_by_type_battery($type_battery, $date, $line);
        $data['data_kategori_reject_by_type_battery'] = $this->M_DashboardPendingWet->get_kategori_reject_by_type_battery($type_battery, $date, $line);
        $data['data_grup_reject_by_type_battery'] = $this->M_DashboardPendingWet->get_grup_reject_by_type_battery($type_battery, $date, $line);

        $data['data_jenis_reject_by_grup_shift'] = $this->M_DashboardPendingWet->get_jenis_reject_by_grup_shift($grup, $shift, $date, $line);
        $data['data_kategori_reject_by_grup_shift'] = $this->M_DashboardPendingWet->get_kategori_reject_by_grup_shift($grup, $shift, $date, $line);
        $data['data_battery_reject_by_grup_shift'] = $this->M_DashboardPendingWet->get_battery_reject_by_grup_shift($grup, $shift, $date, $line);

        $data['data_all_detail_kategori_rejection_by_date'] = $this->M_DashboardPendingWet->get_all_detail_kategori_rejection_by_date($date, $line);
        $data['data_all_detail_battery_rejection_by_date'] = $this->M_DashboardPendingWet->get_all_detail_battery_rejection_by_date($date, $line);
        $data['data_all_detail_grup_rejection_by_date'] = $this->M_DashboardPendingWet->get_all_detail_grup_rejection_by_date($date, $line);

        $data['data_reject_by_jenis_reject_by_month'] = $this->M_DashboardPendingWet->get_detail_rejection_by_jenis_by_month($jenis_reject, $date, $line);
        $data['data_reject_by_type_battery_by_month'] = $this->M_DashboardPendingWet->get_detail_rejection_by_type_battery_by_month($jenis_reject, $date, $line);

        $data['data_all_detail_kategori_rejection_by_month'] = $this->M_DashboardPendingWet->get_all_detail_kategori_rejection_by_month($date, $line);
        $data['data_all_detail_battery_rejection_by_month'] = $this->M_DashboardPendingWet->get_all_detail_battery_rejection_by_month($date, $line);
        $data['data_all_detail_grup_rejection_by_month'] = $this->M_DashboardPendingWet->get_all_detail_grup_rejection_by_month($date, $line);

        $data['detail_summary_rejection'] = $this->M_DashboardPendingWet->get_detail_summary_rejection($jenis_reject, $date, $line);
        $data['detail_summary_rejection_by_month'] = $this->M_DashboardPendingWet->get_detail_summary_rejection_by_month($jenis_reject, $date, $line);

        echo json_encode($data);    
    }
}