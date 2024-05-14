<?= $this->extend('template/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Laporan Potong Battery</h4>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".modal_tambah_potong_battery">
                                        Tambah Potong Battery
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="date" class="form-label">Start Date</label>
                                                <input type="text" name="min" id="min" class="form-control my-2 mr-sm-2" style="width: 200px">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="line" class="form-label">End Date</label>
                                                <input type="text" name="max" id="max" class="form-control my-2 mr-sm-2" style="width: 200px">
                                            </div>
                                        </div>
                                        <div class="col-4" style="text-align:right;">
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target=".modal_download_potong_battery">
                                                Download
                                            </button>
                                        </div>
                                        <div class="col-2" style="float:right;">
                                            <?php
                                            $bulan = date('Y-m');
                                            $uri = current_url(true);
                                            if ($uri->setSilent()->getSegment(4) != NULL) {
                                                $bulan = $uri->getSegment(4);
                                            }
                                            ?>
                                            <input type="month" class="form-control" name="filter_month" id="filter_month" onchange="filter_month()" value="<?= $bulan ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                    <div class="table-responsive">
                                        <table id="example5" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Shift</th>
                                                    <th>Operator</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_potong_battery">
                                                <?php
                                                $number = 0;
                                                ?>
                                                <?php foreach ($potong_battery as $pb) : ?>
                                                    <tr>
                                                        <td><?= $pb['tanggal_produksi'] ?></td>
                                                        <td><?= $pb['shift'] ?></td>
                                                        <td><?= $pb['operator'] ?></td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="<?= base_url() ?>potong_battery/detail_potong_battery/<?= trim($pb['id_lhp_potong_battery']) ?>" class="btn btn-sm btn-primary">Detail</a>
                                                                &nbsp
                                                                <a href="<?php base_url() ?>potong_battery/detail_potong_battery/delete/<?= $pb['id_lhp_potong_battery'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')">Hapus</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Shift</th>
                                                    <th>Operator</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

<div class="modal fade modal_tambah_potong_battery" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Tambah Potong Battery</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>potong_battery/save_data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift" class="form-label">Shift</label>
                                <select class="form-select" id="shift" name="shift" required>
                                    <option selected value="" disabled>-- Pilih Shift --</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="operator" class="form-label">Operator</label>
                                <select class="form-select" id="operator" name="operator" required>
                                    <option selected value="" disabled>-- Operator --</option>
                                    <?php foreach ($data_operator as $dt_op) {
                                        if ($dt_op['status'] != 'Non Aktif') { ?>
                                            <option value="<?= $dt_op['nama'] ?>"><?= $dt_op['nama'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="float: right;">
                    <input type="submit" class="btn btn-primary float-end" value="Tambah">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade modal_download_potong_battery" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Download Potong Battery</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <a href="/cos/download" class="btn btn-danger mb-2">Download</a> -->
            <form action="<?= base_url() ?>potong_battery/download" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-2">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col mb-2">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="float: right;">
                    <input type="submit" class="btn btn-primary float-end" value="Download">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="<?= base_url() ?>assets/dataTables.dateTime.min.js"></script>
<script src="<?= base_url() ?>assets/moment.min.js"></script>
<script>
    var minDate, maxDate;

    // Custom filtering function which will search data in column four between two values
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date(data[0]);

            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        }
    );

    $(document).ready(function() {
        // Create date inputs
        minDate = new DateTime($('#min'), {
            format: 'L'
        });
        maxDate = new DateTime($('#max'), {
            format: 'L'
        });

        // DataTables initialisation
        var table = $('#example5').DataTable({
            "order": [],
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        var column = this;
                        var select = $('<select class="form-select"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                    });
            },
        });

        // Refilter the table
        $('#min, #max').on('change', function() {
            table.draw();
        });
    });

    function filter_month() {
        var bulan = $('#filter_month').val();

        window.location.replace('<?= base_url() ?>potong_battery/month/' + bulan);
    }
</script>
<?= $this->endSection(); ?>