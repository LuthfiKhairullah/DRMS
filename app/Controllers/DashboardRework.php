<?php

namespace App\Controllers;

use App\Models\M_DashboardRework;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DashboardRework extends BaseController
{
    public function __construct()
    {
        $this->M_DashboardRework = new M_DashboardRework();
        $this->session = \Config\Services::session();
    }

    // CONTROLLER DASHBOARD SAW REPAIR

    public function dashboard_saw_repair()
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

        if ($bulan != null or $bulan != $current_month) {
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

        $target = $this->M_DashboardRework->get_data_target_by_year('rework', 'saw_repair', $bulan);
        if(count($target) > 0) {
            $data['target'] = $target[0]['target'];
        } else {
            $target = $this->M_DashboardRework->get_data_target_last_year('rework', 'saw_repair', $bulan);
            if(count($target) > 0) {
                $data['target'] = $target[0]['target'];
            } else {
                $target = $this->M_DashboardRework->get_data_target_first_year('rework', 'saw_repair', $bulan);
                if(count($target) > 0) {
                    $data['target'] = $target[0]['target'];
                } else {
                    $data['target'] = 0;
                }
            }
        }

        // GET DATA SAW REPAIR BY DATE
        $data['data_saw_repair_by_date'] = [];

        while (strtotime($start) <= strtotime($now)) {
            $data_saw_repair_by_date = $this->M_DashboardRework->get_data_saw_repair_by_date($start, $shift, $operator);
            if (!empty($data_saw_repair_by_date)) {
                foreach ($data_saw_repair_by_date as $d_by_date) {
                    $data_saw_repair = [
                        'qty' => $d_by_date['qty'],
                    ];

                    $data['data_saw_repair_by_date'][] = $data_saw_repair;
                }
            } else {
                $data_saw_repair = [
                    'qty' => 0,
                ];

                $data['data_saw_repair_by_date'][] = $data_saw_repair;
            }
            $start = date("Y-m-d", strtotime("+1 days", strtotime($start)));
        }

        // GET DATA SAW REPAIR BY MONTH
        $data['data_saw_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_saw_repair_by_month = $this->M_DashboardRework->get_data_saw_repair_by_month($i, $bulan, $shift, $operator);
            if (!empty($data_saw_repair_by_month)) {
                foreach ($data_saw_repair_by_month as $d_by_date) {
                    $data_saw_repair = [
                        'qty' => $d_by_date['qty'],
                    ];

                    $data['data_saw_repair_by_month'][] = $data_saw_repair;
                }
            } else {
                $data_saw_repair = [
                    'qty' => 0,
                ];

                $data['data_saw_repair_by_month'][] = $data_saw_repair;
            }
        }

        // GET DATA AVERAGE REJECT BY DATE ALL LINE
        $data_operator = $this->M_DashboardRework->get_data_operator_saw_repair_by_year($bulan);
        foreach ($data_operator as $do) {
            $data['data']['data_saw_repair_by_date_by_' . $do['operator']] = [];
            $data['data']['data_saw_repair_by_month_by_' . $do['operator']] = [];
        }
        $data['data_operator'] = $data_operator;

        while (strtotime($start1) <= strtotime($now)) {
            foreach ($data_operator as $do) {
                $data_saw_repair_by_date = $this->M_DashboardRework->get_data_saw_repair_by_date($start1, $shift, $do['operator']);
                if (!empty($data_saw_repair_by_date)) {
                    foreach ($data_saw_repair_by_date as $d_by_date) {
                        $data_saw_repair = [
                            'qty' => $d_by_date['qty'],
                        ];

                        $data['data']['data_saw_repair_by_date_by_' . $do['operator']][] = $data_saw_repair;
                    }
                } else {
                    $data_saw_repair = [
                        'qty' => 0,
                    ];

                    $data['data']['data_saw_repair_by_date_by_' . $do['operator']][] = $data_saw_repair;
                }
            }

            $start1 = date("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }

        for ($i = 1; $i <= 12; $i++) {
            foreach ($data_operator as $do) {
                $data_saw_repair_by_month = $this->M_DashboardRework->get_data_saw_repair_by_month($i, $bulan, $shift, $do['operator']);
                if (!empty($data_saw_repair_by_month)) {
                    foreach ($data_saw_repair_by_month as $d_by_date) {
                        $data_saw_repair = [
                            'qty' => $d_by_date['qty'],
                        ];

                        $data['data']['data_saw_repair_by_month_by_' . $do['operator']][] = $data_saw_repair;
                    }
                } else {
                    $data_saw_repair = [
                        'qty' => 0,
                    ];

                    $data['data']['data_saw_repair_by_month_by_' . $do['operator']][] = $data_saw_repair;
                }
            }
        }

        $data['data_inventory_element_repair'] = [];
        $data_inventory_element_repair = $this->M_DashboardRework->get_data_element_repair_type_by_month($bulan, $shift, $operator);
        foreach ($data_inventory_element_repair as $value) {
            // $data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = ($data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total']) / 2),
                'pasangan_negatif' => ($data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total']) / 2),
                'status' => ''
            ];
        }

        $data_data_saw_repair = [];
        $data_saw_repair = $this->M_DashboardRework->get_data_saw_repair_type_by_month($bulan, $shift, $operator);
        foreach ($data_saw_repair as $value) {
            // $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total']) / 2),
                'pasangan_negatif' => ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total']) / 2),
            ];
        }
        foreach ($data_data_saw_repair as $key => $value) {
            if (array_key_exists($key, $data['data_inventory_element_repair'])) {
                // $data['data_inventory_element_repair'][$key] = $data['data_inventory_element_repair'][$key] - $value;
                $data['data_inventory_element_repair'][$key]['pasangan_positif'] = $data['data_inventory_element_repair'][$key]['pasangan_positif'] - $value['pasangan_positif'];
                $data['data_inventory_element_repair'][$key]['pasangan_negatif'] = $data['data_inventory_element_repair'][$key]['pasangan_negatif'] - $value['pasangan_negatif'];
            }
        }

        $data_stock_saw_repair = [];
        $data_data_stock_saw_repair = $this->M_DashboardRework->get_data_adjustment_stock_saw_repair();
        foreach ($data_data_stock_saw_repair as $value) {
            $data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + $value['total_positif'],
                'pasangan_negatif' => ($data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + $value['total_negatif'],
                'status' => $value['status']
            ];
        }
        foreach ($data_stock_saw_repair as $key => $value) {
            if($value['status'] == 'Non Aktif') {
                unset($data['data_inventory_element_repair'][$key]);
            } else {
                if (array_key_exists($key, $data['data_inventory_element_repair'])) {
                    $data['data_inventory_element_repair'][$key]['pasangan_positif'] = $data['data_inventory_element_repair'][$key]['pasangan_positif'] + $value['pasangan_positif'];
                    $data['data_inventory_element_repair'][$key]['pasangan_negatif'] = $data['data_inventory_element_repair'][$key]['pasangan_negatif'] + $value['pasangan_negatif'];
                }
            }
        }
        
        // dd($data['data_inventory_element_repair']);
        // if (array_key_exists('CG80POS/CG87NEG', $data['data_inventory_element_repair'])) {
        //     // $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 3000;
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 400; // perubahan baru 26/09/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 2731.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 5135.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] -= 2183.5; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] -= 2623; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 2870.5; // perubahan baru 13/12/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 4741.5; // perubahan baru 13/12/2023
        // }
        // if (array_key_exists('CG82POS/CG87NEG', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 3000;
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 4300;
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 1600; // perubahan baru 26/09/2023
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 932.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 1876.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 6624; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 8235; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG85EPOS-UF/WG87NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_positif'] += 1000;
        //     $data['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_negatif'] += 1000;
        //     $data['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_positif'] += 3305; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_negatif'] += 3290; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CM84POS/CM87NEG', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] += 700;
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] += 900;
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] += 1211; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] += 882; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] -= 1095; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] -= 912.5; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 11000;
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 8500;
        // }
        // if (array_key_exists('WM84ESPOS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']['pasangan_positif'] -= 900;
        //     $data['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']['pasangan_negatif'] -= 1000;
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 4302; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 3585; // perubahan baru 15/11/2023
        // } else if (!array_key_exists('CM84POS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] = 4302; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] = 3585; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('WM84POS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 300;
        //     $data['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 280;
        // }
        // if (array_key_exists('CM84POS-UF/CM87NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS-UF/CM87NEG-UF']['pasangan_positif'] -= 423.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CM84POS-UF/CM87NEG-UF']['pasangan_negatif'] -= 1213; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('WG83POS-UF/WG87NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] -= 4393.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] -= 4393.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] -= 52; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] -= 52; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] += 9450.5; // perubahan baru 13/12/2023
        //     $data['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] += 9404; // perubahan baru 13/12/2023
        // }
        // if (array_key_exists('CG82POS-UF/CG87NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_positif'] -= 3137; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 3565.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_positif'] -= 91.5; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 91.5; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('YG80POS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_positif'] += 17; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_negatif'] -= 94; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_positif'] += 60; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_negatif'] += 60; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG80POS-UF/CG87NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_positif'] -= 613; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 714; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_positif'] += 3336; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_negatif'] += 3753; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG80POS/CG85NEG', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG80POS/CG85NEG']['pasangan_positif'] -= 72; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS/CG85NEG']['pasangan_negatif'] -= 112.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CG80POS-UF/CG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG85NEG-UF']['pasangan_positif'] += 2099; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CG80POS-UF/CG85NEG-UF']['pasangan_negatif'] += 2087; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG82POS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] += 47.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] += 47.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YD85POS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YD85POS-UF/YG85NEG-UF']['pasangan_positif'] += 64; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['YD85POS-UF/YG85NEG-UF']['pasangan_negatif'] += 48; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG79HDPOS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YG79HDPOS-UF/YG85NEG-UF']['pasangan_positif'] += 60; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['YG79HDPOS-UF/YG85NEG-UF']['pasangan_negatif'] += 60; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 2828.5; // perubahan baru 14/11/2023
        //     $data['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 754.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG82POS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] += 47.5; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] += 47.5; // perubahan baru 15/11/2023
        // } else if (!array_key_exists('YG82POS-UF/YG85NEG-UF', $data['data_inventory_element_repair'])) {
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] = 47.5; // perubahan baru 15/11/2023
        //     $data['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] = 47.5; // perubahan baru 15/11/2023
        // }
        // unset($data['data_inventory_element_repair']['CG85EPOS-UF/WM85NEG-UF']);
        // unset($data['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG82POS/CG85NEG']);
        // unset($data['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']);
        // unset($data['data_inventory_element_repair']['CM84POS-UF/WM87ESNEG-UF']);
        // unset($data['data_inventory_element_repair']['CG82POS/CR87NEG']);
        // unset($data['data_inventory_element_repair']['WG83POS-UF/WM87ESNEG-UF']);
        // unset($data['data_inventory_element_repair']['CG85EPOS-UF/CG87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG85POS-UF/WM85NEG-UF']);
        // unset($data['data_inventory_element_repair']['CM84POS/WM85NEG-UF']);
        // unset($data['data_inventory_element_repair']['CM84POS-UF/CM87NEG']);
        // unset($data['data_inventory_element_repair']['CG85EPOS-UF/CR87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG82POS/CM87NEG']);
        // unset($data['data_inventory_element_repair']['CG85EPOS-UF/WM87ESNEG-UF']);
        // unset($data['data_inventory_element_repair']['CG85EPOS-UF/CM87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG80POS/CM87NEG']);
        // unset($data['data_inventory_element_repair']['CM84POS/CG87NEG']);
        // unset($data['data_inventory_element_repair']['CM84POS/WG87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG85POS-UF/WG87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CG82POS-UF/CR87NEG-UF']);
        // unset($data['data_inventory_element_repair']['CM84POS-UF/CG85NEG-UF']);
        // unset($data['data_inventory_element_repair']['CM84POS/CM87NEG-UF']);


        return view('dashboardRework/dashboard_saw_repair', $data);
    }

    public function get_detail_saw_repair()
    {
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $date = $this->request->getPost('date');

        $data['data_type_battery_by_month'] = $this->M_DashboardRework->get_data_type_battery_by_month($date, $shift, $operator);
        $data['data_type_battery_by_date'] = $this->M_DashboardRework->get_data_type_battery_by_date($date, $shift, $operator);

        echo json_encode($data);
    }

    public function get_data_element_repair()
    {
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $date = $this->request->getPost('date');

        $data['data_element_repair_type_by_month'] = $this->M_DashboardRework->get_data_element_repair_type_by_month($date, $shift, $operator);
        $data['data_element_repair_type_by_date'] = $this->M_DashboardRework->get_data_element_repair_type_by_date($date, $shift, $operator);

        echo json_encode($data);
    }

    // CONTROLLER DASHBOARD FINISHING REPAIR

    public function dashboard_finishing_repair()
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

        if ($bulan != null or $bulan != $current_month) {
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

        // GET DATA FINISHING REPAIR BY MONTH
        $data['data_finishing_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_all = $this->M_DashboardRework->get_data_finishing_repair_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach ($data_all as $d_all) {
                    $total_aktual = $d_all['total_aktual'];
                    array_push($data['data_finishing_repair_by_month'], $total_aktual);
                }
            } else {
                array_push($data['data_finishing_repair_by_month'], 0);
            }
        }


        // GET DATA PARETO REJECT BY LINE
        $data['data_finishing_repair_by_line'] = [];
        $data['data_total_finishing_repair_by_line'] = [];

        $data_line = $this->M_DashboardRework->get_data_total_finishing_repair_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_finishing_repair_by_line'], 'Line ' . $d_line['line']);
            $data['data_total_finishing_repair_by_line'][] = [
                'total_aktual' => $d_line['total_aktual']
            ];
        }

        // GET DATA FINISHING REPAIR BY DATE ALL LINE
        $data['data_finishing_repair_by_date'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_finishing_repair_by_date = $this->M_DashboardRework->get_data_finishing_repair_all_line_by_date($start1, $child_filter);
            if (!empty($data_finishing_repair_by_date)) {
                foreach ($data_finishing_repair_by_date as $da) {
                    if($da['total_aktual'] != NULL) {
                        $data['data_finishing_repair_by_date'][] = [
                            'total_aktual' => $da['total_aktual']
                        ];
                    } else {
                        $data['data_finishing_repair_by_date'][] = [
                            'total_aktual' => 0
                        ];
                    }
                }
            } else {
                $data['data_finishing_repair_by_date'][] = [
                    'total_aktual' => 0
                ];
            }

            $start1 = date("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_finishing_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_all = $this->M_DashboardRework->get_data_average_finishing_repair_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach ($data_all as $d_all) {
                    $data['data_average_finishing_repair_by_month'][] = [
                        'total_aktual' => $d_all['total_aktual']
                    ];
                }
            } else {
                $data['data_average_finishing_repair_by_month'][] = [
                    'total_aktual' => 0
                ];
            }
        }

        // GET DATA AVERAGE ENVELOPE BY DATE ALL LINE
        $data['data_finishing_repair_line_1'] = [];
        $data['data_finishing_repair_line_2'] = [];
        $data['data_finishing_repair_line_3'] = [];
        $data['data_finishing_repair_line_4'] = [];
        $data['data_finishing_repair_line_5'] = [];
        $data['data_finishing_repair_line_6'] = [];
        $data['data_finishing_repair_line_7'] = [];

        while (strtotime($start2) <= strtotime($now)) {
            for ($i = 1; $i <= 7; $i++) {
                $data_finishing_repair_per_line_by_date = $this->M_DashboardRework->get_data_finishing_repair_all_line($start2, $i);
                if (!empty($data_finishing_repair_per_line_by_date)) {
                    foreach ($data_finishing_repair_per_line_by_date as $d1) {
                        array_push($data['data_finishing_repair_line_' . $i], $d1['total_aktual']);
                    }
                } else {
                    array_push($data['data_finishing_repair_line_' . $i], 0);
                }
            }

            $start2 = date("Y-m-d", strtotime("+1 days", strtotime($start2)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_finishing_repair_by_month_line_1'] = [];
        $data['data_finishing_repair_by_month_line_2'] = [];
        $data['data_finishing_repair_by_month_line_3'] = [];
        $data['data_finishing_repair_by_month_line_4'] = [];
        $data['data_finishing_repair_by_month_line_5'] = [];
        $data['data_finishing_repair_by_month_line_6'] = [];
        $data['data_finishing_repair_by_month_line_7'] = [];

        for ($h = 1; $h <= 12; $h++) {
            for ($i = 1; $i <= 7; $i++) {
                $data_finishing_repair_all_line_by_month = $this->M_DashboardRework->get_data_finishing_repair_all_line_by_month($h, $i);
                if (!empty($data_finishing_repair_all_line_by_month)) {
                    foreach ($data_finishing_repair_all_line_by_month as $dalm) {
                        array_push($data['data_finishing_repair_by_month_line_' . $i], $dalm['total_aktual']);
                    }
                } else {
                    array_push($data['data_finishing_repair_by_month_line_' . $i], 0);
                }
            }
        }

        $data['data_inventory_saw_repair'] = [];
        $data_inventory_saw_repair = $this->M_DashboardRework->get_data_type_battery_saw_repair_type_by_month($bulan, $child_filter);
        foreach ($data_inventory_saw_repair as $value) {
            // $data['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = ($data['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data['data_inventory_saw_repair'][$value['type_battery']] = [
                'total_battery' => ($data['data_inventory_saw_repair'][$value['type_battery']] ?? 0) + $value['total'],
            ];
        }

        $data_data_saw_repair = [];
        $data_saw_repair = $this->M_DashboardRework->get_data_finishing_repair_type_by_month($bulan, $child_filter);
        foreach ($data_saw_repair as $value) {
            // $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_data_saw_repair[$value['type_battery']] = [
                'total_battery' => ($data_data_saw_repair[$value['type_battery']] ?? 0) + $value['total'],
            ];
        }
        foreach ($data_data_saw_repair as $key => $value) {
            if (array_key_exists($key, $data['data_inventory_saw_repair'])) {
                // $data['data_inventory_saw_repair'][$key] = $data['data_inventory_saw_repair'][$key] - $value;
                $data['data_inventory_saw_repair'][$key]['total_battery'] = $data['data_inventory_saw_repair'][$key]['total_battery'] - $value['total_battery'];
            }
        }
        // if (array_key_exists('N50-08-Hyb', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['N50-08-Hyb']['total_battery'] += 116;
        // }
        // if (array_key_exists('N50-08-MF', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['N50-08-MF']['total_battery'] -= 508;
        // }
        // if (array_key_exists('N50-09-Con', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['N50-09-Con']['total_battery'] -= 256;
        // }
        // if (array_key_exists('N70-13-Con', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['N70-13-Con']['total_battery'] -= 44;
        // }
        // if (array_key_exists('NS60-11-Hyb', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['NS60-11-Hyb']['total_battery'] -= 942;
        // }
        // if (array_key_exists('NS60-12-Con', $data['data_inventory_saw_repair'])) {
        //     $data['data_inventory_saw_repair']['NS60-12-Con']['total_battery'] -= 113;
        // }
        $data_stock_finishing_repair = $this->M_DashboardRework->get_data_adjustment_stock_finishing_repair();
        foreach ($data_stock_finishing_repair as $key => $value) {
            if (array_key_exists($value['type_battery'], $data['data_inventory_saw_repair'])) {
                if($value['status'] != 'Non Aktif') {
                $data['data_inventory_saw_repair'][$value['type_battery']]['total_battery'] = $data['data_inventory_saw_repair'][$value['type_battery']]['total_battery'] + $value['total_battery'];
                $data['data_inventory_saw_repair'][$value['type_battery']]['status'] = $value['status'];
                $data['data_inventory_saw_repair'][$value['type_battery']]['id_adjustment'] = $value['id_adjustment'];
                } else {
                unset($data['data_inventory_saw_repair'][$value['type_battery']]);
                }
            }
        }

        foreach ($data['data_inventory_saw_repair'] as $key => $value) {
            $data_finishing_repair = $this->M_DashboardRework->get_data_element_finishing_repair_by_type_by_month($key);
            foreach ($data_finishing_repair as $value_element) {
                $data['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']] = [
                    'pasangan_positif' => ($data['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']]['pasangan_positif'] ?? 0) + (($value_element['pasangan_positif'] * $value['total_battery'])),
                    'pasangan_negatif' => ($data['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']]['pasangan_negatif'] ?? 0) + (($value_element['pasangan_negatif'] * $value['total_battery'])),
                ];
            }
        }

        // $data['data_element_finishing_repair'] = [];
        // $data_finishing_repair = $this->M_DashboardRework->get_data_element_finishing_repair_type_by_month($start, $child_filter);
        // // foreach ($data['data_inventory_saw_repair'] as $key => $value) {
        // //     $data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = [
        // //         'pasangan_positif' => ($data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total_battery'])),
        // //         'pasangan_negatif' => ($data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total_battery'])),
        // //     ];
        // // }
        // foreach ($data_finishing_repair as $value) {
        //     $data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = [
        //         'pasangan_positif' => ($data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total'])),
        //         'pasangan_negatif' => ($data['data_element_finishing_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total'])),
        //     ];
        // }

        return view('dashboardRework/dashboard_finishing_saw_repair', $data);
    }

    public function get_detail_finishing_repair()
    {
        $child_filter = $this->request->getPost('child_filter');
        $date = $this->request->getPost('date');

        $data['data_type_battery_by_month'] = $this->M_DashboardRework->get_data_type_battery_finishing_repair_by_month($date, $child_filter);
        $data['data_type_battery_by_date'] = $this->M_DashboardRework->get_data_type_battery_finishing_repair_by_date($date, $child_filter);

        echo json_encode($data);
    }

    public function get_data_saw_repair()
    {
        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $date = $this->request->getPost('date');

        $data['data_element_repair_type_by_month'] = $this->M_DashboardRework->get_data_element_repair_type_by_month($date, $shift, $operator);
        $data['data_element_repair_type_by_date'] = $this->M_DashboardRework->get_data_element_repair_type_by_date($date, $shift, $operator);

        echo json_encode($data);
    }

    public function dashboard_resume()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $start1 = date('Y-m-01');
        $start2 = date('Y-m-01');
        $start3 = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');

        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $bulan = $this->request->getPost('bulan');

        if ($shift == null) {
            $shift = '';
        }

        if ($operator == null) {
            $operator = '';
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null or $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $start1 = date('Y-m-01', strtotime($bulan));
            $start2 = date('Y-m-01', strtotime($bulan));
            $start3 = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

        $data['shift'] = $shift;
        $data['operator'] = $operator;
        $data['bulan'] = $bulan;

        // GET DATA SAW REPAIR BY DATE
        $data['data_saw_repair_by_date'] = [];

        while (strtotime($start) <= strtotime($now)) {
            $data_saw_repair_by_date = $this->M_DashboardRework->get_data_saw_repair_by_date($start, $shift, $operator);
            if (!empty($data_saw_repair_by_date)) {
                foreach ($data_saw_repair_by_date as $d_by_date) {
                    $data_saw_repair = [
                        'qty' => $d_by_date['qty'],
                    ];

                    $data['data_saw_repair_by_date'][] = $data_saw_repair;
                }
            } else {
                $data_saw_repair = [
                    'qty' => 0,
                ];

                $data['data_saw_repair_by_date'][] = $data_saw_repair;
            }
            $start = date("Y-m-d", strtotime("+1 days", strtotime($start)));
        }

        // GET DATA SAW REPAIR BY MONTH
        $data['data_saw_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_saw_repair_by_month = $this->M_DashboardRework->get_data_saw_repair_by_month($i, $bulan, $shift, $operator);
            if (!empty($data_saw_repair_by_month)) {
                foreach ($data_saw_repair_by_month as $d_by_date) {
                    $data_saw_repair = [
                        'qty' => $d_by_date['qty'],
                    ];

                    $data['data_saw_repair_by_month'][] = $data_saw_repair;
                }
            } else {
                $data_saw_repair = [
                    'qty' => 0,
                ];

                $data['data_saw_repair_by_month'][] = $data_saw_repair;
            }
        }

        $jenis_dashboard = $this->request->getPost('jenis_dashboard');
        $parent_filter = $this->request->getPost('parent_filter');
        $child_filter = $this->request->getPost('child_filter');
        $baby_filter = $this->request->getPost('baby_filter');

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

        if ($bulan != null or $bulan != $current_month) {
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

        // GET DATA FINISHING REPAIR BY MONTH
        $data['data_finishing_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_all = $this->M_DashboardRework->get_data_finishing_repair_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach ($data_all as $d_all) {
                    $total_aktual = $d_all['total_aktual'];
                    array_push($data['data_finishing_repair_by_month'], $total_aktual);
                }
            } else {
                array_push($data['data_finishing_repair_by_month'], 0);
            }
        }


        // GET DATA PARETO REJECT BY LINE
        $data['data_finishing_repair_by_line'] = [];
        $data['data_total_finishing_repair_by_line'] = [];

        $data_line = $this->M_DashboardRework->get_data_total_finishing_repair_line_by_month($bulan);

        foreach ($data_line as $d_line) {
            array_push($data['data_finishing_repair_by_line'], 'Line ' . $d_line['line']);
            $data['data_total_finishing_repair_by_line'][] = [
                'total_aktual' => $d_line['total_aktual']
            ];
        }

        // GET DATA FINISHING REPAIR BY DATE ALL LINE
        $data['data_finishing_repair_by_date'] = [];
        while (strtotime($start1) <= strtotime($now)) {
            $data_finishing_repair_by_date = $this->M_DashboardRework->get_data_finishing_repair_all_line_by_date($start1, $child_filter);
            if (!empty($data_finishing_repair_by_date)) {
                foreach ($data_finishing_repair_by_date as $da) {
                    if($da['total_aktual'] != NULL) {
                        $data['data_finishing_repair_by_date'][] = [
                            'total_aktual' => $da['total_aktual']
                        ];
                    } else {
                        $data['data_finishing_repair_by_date'][] = [
                            'total_aktual' => 0
                        ];
                    }
                }
            } else {
                $data['data_finishing_repair_by_date'][] = [
                    'total_aktual' => 0
                ];
            }

            $start1 = date("Y-m-d", strtotime("+1 days", strtotime($start1)));
        }

        // GET DATA AVERAGE REJECT BY MONTH ALL LINE
        $data['data_average_finishing_repair_by_month'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_all = $this->M_DashboardRework->get_data_average_finishing_repair_by_month($i, $bulan, $child_filter);
            if (!empty($data_all)) {
                foreach ($data_all as $d_all) {
                    $data['data_average_finishing_repair_by_month'][] = [
                        'total_aktual' => $d_all['total_aktual']
                    ];
                }
            } else {
                $data['data_average_finishing_repair_by_month'][] = [
                    'total_aktual' => 0
                ];
            }
        }

        return view('dashboardRework/dashboard_resume', $data);
    }

    public function download_data_inventory_element_repair()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');

        $shift = $this->request->getPost('shift');
        $operator = $this->request->getPost('operator');
        $bulan = $this->request->getPost('bulan');

        if ($shift == null) {
            $shift = '';
        }

        if ($operator == null) {
            $operator = '';
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null) {
            $start = date('Y-m-01', strtotime($bulan));
        }
        $data_inventory['data_inventory_element_repair'] = [];
        $data_inventory_element_repair = $this->M_DashboardRework->get_data_element_repair_type_by_month($bulan, $shift, $operator);
        foreach ($data_inventory_element_repair as $value) {
            // $data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = ($data['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_inventory['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data_inventory['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total']) / 2),
                'pasangan_negatif' => ($data_inventory['data_inventory_element_repair'][$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total']) / 2),
                'status' => ''
            ];
        }

        $data_data_saw_repair = [];
        $data_saw_repair = $this->M_DashboardRework->get_data_saw_repair_type_by_month($bulan, $shift, $operator);
        foreach ($data_saw_repair as $value) {
            // $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + (($value['pasangan_positif'] * $value['total']) / 2),
                'pasangan_negatif' => ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + (($value['pasangan_negatif'] * $value['total']) / 2),
            ];
        }
        foreach ($data_data_saw_repair as $key => $value) {
            if (array_key_exists($key, $data_inventory['data_inventory_element_repair'])) {
                // $data_inventory['data_inventory_element_repair'][$key] = $data_inventory['data_inventory_element_repair'][$key] - $value;
                $data_inventory['data_inventory_element_repair'][$key]['pasangan_positif'] = $data_inventory['data_inventory_element_repair'][$key]['pasangan_positif'] - $value['pasangan_positif'];
                $data_inventory['data_inventory_element_repair'][$key]['pasangan_negatif'] = $data_inventory['data_inventory_element_repair'][$key]['pasangan_negatif'] - $value['pasangan_negatif'];
            }
        }
 
        $data_stock_saw_repair = $this->M_DashboardRework->get_data_adjustment_stock_saw_repair();
        foreach ($data_stock_saw_repair as $value) {
            $data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = [
                'pasangan_positif' => ($data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_positif'] ?? 0) + $value['total_positif'],
                'pasangan_negatif' => ($data_stock_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']]['pasangan_negatif'] ?? 0) + $value['total_negatif'],
                'status' => $value['status']
            ];
        }
        foreach ($data_stock_saw_repair as $key => $value) {
            if($value['status'] == 'Non Aktif') {
                unset($data_inventory['data_inventory_element_repair'][$key]);
            }
            if (array_key_exists($key, $data_inventory['data_inventory_element_repair'])) {
                $data_inventory['data_inventory_element_repair'][$key]['pasangan_positif'] = $data_inventory['data_inventory_element_repair'][$key]['pasangan_positif'] + $value['pasangan_positif'];
                $data_inventory['data_inventory_element_repair'][$key]['pasangan_negatif'] = $data_inventory['data_inventory_element_repair'][$key]['pasangan_negatif'] + $value['pasangan_negatif'];
            }
        }

        // dd($data_inventory['data_inventory_element_repair']);
        // if (array_key_exists('CG80POS/CG87NEG', $data_inventory['data_inventory_element_repair'])) {
        //     // $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 3000;
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 400; // perubahan baru 26/09/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 2731.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 5135.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] -= 2183.5; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] -= 2623; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_positif'] += 2870.5; // perubahan baru 13/12/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG87NEG']['pasangan_negatif'] += 4741.5; // perubahan baru 13/12/2023
        // }
        // if (array_key_exists('CG82POS/CG87NEG', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 3000;
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 4300;
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 1600; // perubahan baru 26/09/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 932.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 1876.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_positif'] += 6624; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS/CG87NEG']['pasangan_negatif'] += 8235; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG85EPOS-UF/WG87NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_positif'] += 1000;
        //     $data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_negatif'] += 1000;
        //     $data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_positif'] += 3305; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WG87NEG-UF']['pasangan_negatif'] += 3290; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CM84POS/CM87NEG', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] += 700;
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] += 900;
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] += 1211; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] += 882; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_positif'] -= 1095; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG']['pasangan_negatif'] -= 912.5; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 11000;
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 8500;
        // }
        // if (array_key_exists('WM84ESPOS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']['pasangan_positif'] -= 900;
        //     $data_inventory['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']['pasangan_negatif'] -= 1000;
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 4302; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 3585; // perubahan baru 15/11/2023
        // } else if (!array_key_exists('CM84POS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] = 4302; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] = 3585; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('WM84POS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 300;
        //     $data_inventory['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 280;
        // }
        // if (array_key_exists('CM84POS-UF/CM87NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/CM87NEG-UF']['pasangan_positif'] -= 423.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/CM87NEG-UF']['pasangan_negatif'] -= 1213; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('WG83POS-UF/WG87NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] -= 4393.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] -= 4393.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] -= 52; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] -= 52; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_positif'] += 9450.5; // perubahan baru 13/12/2023
        //     $data_inventory['data_inventory_element_repair']['WG83POS-UF/WG87NEG-UF']['pasangan_negatif'] += 9404; // perubahan baru 13/12/2023
        // }
        // if (array_key_exists('CG82POS-UF/CG87NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_positif'] -= 3137; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 3565.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_positif'] -= 91.5; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG82POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 91.5; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('YG80POS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_positif'] += 17; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_negatif'] -= 94; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_positif'] += 60; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG80POS-UF/YG85NEG-UF']['pasangan_negatif'] += 60; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG80POS-UF/CG87NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_positif'] -= 613; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_negatif'] -= 714; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_positif'] += 3336; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG87NEG-UF']['pasangan_negatif'] += 3753; // perubahan baru 15/11/2023
        // }
        // if (array_key_exists('CG80POS/CG85NEG', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG85NEG']['pasangan_positif'] -= 72; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS/CG85NEG']['pasangan_negatif'] -= 112.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CG80POS-UF/CG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG85NEG-UF']['pasangan_positif'] += 2099; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CG80POS-UF/CG85NEG-UF']['pasangan_negatif'] += 2087; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG82POS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] += 47.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] += 47.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YD85POS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YD85POS-UF/YG85NEG-UF']['pasangan_positif'] += 64; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['YD85POS-UF/YG85NEG-UF']['pasangan_negatif'] += 48; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG79HDPOS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YG79HDPOS-UF/YG85NEG-UF']['pasangan_positif'] += 60; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG79HDPOS-UF/YG85NEG-UF']['pasangan_negatif'] += 60; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('CM84POS-UF/WM85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_positif'] += 2828.5; // perubahan baru 14/11/2023
        //     $data_inventory['data_inventory_element_repair']['CM84POS-UF/WM85NEG-UF']['pasangan_negatif'] += 754.5; // perubahan baru 14/11/2023
        // }
        // if (array_key_exists('YG82POS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] += 47.5; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] += 47.5; // perubahan baru 15/11/2023
        // } else if (!array_key_exists('YG82POS-UF/YG85NEG-UF', $data_inventory['data_inventory_element_repair'])) {
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_positif'] = 47.5; // perubahan baru 15/11/2023
        //     $data_inventory['data_inventory_element_repair']['YG82POS-UF/YG85NEG-UF']['pasangan_negatif'] = 47.5; // perubahan baru 15/11/2023
        // }
        // unset($data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WM85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['WM84ESPOS-UF/WM85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG82POS/CG85NEG']);
        // unset($data_inventory['data_inventory_element_repair']['WM84POS-UF/WM85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS-UF/WM87ESNEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG82POS/CR87NEG']);
        // unset($data_inventory['data_inventory_element_repair']['WG83POS-UF/WM87ESNEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG85EPOS-UF/CG87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG85POS-UF/WM85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS/WM85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS-UF/CM87NEG']);
        // unset($data_inventory['data_inventory_element_repair']['CG85EPOS-UF/CR87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG82POS/CM87NEG']);
        // unset($data_inventory['data_inventory_element_repair']['CG85EPOS-UF/WM87ESNEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG85EPOS-UF/CM87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG80POS/CM87NEG']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS/CG87NEG']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS/WG87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG85POS-UF/WG87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CG82POS-UF/CR87NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS-UF/CG85NEG-UF']);
        // unset($data_inventory['data_inventory_element_repair']['CM84POS/CM87NEG-UF']);
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Menambahkan data ke worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saw Repair');
        $data = array(
            array('Type Positif', 'Type Negatif', 'Qty Positif', 'Qty Negatif'),
        );
        $isExist = [];
        if($data_inventory['data_inventory_element_repair'] !== NULL) {
            foreach ($data_inventory['data_inventory_element_repair'] as $key => $dl) {
                $type_positif = explode('/', $key)[0];
                $type_negatif = explode('/', $key)[1];
                $data[] = array($type_positif, $type_negatif, $dl['pasangan_positif'], $dl['pasangan_negatif']);
            };
        }

        // Memasukkan data array ke dalam worksheet
        $sheet->fromArray($data);

        // Mengatur header respons HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Saw Repair.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function download_data_inventory_battery_half_finish()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');

        $bulan = $this->request->getPost('bulan');
        $child_filter = $this->request->getPost('child_filter');

        if ($child_filter == null) {
            $child_filter = 0;
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null) {
            $start = date('Y-m-01', strtotime($bulan));
        }
        $data_inventory['data_inventory_saw_repair'] = [];
        $data_inventory_saw_repair = $this->M_DashboardRework->get_data_type_battery_saw_repair_type_by_month($bulan, $child_filter);
        foreach ($data_inventory_saw_repair as $value) {
            // $data_inventory['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = ($data_inventory['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_inventory['data_inventory_saw_repair'][$value['type_battery']] = [
                'total_battery' => ($data_inventory['data_inventory_saw_repair'][$value['type_battery']] ?? 0) + $value['total'],
            ];
        }

        $data_data_saw_repair = [];
        $data_saw_repair = $this->M_DashboardRework->get_data_finishing_repair_type_by_month($bulan, intval($child_filter));
        foreach ($data_saw_repair as $value) {
            // $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_data_saw_repair[$value['type_battery']] = [
                'total_battery' => ($data_data_saw_repair[$value['type_battery']] ?? 0) + $value['total'],
            ];
        }
        foreach ($data_data_saw_repair as $key => $value) {
            if (array_key_exists($key, $data_inventory['data_inventory_saw_repair'])) {
                // $data_inventory['data_inventory_saw_repair'][$key] = $data_inventory['data_inventory_saw_repair'][$key] - $value;
                $data_inventory['data_inventory_saw_repair'][$key]['total_battery'] = $data_inventory['data_inventory_saw_repair'][$key]['total_battery'] - $value['total_battery'];
            }
        }

        if (array_key_exists('N50-08-Hyb', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-08-Hyb']['total_battery'] += 116;
        }
        if (array_key_exists('N50-08-MF', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-08-MF']['total_battery'] -= 508;
        }
        if (array_key_exists('N50-09-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-09-Con']['total_battery'] -= 256;
        }
        if (array_key_exists('N70-13-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N70-13-Con']['total_battery'] -= 44;
        }
        if (array_key_exists('NS60-11-Hyb', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['NS60-11-Hyb']['total_battery'] -= 942;
        }
        if (array_key_exists('NS60-12-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['NS60-12-Con']['total_battery'] -= 113;
        }
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Menambahkan data ke worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saw Repair');
        $data = array(
            array('Type Battery', 'Qty Battery'),
        );
        $isExist = [];
        if($data_inventory['data_inventory_saw_repair'] !== NULL) {
            foreach ($data_inventory['data_inventory_saw_repair'] as $key => $dl) {
                $data[] = array($key, $dl['total_battery']);
            };
        }

        // Memasukkan data array ke dalam worksheet
        $sheet->fromArray($data);

        // Mengatur header respons HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Inventory Battery Half Finish.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    public function download_data_inventory_plate_battery_half_finish()
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');

        $bulan = $this->request->getPost('bulan');
        $child_filter = $this->request->getPost('child_filter');

        if ($child_filter == null) {
            $child_filter = 0;
        }

        if ($bulan == null) {
            $bulan = date('Y-m');
        }

        if ($bulan != null) {
            $start = date('Y-m-01', strtotime($bulan));
        }
        $data_inventory['data_inventory_saw_repair'] = [];
        $data_inventory_saw_repair = $this->M_DashboardRework->get_data_type_battery_saw_repair_type_by_month($bulan, $child_filter);
        foreach ($data_inventory_saw_repair as $value) {
            // $data_inventory['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] = ($data_inventory['data_inventory_saw_repair'][$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_inventory['data_inventory_saw_repair'][$value['type_battery']] = [
                'total_battery' => ($data_inventory['data_inventory_saw_repair'][$value['type_battery']] ?? 0) + $value['total'],
            ];
        }

        $data_data_saw_repair = [];
        $data_saw_repair = $this->M_DashboardRework->get_data_finishing_repair_type_by_month($bulan, intval($child_filter));
        foreach ($data_saw_repair as $value) {
            // $data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] = ($data_data_saw_repair[$value['type_positif'] . '/' . $value['type_negatif']] ?? 0) + $value['total'];
            $data_data_saw_repair[$value['type_battery']] = [
                'total_battery' => ($data_data_saw_repair[$value['type_battery']] ?? 0) + $value['total'],
            ];
        }
        foreach ($data_data_saw_repair as $key => $value) {
            if (array_key_exists($key, $data_inventory['data_inventory_saw_repair'])) {
                // $data_inventory['data_inventory_saw_repair'][$key] = $data_inventory['data_inventory_saw_repair'][$key] - $value;
                $data_inventory['data_inventory_saw_repair'][$key]['total_battery'] = $data_inventory['data_inventory_saw_repair'][$key]['total_battery'] - $value['total_battery'];
            }
        }

        if (array_key_exists('N50-08-Hyb', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-08-Hyb']['total_battery'] += 116;
        }
        if (array_key_exists('N50-08-MF', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-08-MF']['total_battery'] -= 508;
        }
        if (array_key_exists('N50-09-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N50-09-Con']['total_battery'] -= 256;
        }
        if (array_key_exists('N70-13-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['N70-13-Con']['total_battery'] -= 44;
        }
        if (array_key_exists('NS60-11-Hyb', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['NS60-11-Hyb']['total_battery'] -= 942;
        }
        if (array_key_exists('NS60-12-Con', $data_inventory['data_inventory_saw_repair'])) {
            $data_inventory['data_inventory_saw_repair']['NS60-12-Con']['total_battery'] -= 113;
        }

        foreach ($data_inventory['data_inventory_saw_repair'] as $key => $value) {
            $data_finishing_repair = $this->M_DashboardRework->get_data_element_finishing_repair_by_type_by_month($key);
            foreach ($data_finishing_repair as $value_element) {
                $data_inventory['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']] = [
                    'pasangan_positif' => ($data_inventory['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']]['pasangan_positif'] ?? 0) + (($value_element['pasangan_positif'] * $value['total_battery'])),
                    'pasangan_negatif' => ($data_inventory['data_element_finishing_repair'][$value_element['type_positif'] . '/' . $value_element['type_negatif']]['pasangan_negatif'] ?? 0) + (($value_element['pasangan_negatif'] * $value['total_battery'])),
                ];
            }
        }
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Menambahkan data ke worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saw Repair');
        $data = array(
            array('Type Positif', 'Type Negatif', 'Qty Positif', 'Qty Negatif'),
        );
        $isExist = [];
        if($data_inventory['data_element_finishing_repair'] !== NULL) {
            foreach ($data_inventory['data_element_finishing_repair'] as $key => $dl) {
                $type_positif = explode('/', $key)[0];
                $type_negatif = explode('/', $key)[1];
                $data[] = array($type_positif, $type_negatif, $dl['pasangan_positif'], $dl['pasangan_negatif']);
            };
        }

        // Memasukkan data array ke dalam worksheet
        $sheet->fromArray($data);

        // Mengatur header respons HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Inventory Plate Battery Half Finish.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
