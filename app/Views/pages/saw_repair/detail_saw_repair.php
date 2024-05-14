<?= $this->extend('template/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4>Detail Saw Repair</h4>
                        </div>
                        <div class="box-body">
                            <form action="<?= base_url() ?>saw_repair/detail_saw_repair/update" method="post">
                                <div class="row">
                                    <input type="hidden" name="id_saw_repair" value="<?= $data_saw_repair[0]['id_lhp_saw_repair']; ?>">
                                    <div class="col">
                                        <label for="date" class="form-label">Tanggal Produksi</label>
                                        <input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" value="<?= $data_saw_repair[0]['tanggal_produksi'] ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="shift" class="form-label">Shift</label>
                                        <select class="form-select" id="shift" name="shift" required>
                                            <option value="" disabled>-- Pilih Shift --</option>
                                            <option value="1" <?= $data_saw_repair[0]['shift'] == 1 ? 'selected' : '' ?>>1</option>
                                            <option value="2" <?= $data_saw_repair[0]['shift'] == 2 ? 'selected' : '' ?>>2</option>
                                            <option value="3" <?= $data_saw_repair[0]['shift'] == 3 ? 'selected' : '' ?>>3</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="operator" class="form-label">Operator</label>
                                            <select class="form-select" id="operator" name="operator" required>
                                                <option selected value="" disabled>-- Operator --</option>
                                                <?php $selected = false;
                                                foreach ($data_operator as $dt_op) {
                                                    if ($data_saw_repair[0]['operator'] == $dt_op['nama']) $selected = true;
                                                    if ($dt_op['status'] != 'Non Aktif') { ?>
                                                        <option value="<?= $dt_op['nama'] ?>" <?= $data_saw_repair[0]['operator'] == $dt_op['nama'] ? 'selected' : '' ?>><?= $dt_op['nama'] ?></option>
                                                    <?php }
                                                }
                                                if ($selected == false) { ?>
                                                    <option value="<?= $data_saw_repair[0]['operator'] ?>" selected><?= $data_saw_repair[0]['operator'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data_saw_repair[0]['keterangan'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <h1>Saw Repair</h1>
                                <button type="button" class="btn btn-primary btn-sm" onclick="add_baris()"><i class="fa fa-plus"></i> Tambah</button>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped mb-0">
                                        <thead class="header_saw_repair">
                                            <tr>
                                                <th>Type Battery Saw</th>
                                                <th>Qty Repair (Pcs)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="form_saw_repair">
                                            <?php
                                            $number = 0;
                                            if (!empty($detail_saw_repair)) {
                                                foreach ($detail_saw_repair as $dsr) { ?>
                                                    <tr>
                                                        <td>
                                                            <select name="type_battery[]" id="type_battery_<?= $number ?>" class="form-select">
                                                                <option value="" selected disabled>--Pilih Type--</option>
                                                                <?php foreach ($data_type_battery as $dtb) : ?>
                                                                    <option value="<?= $dtb['type_battery'] ?>" <?= $dsr['type_battery'] == $dtb['type_battery'] ? 'selected' : '' ?>><?= $dtb['type_battery'] ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            <input type="hidden" name="id_detail_lhp_saw_repair[]" id="id_detail_lhp_saw_repair_<?= $number ?>" value="<?= $dsr['id_detail_lhp_saw_repair'] ?>">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="qty[]" id="qty_<?= $number ?>" value="<?= $dsr['qty'] ?>" class="form-control">
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center my-2">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    function add_baris() {
        var number = $('.form_saw_repair tr').length;
        var html = '<tr>';
        html += '<td><input type="hidden" name="id_detail_lhp_saw_repair[]" id="id_detail_lhp_saw_repair_' + number + '" value=""><select name="type_battery[]" id="type_battery_' + number + '" class="form-select"><option value="" selected disabled>--Pilih Type--</option><?php foreach ($data_type_battery as $dtb) : ?><option value="<?= $dtb['type_battery'] ?>"><?= $dtb['type_battery'] ?></option><?php endforeach ?></select></td>';
        html += '<td><input type="number" name="qty[]" id="qty_' + number + '" class="form-control"></td>';
        html += '</tr>';
        $('.form_saw_repair').append(html);
    }
</script>
<?= $this->endSection(); ?>