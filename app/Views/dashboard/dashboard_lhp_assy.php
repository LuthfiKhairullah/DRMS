<?= $this->extend('template/dashboard/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<?php
    date_default_timezone_set('Asia/Jakarta');
    $current_date = idate('m', strtotime($bulan));
    if ($current_date != 1) {
        $previous_date = $current_date - 1;
    } else {
        $previous_date = 12;
    }

    if ($child_filter == 0 AND $baby_filter == 'line') {
        $type_chart = 'line';
    } else {
        $type_chart = 'column';
    }

    // FILTER ARRAY BY GRUP
    $arr_data_line_by_grup = array();

    foreach ($data_line_by_grup as $item) {
        if ($item == 0) continue;
        if (!array_key_exists($item['grup'], $arr_data_line_by_grup)) {
            $arr_data_line_by_grup[$item['grup']] = array("grup" => $item['grup'], "data" => array());
        }
        $arr_data_line_by_grup[$item['grup']]["data"][] = $item['data'];
    }

    $res_data_line_by_grup = array_values($arr_data_line_by_grup);

    // FILTER ARRAY BY GRUP MONTHLY
    $arr_data_line_by_grup_month = array();

    foreach ($data_line_by_grup_month as $item) {
        if ($item == 0) continue;
        if (!array_key_exists($item['grup'], $arr_data_line_by_grup_month)) {
            $arr_data_line_by_grup_month[$item['grup']] = array("grup" => $item['grup'], "data" => array());
        }
        $arr_data_line_by_grup_month[$item['grup']]["data"][] = $item['data'];
    }

    $res_data_line_by_grup_month = array_values($arr_data_line_by_grup_month);

    // FILTER ARRAY BY KASUBSIE
    $arr_data_line_by_kss = array();

    foreach ($data_line_by_kss as $item) {
        if ($item == 0) continue;
        if (!array_key_exists($item['kss'], $arr_data_line_by_kss)) {
            $arr_data_line_by_kss[$item['kss']] = array("kss" => $item['kss'], "data" => array());
        }
        $arr_data_line_by_kss[$item['kss']]["data"][] = $item['data'];
    }

    $res_data_line_by_kss = array_values($arr_data_line_by_kss);

    // FILTER ARRAY BY KASUBSIE MONTHLY
    $arr_data_line_by_kss_month = array();

    foreach ($data_line_by_kss_month as $item) {
        if ($item == 0) continue;
        if (!array_key_exists($item['kss'], $arr_data_line_by_kss_month)) {
            $arr_data_line_by_kss_month[$item['kss']] = array("kss" => $item['kss'], "data" => array());
        }
        $arr_data_line_by_kss_month[$item['kss']]["data"][] = $item['data'];
    }

    $res_data_line_by_kss_month = array_values($arr_data_line_by_kss_month);
?>

<div class="content-wrapper" style="margin-left:0;">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
            <!-- <div class="row">
                <div class="col-12" style="text-align:center;">
                    <h1>Performance Dashboard</h1>
                    <br>
                </div>
            </div> -->
            <!-- <div class="row"> -->
				<!-- <div class="col-12">														 -->
					<!-- <div class="box" >
						<div class="box-header no-border px-0"> -->
							<!-- <h4 class="box-title">Current Running Courses</h4>	 -->
                            <!-- <form action="<?=base_url()?>dashboard/assy" method="POST">
                                <div class="row">
                                    <div class="col-3">
                                        <select class="form-select" name="jenis_dashboard" id="jenis_dashboard">
                                            <option value="1">Efficiency</option>
                                            <option value="2">Unit / MH</option>
                                        </select>
                                    </div>

                                    <div class="col-3" style="display:flex;">
                                        <select class="form-select" name="parent_filter" id="parent_filter" style="display:none">
                                            <option value="line" <?= ($parent_filter == 'line') ? 'selected':''?>>Line</option>
                                        </select>
                                        <select class="form-select" name="child_filter" id="child_filter">
                                            <option value="0" <?= ($child_filter == '0') ? 'selected':''?>>All</option>
                                            <?php for ($i=1; $i <= 7 ; $i++) { ?>
                                                <option value="<?=$i?>" <?= ($child_filter == $i) ? 'selected':''?>>Line <?=$i?></option>
                                            <?php } ?>
                                        </select>
                                        &nbsp;
                                        <select class="form-select" name="baby_filter" id="baby_filter">
                                            <?php if ($child_filter == 0) { ?>
                                                <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                            <?php } else { ?>
                                                <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                                <option value="shift" <?= ($baby_filter == 'shift') ? 'selected':''?>>By Shift</option>
                                                <option value="grup" <?= ($baby_filter == 'grup') ? 'selected':''?>>By Grup</option>
                                                <option value="kasubsie" <?= ($baby_filter == 'kasubsie') ? 'selected':''?>>By Kasubsie</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-3" style="display:flex;">
                                        <input type="month" class="form-control" name="bulan" id="bulan" value="<?= $bulan ?>">
                                    </div>
                                    <div class="col-3" style="display: flex; flex-direction: column;">
                                        <button class="btn btn-sm btn-success"> Filter </button>
                                    </div>
                                </div>
                            </form> -->
							<!-- <ul class="box-controls pull-right d-md-flex d-none">
							  <li>
								<button class="btn btn-primary-light px-10">View All</button>
							  </li>
							</ul> -->
						<!-- </div>
					</div> -->
				<!-- </div> -->
                
				<!-- <div class="col-xl-3 col-md-6 col-12">
					<div class="box pull-up">
						<div class="box-body">	
							<div class="bg-primary rounded">
								<h5 class="text-white text-center p-10">Year To Date</h5>
							</div>							
						</div>
                        <div class="box-body text-center">
                            <h1 class="countnm fs-50" style="margin-top: -30px;"><?=json_encode($data_all_year)?>%</h1>
                        </div>				
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12">
					<div class="box pull-up">
						<div class="box-body">	
							<div class="bg-warning rounded">
								<h5 class="text-white text-center p-10">Target <?=date('Y')?></h5>
							</div>							
						</div>	
                        <div class="box-body text-center">
                            <h1 class="countnm fs-50" style="margin-top: -30px;"><?= json_encode($target); ?>%</h1>
                        </div>						
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12">
					<div class="box pull-up">
						<div class="box-body">	
							<div class="bg-danger rounded">
								<h5 class="text-white text-center p-10"><?=date('F', mktime(0, 0, 0, $previous_date, 10))?> Efficiency</h5>
							</div>								
						</div>
                        <div class="box-body text-center">
                            <h1 class="countnm fs-50" style="margin-top: -30px;"><?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?>%</h1>
                        </div>					
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12">
					<div class="box pull-up">
						<div class="box-body">	
							<div class="bg-info rounded">
								<h5 class="text-white text-center p-10"> <?=date('F', mktime(0, 0, 0, $current_date, 10))?> Efficiency</h5>
							</div>						
						</div>	
                        <div class="box-body text-center">
                            <h1 class="countnm fs-50" style="margin-top: -30px;"><?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>%</h1>
                        </div>
					</div>
				</div> -->
			<!-- </div> -->

            <div class="row">
                    <div class="box bg-transparent">
                        <div class="box-body" style="display:flex">
                            <div class="col-2">
                                <form action="<?=base_url()?>dashboard/assy" method="POST">
                                    <select class="form-select" name="jenis_dashboard" id="jenis_dashboard" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px; display:none;">
                                        <option value="1">Efficiency</option>
                                        <option value="2">Unit / MH</option>
                                    </select>
                                    &nbsp;
                                    <select class="form-select" name="parent_filter" id="parent_filter" style="display:none">
                                        <option value="line" <?= ($parent_filter == 'line') ? 'selected':''?>>Line</option>
                                    </select>
                                    &nbsp;
                                    <select class="form-select" name="child_filter" id="child_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                        <option value="0" <?= ($child_filter == '0') ? 'selected':''?>>All</option>
                                        <!-- <option value="amb1" <?= ($child_filter === 'amb1') ? 'selected':''?>>AMB 1</option>
                                        <option value="amb2" <?= ($child_filter === 'amb2') ? 'selected':''?>>AMB 2</option> -->
                                        <?php for ($i=1; $i <= 7 ; $i++) { ?>
                                            <option value="<?=$i?>" <?= ($child_filter == $i) ? 'selected':''?>>Line <?=$i?></option>
                                        <?php } ?>
                                    </select>
                                    &nbsp;
                                    <select class="form-select" name="baby_filter" id="baby_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                        <?php if ($child_filter == 0) { ?>
                                            <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                            <option value="line" <?= ($baby_filter == 'line') ? 'selected':''?>>By Line</option>
                                        <?php } else { ?>
                                            <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                            <option value="shift" <?= ($baby_filter == 'shift') ? 'selected':''?>>By Shift</option>
                                            <option value="grup" <?= ($baby_filter == 'grup') ? 'selected':''?>>By Grup</option>
                                            <option value="kasubsie" <?= ($baby_filter == 'kasubsie') ? 'selected':''?>>By Kasubsie</option>
                                        <?php } ?>
                                    </select>
                                    &nbsp;
                                    <input type="month" class="form-control" name="bulan" id="bulan" value="<?= $bulan ?>" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                    &nbsp;
                                    <div style="display: flex; flex-direction: column;" >
                                        <button class="btn btn-sm btn-success" style="font-size: 20px;font-weight: 900;width: 175px;"> Filter </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-6" style="display:flex; margin-top:40px;">
                                <div class="col-3" style="display:flex;text-align:center;flex-direction: column;align-items: center;flex-wrap: nowrap;justify-content: space-around; margin-left:-140px; margin-top:-65px;">
                                    <a href="<?=base_url()?>dashboard/assy" class="waves-effect waves-light btn btn-rounded btn-success btn-lg btn-nav">Efficiency</a>
                                    <a href="<?=base_url()?>dashboard/reject" class="waves-effect waves-light btn btn-rounded btn-outline btn-danger btn-lg btn-nav">Rejection</a>
                                    <!-- <button type="button" class="waves-effect waves-light btn btn-outline btn-rounded btn-danger btn-lg btn-nav" data-bs-toggle="modal" data-bs-target="#modal_rejection">Rejection</button> -->
                                    <a href="<?=base_url()?>dashboard/line_stop" class="waves-effect waves-light btn btn-outline btn-rounded btn-warning btn-lg btn-nav">Line Stop</a>
                                    <!-- <button class="waves-effect waves-light btn btn-outline btn-rounded btn-success btn-lg btn-nav">Overtime</button> -->
                                </div>
                                <div class="col-3">
                                    <div id="year_to_date_chart" style="height:250px;"></div>
                                    <!-- <div style="text-align: center;margin-top: 60px;">
                                        <button type="button" class="waves-effect waves-light btn btn-outline btn-rounded btn-danger btn-lg btn-nav" data-bs-toggle="modal" data-bs-target="#modal_rejection">Rejection</a>
                                    </div> -->
                                </div>
                                <div class="col-3">
                                    <div id="target_chart" style="height:250px;"></div>
                                    <!-- <div style="text-align: center;margin-top: 60px;">
                                        <a href="<?=base_url()?>dashboard/line_stop" class="waves-effect waves-light btn btn-outline btn-rounded btn-warning btn-lg btn-nav">Line Stop</a>
                                    </div> -->
                                </div>
                                <!-- <div class="col-3">
                                    <div id="previous_month_chart" style="height:250px;"></div>
                                </div> -->
                                <div class="col-3">
                                    <div id="current_month_chart" style="height:250px;"></div>
                                    <!-- <div style="text-align: center;margin-top: 60px;">
                                        <button class="waves-effect waves-light btn btn-outline btn-rounded btn-success btn-lg btn-nav">Overtime</button>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-4" style="text-align:center">
                                <!-- <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <button class="btn btn-danger">Rejection</button>
                                        <button class="btn btn-info">Line Stop</button>
                                        <button class="btn btn-success">Overtime</button>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-12">
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <div>
                                            <h4 style="font-weight: 500;color: yellow;">Performance Comparison </h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table" style="width: 100%; margin: 0 auto; color:white; font-weight:700; font-size:18px;">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td><?=date('F', mktime(0, 0, 0, $previous_date, 10))?></td>
                                                        <td><?=date('F', mktime(0, 0, 0, $current_date, 10))?></td>
                                                        <td>Status</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i=1; $i <= 7; $i++) { ?>
                                                        <tr style="line-height: 0px;">
                                                            <td>Line <?=$i?></td>
                                                                <td><?=${'data_line_'.$i.'_previous_month'}[0]?> %</td>
                                                            
                                                            <td><?=${'data_line_'.$i.'_current_month'}[0]?> %</td>
                                                           
                                                            <td>
                                                                <?php if(${'data_line_'.$i.'_current_month'}[0] > ${'data_line_'.$i.'_previous_month'}[0]) {
                                                                    echo '<i class="fa fa-arrow-up" style="color:green"></i>';
                                                                } else if(${'data_line_'.$i.'_current_month'}[0] < ${'data_line_'.$i.'_previous_month'}[0]) {
                                                                    echo '<i class="fa fa-arrow-down" style="color:red"></i>';
                                                                } else if(${'data_line_'.$i.'_current_month'}[0] == ${'data_line_'.$i.'_previous_month'}[0]) {
                                                                    echo '<i class="fa fa-minus" style="color:yellow"></i>';
                                                                } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

			<div class="row" id="efficiency-wrapper">
				<div class="col-xl-8 col-12">
					<div class="box bg-transparent">
						<div class="box-body">
                            <figure class="highcharts-figure">
                                <div id="main_chart"></div>
                            </figure>
						</div>
					</div>										
				</div>
				<div class="col-xl-4 col-12">
					<div class="box bg-transparent">
						<div class="box-body">
                            <figure class="highcharts-figure">
                                <div id="side_chart"></div>
                            </figure>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->

<!-- MODAL REJECTION -->
<div id="modal_rejection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Rejection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <a href="<?=base_url()?>dashboard/reject" class="btn btn-rounded btn-danger btn-nav">Battery Rejection</a>
                    </div>
                    <div class="col-6">
                        <a href="<?=base_url()?>dashboard/rejectCutting" target="_blank" class="btn btn-rounded btn-danger btn-nav">Plate Cutting Rejection</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODAL -->
<div id="modal_detail_ls" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="width:120%;">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Detail Line Stop</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
            <div class="modal-body">
                <table class="table" id="tbl_breakdown" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 23%">Batch Jam</th>
                            <th style="width: 10%">No WO</th>
                            <th style="width: 55%">Type Battery</th>
                            <th style="width: 5%">Jenis Line Stop</th>
                            <th style="width: 2%">Kategori Line Stop</th>
                            <th style="width: 5%">Uraian</th>
                            <th style="width: 2%">Total Menit</th>
                        </tr>
                    </thead>
                    <tbody id="data_breakdown">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="float: right;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    $(document).ready(function() {
        $('#child_filter').change(function() {
            var selectedValue = $(this).val();
            populateSecondarySelect(selectedValue);
        });

        setTimeout(function () { location.reload(1); }, 60*60*1000);
    });

    function populateSecondarySelect(selectedValue) {
        $('#baby_filter').empty();
        
        if (selectedValue == '0') {
            $('#baby_filter').append($('<option>', {
                value: 'average',
                text: 'By Average'
            }));

            $('#baby_filter').append($('<option>', {
                value: 'line',
                text: 'By Line'
            }));
        } else {
            $('#baby_filter').append($('<option>', {
                value: 'average',
                text: 'By Average'
            }));
            
            $('#baby_filter').append($('<option>', {
                value: 'shift',
                text: 'By Shift'
            }));

            $('#baby_filter').append($('<option>', {
                value: 'grup',
                text: 'By Grup'
            }));

            $('#baby_filter').append($('<option>', {
                value: 'kasubsie',
                text: 'By Kasubsie'
            }));
        }
    }

    // PIE CHART YEAR TO GET
    var year_to_date_chart = echarts.init(document.getElementById('year_to_date_chart'));
    year_to_date_chart.setOption(
        {
            title: {
                text: '<?=json_encode($data_all_year)?>%',
                subtext: 'Year To Date',
                x: 'center',
                y: 'center',
                itemGap: 5,
                textStyle: {
                    color: '#ffffff',
                    fontSize: 30,
                    fontWeight: '700'
                },
                subtextStyle: {
                    color: '#ffffff',
                    fontSize: 15,
                    fontWeight: 'normal'
                }

            },           
            series: [
                {
                    name: '1',
                    type: 'pie',
                    clockWise: false,
                    radius: ['63%', '90%'],
                    silent: true,
                    itemStyle: {
                        normal: {
                            label: {show: false},
                            labelLine: {show: false}
                        }
                    },
                    data: [
                        {
                            value: <?=json_encode($data_all_year)?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'blue'
                            }
                        },
                        {
                            value: 100 - <?=json_encode($data_all_year)?>,
                            name: 'invisible',
                            itemStyle: {
                                color: 'grey'
                            }
                        }
                    ]
                },
            ]
        }
    );

    // PIE CHART TARGET
    var target_chart = echarts.init(document.getElementById('target_chart'));
    target_chart.setOption(
        {
            title: {
                text: '<?= json_encode($target) ?>%',
                subtext: 'Target <?= date('Y', strtotime($bulan))?>',
                x: 'center',
                y: 'center',
                itemGap: 5,
                textStyle: {
                    color: '#ffffff',
                    fontSize: 30,
                    fontWeight: '700'
                },
                subtextStyle: {
                    color: '#ffffff',
                    fontSize: 15,
                    fontWeight: 'normal'
                }

            },           
            series: [
                {
                    name: '1',
                    type: 'pie',
                    clockWise: false,
                    radius: ['63%', '90%'],
                    silent: true,
                    itemStyle: {
                        normal: {
                            label: {show: false},
                            labelLine: {show: false}
                        }
                    },
                    data: [
                        {
                            value: <?= json_encode($target); ?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'red'
                            }
                        },
                        {
                            value: 100 - <?= json_encode($target) ?>,
                            name: 'invisible',
                            itemStyle: {
                                color: 'grey'
                            }
                        }
                    ]
                },
            ]
        }
    );

    // PIE CHART Previous Month
    // var previous_month_chart = echarts.init(document.getElementById('previous_month_chart'));
    // previous_month_chart.setOption(
    //     {
    //         title: {
    //             text: '<?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?>%',
    //             subtext: '<?=date('F', mktime(0, 0, 0, $previous_date, 10))?> Efficiency',
    //             x: 'center',
    //             y: 'center',
    //             itemGap: 5,
    //             textStyle: {
    //                 color: '#ffffff',
    //                 fontSize: 30,
    //                 fontWeight: '700'
    //             },
    //             subtextStyle: {
    //                 color: '#ffffff',
    //                 fontSize: 15,
    //                 fontWeight: 'normal'
    //             }

    //         },           
    //         series: [
    //             {
    //                 name: '1',
    //                 type: 'pie',
    //                 clockWise: false,
    //                 radius: ['75%', '90%'],
    //                 silent: true,
    //                 itemStyle: {
    //                     normal: {
    //                         label: {show: false},
    //                         labelLine: {show: false}
    //                     }
    //                 },
    //                 data: [
    //                     {
    //                         value: <?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?>,
    //                         name: 'Monday',
    //                         itemStyle: {
    //                             color: 'red'
    //                         }
    //                     },
    //                     {
    //                         value: 100 - <?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?>,
    //                         name: 'invisible',
    //                         itemStyle: {
    //                             color: 'grey'
    //                         }
    //                     }
    //                 ]
    //             },
    //         ]
    //     }
    // );

    // PIE CHART Current Month
    var current_month_chart = echarts.init(document.getElementById('current_month_chart'));
    current_month_chart.setOption(
        {
            title: {
                text: '<?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>%',
                subtext: '<?=date('F', mktime(0, 0, 0, $current_date, 10))?> Efficiency',
                x: 'center',
                y: 'center',
                itemGap: 5,
                textStyle: {
                    color: '#ffffff',
                    fontSize: 30,
                    fontWeight: '700'
                },
                subtextStyle: {
                    color: '#ffffff',
                    fontSize: 15,
                    fontWeight: 'normal'
                }

            },           
            series: [
                {
                    name: '1',
                    type: 'pie',
                    clockWise: false,
                    radius: ['63%', '90%'],
                    silent: true,
                    itemStyle: {
                        normal: {
                            label: {show: false},
                            labelLine: {show: false}
                        }
                    },
                    data: [
                        {
                            value: <?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'orange'
                            }
                        },
                        {
                            value: 100 - <?=json_encode($data_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>,
                            name: 'invisible',
                            itemStyle: {
                                color: 'grey'
                            }
                        }
                    ]
                },
            ]
        }
    );

    Highcharts.chart('side_chart', {
        chart: {
            backgroundColor: 'transparent',
            type: '<?=$type_chart?>'
            // type: 'column',
            // backgroundColor: '#0c1a32',
            
        },
        exporting: {
            enabled: false
        },
        title: {
            text: 'Monthly Efficiency',
            style: {
                color: '#ffffff',
                fontSize: '20px'
            }
        },
        subtitle: {
            text: 'Source: Laporan Harian Produksi',
            align: 'center',
            style: {
                color: '#ffffff',
                fontSize: '15px'
            }
        },

        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true,
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },

        <?php if ($baby_filter == 'average') { ?>
            yAxis: 
                [{
                    title: {
                        text: 'Qty',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    },
                    opposite: true,
                },{
                    title: {
                        text: '%',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                }],
        <?php } else { ?>
            yAxis: 
                {
                    title: {
                        text: '%',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
        <?php } ?>

        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    formatter: function(){
                        return (this.y!=0)?this.y:"";
                    },
                    style: {
                        color: '#ffffff',
                        textOutline: 0,
                        fontSize: 14
                    },
                },
                // pointWidth: 30,
            }
        },
        legend: {
            <?php if (($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'line') { ?>
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                itemStyle: {
                    color: '#ffffff'
                },
            <?php } else if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND ($baby_filter == 'shift' OR $baby_filter == 'grup' OR $baby_filter == 'kasubsie')) { ?>
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                itemStyle: {
                    color: '#ffffff'
                },
                enabled: true,
            <?php } else { ?>
                enabled: false
            <?php } ?>
            },
        colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],

        series: [
            
            <?php if (($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'line') { ?>
                {
                    name: 'Line 1',
                    data: <?php echo json_encode($data_by_month_line_1); ?>,
                },
                {
                    name: 'Line 2',
                    data: <?php echo json_encode($data_by_month_line_2); ?>,
                },
                {
                    name: 'Line 3',
                    data: <?php echo json_encode($data_by_month_line_3); ?>,
                },
                {
                    name: 'Line 4',
                    data: <?php echo json_encode($data_by_month_line_4); ?>,
                },
                {
                    name: 'Line 5',
                    data: <?php echo json_encode($data_by_month_line_5); ?>,
                },
                {
                    name: 'Line 6',
                    data: <?php echo json_encode($data_by_month_line_6); ?>,
                },
                {
                    name: 'Line 7',
                    data: <?php echo json_encode($data_by_month_line_7); ?>,
                }
            <?php } else if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND $baby_filter == 'shift') { ?>
                {
                    name: 'Shift 1',
                    data: <?php echo json_encode($data_by_month_line_shift_1); ?>,
                },
                {
                    name: 'Shift 2',
                    data: <?php echo json_encode($data_by_month_line_shift_2); ?>,
                },
                {
                    name: 'Shift 3',
                    data: <?php echo json_encode($data_by_month_line_shift_3); ?>,
                },
            <?php } elseif ($child_filter != null AND $child_filter != 0 AND $baby_filter == 'grup') {
                foreach ($res_data_line_by_grup_month as $r_data_line_by_grup) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_grup['grup']); ?>,
                        data: <?php echo json_encode($r_data_line_by_grup['data']); ?>,
                    },
                <?php } ?>
            <?php } else if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND $baby_filter == 'kasubsie') {
                foreach ($res_data_line_by_kss_month as $r_data_line_by_kss) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_kss['kss']); ?>,
                        data: <?php echo json_encode($r_data_line_by_kss['data']); ?>,
                    },
                <?php } ?>
            <?php } else { ?>
                {
                    name: 'Persentase',
                    yAxis: 1,
                    data: <?php echo json_encode($data_all_month); ?>,
                },{
                    type: 'spline',
                    name: 'Qty',
                    data: <?php echo json_encode($qty_data_all_month); ?>,
                    color:'green',
                },{
                    type: 'spline',
                    name: 'Target',
                    dashStyle: 'Dash',
                    data: [<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>,<?= json_encode($target); ?>],
                    color:'red',
                    yAxis: 1,
                    tooltip: {
                        valueSuffix: ' %'
                    }
                }
            <?php } ?>        
        ],
        tooltip: {
            shared: true
        },
    });

    // GENERATE X AXIS DATE
    <?php
        $dates = array();

        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $now = date('Y-m-d');

        $target_date = array();

        $current_month = date('Y-m');
        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

        while (strtotime($start) <= strtotime($now)) {
            array_push($dates, date("d", strtotime($start)));
            array_push($target_date, $target);
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
        }

        // $dates=array();

        // for($d=1; $d<=31; $d++)
        // {
        //     $time=mktime(12, 0, 0, date('m'), $d, date('Y'));
        //     if (date('m', $time)==date('m'))
        //         $dates[]=date('d', $time);
        // }
    ?>

    Highcharts.chart('main_chart', {
        chart: {
            // type: 'column',
            // backgroundColor: '#12213c',
            // backgroundColor: '#0c1a32',
            backgroundColor: 'transparent',
            type: '<?=$type_chart?>',
            height: 440,
        },

        exporting: {
            enabled: false
        },

        title: {
            text: 'Daily Efficiency',
            align: 'center',
            style: {
                color: '#ffffff',
                fontSize: '20px'
            }
        },

        subtitle: {
            text: 'Source: Laporan Harian Produksi',
            align: 'center',
            style: {
                color: '#ffffff',
                fontSize: '15px'
            }
        },
        <?php if ($baby_filter == 'average') { ?>
            yAxis: 
                [{
                    title: {
                        text: 'Qty',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    },
                    opposite: true,
                },{
                    title: {
                        text: '%',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                }],
        <?php } else { ?>
            yAxis: 
                {
                    title: {
                        text: '%',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
        <?php } ?>
        

        xAxis: {
            categories: <?= json_encode($dates); ?>,
            labels: {
                style: {
                    color: '#ffffff'
                },
                // formatter: function(){
                //     return (this.value != 0 || this.value != null) ? this.value : "";
                // }
            }
        },

        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            layout: 'horizontal',
            itemStyle: {
                color: '#ffffff'
            }
        },

        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function(){
                        return (this.y!=0)?this.y:"";
                    },
                    style: {
                        color: '#ffffff',
                        textOutline: 0,
                        fontSize: 14
                    },
                },
                // pointWidth: 30,
            }
        },
        colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],
        
        series: [
            <?php if (($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'average') { ?>
                {
                    name: 'Persentase',
                    data: <?php echo json_encode($data_all_line); ?>,
                    color:'yellow',
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                    '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                    '<td>' + data[i].no_wo + '</td>' +
                                                    '<td>' + data[i].type_battery + '</td>' +
                                                    '<td>' + data[i].jenis_breakdown + '</td>' +
                                                    '<td>' + data[i].proses_breakdown + '</td>' +
                                                    '<td>' + data[i].uraian_breakdown + '</td>' +
                                                    '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                            
                                            total_breakdown += parseInt(data[i].menit_breakdown);
                                        }
                                        html += '<tr>' +
                                                    '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                '</tr>';
                                        
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    },
                    type: 'column',
                    yAxis: 1,
                },{
                    name: 'Qty',
                    type: 'spline',
                    data: <?php echo json_encode($data_all_line_pcs); ?>,
                    color:'green',
                },
            <?php } ?>

            <?php if (($parent_filter == 'line' OR $parent_filter == null) AND ($child_filter == null OR $child_filter == 0) AND $baby_filter == 'line') { ?>
                {
                    name: 'Line 1',
                    data: <?php echo json_encode($data_line_1); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 1;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                    '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                    '<td>' + data[i].no_wo + '</td>' +
                                                    '<td>' + data[i].type_battery + '</td>' +
                                                    '<td>' + data[i].jenis_breakdown + '</td>' +
                                                    '<td>' + data[i].proses_breakdown + '</td>' +
                                                    '<td>' + data[i].uraian_breakdown + '</td>' +
                                                    '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';

                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 2',
                    data: <?php echo json_encode($data_line_2); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 2;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                        '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                        '<td>' + data[i].no_wo + '</td>' +
                                                        '<td>' + data[i].type_battery + '</td>' +
                                                        '<td>' + data[i].jenis_breakdown + '</td>' +
                                                        '<td>' + data[i].proses_breakdown + '</td>' +
                                                        '<td>' + data[i].uraian_breakdown + '</td>' +
                                                        '<td>' + data[i].menit_breakdown + '</td>' +
                                                    '</tr>';
                                                    total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 3',
                    data: <?php echo json_encode($data_line_3); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 3;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';

                                                    total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 4',
                    data: <?php echo json_encode($data_line_4); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 4;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 5',
                    data: <?php echo json_encode($data_line_5); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 5;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 6',
                    data: <?php echo json_encode($data_line_6); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 6;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Line 7',
                    data: <?php echo json_encode($data_line_7); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = 7;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';

                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
            <?php } ?>

            <?php if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND ($baby_filter == null OR $baby_filter == 'average')) { ?>
                {
                    // name: 'Line <?=$child_filter?>',
                    name: 'Persentase',
                    type: 'column',
                    yAxis: 1,
                    data: <?php echo json_encode(${'data_line_'.$child_filter}); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                        }
                                        html += '<tr>' +
                                                    '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Qty',
                    data: <?php echo json_encode(${'data_line_'.$child_filter.'_pcs'}); ?>,
                    color: 'green',
                    type: 'spline',
                },
            <?php } ?>

            <?php if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND $baby_filter == 'shift') { ?>
                {
                    name: 'Shift 1',
                    data: <?php echo json_encode($data_line_shift_1); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                var shift = 1;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop_by_shift'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line,
                                        shift: shift
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                        }
                                        html += '<tr>' +
                                                    '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Shift 2',
                    data: <?php echo json_encode($data_line_shift_2); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                var shift = 2;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop_by_shift'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line,
                                        shift: shift
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                        }
                                        html += '<tr>' +
                                                    '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
                {
                    name: 'Shift 3',
                    data: <?php echo json_encode($data_line_shift_3); ?>,
                    point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                var shift = 3;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop_by_shift'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line,
                                        shift: shift
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                        }
                                        html += '<tr>' +
                                                    '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                },
            <?php } ?>

            <?php if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND $baby_filter == 'grup') { 
                foreach ($res_data_line_by_grup as $r_data_line_by_grup) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_grup['grup']); ?>,
                        data: <?php echo json_encode($r_data_line_by_grup['data']); ?>,
                        point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                var grup = <?= json_encode($r_data_line_by_grup['grup']); ?>;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop_by_grup'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line,
                                        grup: grup
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                    },
                <?php } ?>
            <?php } ?>

            <?php if ($child_filter != null AND $child_filter != '0' AND $child_filter != 0 AND $baby_filter == 'kasubsie') { 
                foreach ($res_data_line_by_kss as $r_data_line_by_kss) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_kss['kss']); ?>,
                        data: <?php echo json_encode($r_data_line_by_kss['data']); ?>,
                        point: {
                        events: {
                            click: function() {
                                var date = $('#bulan').val()+'-'+this.category;
                                var line = <?=$child_filter?>;
                                var kss = <?= json_encode($r_data_line_by_kss['kss']); ?>;
                                $.ajax({
                                    url: "<?= base_url('dashboard/assy/get_data_line_stop_by_kss'); ?>",
                                    type: "POST",
                                    data: {
                                        date: date,
                                        line: line,
                                        kss: kss
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        var html = '';
                                        var i;
                                        var total_breakdown = 0;
                                        for (i = 0; i < data.length; i++) {
                                            html += '<tr>' +
                                                '<td style="width:125px;">' + data[i].jam_start.substring(0, 5) + '-' + data[i].jam_end.substring(0, 5) + '</td>' +
                                                '<td>' + data[i].no_wo + '</td>' +
                                                '<td>' + data[i].type_battery + '</td>' +
                                                '<td>' + data[i].jenis_breakdown + '</td>' +
                                                '<td>' + data[i].proses_breakdown + '</td>' +
                                                '<td>' + data[i].uraian_breakdown + '</td>' +
                                                '<td>' + data[i].menit_breakdown + '</td>' +
                                                '</tr>';
                                                total_breakdown += parseInt(data[i].menit_breakdown);
                                            }
                                            html += '<tr>' +
                                                        '<td colspan="6" style="text-align:center;"><h5><b>Total</b></h5></td>' +
                                                        '<td><h5><b>' + total_breakdown + '</b></h5></td>' +
                                                    '</tr>';
                                        $('#data_breakdown').html(html);
                                        $('#modal_detail_ls').modal('show');
                                    }
                                });
                            }
                        }
                    }
                    },
                <?php } ?>
            <?php } ?>
            {
                    type: 'spline',
                    name: 'Target',
                    dashStyle: 'Dash',
                    data: <?php echo json_encode($target_date); ?>,
                    color:'red',
                    <?php if($baby_filter == 'average') { ?>
                        yAxis: 1,
                    <?php } ?>                    
                    tooltip: {
                        valueSuffix: ' %'
                    }
                }
        ],

        tooltip: {
            shared: true
        },

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
<?= $this->endSection(); ?>