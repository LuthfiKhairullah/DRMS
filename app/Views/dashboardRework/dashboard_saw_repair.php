<?= $this->extend('template/dashboardRework/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<?php date_default_timezone_set('Asia/Jakarta'); ?>
<?php
$current_date = idate('m', strtotime($bulan));
if ($current_date != 1) {
  $previous_date = $current_date - 1;
} else {
  $previous_date = 12;
}
$uri = current_url(true);
?>

<div class="content-wrapper" style="margin-left:0;">
  <div class="container-full">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="box bg-transparent">
          <div class="box-body" style="display:flex">
            <div class="col-2">
              <form action="<?= base_url() ?>dashboard_rework/saw_repair" method="POST">
                <select class="form-select" name="shift" id="shift" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                  <option value="">All Shift</option>
                  <option value="1" <?= $shift == 1 ? 'selected' : '' ?>>Shift 1</option>
                  <option value="2" <?= $shift == 2 ? 'selected' : '' ?>>Shift 2</option>
                  <option value="3" <?= $shift == 3 ? 'selected' : '' ?>>Shift 3</option>
                </select>
                &nbsp;
                <select class="form-select" name="operator" id="operator" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;" onchange="changeOperator(this)">
                  <option value="">All Operator</option>
                  <option value="Eko BS" <?= $operator == 'Eko BS' ? 'selected' : '' ?>>Eko BS</option>
                  <option value="Kistoro" <?= $operator == 'Kistoro' ? 'selected' : '' ?>>Kistoro</option>
                  <option value="M. Tohar" <?= $operator == 'M. Tohar' ? 'selected' : '' ?>>M. Tohar</option>
                  <option value="Purwanta" <?= $operator == 'Purwanta' ? 'selected' : '' ?>>Purwanta</option>
                  <option value="Renas" <?= $operator == 'Renas' ? 'selected' : '' ?>>Renas</option>
                  <option value="Sarjono" <?= $operator == 'Sarjono' ? 'selected' : '' ?>>Sarjono</option>
                  <option value="Widodo" <?= $operator == 'Widodo' ? 'selected' : '' ?>>Widodo</option>
                </select>
                &nbsp;
                <select class="form-select" name="jenis_dashboard" id="jenis_dashboard" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                  <option value="1" <?= $jenis_dashboard == '1' ? 'selected' : '' ?>>By Average</option>
                  <option value="2" <?= $jenis_dashboard == '2' ? 'selected' : '' ?>>By Operator</option>
                </select>
                &nbsp;
                <input type="month" class="form-control" name="bulan" id="bulan" value="<?= $bulan ?>" style="border-width: thick;border: wh;font-size: 20px;font-weight: 900;width: 250px;">
                &nbsp;
                <div style="display: flex; flex-direction: column;">
                  <button class="btn btn-sm btn-success" style="font-size: 20px;font-weight: 900;width: 250px;"> Filter </button>
                </div>
              </form>
            </div>
            <div class="col-3"></div>
            <div class="col-3"></div>
            <div class="col-4" style="text-align:center">
                <div>
                    <h4 style="font-weight: 500;color: yellow;">Qty Comparison </h4>
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
                            <tr style="line-height: 0px;">
                                <td>Battery</td>
                                <td><?= $data_saw_repair_by_month[$previous_date - 1]['qty'] ?></td>
                                <td><?= $data_saw_repair_by_month[$current_date - 1]['qty'] ?></td>
                                <td>
                                    <?php if($data_saw_repair_by_month[$current_date - 1]['qty'] > $data_saw_repair_by_month[$previous_date - 1]['qty']) {
                                        echo '<i class="fa fa-arrow-up" style="color:green"></i>';
                                    } else if($data_saw_repair_by_month[$current_date - 1]['qty'] < $data_saw_repair_by_month[$previous_date - 1]['qty']) {
                                        echo '<i class="fa fa-arrow-down" style="color:red"></i>';
                                    } else if($data_saw_repair_by_month[$current_date - 1]['qty'] == $data_saw_repair_by_month[$previous_date - 1]['qty']) {
                                        echo '<i class="fa fa-minus" style="color:yellow"></i>';
                                    } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>                                        
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-lg-6">
          <figure class="highcharts-figure">
            <div id="total_daily_saw_repair_chart"></div>
          </figure>
        </div>
        <div class="col-12 col-lg-6">
          <figure class="highcharts-figure">
            <div id="total_monthly_saw_repair_chart"></div>
          </figure>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>
<!-- /.content-wrapper -->

<!-- MODAL -->
<div class="modal fade" id="saw_repair_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width:120%;">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Detail SAW Rework</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="detail_pareto_type_battery"></div>
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
<?= $this->endSection();
?>

<?= $this->section('script'); ?>
<script>

  // GENERATE X AXIS DATE
  <?php
  $dates = array();
  $target_by_date = array();
  $target_by_month = array();

  date_default_timezone_set('Asia/Jakarta');
  $start = date('Y-m-01');
  $now = date('Y-m-d');

  $current_month = date('Y-m');
  if ($bulan != null or $bulan != $current_month) {
    $start = date('Y-m-01', strtotime($bulan));
    $now = date('Y-m-t', strtotime($bulan));
  }

  while (strtotime($start) <= strtotime($now)) {
    array_push($dates, date("d", strtotime($start)));
    array_push($target_by_date, $target);
    $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
  }

  for ($i = 0; $i < 12; $i++) {
    array_push($target_by_month, $target);
  }
  ?>
  <?php if($jenis_dashboard == 1) { ?>
  Highcharts.chart('total_daily_saw_repair_chart', {
    chart: {
      type: 'column',
      backgroundColor: 'transparent',
    },

    exporting: {
      enabled: false
    },

    title: {
      text: 'Daily SAW Rework (Half Finish)',
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
    },

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
            events : {
                click: function(event) {
                    let bulan = document.querySelector('#bulan').value;
                    let shift = document.querySelector('#shift').value;
                    let operator = document.querySelector('#operator').value;
                    let date = bulan + '-' + event.point.category;

                    $.ajax({
                        url: "<?= base_url('dashboard_rework/saw_repair/get_detail_saw_repair'); ?>",
                        type: "POST",
                        data: {
                            date: date,
                            shift: shift,
                            operator: operator
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            let data_type_battery = data['data_type_battery_by_date'];
                            let i;
                            let arr_type_battery = [];
                            let arr_qty_type_battery = [];
                            for (i = 0; i < data_type_battery.length; i++) {
                                arr_type_battery.push(data_type_battery[i].type_battery);
                                arr_qty_type_battery.push(parseFloat(data_type_battery[i].qty));
                            }

                            $('#detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                            <div id="chart_detail_pareto_type_battery"></div>
                                                                        </figure>`
                                                                    );
                            Highcharts.chart('chart_detail_pareto_type_battery', {
                                chart: {
                                        backgroundColor: 'transparent',
                                        type: 'column'
                                    },
                                    exporting: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Detail SAW Rework',
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
                                            text: 'Battery',
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
                                        name: 'Battery',
                                        type: 'column',
                                        data: arr_qty_type_battery,
                                        color:'yellow',
                                    }],
                                    tooltip: {
                                      shared: true
                                    },
                            });
                            
                            $('#saw_repair_modal').modal('show');
                        }
                    })
                }
            }
        }
    },
    colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],

    series: [{
      name: 'Battery',
      type: 'column',
      data: [<?php foreach($data_saw_repair_by_date as $d_by_date) {
        echo $d_by_date['qty'] . ',';
      } ?>]
    }, {
      type: 'spline',
      name: 'Target',
      dashStyle: 'Dash',
      data: <?= json_encode($target_by_date); ?>,
      color:'red'
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

  Highcharts.chart('total_monthly_saw_repair_chart', {
    chart: {
      type: 'column',
      backgroundColor: 'transparent',
    },

    exporting: {
      enabled: false
    },

    title: {
      text: 'Monthly SAW Rework (Half Finish)',
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
            events : {
                click: function(event) {
                    let bulan = document.querySelector('#bulan').value;
                    let shift = document.querySelector('#shift').value;
                    let operator = document.querySelector('#operator').value;
                    bulan = new Date(bulan);
                    let date = bulan.getFullYear() + '-' + String(event.point.index + 1).padStart(2, '0') + '-01';

                    $.ajax({
                        url: "<?= base_url('dashboard_rework/saw_repair/get_detail_saw_repair'); ?>",
                        type: "POST",
                        data: {
                            date: date,
                            shift: shift,
                            operator: operator
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            let data_type_battery = data['data_type_battery_by_month'];
                            let i;
                            let arr_type_battery = [];
                            let arr_qty_type_battery = [];
                            for (i = 0; i < data_type_battery.length; i++) {
                                arr_type_battery.push(data_type_battery[i].type_battery);
                                arr_qty_type_battery.push(parseFloat(data_type_battery[i].qty));
                            }

                            $('#detail_pareto_type_battery').html(`  <figure class="highcharts-figure">
                                                                            <div id="chart_detail_pareto_type_battery"></div>
                                                                        </figure>`
                                                                    );
                            Highcharts.chart('chart_detail_pareto_type_battery', {
                                chart: {
                                        backgroundColor: 'transparent',
                                        type: 'column'
                                    },
                                    exporting: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Detail SAW Rework',
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
                                            text: 'Battery',
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
                                        name: 'Battery',
                                        type: 'column',
                                        data: arr_qty_type_battery,
                                        color:'yellow',
                                    }],
                                    tooltip: {
                                      shared: true
                                    },
                            });
                            
                            $('#saw_repair_modal').modal('show');
                        }
                    })
                }
            }
        }
    },
    colors: ['yellow', 'red', 'cyan', 'azure', 'LawnGreen', 'orange', 'blue'],

    series: [{
      name: 'Battery',
      type: 'column',
      data: [<?php foreach($data_saw_repair_by_month as $d_by_month) {
        echo $d_by_month['qty'] . ',';
      } ?>]
    }, {
      type: 'spline',
      name: 'Target',
      dashStyle: 'Dash',
      data: <?= json_encode($target_by_month); ?>,
      color:'red'
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
  <?php } else { ?>
  Highcharts.chart('total_daily_saw_repair_chart', {
    chart: {
      type: 'spline',
      backgroundColor: 'transparent',
    },

    exporting: {
      enabled: false
    },

    title: {
      text: 'Daily SAW Rework (Half Finish)',
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
    },

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
        spline: {
            dataLabels: {
                style: {
                    color: '#ffffff',
                    textOutline: 0,
                    fontSize: 14
                },
            },
        }
    },
    colors: ['yellow', 'lawngreen', 'cyan', 'azure', 'red', 'orange', 'blue'],

    series: [<?php foreach ($data_operator as $do) { ?>
      {
        name: '<?= $do['operator'] ?>',
        type: 'spline',
        data: [<?php foreach($data['data_saw_repair_by_date_by_' . $do['operator']] as $d_by_date) {
          echo $d_by_date['qty'] . ',';
        } ?>]
      },
      <?php } ?>
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

  Highcharts.chart('total_monthly_saw_repair_chart', {
    chart: {
      type: 'spline',
      backgroundColor: 'transparent',
    },

    exporting: {
      enabled: false
    },

    title: {
      text: 'Monthly SAW Rework (Half Finish)',
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

    legend: {
      align: 'center',
      verticalAlign: 'bottom',
      layout: 'horizontal',
      itemStyle: {
        color: '#ffffff'
      }
    },
    plotOptions: {
        spline: {
            dataLabels: {
                style: {
                    color: '#ffffff',
                    textOutline: 0,
                    fontSize: 14
                },
            },
        }
    },
    colors: ['yellow', 'lawngreen', 'cyan', 'azure', 'red', 'orange', 'blue'],

    series: [<?php foreach ($data_operator as $do) { ?>
      {
        name: '<?= $do['operator'] ?>',
        type: 'spline',
        data: [<?php foreach($data['data_saw_repair_by_month_by_' . $do['operator']] as $d_by_month) {
          echo $d_by_month['qty'] . ',';
        } ?>]
      },
      <?php } ?>
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
  <?php } ?>

  function changeOperator(operator) {
    let jenis_dashboard = document.querySelector('#jenis_dashboard');
    if(operator.value != 1) {
      jenis_dashboard.innerHTML = `<option value="1">By Average</option>`;
    } else {
      jenis_dashboard.innerHTML = `
        <option value="1">By Average</option>
        <option value="2">By Operator</option>
      `;
    }
  }
</script>
<?= $this->endSection(); ?>