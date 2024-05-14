<?= $this->extend('template/dashboardPlateRejection/layout'); ?>

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

    if ($child_filter != null AND $child_filter != 0 AND $baby_filter == 'average') {
        $type_chart = 'line';
    } else {
        $type_chart = 'column';
    }
?>

<div class="content-wrapper" style="margin-left:0; margin-top:50px;">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
            <div class="row">
                <div class="box bg-transparent">
                    <div class="box-body" style="display:flex">
                        <div class="col-2">
                            <form action="<?=base_url()?>dashboard_plate_rejection/cos" method="POST">
                                <select class="form-select" name="jenis_dashboard" id="jenis_dashboard" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px; display:none;">
                                    <option value="1">Rejection</option>
                                    <option value="2">Unit / MH</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="parent_filter" id="parent_filter" style="display:none">
                                    <option value="line" <?= ($parent_filter == 'line') ? 'selected':''?>>Line</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="child_filter" id="child_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                                    <option value="0" <?= ($child_filter == '0') ? 'selected':''?>>All</option>
                                    <?php for ($i=1; $i <= 7 ; $i++) { ?>
                                        <option value="<?=$i?>" <?= ($child_filter == $i) ? 'selected':''?>>Line <?=$i?></option>
                                    <?php } ?>
                                </select>
                                &nbsp;
                                <select class="form-select" name="baby_filter" id="baby_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                                    <?php if ($child_filter == 0) { ?>
                                        <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                        <option value="line" <?= ($baby_filter == 'line') ? 'selected':''?>>By Line</option>
                                    <?php } else { ?>
                                        <option value="average" <?= ($baby_filter == 'average') ? 'selected':''?>>By Average</option>
                                    <?php } ?>
                                </select>
                                &nbsp;
                                <input type="month" class="form-control" name="bulan" id="bulan" value="<?= $bulan ?>" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                                &nbsp;
                                <div style="display: flex; flex-direction: column;" >
                                    <button class="btn btn-sm btn-success" style="font-size: 20px;font-weight: 900;width: 250px;"> Filter </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-2" style="display:flex;text-align:center;flex-direction: column;align-items: center;flex-wrap: nowrap;justify-content: space-around;">
                          <a href="<?=base_url()?>dashboard_plate_rejection/reject_plate_cutting" class="waves-effect waves-light btn btn-rounded btn-outline btn-success btn-lg btn-nav" style="width: 250px">Plate Cutting</a>
                          <a href="<?=base_url()?>dashboard_plate_rejection/envelope" class="waves-effect waves-light btn btn-rounded btn-outline btn-info btn-lg btn-nav" style="width: 250px">Envelope</a>
                          <a href="<?=base_url()?>dashboard_plate_rejection/cos" class="waves-effect waves-light btn btn-rounded btn-primary btn-lg btn-nav" style="width: 250px">COS</a>
                          <a href="<?=base_url()?>dashboard_plate_rejection/potong_battery" class="waves-effect waves-light btn btn-rounded btn-outline btn-danger btn-lg btn-nav" style="width: 250px">Potong Battery</a>
                        </div>
                        <div class="col-4" style="display:flex; margin-top:35px;">
                                <div class="col-4">
                                    <div id="year_to_date_chart" style="height:250px;"></div>
                                </div>
                                <div class="col-4">
                                    <div id="target_chart" style="height:250px;"></div>
                                </div>
                                <div class="col-4">
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
            <?php } else if ($baby_filter == 'line') { ?>
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
                <h4 class="modal-title" id="myLargeModalLabel">Detail COS</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_pareto_type_battery_cos"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tbl_cos" width="100%">
                        <thead>
                            <tr>
                                <th>Type Battery</th>
                                <th>Hasil Produksi</th>
                                <th>Tersangkut</th>
                                <th>Terbakar</th>
                                <th>Lug Lepas</th>
                                <th>Strip Tipis</th>
                            </tr>
                            </thead>
                        <tbody id="data_cos"></tbody>
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

<!-- SUB MODAL -->
<div class="modal fade" id="sub_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:120%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Detail COS</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sub_detail_pareto_cos"></div>
                <div id="sub_detail_pareto_kategori_reject"></div>
                <div id="sub_detail_pareto_type_battery"></div>
                <div id="sub_detail_pareto_grup_shift"></div>
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

    // PIE CHART Target
    var target_chart = echarts.init(document.getElementById('target_chart'));
    target_chart.setOption(
        {
            title: {
                text: '<?= json_encode($target) ?>%',
                subtext: 'Target <?= date('Y', strtotime($bulan)) ?>',
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

    // PIE CHART Current Month
    var current_month_chart = echarts.init(document.getElementById('current_month_chart'));
    current_month_chart.setOption(
        {
            title: {
                text: '<?=json_encode($data_cos_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>%',
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
                            value: <?=json_encode($data_cos_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?> * 100,
                            name: 'Monday',
                            itemStyle: {
                                color: 'orange'
                            }
                        },
                        {
                            value: 100 - (<?=json_encode($data_cos_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?> * 100),
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
            categories: <?php echo json_encode($data_cos_by_line); ?>,
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
                text: 'Battery',
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
            }
        },
        legend: {
                enabled: false
            },

        series: [{
            name: 'Battery',
            type: 'spline',
            data: [<?php foreach ($data_total_cos_by_line as $d_by_line) {
                echo $d_by_line['panel'] . ',';
            } ?>],
            color:'green',
        }, {
            name: 'Persentase',
            type: 'column',
            yAxis: 1,
            data: [<?php foreach ($data_total_cos_by_line as $d_by_line) {
                echo $d_by_line['persen'] . ',';
            } ?>],
            color:'yellow',
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

    // GENERATE X AXIS DATE
    <?php
        $dates = array();

        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-01');
        $now = date('Y-m-d');

        $target_by_date = array();
        $target_by_month = array();

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

        for ($i = 0; $i < 12; $i++) {
            array_push($target_by_month, $target);
        }
    ?>

    // VALIDASI FILTER

    <?php if($baby_filter == 'average') { ?>
        Highcharts.chart('average_daily_chart', {
            chart: {
            type: 'column',
            backgroundColor: 'transparent',
            },

            exporting: {
            enabled: false
            },

            title: {
            text: 'Daily COS',
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
                text: 'Battery',
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
                                url: '<?= base_url('dashboard_plate_rejection/cos/get_detail_cos') ?>',
                                type: 'POST',
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: 'JSON',
                                success: function(data) {
                                    console.log(data);
                                    var data_qty_type_battery_cos = data['data_qty_type_battery_cos'];
                                    var arr_type_battery_cos = [];
                                    var arr_qty_type_battery_cos = [];
                                    var sortedKeys = Object.keys(data_qty_type_battery_cos[0]).sort((a, b) => (data_qty_type_battery_cos[0][b] - data_qty_type_battery_cos[0][a]));
                                    var data_data_qty_type_battery_cos = {};
                                    sortedKeys.forEach(key => {
                                        data_data_qty_type_battery_cos[key] = data_qty_type_battery_cos[0][key];
                                    });
                                    for (let [key, value] of Object.entries(data_data_qty_type_battery_cos)) {
                                        arr_type_battery_cos.push(key.replace('_', ' ').toUpperCase());
                                        arr_qty_type_battery_cos.push(value);
                                    }
                                    $('#detail_pareto_type_battery_cos').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_type_battery_cos"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_type_battery_cos', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Rejection Internal (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_type_battery_cos,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                title: {
                                                    text: 'Cell',
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
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            series: [{
                                                name: 'Cell',
                                                type: 'column',
                                                data: arr_qty_type_battery_cos,
                                                color:'yellow',

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

                                    var html = '';
                                    var i;
                                    var total_produksi = 0;
                                    var total_tersangkut = 0;
                                    var total_terbakar = 0;
                                    var total_lug_lepas = 0;
                                    var total_strap_tipis = 0;
                                    for (i = 0; i < data['data_type_battery_cos_by_date'].length; i++) {
                                        html += '<tr>' +
                                                '<td style="white-space: nowrap">' + data['data_type_battery_cos_by_date'][i].type_battery + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_date'][i].total_produksi + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_date'][i].tersangkut + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_date'][i].terbakar + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_date'][i].lug_lepas + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_date'][i].strap_tipis + '</td>' +
                                            '</tr>';
                                            total_produksi += parseInt(data['data_type_battery_cos_by_date'][i].total_produksi);
                                            total_tersangkut += parseInt(data['data_type_battery_cos_by_date'][i].tersangkut);
                                            total_terbakar += parseInt(data['data_type_battery_cos_by_date'][i].terbakar);
                                            total_lug_lepas += parseInt(data['data_type_battery_cos_by_date'][i].lug_lepas);
                                            total_strap_tipis += parseInt(data['data_type_battery_cos_by_date'][i].strap_tipis);
                                        }
                                        html += '<tr>' +
                                                    '<td><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_produksi + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_tersangkut + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_terbakar + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_lug_lepas + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_strap_tipis + '</b></h5></td>' +
                                                '</tr>';
                                    $('#data_cos').html(html);
                                    
                                    $('#main_modal').modal('show');
                                }
                            })
                        }
                    }
                }
            },
            colors: ['green', 'yellow', 'cyan', 'azure', 'red', 'orange', 'blue'],
            
            series: [{
                name: 'Battery',
                type: 'spline',
                data: [<?php foreach ($data_average_cos_by_date_all_line as $d_by_date) {
                    echo $d_by_date['panel'] . ',';
                } ?>]
            }, {
                name: 'Persentase',
                type: 'column',
                yAxis: 1,
                data: [<?php foreach ($data_average_cos_by_date_all_line as $d_by_date) {
                    echo $d_by_date['persentase'] . ',';
                } ?>]
            },
            {
                type: 'spline',
                name: 'Target',
                dashStyle: 'Dash',
                data: <?= json_encode($target_by_date); ?>,
                color:'red',
                yAxis: 1
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

        Highcharts.chart('average_month_chart', {
            chart: {
            type: 'column',
            backgroundColor: 'transparent',
            },

            exporting: {
            enabled: false
            },

            title: {
            text: 'Monthly COS',
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
                text: 'Battery',
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
                        click: function(e) {
                            var date = '01-'+e.point.category+'-<?=date('Y')?>';
                            var line = <?=$child_filter?>;
                            $.ajax({
                                url: "<?= base_url('dashboard_plate_rejection/cos/get_detail_cos') ?>",
                                type: "post",
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    var data_qty_type_battery_cos = data['data_qty_type_battery_cos_by_month'];
                                    var arr_type_battery_cos = [];
                                    var arr_qty_type_battery_cos = [];
                                    var sortedKeys = Object.keys(data_qty_type_battery_cos[0]).sort((a, b) => (data_qty_type_battery_cos[0][b]) - (data_qty_type_battery_cos[0][a]));
                                    var data_data_qty_type_battery_cos = {};
                                    sortedKeys.forEach(key => {
                                        data_data_qty_type_battery_cos[key] = data_qty_type_battery_cos[0][key];
                                    });
                                    for (let [key, value] of Object.entries(data_data_qty_type_battery_cos)) {
                                        arr_type_battery_cos.push(key.replace('_', ' ').toUpperCase());
                                        arr_qty_type_battery_cos.push(value);
                                    }
                                    $('#detail_pareto_type_battery_cos').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_type_battery_cos"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_type_battery_cos', {
                                       chart: {
                                            type: 'column',
                                            backgroundColor: 'transparent',
                                            },

                                            exporting: {
                                            enabled: false
                                            },
                                            title: {
                                                text: 'Detail COS',
                                                align: 'center',
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_type_battery_cos,
                                                crosshair: true,
                                                labels: {
                                                    style: {
                                                        color: '#ffffff'
                                                    }
                                                }
                                            },
                                            yAxis: [{
                                                title: {
                                                    text: 'Cell',
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
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            series: [{
                                                name: 'Cell',
                                                type: 'column',
                                                data: arr_qty_type_battery_cos,
                                                color:'yellow',
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

                                    var html = '';
                                    var i;
                                    var total_produksi = 0;
                                    var total_tersangkut = 0;
                                    var total_terbakar = 0;
                                    var total_lug_lepas = 0;
                                    var total_strap_tipis = 0;
                                    for (i = 0; i < data['data_type_battery_cos_by_month'].length; i++) {
                                        html += '<tr>' +
                                                '<td style="white-space: nowrap">' + data['data_type_battery_cos_by_month'][i].type_battery + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_month'][i].total_produksi + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_month'][i].tersangkut + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_month'][i].terbakar + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_month'][i].lug_lepas + '</td>' +
                                                '<td>' + data['data_type_battery_cos_by_month'][i].strap_tipis + '</td>' +
                                            '</tr>';
                                            total_produksi += parseInt(data['data_type_battery_cos_by_month'][i].total_produksi);
                                            total_tersangkut += parseInt(data['data_type_battery_cos_by_month'][i].tersangkut);
                                            total_terbakar += parseInt(data['data_type_battery_cos_by_month'][i].terbakar);
                                            total_lug_lepas += parseInt(data['data_type_battery_cos_by_month'][i].lug_lepas);
                                            total_strap_tipis += parseInt(data['data_type_battery_cos_by_month'][i].strap_tipis);
                                        }
                                        html += '<tr>' +
                                                    '<td><h5><b>Total</b></h5></td>' +
                                                    '<td><h5><b>' + total_produksi + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_tersangkut + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_terbakar + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_lug_lepas + '</b></h5></td>' +
                                                    '<td><h5><b>' + total_strap_tipis + '</b></h5></td>' +
                                                '</tr>';
                                    $('#data_cos').html(html);
                                    
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
                    }
                <?php } else { ?>
                    enabled: false
                <?php } ?>
                },
            colors: ['green', 'yellow', 'cyan', 'azure', 'red', 'orange', 'blue'],

            series: [{
                name: 'Battery',
                type: 'spline',
                data: [<?php foreach ($data_average_cos_by_month as $d_by_month) {
                    echo $d_by_month['panel'] . ',';
                } ?>]
            }, {
                name: 'Persentase',
                type: 'column',
                yAxis: 1,
                data: [<?php foreach ($data_average_cos_by_month as $d_by_month) {
                    echo $d_by_month['persentase'] . ',';
                } ?>]
            }, {
                type: 'spline',
                name: 'Target',
                dashStyle: 'Dash',
                data: <?= json_encode($target_by_month); ?>,
                color:'red',
                yAxis: 1,
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
    <?php } elseif ($baby_filter == 'line') { ?>
        Highcharts.chart('average_daily_chart_by_line', {
            chart: {
                backgroundColor: 'transparent',
                type: 'line'
            },
    
            exporting: {
                enabled: false
            },
    
            title: {
                text: 'Daily COS <?=($child_filter == 0) ? 'All Line' : 'Line '.$child_filter?>',
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
                    text: '%'
                },
                labels: {
                    style: {
                        color: '#ffffff'
                    }
                }
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
                }
            },
            colors: ['yellow', 'green', 'cyan', 'azure', 'red', 'orange', 'blue'],
            
            series: [{
                        name: 'Line 1',
                        data: <?php echo json_encode($data_cos_line_1); ?>
                    },
                    {
                        name: 'Line 2',
                        data: <?php echo json_encode($data_cos_line_2); ?>
                    },
                    {
                        name: 'Line 3',
                        data: <?php echo json_encode($data_cos_line_3); ?>
                    },
                    {
                        name: 'Line 4',
                        data: <?php echo json_encode($data_cos_line_4); ?>
                    },
                    {
                        name: 'Line 5',
                        data: <?php echo json_encode($data_cos_line_5); ?>
                    },
                    {
                        name: 'Line 6',
                        data: <?php echo json_encode($data_cos_line_6); ?>
                    },
                    {
                        name: 'Line 7',
                        data: <?php echo json_encode($data_cos_line_7); ?>
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
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Monthly COS',
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
                title: {
                    text: '%'
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
            colors: ['yellow', 'red', 'cyan', 'azure', 'green', 'orange', 'blue'],
    
            series: [
                {
                    name: 'Line 1',
                    data: <?php echo json_encode($data_cos_by_month_line_1); ?>,
                },
                {
                    name: 'Line 2',
                    data: <?php echo json_encode($data_cos_by_month_line_2); ?>,
                },
                {
                    name: 'Line 3',
                    data: <?php echo json_encode($data_cos_by_month_line_3); ?>,
                },
                {
                    name: 'Line 4',
                    data: <?php echo json_encode($data_cos_by_month_line_4); ?>,
                },
                {
                    name: 'Line 5',
                    data: <?php echo json_encode($data_cos_by_month_line_5); ?>,
                },
                {
                    name: 'Line 6',
                    data: <?php echo json_encode($data_cos_by_month_line_6); ?>,
                },
                {
                    name: 'Line 7',
                    data: <?php echo json_encode($data_cos_by_month_line_7); ?>,
                }
            ],
            tooltip: {
                shared: true
            },
        });
    <?php    }   ?>
</script>
<?= $this->endSection(); ?>