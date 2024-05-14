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

    if ($child_filter != null AND $child_filter != 0 AND $baby_filter == 'average') {
        $type_chart = 'line';
    } else {
        $type_chart = 'column';
    }

    $data = $data_line_stop_by_date;
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

    $data_daily_persentase = $data_line_stop_by_date_persentase;
    $merged_daily_persentase = array();
    foreach ($data_daily_persentase as $item) {
        $name = $item["name"];
        if (!isset($merged_daily_persentase[$name])) {
            $merged_daily_persentase[$name] = array("name" => $name, "data" => array($item["data"]));
        } else {
            array_push($merged_daily_persentase[$name]["data"], $item["data"]);
        }
    }
    $result_daily_line_stop_persentase = array_values($merged_daily_persentase);
?>

<div class="content-wrapper" style="margin-left:0; margin-top:50px;">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
            <div class="row">
                <div class="box bg-transparent">
                    <div class="box-body" style="display:flex">
                        <div class="col-2">
                            <form action="<?=base_url()?>dashboard/line_stop_mcb" method="POST">
                                <select class="form-select" name="jenis_dashboard" id="jenis_dashboard" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px; display:none;">
                                    <option value="1">Line Stop</option>
                                    <option value="2">Unit / MH</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="parent_filter" id="parent_filter" style="display:none">
                                    <option value="line" <?= ($parent_filter == 'line') ? 'selected':''?>>Line</option>
                                </select>
                                &nbsp;
                                <select class="form-select" name="child_filter" id="child_filter" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 175px;">
                                    <option value="0" <?= ($child_filter == '0') ? 'selected':''?>>All</option>
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
                                <a href="<?=base_url()?>dashboard/assy/mcb" class="waves-effect waves-light btn btn-rounded btn-outline btn-success btn-lg btn-nav">Efficiency</a>
                                <a href="<?=base_url()?>dashboard/reject_mcb" class="waves-effect waves-light btn btn-rounded btn-outline btn-danger btn-lg btn-nav">Rejection</a>
                                <a href="<?=base_url()?>dashboard/line_stop_mcb" class="waves-effect waves-light btn btn-rounded btn-warning btn-lg btn-nav">Line Stop</a>
                            </div>
                            <div class="col-3">
                                <div id="year_to_date_chart" style="height:250px;"></div>
                            </div>
                            <div class="col-3">
                                <div id="previous_month_chart" style="height:250px;"></div>
                            </div>
                            <div class="col-3">
                                <div id="current_month_chart" style="height:250px;"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="box bg-transparent">
                                <div class="box-body">
                                    <figure class="highcharts-figure">
                                        <div id="pareto_line_stop"></div>
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
<div class="modal fade" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:120%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Detail Line Stop</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_pareto_jenis_line_stop"></div>
                <div id="detail_pareto_kategori_line_stop"></div>
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
                <h4 class="modal-title" id="myLargeModalLabel">Detail Line Stop</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sub_detail_pareto_jenis_line_stop"></div>
                <div id="sub_detail_pareto_kategori_line_stop"></div>
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
                            value: parseFloat(<?=json_encode($data_all_year)?>),
                            name: 'Monday',
                            itemStyle: {
                                color: 'blue'
                            }
                        },
                        {
                            value: 100 - parseFloat(<?=json_encode($data_all_year)?>),
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
    var previous_month_chart = echarts.init(document.getElementById('previous_month_chart'));
    previous_month_chart.setOption(
        {
            title: {
                text: '<?=json_encode($data_line_stop_previous_month)?>%',
                subtext: '<?=date('F', mktime(0, 0, 0, $previous_date, 10))?> Line Stop',
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
                            value: <?=json_encode($data_line_stop_previous_month)?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'red'
                            }
                        },
                        {
                            value: 100 - parseFloat(<?=json_encode($data_line_stop_previous_month)?>),
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
                text: '<?=json_encode($data_line_stop_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>%',
                subtext: '<?=date('F', mktime(0, 0, 0, $current_date, 10))?> Line Stop',
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
                            value: <?=json_encode($data_line_stop_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>,
                            name: 'Monday',
                            itemStyle: {
                                color: 'orange'
                            }
                        },
                        {
                            value: 100 - parseFloat(<?=json_encode($data_line_stop_all_month[date('n', mktime(0, 0, 0, $current_date, 10)) - 1])?>),
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

    Highcharts.chart('pareto_line_stop', {
        chart: {
            backgroundColor: 'transparent',
            type: 'column',
            // backgroundColor: '#0c1a32',
            height: 300
            
        },
        exporting: {
            enabled: false
        },
        title: {
            text: '<?=date('F', strtotime($bulan))?> Line Stop (%)',
            style: {
                color: '#ffffff',
                fontSize: '20px'
            }
        },
        xAxis: {
            categories: <?php echo json_encode($data_line_stop_by_line); ?>,
            crosshair: true,
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: '%'
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
                enabled: false
            },

        series: [{
            data: <?php echo json_encode($data_total_line_stop_by_line); ?>,
            color:'yellow',

        },
    ]
    });

    // GENERATE X AXIS DATE
    <?php
        $dates = array();

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
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
        }
    ?>

    // VALIDASI FILTER

    <?php if($baby_filter == 'average') { ?>
        Highcharts.chart('average_daily_chart', {
            chart: {
                type: 'column',
                // backgroundColor: '#12213c',
                // backgroundColor: '#0c1a32',
                backgroundColor: 'transparent',
                // type: '<?=$type_chart?>'
            },

            exporting: {
                enabled: false
            },

            title: {
                text: 'Daily Line Stop <?=($child_filter == 0) ? 'All Line' : 'Line '.$child_filter?>',
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
                            text: 'Menit',
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
                        min: 0,
                        max: 100,
                        title: {
                            text: '%'
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
                                url: '<?= base_url('dashboard/line_stop_mcb/get_detail_line_stop') ?>',
                                type: 'POST',
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: 'JSON',
                                success: function(data) {
                                    console.log(data);
                                    var data_jenis_line_stop = data['data_jenis_line_stop'];
                                    var i;
                                    var arr_jenis_line_stop = [];
                                    var arr_qty_jenis_line_stop = [];
                                    var arr_menit_jenis_line_stop = [];
                                    for (i = 0; i < data_jenis_line_stop.length; i++) {
                                        arr_jenis_line_stop.push(data_jenis_line_stop[i].jenis_breakdown);
                                        arr_qty_jenis_line_stop.push(parseFloat(((data_jenis_line_stop[i].qty / data['total_aktual_by_date'][0]['loading_time']) * 100).toFixed(2)));
                                        arr_menit_jenis_line_stop.push(parseInt(data_jenis_line_stop[i].qty));
                                    }
                                    console.log(arr_qty_jenis_line_stop);
                                    $('#detail_pareto_jenis_line_stop').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_jenis_line_stop"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_jenis_line_stop', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: `Detail Line Stop (${date})`,
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_jenis_line_stop,
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
                                                    text: 'Menit',
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
                                                            var jenis_line_stop = event.point.category;

                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/line_stop_mcb/get_detail_line_stop'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    jenis_line_stop: jenis_line_stop
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data['data_line_stop_by_jenis_line_stop']);
                                                                    var data_line_stop_by_jenis_line_stop = data['data_line_stop_by_jenis_line_stop'];
                                                                    var i;
                                                                    var arr_kategori_line_stop = [];
                                                                    var arr_qty_kategori_line_stop = [];
                                                                    var arr_menit_kategori_line_stop = [];

                                                                    var data1 = [];
                                                                    var data2 = [];

                                                                    var data3 = [];
                                                                    var data4 = [];

                                                                    if (jenis_line_stop == 'ANDON') {
                                                                        for (i = 0; i < data_line_stop_by_jenis_line_stop.length; i++) {
                                                                            data1.push(data_line_stop_by_jenis_line_stop[i].proses_breakdown.split("-")[3]);
                                                                            data2.push(data_line_stop_by_jenis_line_stop[i].qty);

                                                                            var mergedObject = data1.reduce((result, key, index) => {
                                                                                if (result[key]) {
                                                                                    result[key] += data2[index];
                                                                                } else {
                                                                                    result[key] = data2[index];
                                                                                }
                                                                                return result;
                                                                            }, {});
                                                                        }

                                                                        data3.push(Object.keys(mergedObject));
                                                                        data4.push(Object.values(mergedObject));

                                                                        for (i = 0; i < data3[0].length; i++) {
                                                                            arr_kategori_line_stop.push(data3[0][i]);
                                                                            arr_qty_kategori_line_stop.push(parseFloat(((data4[0][i] / data['total_aktual_by_date'][0]['loading_time']) * 100).toFixed(2)));
                                                                            arr_menit_kategori_line_stop.push(parseInt(data4[0][i]));
                                                                        }
                                                                    } else {
                                                                        for (i = 0; i < data_line_stop_by_jenis_line_stop.length; i++) {
                                                                            arr_kategori_line_stop.push(data_line_stop_by_jenis_line_stop[i].proses_breakdown);
                                                                            arr_qty_kategori_line_stop.push(parseFloat(((data_line_stop_by_jenis_line_stop[i].qty / data['total_aktual_by_date'][0]['loading_time']) * 100).toFixed(2)));
                                                                            arr_menit_kategori_line_stop.push(parseInt(data_line_stop_by_jenis_line_stop[i].qty));
                                                                        }
                                                                    }

                                                                    console.log(mergedObject);

                                                                    $('#sub_detail_pareto_kategori_line_stop').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_detail_pareto_ketegori_line_stop"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_detail_pareto_ketegori_line_stop', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail '+jenis_line_stop+' Line Stop',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_line_stop,
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
                                                                                    text: 'Menit',
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
                                                                                data: arr_qty_kategori_line_stop,
                                                                                color:'yellow',
                                                                                yAxis: 1
                                                                            },{
                                                                                name: 'Menit',
                                                                                type: 'spline',
                                                                                data: arr_menit_kategori_line_stop,
                                                                                color:'red',
                                                                            }]
                                                                    });

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
                                                data: arr_qty_jenis_line_stop,
                                                color:'yellow',
                                                yAxis: 1
                                            },{
                                                name: 'Menit',
                                                type: 'spline',
                                                data: arr_menit_jenis_line_stop,
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
            colors: ['yellow', 'green', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],
            
            series: [{
                name: 'Persentase',
                type: 'column',
                yAxis: 1,
                data: <?= json_encode($data_average_line_stop_by_date_all_line); ?>
            }, {
                name: 'Menit',
                type: 'spline',
                data: <?= json_encode($data_menit_line_stop_by_date_all_line); ?>
                // color: 'green'
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
                // type: '<?=$type_chart?>'
                type: 'column',
                // backgroundColor: '#0c1a32',
                
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Monthly Line Stop',
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
                max: 100,
                title: {
                    text: '%'
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
                    events: {
                        click: function(e) {
                            // var date = '01-'+e.point.category+'-<?=date('Y')?>';
                            var date = '01-'+e.point.category+'-'+$('#bulan').val().substr(0, 4);
                            var line = <?=$child_filter?>;
                            // alert(date);
                            $.ajax({
                                url: "<?= base_url('dashboard/line_stop_mcb/get_detail_line_stop') ?>",
                                type: "post",
                                data: {
                                    date: date,
                                    line: line
                                },
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    var data_jenis_line_stop = data['data_jenis_line_stop_by_month'];
                                    var i;
                                    var arr_jenis_line_stop = [];
                                    var arr_qty_jenis_line_stop = [];
                                    for (i = 0; i < data_jenis_line_stop.length; i++) {
                                        arr_jenis_line_stop.push(data_jenis_line_stop[i].jenis_line_stop);
                                        arr_qty_jenis_line_stop.push(data_jenis_line_stop[i].qty);
                                    }
                                    $('#detail_pareto_jenis_line_stop').html(`<figure class="highcharts-figure">
                                                                                            <div id="chart_pareto_jenis_line_stop"></div>
                                                                                        </figure>
                                                                                    `);
                                    Highcharts.chart('chart_pareto_jenis_line_stop', {
                                        chart: {
                                                backgroundColor: 'transparent',
                                                type: 'column'
                                            },
                                            exporting: {
                                                enabled: false
                                            },
                                            title: {
                                                text: 'Detail Jenis Line Stop',
                                                style: {
                                                    color: '#ffffff',
                                                    fontSize: '20px'
                                                }
                                            },
                                            xAxis: {
                                                categories: arr_jenis_line_stop,
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
                                                    text: '%'
                                                }
                                            },
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
                                                            var jenis_line_stop = event.point.category;
                                                            $.ajax({
                                                                url: "<?= base_url('dashboard/line_stop_mcb/get_detail_line_stop'); ?>",
                                                                type: "POST",
                                                                data: {
                                                                    date: date,
                                                                    line: line,
                                                                    jenis_line_stop: jenis_line_stop
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);
                                                                    var data_line_stop_by_jenis_line_stop = data['data_line_stop_by_jenis_line_stop_by_month'];
                                                                    var i;
                                                                    var arr_kategori_line_stop = [];
                                                                    var arr_qty_kategori_line_stop = [];
                                                                    for (i = 0; i < data_line_stop_by_jenis_line_stop.length; i++) {
                                                                        arr_kategori_line_stop.push(data_line_stop_by_jenis_line_stop[i].kategori_line_stop);
                                                                        arr_qty_kategori_line_stop.push(data_line_stop_by_jenis_line_stop[i].qty);
                                                                    }
                                                                    $('#sub_detail_pareto_kategori_line_stop').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_pareto_ketegori_line_stop"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_pareto_ketegori_line_stop', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail '+jenis_line_stop+' Line Stop',
                                                                                style: {
                                                                                    color: '#ffffff',
                                                                                    fontSize: '20px'
                                                                                }
                                                                            },
                                                                            xAxis: {
                                                                                categories: arr_kategori_line_stop,
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
                                                                                    text: '%'
                                                                                }
                                                                            },
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
                                                                            series: [{
                                                                                name: 'Total',
                                                                                data: arr_qty_kategori_line_stop,
                                                                                color:'yellow',

                                                                            }]
                                                                    });

                                                                    var data_line_stop_by_type_battery = data['data_line_stop_by_type_battery_by_month'];
                                                                    var i;
                                                                    var arr_type_battery = [];
                                                                    var arr_qty_type_battery = [];
                                                                    for (i = 0; i < data_line_stop_by_type_battery.length; i++) {
                                                                        arr_type_battery.push(data_line_stop_by_type_battery[i].type_battery);
                                                                        arr_qty_type_battery.push(data_line_stop_by_type_battery[i].qty);
                                                                    }
                                                                    $('#sub_detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                                                                    <div id="chart_pareto_battery_line_stop"></div>
                                                                                                                </figure>`
                                                                                                            );
                                                                    Highcharts.chart('chart_pareto_battery_line_stop', {
                                                                        chart: {
                                                                                backgroundColor: 'transparent',
                                                                                type: 'column'
                                                                            },
                                                                            exporting: {
                                                                                enabled: false
                                                                            },
                                                                            title: {
                                                                                text: 'Detail Type Battery Line Stop',
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
                                                                            yAxis: {
                                                                                min: 0,
                                                                                title: {
                                                                                    text: '%'
                                                                                }
                                                                            },
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
                                                                            series: [{
                                                                                name: 'Total',
                                                                                data: arr_qty_type_battery,
                                                                                color:'yellow',

                                                                            }]
                                                                    });
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
                                            series: [{
                                                name: 'Total',
                                                data: arr_qty_jenis_line_stop,
                                                color:'yellow',

                                            }]
                                    });
                                    $('#detail_pareto_kategori_line_stop').html(``);
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
                    }
                <?php } else { ?>
                    enabled: false
                <?php } ?>
                },
            colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],

            series: [{
                name: 'All Line',
                data: <?= json_encode($data_average_line_stop_by_month); ?>
            }
            ],
        });
    <?php } elseif ($baby_filter == 'line') { ?>
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
                text: 'Daily Line Stop <?=($child_filter == 0) ? 'All Line' : 'Line '.$child_filter?>',
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
                    // pointWidth: 30,
                }
            },
            colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],
            
            series: [
                {
                    name: 'MCB',
                    data: <?php echo json_encode($data_line_stop_line_10); ?>
                },
            ],
    
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
                text: 'Monthly Line Stop',
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
                    text: '%'
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
    
            series: [
                {
                    name: 'MCB',
                    data: <?php echo json_encode($data_line_stop_by_month_line_10); ?>,
                },
            ]
        });
    <?php    }   ?>
</script>
<?= $this->endSection(); ?>