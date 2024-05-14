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
                                    <h4 class="box-title">Laporan PW</h4>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".modal_tambah_pw">
                                        Tambah PW
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
                                            <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target=".modal_download_pw">
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
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>Line</th>
                                                    <th>Shift</th>
                                                    <th>Team</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_pw">
                                                <?php
                                                $number = 0;
                                                foreach ($data_pw as $dc) :
                                                ?>
                                                    <tr>
                                                        <th><?= $number = $number + 1 ?></th>
                                                        <td><?= $dc['tanggal_produksi'] ?></td>
                                                        <td><?= $dc['line'] ?></td>
                                                        <td><?= $dc['shift'] ?></td>
                                                        <td><?= $dc['team'] ?></td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="<?= base_url() ?>pw/detail_pw/<?= $dc['id_lhp_pw'] ?>" class="btn btn-primary btn-sm">Detail</a>
                                                                &nbsp
                                                                <form action="<?php base_url() ?>pw/detail_pw/delete" method="POST">
                                                                    <input type="hidden" name="id" id="id" value="<?= $dc['id_lhp_pw'] ?>">
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>Line</th>
                                                    <th>Shift</th>
                                                    <th>Team</th>
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

<div class="modal fade modal_tambah_pw" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Tambah PW</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>pw/save" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="line" class="form-label">Line</label>
                                <select class="form-select" id="line" name="line" required>
                                    <option selected value="" disabled>-- Pilih Line --</option>
                                    <?php foreach ($data_line as $ln) { ?>
                                        <option value="<?= $ln['id_line'] ?>"><?= $ln['nama_line'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
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
                        <div class="col-3">
                            <div class="form-group">
                                <label for="team" class="form-label">Team</label>
                                <div>
                                    <select class="form-select" id="team" name="team" required>
                                        <option selected value="" disabled>-- Pilih Team --</option>
                                        <?php foreach ($data_team as $dt) {
                                            if ($dt['status'] != 'Non Aktif') { ?>
                                                <option value="<?= trim($dt['team']) ?>"><?= trim($dt['team']) ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
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

<div class="modal fade modal_download_pw" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Download PW</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>pw/download" method="post">
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
            var date = new Date(data[1]);

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
        $('.modal .select2').select2({
            dropdownParent: $('.modal')
        });

        // Refilter the table
        $('#min, #max').on('change', function() {
            table.draw();
        });
    });

    function filter_month() {
        var bulan = $('#filter_month').val();

        window.location.replace(window.location.origin + '/envelope/month/' + bulan);
    }
</script>
<?= $this->endSection(); ?>