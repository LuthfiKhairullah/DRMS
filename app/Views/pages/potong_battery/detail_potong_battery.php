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
                            <h4>Detail Potong Battery</h4>
                        </div>
                        <div class="box-body">
                            <form action="<?= base_url() ?>potong_battery/detail_potong_battery/update" method="post">
                                <div class="row">
                                    <input type="hidden" name="id_potong_battery" value="<?= $data_potong_battery[0]['id_lhp_potong_battery']; ?>">
                                    <div class="col">
                                        <label for="date" class="form-label">Tanggal Produksi</label>
                                        <input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" value="<?= $data_potong_battery[0]['tanggal_produksi'] ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="shift" class="form-label">Shift</label>
                                        <select class="form-select" id="shift" name="shift" required>
                                            <option value="" disabled>-- Pilih Shift --</option>
                                            <option value="1" <?= $data_potong_battery[0]['shift'] == 1 ? 'selected' : '' ?>>1</option>
                                            <option value="2" <?= $data_potong_battery[0]['shift'] == 2 ? 'selected' : '' ?>>2</option>
                                            <option value="3" <?= $data_potong_battery[0]['shift'] == 3 ? 'selected' : '' ?>>3</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="operator" class="form-label">Operator</label>
                                            <select class="form-select" id="operator" name="operator" required>
                                                <option selected value="" disabled>-- Operator --</option>
                                                <?php $selected = false;
                                                foreach ($data_operator as $dt_op) {
                                                    if ($data_potong_battery[0]['operator'] == $dt_op['nama']) $selected = true;
                                                    if ($dt_op['status'] != 'Non Aktif') { ?>
                                                        <option value="<?= $dt_op['nama'] ?>" <?= $data_potong_battery[0]['operator'] == $dt_op['nama'] ? 'selected' : '' ?>><?= $dt_op['nama'] ?></option>
                                                    <?php }
                                                }
                                                if ($selected == false) { ?>
                                                    <option value="<?= $data_potong_battery[0]['operator'] ?>" selected><?= $data_potong_battery[0]['operator'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h1>Plate NG</h1>
                                <button type="button" class="btn btn-primary btn-sm" onclick="add_baris_plate()"><i class="fa fa-plus"></i> Tambah</button>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped mb-0">
                                        <thead class="header_plate_ng">
                                            <tr>
                                                <!-- <th>Jenis</th> -->
                                                <th>Type</th>
                                                <th>Bolong (Pnl)</th>
                                                <th>Lug Pendek (Pnl)</th>
                                                <th>Patah Frame (Pnl)</th>
                                                <th>Rontok (Pnl)</th>
                                                <th>Other (Pnl)</th>
                                                <th>Total (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="form_plate_ng">
                                            <?php
                                            if (!empty($data_plate_ng)) {
                                                foreach ($data_plate_ng as $dpn) { ?>
                                                    <tr>
                                                        <td><select class="form-control select2" name="type[]" style="width:200px;" required>
                                                                <option value="" disabled selected>-- Pilih Type --</option><?php foreach ($data_plate as $dtb) : ?><option value="<?= $dtb['plate'] ?>" <?= $dpn['type'] == $dtb['plate'] ? 'selected' : '' ?>><?= $dtb['plate'] ?></option><?php endforeach ?>
                                                            </select></td>
                                                        <td><input type="number" class="form-control" name="bolong[]" value="<?= $dpn['bolong'] ?>"></td>
                                                        <td><input type="number" class="form-control" name="lug_pendek[]" value="<?= $dpn['lug_pendek'] ?>"></td>
                                                        <td><input type="number" class="form-control" name="patah_frame[]" value="<?= $dpn['patah_frame'] ?>"></td>
                                                        <td><input type="number" class="form-control" name="rontok[]" value="<?= $dpn['rontok'] ?>"></td>
                                                        <td><input type="number" class="form-control" name="other[]" value="<?= $dpn['other'] ?>"></td>
                                                        <td><input type="number" class="form-control" name="total[]" value="<?= $dpn['total'] ?>"><input type="hidden" name="id_detail_lhp_potong_battery_plate[]" value="<?= $dpn['id_detail_lhp_potong_battery_plate'] ?>"></td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center my-2">
                                    <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                                </div>
                                <br>
                                <br>
                                <h1>Element Repair</h1>
                                <button type="button" class="btn btn-primary btn-sm" onclick="add_baris_element()"><i class="fa fa-plus"></i> Tambah</button>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped mb-0">
                                        <thead class="header_element_repair">
                                            <tr>
                                                <th>Type Element Positif</th>
                                                <th>Pasangan Positif</th>
                                                <th>Type Element Negatif</th>
                                                <th>Pasangan Negatif</th>
                                                <th>Total (Sel)</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="form_element_repair">
                                            <?php
                                            if (!empty($data_element)) {
                                                foreach ($data_element as $de) { ?>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control select2" name="type_element_positif[]" style="width:200px;" required>
                                                                <option value="" disabled selected>-- Pilih Type --</option>
                                                                <?php foreach ($data_plate_positif as $dtp) : ?>
                                                                    <option value="<?= $dtp['plate'] ?>" <?= $dtp['plate'] == $de['type_positif'] ? 'selected' : '' ?>><?= $dtp['plate'] ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control" name="pasangan_positif[]" value="<?= $de['pasangan_positif'] ?>"></td>
                                                        <td>
                                                            <select class="form-control select2" name="type_element_negatif[]" style="width:200px;" required>
                                                                <option value="">-- Pilih Type--</option>
                                                                <?php foreach ($data_plate_negatif as $dtn) : ?>
                                                                    <option value="<?= $dtn['plate'] ?>" <?= $dtn['plate'] == $de['type_negatif'] ? 'selected' : '' ?>><?= $dtn['plate'] ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control" name="pasangan_negatif[]" value="<?= $de['pasangan_negatif'] ?>"></td>
                                                        <td>
                                                            <input type="number" class="form-control" name="total_element[]" value="<?= $de['total'] ?>">
                                                            <input type="hidden" name="id_detail_lhp_potong_battery_element[]" value="<?= $de['id_detail_lhp_potong_battery_element'] ?>">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="keterangan[]" value="<?= $de['keterangan'] ?>"></td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
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
    function add_baris_plate() {
        var html = '';
        html += '<tr>';
        // html += '<td><select class="form-control select2" name="jenis[]" style="width:200px;" required><option value="" selected disabled>-- Pilih Jenis --</option><option value="Unform">Unform</option><option value="Form">Form</option></td>';
        html += '<td><select class="form-control select2" name="type[]" style="width:200px;" required><option value="" disabled selected>-- Pilih Type --</option><?php foreach ($data_plate as $dtb) : ?><option value="<?= $dtb['plate'] ?>"><?= $dtb['plate'] ?></option><?php endforeach ?></select></td>';
        html += '<td><input type="number" class="form-control" name="bolong[]"></td>';
        html += '<td><input type="number" class="form-control" name="lug_pendek[]"></td>';
        html += '<td><input type="number" class="form-control" name="patah_frame[]"></td>';
        html += '<td><input type="number" class="form-control" name="rontok[]"></td>';
        html += '<td><input type="number" class="form-control" name="other[]"></td>';
        html += '<td><input type="number" step="0.01" class="form-control" name="total[]" required><input type="hidden" name="id_detail_lhp_potong_battery_plate[]"</td>';
        html += '</tr>';
        $('.form_plate_ng').append(html);

        $('.select2').select2();
    }

    function add_baris_element() {
        var html = '';
        html += '<tr>';
        html += '<td><select class="form-control select2" name="type_element_positif[]" required><option value="">-- Pilih Type--</option><?php foreach ($data_plate_positif as $dtp) : ?><option value="<?= $dtp['plate'] ?>"><?= $dtp['plate'] ?></option><?php endforeach ?></select></td>';
        html += '<td><input type="number" class="form-control" name="pasangan_positif[]" required></td>';
        html += '<td><select class="form-control select2" name="type_element_negatif[]" required><option value="">-- Pilih Type--</option><?php foreach ($data_plate_negatif as $dtn) : ?><option value="<?= $dtn['plate'] ?>"><?= $dtn['plate'] ?></option><?php endforeach ?></select></td>';
        html += '<td><input type="number" class="form-control" name="pasangan_negatif[]" required></td>';
        html += '<td><input type="number" class="form-control" name="total_element[]" required><input type="hidden" name="id_detail_lhp_potong_battery_element[]"</td>';
        html += '<td><input type="text" class="form-control" name="keterangan[]"></td>';
        html += '</tr>';
        $('.form_element_repair').append(html);

        $('.select2').select2();
    }
</script>
<?= $this->endSection(); ?>