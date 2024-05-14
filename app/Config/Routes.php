<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Login::index');

//LOGIN
$routes->get('/login', 'Login::index');
$routes->post('/login/proses_login', 'Login::proses_login');
$routes->get('/logout', 'Login::logout');


$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    //DASHBOARD VIEW PROD2
    $routes->get('assy/amb1', 'DashboardAmb1::dashboard_lhp_assy');
    $routes->post('assy/amb1', 'DashboardAmb1::dashboard_lhp_assy');
    $routes->get('assy/amb2', 'DashboardAmb2::dashboard_lhp_assy');
    $routes->post('assy/amb2', 'DashboardAmb2::dashboard_lhp_assy');
    $routes->get('assy/mcb', 'DashboardMCB::dashboard_lhp_assy');
    $routes->post('assy/mcb', 'DashboardMCB::dashboard_lhp_assy');
    $routes->get('assy/home', 'Dashboard::index2');
    
    //DASHBOARD EFF ASSY
    $routes->get('/', 'Dashboard::index');
    $routes->get('assy', 'Dashboard::dashboard_lhp_assy');
    $routes->post('assy', 'Dashboard::dashboard_lhp_assy');
    $routes->post('assy/get_data_line_stop', 'Dashboard::get_data_line_stop');
    $routes->post('assy/get_data_line_stop_by_shift', 'Dashboard::get_data_line_stop_by_shift');
    $routes->post('assy/get_data_line_stop_by_grup', 'Dashboard::get_data_line_stop_by_grup');
    $routes->post('assy/get_data_line_stop_by_kss', 'Dashboard::get_data_line_stop_by_kss');

    //DASHBOARD EFF WET FINISHING
    $routes->get('wet_charging', 'DashboardRectifierWet::dashboard_lhp_wet_charging');
    $routes->post('wet_charging', 'DashboardRectifierWet::dashboard_lhp_wet_charging');
    $routes->post('wet_charging/get_data_charge_by_date', 'DashboardRectifierWet::get_data_charge_by_date');
    $routes->post('wet_charging/get_data_line_stop_by_shift', 'DashboardRectifierWet::get_data_line_stop_by_shift');
    $routes->post('wet_charging/get_data_line_stop_by_grup', 'DashboardRectifierWet::get_data_line_stop_by_grup');
    
    //DASHBOARD EFF WET FINISHING
    $routes->get('wet_finishing', 'DashboardWetFinishing::dashboard_lhp_wet_finishing');
    $routes->post('wet_finishing', 'DashboardWetFinishing::dashboard_lhp_wet_finishing');
    $routes->post('wet_finishing/get_data_line_stop', 'DashboardWetFinishing::get_data_line_stop');
    $routes->post('wet_finishing/get_data_line_stop_by_shift', 'DashboardWetFinishing::get_data_line_stop_by_shift');
    $routes->post('wet_finishing/get_data_line_stop_by_grup', 'DashboardWetFinishing::get_data_line_stop_by_grup');
    $routes->post('wet_finishing/get_data_line_stop_by_kss', 'DashboardWetFinishing::get_data_line_stop_by_kss');
    
    $routes->get('wet_finishing/wet_a', 'DashboardWetFinishingA::dashboard_lhp_wet_finishing');
    $routes->post('wet_finishing/wet_a', 'DashboardWetFinishingA::dashboard_lhp_wet_finishing');
    $routes->get('wet_finishing/wet_f', 'DashboardWetFinishingF::dashboard_lhp_wet_finishing');
    $routes->post('wet_finishing/wet_f', 'DashboardWetFinishingF::dashboard_lhp_wet_finishing');
    
    //DASHBOARD PENDING WET
    $routes->get('pending_wet', 'DashboardPendingWet::dashboard_pending_wet');
    $routes->post('pending_wet', 'DashboardPendingWet::dashboard_pending_wet');
    $routes->post('pending_wet/get_detail_pending', 'DashboardPendingWet::get_detail_pending');

    // DASHBOARD REJECT
    $routes->get('reject', 'DashboardAssyRejection::dashboard_reject_assy');
    $routes->post('reject', 'DashboardAssyRejection::dashboard_reject_assy');
    $routes->post('reject/get_detail_rejection', 'DashboardAssyRejection::get_detail_rejection');
    $routes->get('reject/get_detail_rejection', 'DashboardAssyRejection::get_detail_rejection');

    $routes->get('reject_wet', 'DashboardAssyRejectionWET::dashboard_reject_assy');
    $routes->post('reject_wet', 'DashboardAssyRejectionWET::dashboard_reject_assy');
    $routes->post('reject_wet/get_detail_rejection', 'DashboardAssyRejectionWET::get_detail_rejection');
    $routes->get('reject_wet/get_detail_rejection', 'DashboardAssyRejectionWET::get_detail_rejection');

    $routes->get('reject_mcb', 'DashboardAssyRejectionMCB::dashboard_reject_assy');
    $routes->post('reject_mcb', 'DashboardAssyRejectionMCB::dashboard_reject_assy');
    $routes->post('reject_mcb/get_detail_rejection', 'DashboardAssyRejectionMCB::get_detail_rejection');
    $routes->get('reject_mcb/get_detail_rejection', 'DashboardAssyRejectionMCB::get_detail_rejection');

    // DASHBOARD LINE STOP ASSY
    $routes->get('line_stop', 'DashboardAssyLineStop::dashboard_line_stop_assy');
    $routes->post('line_stop', 'DashboardAssyLineStop::dashboard_line_stop_assy');
    $routes->post('line_stop/get_detail_line_stop', 'DashboardAssyLineStop::get_detail_line_stop');
    $routes->get('line_stop/get_detail_line_stop', 'DashboardAssyLineStop::get_detail_line_stop');

    $routes->get('line_stop_wet', 'DashboardAssyLineStopWET::dashboard_line_stop_assy');
    $routes->post('line_stop_wet', 'DashboardAssyLineStopWET::dashboard_line_stop_assy');
    $routes->post('line_stop_wet/get_detail_line_stop', 'DashboardAssyLineStopWET::get_detail_line_stop');
    $routes->get('line_stop_wet/get_detail_line_stop', 'DashboardAssyLineStopWET::get_detail_line_stop');

    $routes->get('line_stop_mcb', 'DashboardAssyLineStopMCB::dashboard_line_stop_assy');
    $routes->post('line_stop_mcb', 'DashboardAssyLineStopMCB::dashboard_line_stop_assy');
    $routes->post('line_stop_mcb/get_detail_line_stop', 'DashboardAssyLineStopMCB::get_detail_line_stop');
    $routes->get('line_stop_mcb/get_detail_line_stop', 'DashboardAssyLineStopMCB::get_detail_line_stop');
});


//DASHBOARD REWORK

$routes->group('dashboard_rework', ['filter' => 'auth'], function ($routes) {
    //DASHBOARD SAW REPAIR
    $routes->get('saw_repair', 'DashboardRework::dashboard_saw_repair');
    $routes->post('download_data_inventory_element_repair', 'DashboardRework::download_data_inventory_element_repair');
    $routes->post('saw_repair', 'DashboardRework::dashboard_saw_repair');
    $routes->post('saw_repair/get_detail_saw_repair', 'DashboardRework::get_detail_saw_repair');
});

//DASHBOARD PLATE REJECTION

$routes->group('dashboard_plate_rejection', ['filter' => 'auth'], function ($routes) {
    //DASHBOARD PLATE CUTTING
    $routes->get('reject_plate_cutting', 'DashboardPlateRejection::dashboard_reject_plate_cutting');
    $routes->post('reject_plate_cutting', 'DashboardPlateRejection::dashboard_reject_plate_cutting');
    $routes->post('reject_plate_cutting/get_detail_rejection', 'DashboardPlateRejection::get_detail_rejection');
    
    //DASHBOARD ENVELOPE
    $routes->get('envelope', 'DashboardPlateRejection::dashboard_envelope');
    $routes->post('envelope', 'DashboardPlateRejection::dashboard_envelope');
    $routes->post('envelope/get_detail_envelope', 'DashboardPlateRejection::get_detail_envelope');
    
    //DASHBOARD COS
    $routes->get('cos', 'DashboardPlateRejection::dashboard_cos');
    $routes->post('cos', 'DashboardPlateRejection::dashboard_cos');
    $routes->post('cos/get_detail_cos', 'DashboardPlateRejection::get_detail_cos');
    
    //DASHBOARD POTONG BATTERY
    $routes->get('potong_battery', 'DashboardPlateRejection::dashboard_potong_battery');
    $routes->post('potong_battery', 'DashboardPlateRejection::dashboard_potong_battery');
    $routes->post('potong_battery/get_data_plate_ng', 'DashboardPlateRejection::get_data_plate_ng');
    $routes->post('potong_battery/get_data_element_repair', 'DashboardPlateRejection::get_data_element_repair');
});

//LHP
$routes->group('lhp', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Home::lhp_view');
    $routes->get('month/(:any)', 'Home::lhp_view/$1');
    $routes->post('add_lhp', 'Home::add_lhp');
    $routes->get('delete_lhp/(:num)', 'Home::delete_lhp/$1');
    $routes->post('getPartNo', 'Home::getPartNo');
    $routes->post('getCT', 'Home::getCT');
    $routes->post('get_proses_breakdown', 'Home::get_proses_breakdown');
    $routes->post('get_kategori_reject', 'Home::get_kategori_reject');
    $routes->post('get_kategori_pending', 'Home::get_kategori_pending');
    $routes->post('save_lhp', 'Home::save_lhp');
    $routes->get('detail_lhp/(:num)', 'Home::detail_lhp/$1');
    $routes->post('update_lhp', 'Home::update_lhp');
    $routes->post('get_data_andon', 'Home::get_data_andon');
    $routes->post('pilih_andon', 'Home::pilih_andon');
    $routes->get('hapus_lhp/(:num)', 'Home::hapus_lhp/$1');
    $routes->get('delete_line_stop/(:num)/(:num)', 'Home::delete_line_stop/$1/$2');
    $routes->get('delete_reject/(:num)/(:num)', 'Home::delete_reject/$1/$2');
    $routes->get('delete_pending/(:num)/(:num)', 'Home::delete_pending/$1/$2');
    $routes->post('download', 'Home::download');
    $routes->get('update_kategori_andon', 'Home::get_kategori_andon');
});

//MCB
$routes->group('mcb', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MCB::mcb_view');
    $routes->get('month/(:segment)', 'MCB::mcb_view/$1');
    $routes->post('download', 'MCB::download');
});

//WET FINISHING
$routes->group('wet_finishing', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'WET_Finishing::wet_view');
    $routes->get('month/(:segment)', 'WET_Finishing::wet_view/$1');
    $routes->post('add_lhp', 'WET_Finishing::add_lhp');
    $routes->get('detail_lhp/(:num)', 'WET_Finishing::detail_lhp/$1');
    $routes->post('get_kategori_pending', 'WET_Finishing::get_kategori_pending');
    $routes->post('update_lhp', 'WET_Finishing::update_lhp');
    $routes->get('hapus_lhp/(:num)', 'WET_Finishing::hapus_lhp/$1');
    $routes->post('download', 'WET_Finishing::download');
});

//PLATECUTTING
$routes->group('platecutting', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PlateCutting::platecutting_view');
    $routes->get('month/(:segment)', 'PlateCutting::platecutting_view/$1');
    $routes->post('save', 'PlateCutting::save');
    $routes->get('detail_platecutting/(:segment)', 'PlateCutting::detail_platecutting/$1');
    $routes->post('detail_platecutting/delete', 'PlateCutting::delete_platecutting');
    $routes->post('download', 'PlateCutting::download');
});

//ENVELOPE
$routes->group('envelope', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Envelope::envelope_view');
    $routes->get('month/(:segment)', 'Envelope::envelope_view/$1');
    $routes->post('save', 'Envelope::save');
    $routes->get('detail_envelope/(:segment)', 'Envelope::detail_envelope/$1');
    $routes->post('detail_envelope/delete', 'Envelope::delete_envelope');
    $routes->post('download', 'Envelope::download');
});

//COS
$routes->group('cos', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Cos::cos_view');
    $routes->get('month/(:segment)', 'Cos::cos_view/$1');
    $routes->post('save', 'Cos::save');
    $routes->get('detail_cos/(:segment)', 'Cos::detail_cos/$1');
    $routes->post('detail_cos/edit', 'Cos::edit');
    $routes->post('detail_cos/delete', 'Cos::delete_cos');
    $routes->post('download', 'Cos::download');
    $routes->post('getPartNo', 'Cos::getPartNo');
    $routes->post('material_in', 'Cos::material_in');
    $routes->post('qty_material_in', 'Cos::qty_material_in');
    $routes->post('actual_loading', 'Cos::actual_loading');
});

//PW
$routes->group('pw', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Pw::pw_view');
    $routes->get('month/(:segment)', 'Pw::pw_view/$1');
    $routes->post('save', 'Pw::save');
    $routes->get('detail_pw/(:segment)', 'Pw::detail_pw/$1');
    $routes->post('detail_pw/edit', 'Pw::edit');
    $routes->post('detail_pw/delete', 'Pw::delete_pw');
    $routes->post('download', 'Pw::download');
    $routes->post('getPartNo', 'Pw::getPartNo');
});

//MASTER PLATE
$routes->group('master_plate', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterPlate::index');
    $routes->post('edit', 'MasterPlate::update_plate');
    $routes->post('delete', 'MasterPlate::delete_plate');
});

//MASTER TYPE BATTERY SAW REPAIR
$routes->group('master_type_battery_saw_repair', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterTypeBatterySawRepair::index');
    $routes->post('edit', 'MasterTypeBatterySawRepair::update_type_battery');
    $routes->post('delete', 'MasterTypeBatterySawRepair::delete_type_battery');
});

$routes->group('saw_repair', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'SawRepair::index');
    $routes->get('month/(:segment)', 'SawRepair::index/$1');
    $routes->post('save_data', 'SawRepair::save_data');
    $routes->get('detail_saw_repair/(:num)', 'SawRepair::detail_lhp_saw_repair/$1');
    $routes->post('detail_saw_repair/update', 'SawRepair::update');
    $routes->get('detail_saw_repair/delete/(:num)', 'SawRepair::delete/$1');
    $routes->post('download', 'SawRepair::download');
});

$routes->group('potong_battery', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PotongBattery::index');
    $routes->get('month/(:segment)', 'PotongBattery::index/$1');
    $routes->post('save_data', 'PotongBattery::save_data');
    $routes->get('detail_potong_battery/(:num)', 'PotongBattery::detail_lhp_potong_battery/$1');
    $routes->post('detail_potong_battery/update', 'PotongBattery::update');
    $routes->get('detail_potong_battery/delete/(:num)', 'PotongBattery::delete/$1');
    $routes->post('download', 'PotongBattery::download');
});

// Master Group Leader
$routes->group('master_group_leader', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterGroupLeader::index');
    $routes->post('add_group_leader', 'MasterGroupLeader::add_group_leader');
    $routes->post('update_group_leader', 'MasterGroupLeader::update_group_leader');
    $routes->post('delete_group_leader', 'MasterGroupLeader::delete_group_leader');
});

// Master Operator
$routes->group('master_operator', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterOperator::index');
    $routes->post('add_operator', 'MasterOperator::add_operator');
    $routes->post('update_operator', 'MasterOperator::update_operator');
    $routes->post('delete_operator', 'MasterOperator::delete_operator');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
