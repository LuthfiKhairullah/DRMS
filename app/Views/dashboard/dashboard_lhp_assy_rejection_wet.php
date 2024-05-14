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

    if ($child_filter == 0 AND ($baby_filter == 'line' || $baby_filter == 'grup')) {
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

    $data = $data_reject_by_date;
    $merged = array();
    foreach ($data as $item) {
        $name = $item["name"];
        if (!isset($merged[$name])) {
            $merged[$name] = array("name" => $name, "data" => array($item["data"]));
        } else {
            array_push($merged[$name]["data"], $item["data"]);
        }
    }
    $result = array_values($merged);

    $data_daily_persentase = $data_reject_by_date_persentase;
    $merged_daily_persentase = array();
    foreach ($data_daily_persentase as $item) {
        $name = $item["name"];
        if (!isset($merged_daily_persentase[$name])) {
            $merged_daily_persentase[$name] = array("name" => $name, "data" => array($item["data"]));
        } else {
            array_push($merged_daily_persentase[$name]["data"], $item["data"]);
        }
    }
    $result_daily_reject_persentase = array_values($merged_daily_persentase);

    // REMOVE SETTING PERSENTASE
    foreach ($result_daily_reject_persentase as $key => $item) {
        if ($item["name"] === "SETTING ") {
            unset($result_daily_reject_persentase[$key]);
            break;
        }
    }

    $result_daily_reject_persentase_without_setting = array_values($result_daily_reject_persentase);

    // REMOVE SETTING QTY
    foreach ($result as $key => $item) {
        if ($item["name"] === "SETTING ") {
            unset($result[$key]);
            break;
        }
    }

    $result_without_setting = array_values($result);
    
?>

<div class="content-wrapper" style="margin-left:0; margin-top:50px;">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
            <div class="row">
                <div class="box bg-transparent">
                    <div class="box-body" style="display:flex">
                        <div class="col-2">
                            <form action="<?=base_url()?>dashboard/reject_wet" method="POST">
                                <select class="form-select" name="jenis_dashboard" id="jenis_dashboard" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px; display:none;">
                                    <option value="1">Rejection</option>
                                    <option value="2">Unit / MH</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="parent_filter" id="parent_filter" style="display:none">
                                    <option value="line" <?= ($parent_filter == 'line') ? 'selected':''?>>Line</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="child_filter" id="child_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                    <option value="0" <?= ($child_filter == '0') ? 'selected':''?>>All</option>
                                    <?php for ($i=8; $i <= 9 ; $i++) { ?>
                                        <option value="<?=$i?>" <?= ($child_filter == $i) ? 'selected':''?>><?=$i == 8 ? 'Wet A' : 'Wet F' ?></option>
                                    <?php } ?>
                                </select>
                                &nbsp;
                                <select class="form-select" name="baby_filter" id="baby_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                    <?php if ($child_filter == 0) { ?>
                                        <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                        <option value="line" <?= ($baby_filter == 'line') ? 'selected':''?>>By Line</option>
                                        <option value="grup" <?= ($baby_filter == 'grup') ? 'selected':''?>>By Grup</option>
                                    <?php } else { ?>
                                        <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                        <!-- <option value="shift" <?= ($baby_filter == 'shift') ? 'selected':''?>>By Shift</option> -->
                                        <option value="grup" <?= ($baby_filter == 'grup') ? 'selected':''?>>By Grup</option>
                                        <!-- <option value="kasubsie" <?= ($baby_filter == 'kasubsie') ? 'selected':''?>>By Kasubsie</option> -->
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
                        <div class="col-6" style="display:flex; margin-top:35px;">
                            <div class="col-3" style="display:flex;text-align:center;flex-direction: column;align-items: center;flex-wrap: nowrap;justify-content: space-around; margin-left:-70px; margin-top:-65px;">
                                <a href="<?=base_url()?>dashboard/wet_finishing" class="waves-effect waves-light btn btn-outline btn-rounded btn-success btn-lg btn-nav">Efficiency</a>
                                <a href="<?=base_url()?>dashboard/reject_wet" class="waves-effect waves-light btn btn-rounded btn-danger btn-lg btn-nav">Rejection</a>
                                <a href="<?=base_url()?>dashboard/line_stop_wet" class="waves-effect waves-light btn btn-outline btn-rounded btn-warning btn-lg btn-nav">Line Stop</a>
                            </div>
                            <div class="col-3">
                                <div id="year_to_date_chart" style="height:250px;"></div>
                            </div>
                            <div class="col-3">
                                <div id="target_chart" style="height:250px;"></div>
                            </div>
                            <div class="col-3">
                                <div id="current_month_chart" style="height:250px;"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="box bg-transparent">
                                <div class="box-body">
                                    <figure class="highcharts-figure">
                                        <div id="pareto_reject"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($baby_filter == 'average') { ?>
                <!-- CHART AVG DAILY REJECTION DAN AVG MONTHLY REJECTION ALL ASSY (%) -->
                <div class="row">
                    <div class="col-xl-8 col-12">
                        <div class="box bg-transparent">
                            <div class="box-body">
                                <figure class="highcharts-figure">
                                    <div id="average_daily_chart"></div>
                                </figure>
                            </div>
                        </div>										
                    </div>
                    <div class="col-xl-4 col-12">
                        <div class="box bg-transparent">
                            <div class="box-body">
                                <figure class="highcharts-figure">
                                    <div id="average_month_chart"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else if ($baby_filter == 'line' || $baby_filter == 'grup') { ?>
                <!-- CHART AVG DAILY REJECTION DAN AVG MONTHLY REJECTION SHOW PER LINE (%) -->
                <div class="row">
                    <div class="col-xl-8 col-12">
                        <div class="box bg-transparent">
                            <div class="box-body">
                                <figure class="highcharts-figure">
                                    <div id="average_daily_chart_by_line"></div>
                                </figure>
                            </div>
                        </div>										
                    </div>
                    <div class="col-xl-4 col-12">
                        <div class="box bg-transparent">
                            <div class="box-body">
                                <figure class="highcharts-figure">
                                    <div id="average_month_chart_by_line"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->

<!-- MODAL -->
<div class="modal fade" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:120%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Detail Rejection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_pareto_jenis_reject"></div>
                <div id="detail_pareto_kategori_reject"></div>
                <div id="detail_pareto_type_battery"></div>
                <div id="detail_pareto_grup_shift"></div>
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

<!-- SUB MODAL -->
<div class="modal fade" id="sub_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:120%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Detail Rejection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sub_detail_pareto_jenis_reject"></div>
                <div id="sub_detail_pareto_kategori_reject"></div>
                <div id="sub_detail_pareto_type_battery"></div>
                <div id="sub_detail_pareto_grup_shift"></div>
                <br><br>
                <div id="detail_table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kategori Reject</th>
                                <th>Type Battery</th>
                                <th>PIC</th>
                                <th>Shift</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody id="detail_summary_rejection"></tbody>
                    </table>
                </div>
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

            $('#baby_filter').append($('<option>', {
                value: 'grup',
                text: 'By Grup'
            }));
        } else {
            $('#baby_filter').append($('<option>', {
                value: 'average',
                text: 'By Average'
            }));
            
            // $('#baby_filter').append($('<option>', {
            //     value: 'shift',
            //     text: 'By Shift'
            // }));

            $('#baby_filter').append($('<option>', {
                value: 'grup',
                text: 'By Grup'
            }));

            // $('#baby_filter').append($('<option>', {
            //     value: 'kasubsie',
            //     text: 'By Kasubsie'
            // }));
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
                            value: <?=json_encode($data_all_year)?> * 100,
                            name: 'Monday',
                            itemStyle: {
                                color: 'blue'
                            }
                        },
                        {
                            value: 100 - (<?=json_encode($data_all_year)?> * 100),
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
                subtext: 'Batas Rejection',
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
                            value: <?= json_encode($target * 100) ?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'red'
                            }
                        },
                        {
                            value: <?= json_encode($target > 1 ? $target * 100 : 100) ?> - <?= json_encode($target * 100) ?>,
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
    //             text: '<?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?>%',
    //             subtext: '<?=date('F', mktime(0, 0, 0, $previous_date, 10))?> Rejection',
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
    //                         value: <?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?> * 100,
    //                         name: 'Monday',
    //                         itemStyle: {
    //                             color: 'red'
    //                         }
    //                     },
    //                     {
    //                         value: 100 - (<?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $previous_date, 10)) - 1])?> * 100),
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
                text: '<?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>%',
                subtext: '<?=date('F', mktime(0, 0, 0, $current_date, 10))?> Rejection',
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
                            value: <?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?> * 100,
                            name: 'Monday',
                            itemStyle: {
                                color: 'orange'
                            }
                        },
                        {
                            value: 100 - (<?=json_encode($data_reject_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?> * 100),
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

    Highcharts.chart('pareto_reject', {
        chart: {
            backgroundColor: 'transparent',
            type: 'column',
            // backgroundColor: '#0c1a32',
            height:250
            
        },
        exporting: {
            enabled: false
        },
        title: {
            text: '<?=date('F', strtotime($bulan))?> Rejection (%)',
            style: {
                color: '#ffffff',
                fontSize: '20px'
            }
        },
        xAxis: {
            categories: <?php echo json_encode($data_reject_by_line); ?>,
            crosshair: true,
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: [{
            gridLineWidth: 0,
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
            opposite: true
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
            enabled: false
        },
        tooltip: {
            shared: true
        },

        series: [{
            name: 'Persentase',
            data: <?php echo json_encode($data_total_reject_by_line); ?>,
            color:'yellow',
            yAxis: 1,
        }, {
            name: 'Qty',
            data: <?php echo json_encode($data_qty_reject_by_line); ?>,
            color:'green',
            type: 'spline'
        },
        {
            type: 'spline',
            name: 'Target',
            dashStyle: 'dash',
            data: [<?php for($i = 0; $i < count($data_reject_by_line); $i++) { 
                echo json_encode($target) . ',';
             } ?>],
            color:'red',
            yAxis: 1,
        }]
    });

    // GENERATE X AXIS DATE
    <?php
        $dates = array();
        $target_by_date = array();
        $target_by_month = array();

        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $now = date('Y-m-d');

        $current_month = date('Y-m');
        if ($bulan != null OR $bulan != $current_month) {
            $start = date('Y-m-01', strtotime($bulan));
            $now = date('Y-m-t', strtotime($bulan));
        }

        while (strtotime($start) <= strtotime($now)) {
            array_push($dates, date("d", strtotime($start)));
            array_push($target_by_date, $target);
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
        }

        for ($i=0; $i < 12; $i++) { 
            array_push($target_by_month, $target);
        }
    ?>

    // VALIDASI FILTER

    <?php if($baby_filter == 'average') { ?>
        Highcharts.chart('average_daily_chart', {
            chart: {
                // type: 'column',
                // backgroundColor: '#12213c',
                // backgroundColor: '#0c1a32',
                backgroundColor: 'transparent',
                type: '<?=$type_chart?>'
            },

            exporting: {
                enabled: false
            },

            title: {
                text: 'Daily Rejection <?=($child_filter == 0) ? 'All Line' : 'Line '.$child_filter?>',
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

            yAxis: [{
                        gridLineWidth: 0,
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
                        opposite: true
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

            xAxis: {
                categories: <?php echo json_encode($dates); ?>,
                labels: {
                    style: {
                        color: '#ffffff'
                    }
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
                    events: {
                        click : function(e) {
                            var date = $('#bulan').val() + '-' + e.point.category;
                            var line = <?= $child_filter ?>;
                            $.ajax({
                                url: '<?= base_url('dashboard/reject_wet/get_detail_rejection') ?>',
                                type: 'POST',
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: 'JSON',
                                success: function(data) {
                                    console.log(data);
                                    var data_jenis_reject = data['data_jenis_reject'];
                                    var i;
                                    var arr_jenis_reject = [];
                                    var arr_qty_jenis_reject = [];
                                    var arr_qty_jenis_reject_pcs = [];
                                    for (i = 0; i < data_jenis_reject.length; i++) {
                                        // if (data_jenis_reject[i].jenis_reject != 'SETTING ') {
                                            arr_jenis_reject.push(data_jenis_reject[i].jenis_reject);
                                            arr_qty_jenis_reject.push(parseFloat(((data_jenis_reject[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                            arr_qty_jenis_reject_pcs.push(parseInt(data_jenis_reject[i].qty));
                                        // }
                                    }
                                    console.log(arr_qty_jenis_reject);
                                    $('#detail_pareto_jenis_reject').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_jenis_reject"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_jenis_reject', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Rejection (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_jenis_reject,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [
                                                {
                                                min: 0,
                                                title: {
                                                    text: 'Qty',
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                },
                                                opposite: true
                                            },
                                                {
                                                min: 0,
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
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0,
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            color: '#ffffff',
                                                            textOutline: 0,
                                                            fontSize: 14
                                                        },
                                                    },
                                                    events: {
                                                        click: function(event) {
                                                            var jenis_reject = event.point.category;

                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/reject_wet/get_detail_rejection'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    jenis_reject: jenis_reject
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);
                                                                    var data_reject_by_jenis_reject = data['data_reject_by_jenis_reject'];
                                                                    var i;
                                                                    var arr_kategori_reject = [];
                                                                    var arr_qty_kategori_reject = [];
                                                                    var arr_qty_kategori_reject_pcs = [];
                                                                    for (i = 0; i < data_reject_by_jenis_reject.length; i++) {
                                                                        arr_kategori_reject.push(data_reject_by_jenis_reject[i].kategori_reject);
                                                                        arr_qty_kategori_reject.push(parseFloat(((data_reject_by_jenis_reject[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_kategori_reject_pcs.push(parseInt((data_reject_by_jenis_reject[i].qty)));
                                                                        
                                                                    }
                                                                    $('#sub_detail_pareto_kategori_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_ketegori_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_ketegori_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail '+jenis_reject+' Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_reject,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    },
                                                                                    events: {
                                                                                        click: function(event) {
                                                                                            $('#sub_modal').modal('show');
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_kategori_reject,
                                                                                color:'yellow',
                                                                                yAxis: 1

                                                                            },{
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_kategori_reject_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var data_reject_by_type_battery = data['data_reject_by_type_battery'];
                                                                    var i;
                                                                    var arr_type_battery = [];
                                                                    var arr_qty_type_battery = [];
                                                                    var arr_qty_type_battery_pcs = [];
                                                                    for (i = 0; i < data_reject_by_type_battery.length; i++) {
                                                                        arr_type_battery.push(data_reject_by_type_battery[i].type_battery);
                                                                        arr_qty_type_battery.push(parseFloat(((data_reject_by_type_battery[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_type_battery_pcs.push(parseInt((data_reject_by_type_battery[i].qty)));                                                                        
                                                                    }
                                                                    $('#sub_detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_battery_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_battery_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Type Battery Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_type_battery,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                    enabled: true,
                                                                                    style: {
                                                                                        color: '#ffffff',
                                                                                        textOutline: 0,
                                                                                        fontSize: 14
                                                                                    },
                                                                                },
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_type_battery,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_type_battery_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var data_reject_by_grup = data['data_reject_by_grup'];
                                                                    var i;
                                                                    var arr_grup = [];
                                                                    var arr_qty_grup = [];
                                                                    var arr_qty_grup_pcs = [];
                                                                    for (i = 0; i < data_reject_by_grup.length; i++) {
                                                                        arr_grup.push(data_reject_by_grup[i].nama_pic+' ('+data_reject_by_grup[i].shift+')');
                                                                        arr_qty_grup.push(parseFloat(((data_reject_by_grup[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_grup_pcs.push(parseInt(((data_reject_by_grup[i].qty))));
                                                                        
                                                                    }
                                                                    $('#sub_detail_pareto_grup_shift').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_grup_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_grup_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Grup Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_grup,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                    enabled: true,
                                                                                    style: {
                                                                                        color: '#ffffff',
                                                                                        textOutline: 0,
                                                                                        fontSize: 14
                                                                                    },
                                                                                },
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_grup,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_grup_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var detail_summary_rejection = data['detail_summary_rejection'];
                                                                    var add_table = '';
                                                                    var i;
                                                                    var total_detail_reject = 0;
                                                                    for (i = 0; i < detail_summary_rejection.length; i++) {
                                                                        total_detail_reject += parseInt(detail_summary_rejection[i].qty);
                                                                        add_table += `<tr>
                                                                                            <td>`+detail_summary_rejection[i].kategori_reject+`</td>
                                                                                            <td>`+detail_summary_rejection[i].type_battery+`</td>
                                                                                            <td>`+detail_summary_rejection[i].nama_pic+`</td>
                                                                                            <td>`+detail_summary_rejection[i].shift+`</td>
                                                                                            <td>`+detail_summary_rejection[i].qty+`</td>
                                                                                        </tr>`;
                                                                    }
                                                                    add_table += `<tr>
                                                                                        <th colspan="4" style="text-align: center;"><b>Total</b></th>
                                                                                        <th>`+total_detail_reject+`</th>
                                                                                    </tr>`;
                                                                    $('#detail_summary_rejection').html(add_table);

                                                                    $('#sub_modal').modal('show');
                                                                }
                                                            });
                                                        }
                                                    }
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                name: 'Persen',
                                                type: 'column',
                                                data: arr_qty_jenis_reject,
                                                color:'yellow',
                                                yAxis: 1
                                            },
                                            {
                                                name: 'Pcs',
                                                type: 'spline',
                                                data: arr_qty_jenis_reject_pcs,
                                                color:'red',
                                            }]
                                    });

                                    var data_kategori_reject = data['data_all_detail_kategori_rejection_by_date'];
                                    var i;
                                    var arr_kategori_reject = [];
                                    var arr_qty_kategori_reject = [];
                                    var arr_qty_kategori_reject_pcs = [];
                                    for (i = 0; i < data_kategori_reject.length; i++) {
                                        // if (data_kategori_reject[i].kategori_reject != 'Punch Hole Setting Container') {
                                            arr_kategori_reject.push(data_kategori_reject[i].kategori_reject);
                                            arr_qty_kategori_reject.push(parseFloat(((data_kategori_reject[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                            arr_qty_kategori_reject_pcs.push(parseInt(((data_kategori_reject[i].qty))));
                                        // }
                                    }
                                    $('#detail_pareto_kategori_reject').html(`<figure class="highcharts-figure">
                                                                                    <div id="chart_pareto_kategori_reject"></div>
                                                                                </figure>
                                                                            `);
                                    Highcharts.chart('chart_pareto_kategori_reject', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Ketegori Rejection (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_kategori_reject,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                min: 0,
                                                title: {
                                                    text: 'Qty',
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                },
                                                opposite: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },{
                                                min: 0,
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
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0,
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            color: '#ffffff',
                                                            textOutline: 0,
                                                            fontSize: 14
                                                        },
                                                    },
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                name: 'Persen',
                                                type: 'column',
                                                data: arr_qty_kategori_reject,
                                                color:'yellow',
                                                yAxis: 1
                                            },
                                            {
                                                name: 'Pcs',
                                                type: 'spline',
                                                data: arr_qty_kategori_reject_pcs,
                                                color:'red',
                                            }]
                                    });

                                    var data_battery_reject = data['data_all_detail_battery_rejection_by_date'];
                                    var i;
                                    var arr_battery_reject = [];
                                    var arr_qty_battery_reject = [];
                                    var arr_qty_battery_reject_pcs = [];
                                    for (i = 0; i < data_battery_reject.length; i++) {
                                        arr_battery_reject.push(data_battery_reject[i].type_battery);
                                        arr_qty_battery_reject.push(parseFloat(((data_battery_reject[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                        arr_qty_battery_reject_pcs.push(parseInt(((data_battery_reject[i].qty))));                                        
                                    }
                                    $('#detail_pareto_type_battery').html(`<figure class="highcharts-figure">
                                                                                    <div id="chart_pareto_battery_reject"></div>
                                                                                </figure>
                                                                            `);
                                    Highcharts.chart('chart_pareto_battery_reject', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Type Battery Rejection (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_battery_reject,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                min: 0,
                                                title: {
                                                    text: 'Qty',
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                },
                                                opposite: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },{
                                                min: 0,
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
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0,
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            color: '#ffffff',
                                                            textOutline: 0,
                                                            fontSize: 14
                                                        },
                                                    },
                                                    events : {
                                                        click: function(event) {
                                                            var type_battery = event.point.category;

                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/reject_wet/get_detail_rejection'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    type_battery: type_battery
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);
                                                                    var data_jenis_reject_by_type_battery = data['data_jenis_reject_by_type_battery'];
                                                                    var i;
                                                                    var arr_jenis_reject_battery = [];
                                                                    var arr_qty_jenis_reject_battery = [];
                                                                    var arr_qty_jenis_reject_battery_pcs = [];
                                                                    for (i = 0; i < data_jenis_reject_by_type_battery.length; i++) {
                                                                        arr_jenis_reject_battery.push(data_jenis_reject_by_type_battery[i].jenis_reject);
                                                                        arr_qty_jenis_reject_battery.push(parseFloat(((data_jenis_reject_by_type_battery[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_jenis_reject_battery_pcs.push(parseInt(((data_jenis_reject_by_type_battery[i].qty))));
                                                                    }

                                                                    $('#sub_detail_pareto_jenis_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_jenis_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_jenis_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_jenis_reject_battery,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },

                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_jenis_reject_battery,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_jenis_reject_battery_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var data_kategori_reject_by_type_battery = data['data_kategori_reject_by_type_battery'];
                                                                    var i;
                                                                    var arr_kategori_reject_battery = [];
                                                                    var arr_qty_kategori_reject_battery = [];
                                                                    var arr_qty_kategori_reject_battery_pcs = [];
                                                                    for (i = 0; i < data_kategori_reject_by_type_battery.length; i++) {
                                                                        arr_kategori_reject_battery.push(data_kategori_reject_by_type_battery[i].kategori_reject);
                                                                        arr_qty_kategori_reject_battery.push(parseFloat(((data_kategori_reject_by_type_battery[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_kategori_reject_battery_pcs.push(parseInt(((data_kategori_reject_by_type_battery[i].qty))));
                                                                    }

                                                                    $('#sub_detail_pareto_kategori_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_kategori_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_kategori_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Kategori Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_reject_battery,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_kategori_reject_battery,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_kategori_reject_battery_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    

                                                                    $('#sub_detail_pareto_type_battery').html(``);

                                                                    var data_grup_reject_by_type_battery = data['data_grup_reject_by_type_battery'];
                                                                    var i;
                                                                    var arr_grup_reject_battery = [];
                                                                    var arr_qty_grup_reject_battery = [];
                                                                    var arr_qty_grup_reject_battery_pcs = [];
                                                                    for (i = 0; i < data_grup_reject_by_type_battery.length; i++) {
                                                                        arr_grup_reject_battery.push(data_grup_reject_by_type_battery[i].nama_pic+' ('+data_grup_reject_by_type_battery[i].shift+')');
                                                                        arr_qty_grup_reject_battery.push(parseFloat(((data_grup_reject_by_type_battery[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_grup_reject_battery_pcs.push(parseInt(((data_grup_reject_by_type_battery[i].qty))));
                                                                    }

                                                                    $('#sub_detail_pareto_grup_shift').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_grup_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_grup_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Grup Shift Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_grup_reject_battery,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_grup_reject_battery,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_grup_reject_battery_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    
                                                                    $('#sub_modal').modal('show');
                                                                }
                                                            })
                                                        }
                                                    }

                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                name: 'Persen',
                                                type: 'column',
                                                data: arr_qty_battery_reject,
                                                color:'yellow',
                                                yAxis: 1

                                            },
                                            {
                                                name: 'Pcs',
                                                type: 'spline',
                                                data: arr_qty_battery_reject_pcs,
                                                color:'red',
                                            }]
                                    });

                                    var data_grup_reject = data['data_all_detail_grup_rejection_by_date'];
                                    var i;
                                    var arr_grup_reject = [];
                                    var arr_qty_grup_reject = [];
                                    var arr_qty_grup_reject_pcs = [];
                                    for (i = 0; i < data_grup_reject.length; i++) {
                                        arr_grup_reject.push(data_grup_reject[i].nama_pic+' ('+data_grup_reject[i].shift+')');
                                        arr_qty_grup_reject.push(parseFloat(((data_grup_reject[i].total_reject / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                        arr_qty_grup_reject_pcs.push(parseFloat(((data_grup_reject[i].total_reject ))));                                       
                                        
                                    }
                                    $('#detail_pareto_grup_shift').html(`<figure class="highcharts-figure">
                                                                                    <div id="chart_pareto_grup_reject"></div>
                                                                                </figure>
                                                                            `);
                                    Highcharts.chart('chart_pareto_grup_reject', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Grup Rejection (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_grup_reject,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                min: 0,
                                                title: {
                                                    text: 'Qty',
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                },
                                                opposite: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },{
                                                min: 0,
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
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0,
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            color: '#ffffff',
                                                            textOutline: 0,
                                                            fontSize: 14
                                                        },
                                                    },
                                                    events : {
                                                        click: function(event) {
                                                            var group = event.point.category;
                                                            var regex = /^(.*?)\s*\((\d+)\)$/;
                                                            var matches = group.match(regex);

                                                            if (matches) {
                                                                var name = matches[1].trim();
                                                                var shift = matches[2];
                                                            } else {
                                                                console.log('Invalid format');
                                                            }

                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/reject_wet/get_detail_rejection'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    grup: name,
                                                                    shift: shift
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);
                                                                    var data_detail_reject_by_grup = data['data_jenis_reject_by_grup_shift'];
                                                                    var i;
                                                                    var arr_jenis_reject = [];
                                                                    var arr_qty_jenis_reject = [];
                                                                    var arr_qty_jenis_reject_pcs = [];
                                                                    for (i = 0; i < data_detail_reject_by_grup.length; i++) {
                                                                        arr_jenis_reject.push(data_detail_reject_by_grup[i].jenis_reject);
                                                                        arr_qty_jenis_reject.push(parseFloat(((data_detail_reject_by_grup[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_jenis_reject_pcs.push(parseInt(((data_detail_reject_by_grup[i].qty))));                                                                        
                                                                    }

                                                                    $('#sub_detail_pareto_jenis_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_jenis_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_jenis_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_jenis_reject,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    },
                                                                                    events: {
                                                                                        click: function(event) {
                                                                                            $('#sub_modal').modal('show');
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_jenis_reject,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_jenis_reject_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var data_kategori_reject_by_grup = data['data_kategori_reject_by_grup_shift'];
                                                                    var i;
                                                                    var arr_kategori_reject = [];
                                                                    var arr_qty_kategori_reject = [];
                                                                    var arr_qty_kategori_reject_pcs = [];
                                                                    for (i = 0; i < data_kategori_reject_by_grup.length; i++) {
                                                                        arr_kategori_reject.push(data_kategori_reject_by_grup[i].kategori_reject);
                                                                        arr_qty_kategori_reject.push(parseFloat(((data_kategori_reject_by_grup[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_kategori_reject_pcs.push(parseInt(((data_kategori_reject_by_grup[i].qty))));
                                                                        
                                                                    }

                                                                    $('#sub_detail_pareto_kategori_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_kategori_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_kategori_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Kategori Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_reject,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    },
                                                                                    events: {
                                                                                        click: function(event) {
                                                                                            $('#sub_modal').modal('show');
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                type: 'column',
                                                                                data: arr_qty_kategori_reject,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_kategori_reject_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    var data_battery_reject_by_grup = data['data_battery_reject_by_grup_shift'];
                                                                    var i;
                                                                    var arr_battery_reject = [];
                                                                    var arr_qty_battery_reject = [];
                                                                    var arr_qty_battery_reject_pcs = [];
                                                                    for (i = 0; i < data_battery_reject_by_grup.length; i++) {
                                                                        arr_battery_reject.push(data_battery_reject_by_grup[i].type_battery);
                                                                        arr_qty_battery_reject.push(parseFloat(((data_battery_reject_by_grup[i].qty / data['total_aktual_by_date'][0]['total_aktual']) * 100).toFixed(2)));
                                                                        arr_qty_battery_reject_pcs.push(parseInt(((data_battery_reject_by_grup[i].qty))));
                                                                        
                                                                    }

                                                                    $('#sub_detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_battery_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_battery_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Type Battery',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_battery_reject,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                min: 0,
                                                                                title: {
                                                                                    text: 'Qty',
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                },
                                                                                opposite: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },{
                                                                                min: 0,
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    },
                                                                                    events: {
                                                                                        click: function(event) {
                                                                                            $('#sub_modal').modal('show');
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persen',
                                                                                data: arr_qty_battery_reject,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },
                                                                            {
                                                                                name: 'Pcs',
                                                                                type: 'spline',
                                                                                data: arr_qty_battery_reject_pcs,
                                                                                color:'red',
                                                                            }]
                                                                    });

                                                                    $('#sub_detail_pareto_grup_shift').html(``);

                                                                    $('#sub_modal').modal('show');
                                                                }
                                                            })
                                                        }
                                                    }
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                name: 'Persen',
                                                type: 'column',
                                                data: arr_qty_grup_reject,
                                                color:'yellow',
                                                yAxis: 1
                                            },
                                            {
                                                name: 'Pcs',
                                                type: 'spline',
                                                data: arr_qty_grup_reject_pcs,
                                                color:'red',
                                            }]
                                    });

                                    $('#main_modal').modal('show');
                                }
                            })
                        }
                    }
                }
            },
            colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],
            
            series: [{
                name: 'Persentase',
                type: 'column',
                yAxis: 1,
                data: <?= json_encode($data_average_reject_by_date_all_line); ?>
            }, {
                name: 'Qty',
                type: 'spline',
                data: <?= json_encode($data_qty_reject_by_date_all_line); ?>,
                color: 'green'
            }, {
                name: 'Target',
                type: 'spline',
                dashStyle: 'dash',
                data: <?= json_encode($target_by_date); ?>,
                yAxis: 1,
                color: 'red'
            }],

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

        Highcharts.chart('average_month_chart', {
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
                text: 'Monthly Rejection',
                style: {
                    color: '#ffffff',
                    fontSize: '20px'
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
            yAxis: [{
                min: 0,
                title: {
                    text: 'Qty',
                    style: {
                        color: '#ffffff'
                    }
                },
                opposite: true,
                labels: {
                    style: {
                        color: '#ffffff'
                    }
                }
            },{
                min: 0,
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
                    events: {
                        click: function(e) {
                            var date = '01-'+e.point.category+'-<?=date('Y')?>';
                            var line = <?=$child_filter?>;
                            // alert(date);
                            $.ajax({
                                url: "<?= base_url('dashboard/reject_wet/get_detail_rejection') ?>",
                                type: "post",
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    var data_jenis_reject = data['data_jenis_reject_by_month'];
                                    var i;
                                    var arr_jenis_reject = [];
                                    var arr_qty_jenis_reject = [];
                                    var arr_average_jenis_reject = [];
                                    for (i = 0; i < data_jenis_reject.length; i++) {
                                        arr_jenis_reject.push(data_jenis_reject[i].jenis_reject);
                                        arr_qty_jenis_reject.push(data_jenis_reject[i].qty);
                                        if(data_jenis_reject[i].total_aktual > 0) arr_average_jenis_reject.push(parseFloat(((data_jenis_reject[i].qty / data_jenis_reject[i].total_aktual) * 100).toFixed(2)));
                                        else arr_average_jenis_reject.push(0);
                                    }
                                    $('#detail_pareto_jenis_reject').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_jenis_reject"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_jenis_reject', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: 'Detail Jenis Rejection',
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_jenis_reject,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                gridLineWidth: 0,
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
                                                opposite: true
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
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0,
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            color: '#ffffff',
                                                            textOutline: 0,
                                                            fontSize: 14
                                                        },
                                                    },
                                                    events: {
                                                        click: function(event) {
                                                            var jenis_reject = event.point.category;
                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/reject_wet/get_detail_rejection'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    jenis_reject: jenis_reject
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);
                                                                    var data_reject_by_jenis_reject = data['data_reject_by_jenis_reject_by_month'];
                                                                    var i;
                                                                    var arr_kategori_reject = [];
                                                                    var arr_qty_kategori_reject = [];
                                                                    var arr_average_kategori_reject = [];
                                                                    for (i = 0; i < data_reject_by_jenis_reject.length; i++) {
                                                                        arr_kategori_reject.push(data_reject_by_jenis_reject[i].kategori_reject);
                                                                        arr_qty_kategori_reject.push(data_reject_by_jenis_reject[i].qty);
                                                                        if(data_reject_by_jenis_reject[i].total_aktual > 0) arr_average_kategori_reject.push(parseFloat(((data_reject_by_jenis_reject[i].qty / data_reject_by_jenis_reject[i].total_aktual) * 100).toFixed(2)));
                                                                        else arr_average_kategori_reject.push(0);
                                                                    }
                                                                    $('#sub_detail_pareto_kategori_reject').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_pareto_ketegori_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_pareto_ketegori_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail '+jenis_reject+' Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_reject,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                gridLineWidth: 0,
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
                                                                                opposite: true
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                        enabled: true,
                                                                                        style: {
                                                                                            color: '#ffffff',
                                                                                            textOutline: 0,
                                                                                            fontSize: 14
                                                                                        },
                                                                                    },
                                                                                    events: {
                                                                                        click: function(event) {
                                                                                            $('#sub_modal').modal('show');
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persentase',
                                                                                data: arr_average_kategori_reject,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            }, {
                                                                                name: 'Qty',
                                                                                data: arr_qty_kategori_reject,
                                                                                color:'red',
                                                                                type: 'spline'
                                                                            }]
                                                                    });

                                                                    var data_reject_by_type_battery = data['data_reject_by_type_battery_by_month'];
                                                                    var i;
                                                                    var arr_type_battery = [];
                                                                    var arr_qty_type_battery = [];
                                                                    var arr_average_type_battery = [];
                                                                    for (i = 0; i < data_reject_by_type_battery.length; i++) {
                                                                        arr_type_battery.push(data_reject_by_type_battery[i].type_battery);
                                                                        arr_qty_type_battery.push(data_reject_by_type_battery[i].qty);
                                                                        if(data_reject_by_type_battery[i].total_aktual > 0) arr_average_type_battery.push(parseFloat(((data_reject_by_type_battery[i].qty / data_reject_by_type_battery[i].total_aktual) * 100).toFixed(3)));
                                                                        else arr_average_type_battery.push(0);
                                                                    }
                                                                    $('#sub_detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_pareto_battery_reject"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_pareto_battery_reject', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Type Battery Rejection',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_type_battery,
                                                                                crosshair: true,
                                                                                labels: {
                                                                                    style: {
                                                                                        color: '#ffffff'
                                                                                    }
                                                                                }
                                                                            },
                                                                            yAxis: [{
                                                                                gridLineWidth: 0,
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
                                                                                opposite: true
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
                                                                            plotOptions: {
                                                                                column: {
                                                                                    pointPadding: 0.2,
                                                                                    borderWidth: 0,
                                                                                    dataLabels: {
                                                                                    enabled: true,
                                                                                    style: {
                                                                                        color: '#ffffff',
                                                                                        textOutline: 0,
                                                                                        fontSize: 14
                                                                                    },
                                                                                },
                                                                                }
                                                                            },
                                                                            legend: {
                                                                                enabled: false
                                                                            },
                                                                            tooltip: {
                                                                                shared: true
                                                                            },
                                                                            series: [{
                                                                                name: 'Persentase',
                                                                                data: arr_average_type_battery,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            }, {
                                                                                name: 'Qty',
                                                                                data: arr_qty_type_battery,
                                                                                color:'red',
                                                                                type: 'spline'
                                                                            }]
                                                                    });

                                                                    var detail_summary_rejection = data['detail_summary_rejection_by_month'];
                                                                    var add_table = '';
                                                                    var i;
                                                                    var total_detail_reject = 0;
                                                                    for (i = 0; i < detail_summary_rejection.length; i++) {
                                                                        total_detail_reject += parseInt(detail_summary_rejection[i].qty);
                                                                        add_table += `<tr>
                                                                                            <td>`+detail_summary_rejection[i].kategori_pending+`</td>
                                                                                            <td>`+detail_summary_rejection[i].type_battery+`</td>
                                                                                            <td>`+detail_summary_rejection[i].nama_pic+`</td>
                                                                                            <td>`+detail_summary_rejection[i].shift+`</td>
                                                                                            <td>`+detail_summary_rejection[i].qty+`</td>
                                                                                        </tr>`;
                                                                    }
                                                                    add_table += `<tr>
                                                                                        <th colspan="4" style="text-align: center;"><b>Total</b></th>
                                                                                        <th>`+total_detail_reject+`</th>
                                                                                    </tr>`;
                                                                    $('#detail_summary_rejection').html(add_table);

                                                                    $('#sub_detail_pareto_grup_shift').html(``);

                                                                    $('#sub_modal').modal('show');
                                                                }
                                                            });
                                                        }
                                                    }
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                name: 'Persentase',
                                                data: arr_average_jenis_reject,
                                                color:'yellow',
                                                yAxis: 1
                                            }, {
                                                name: 'Qty',
                                                data: arr_qty_jenis_reject,
                                                color:'red',
                                                type: 'spline'
                                            }]
                                    });
                                    $('#detail_pareto_kategori_reject').html(``);
                                    $('#detail_pareto_type_battery').html(``);
                                    $('#detail_pareto_grup_shift').html(``);
                                    $('#main_modal').modal('show');
                                }
                            });
                        }
                    }
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
                <?php } else { ?>
                    enabled: false
                <?php } ?>
                },
            colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],

            series: [{
                name: 'Persentase',
                data: <?= json_encode($data_average_reject_by_month); ?>,
                yAxis: 1
            }, {
                name: 'Qty',
                data: <?php echo json_encode($data_qty_reject_by_month  ); ?>,
                color:'green',
                type: 'spline'
            }
            ],
            tooltip: {
                shared: true
            },
        });
    <?php } elseif ($baby_filter == 'line' || $baby_filter == 'grup') { ?>
        Highcharts.chart('average_daily_chart_by_line', {
            chart: {
                // type: 'column',
                // backgroundColor: '#12213c',
                // backgroundColor: '#0c1a32',
                backgroundColor: 'transparent',
                type: 'line'
            },
    
            exporting: {
                enabled: false
            },
    
            title: {
                text: 'Daily Rejection <?=($child_filter == 0) ? 'All Line' : ($child_filter == 8 ? 'Wet A' : 'Wet F')?>',
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
    
            yAxis: {
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
                },
                min: 0,
            },
    
            xAxis: {
                categories: <?php echo json_encode($dates); ?>,
                labels: {
                    style: {
                        color: '#ffffff'
                    },
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

            tooltip: {
                shared: true
            },
            
            series: [
                <?php if($baby_filter == 'line') { ?>
                {
                    name: 'Wet A',
                    data: <?php echo json_encode($data_reject_line_8); ?>
                },
                {
                    name: 'Wet F',
                    data: <?php echo json_encode($data_reject_line_9); ?>
                },
                <?php } else if($baby_filter == 'grup') {
                    foreach ($res_data_line_by_grup as $r_data_line_by_grup) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_grup['grup']); ?>,
                        data: <?php echo json_encode($r_data_line_by_grup['data']); ?>,
                    },
                <?php }
                } ?>
                {
                    type: 'spline',
                    name: 'Target',
                    dashStyle: 'dash',
                    data: [<?php for($i = 0; $i < count($dates); $i++) { 
                        echo json_encode($target) . ',';
                    } ?>],
                    color:'red',
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
    
        Highcharts.chart('average_month_chart_by_line', {
            chart: {
                backgroundColor: 'transparent',
                type: 'line'
                // type: 'column',
                // backgroundColor: '#0c1a32',
                
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Monthly Rejection',
                style: {
                    color: '#ffffff',
                    fontSize: '20px'
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
            yAxis: {
                min: 0,
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
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal',
                    itemStyle: {
                        color: '#ffffff'
                    }
                },
            colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],
            tooltip: {
                shared: true
            },
            
            series: [
                <?php if($baby_filter == 'line') { ?>
                {
                    name: 'Wet A',
                    data: <?php echo json_encode($data_reject_by_month_line_8); ?>,
                },
                {
                    name: 'Wet F',
                    data: <?php echo json_encode($data_reject_by_month_line_9); ?>,
                },
                <?php } else if($baby_filter == 'grup') {
                    foreach ($res_data_line_by_grup_month as $r_data_line_by_grup) { ?>
                    {
                        name: <?= json_encode($r_data_line_by_grup['grup']); ?>,
                        data: <?php echo json_encode($r_data_line_by_grup['data']); ?>,
                    },
                <?php }
                } ?>
            ]
        });
    <?php    }   ?>
</script>
<?= $this->endSection(); ?>