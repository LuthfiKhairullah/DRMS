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
                            <h4>Detail COS</h4>
                        </div>
                        <div class="box-body">
                            <form action="<?= base_url() ?>cos/detail_cos/edit" method="post">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="<?= $data_lhp_cos[0]['id_lhp_cos']; ?>">
                                    <div class="col">
                                        <label for="date" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?= $data_lhp_cos[0]['tanggal_produksi']; ?>">
                                    </div>
                                    <div class="col">
                                        <label for="line" class="form-label">Line</label>
                                        <select class="form-select" id="line" name="line">
                                            <option value="" disabled>-- Pilih Line --</option>
                                            <?php foreach ($data_line as $ln) { ?>
                                                <option value="<?= $ln['id_line'] ?>" <?= $ln['id_line'] == $data_lhp_cos[0]['line'] ? 'selected' : '' ?>><?= $ln['nama_line'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="shift" class="form-label">Shift</label>
                                        <select class="form-select" id="shift" name="shift">
                                            <option value="" disabled>-- Pilih Shift --</option>
                                            <?php for ($j = 1; $j <= 3; $j++) {
                                                if ($data_lhp_cos[0]['shift'] === $j) { ?>
                                                    <option selected value="<?= $j ?>"><?= $j ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $j ?>"><?= $j ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="team" class="form-label">Team</label>
                                        <select class="form-select" id="team" name="team">
                                            <option value="" disabled>-- Pilih Team --</option>
                                            <?php $selected = false;
                                            foreach ($data_team as $dt) {
                                                if ($dt['status'] != 'Non Aktif') {
                                                    if ($data_lhp_cos[0]['team'] === $dt['team']) {
                                                        $selected = true; ?>
                                                        <option selected value="<?= $data_lhp_cos[0]['team'] ?>"><?= $data_lhp_cos[0]['team'] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $dt['team'] ?>"><?= $dt['team'] ?></option>
                                                <?php }
                                                }
                                            }
                                            if ($selected == false) { ?>
                                                <option selected value="<?= $data_lhp_cos[0]['team'] ?>"><?= $data_lhp_cos[0]['team'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <h2>COS</h2>
                                <div class="d-flex justify-content-between mt-4">
                                    <div>
                                        <button type="button" class="btn btn-primary" id="add_form" onclick="add_cos()">Add</button>
                                        &nbsp;
                                        <button type="button" class="btn btn-danger" id="delete_form" onclick="delete_cos()">Delete</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>No WO</th>
                                                <th>Type Battery</th>
                                                <th>Hasil</th>
                                                <th>Tersangkut</th>
                                                <th>Terbakar</th>
                                                <th>Lug Lepas</th>
                                                <th>Strap Tipis</th>
                                                <th>Dross 1</th>
                                                <th>Dross 2</th>
                                                <th>Dross 3</th>
                                                <th>Timbangan Strap 1</th>
                                                <th>Timbangan Strap 2</th>
                                                <th>Timbangan Strap 3</th>
                                                <th>Timbangan Strap 4</th>
                                            </tr>
                                        </thead>
                                        <tbody class="form_cos"></tbody>
                                    </table>
                                </div>
                                <div class="text-center my-2">
                                    <button type="submit" class="btn btn-primary" id="submit-form" style="width: 200px">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal" id="loading-modal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color:rgba(0, 0, 0, 0.01);">
            <div class="modal-body text-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-2 text-light">Loading...</h5>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    function data_cos() {
        let baris = 0;
        <?php foreach ($data_detail_lhp_cos as $ddc) { ?>
            baris = document.querySelectorAll('.form').length;
            $('.form_cos').append(`
			<tr class="form" id="form_${baris}">
                <input type="hidden" name="id_detail_cos[]" value="<?= $ddc['id_detail_lhp_cos']; ?>">
                <td>
                    <select class="form-select select2" id="no_wo_${baris}" onchange="getPartNo(${baris})" name="no_wo[]" style="width: 125px">
                        <option value="" selected>-- Pilih No WO --</option>
                        <?php $cek_wo = true;
                        foreach ($data_wo as $dw) {
                        ?>
                            <?php if (trim($ddc['no_wo']) === trim($dw['PDNO'])) : ?>
                                <option value="<?= trim($ddc['no_wo']) ?>" selected><?= trim($ddc['no_wo']) ?></option>
                            <?php else : ?>
                                <option value="<?= trim($dw['PDNO']) ?>"><?= trim($dw['PDNO']) ?></option>
                            <?php endif ?>
                        <?php
                        }
                        ?>
                        <option value="<?= trim($ddc['no_wo']) ?>" selected><?= trim($ddc['no_wo']) ?></option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="type_battery[]" id="type_battery_${baris}" value="<?= $ddc['type_battery'] ?>" style="width: 225px" readonly>
                </td>
                <td>
                    <input type="text" class="form-control" name="hasil[]" id="hasil_${baris}" value="<?= $ddc['hasil'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="tersangkut[]" id="tersangkut_${baris}" value="<?= $ddc['tersangkut'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="terbakar[]" id="terbakar_${baris}" value="<?= $ddc['terbakar'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="lug_lepas[]" id="lug_lepas_${baris}" value="<?= $ddc['lug_lepas'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="strap_tipis[]" id="strap_tipis_${baris}" value="<?= $ddc['strap_tipis'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_1[]" id="dross_1_${baris}" value="<?= $ddc['dross_1'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_2[]" id="dross_2_${baris}" value="<?= $ddc['dross_2'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_3[]" id="dross_3_${baris}" value="<?= $ddc['dross_3'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_1[]" id="timbangan_strap_1_${baris}" value="<?= $ddc['timbangan_strap_1'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_2[]" id="timbangan_strap_2_${baris}" value="<?= $ddc['timbangan_strap_2'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_3[]" id="timbangan_strap_3_${baris}" value="<?= $ddc['timbangan_strap_3'] ?>" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_4[]" id="timbangan_strap_4_${baris}" value="<?= $ddc['timbangan_strap_4'] ?>" style="width:100px;">
                </td>
            </tr>
		`);
        <?php } ?>
        $('.select2').select2();
    }
    data_cos();

    function add_cos() {
        const baris = document.querySelectorAll('.form').length;
        $('.form_cos').append(`
            <tr class="form" id="form_${baris}">
                <input type="hidden" name="id_detail_cos[]" value="">
                <td>
                    <select class="form-select select2" id="no_wo_${baris}" onchange="getPartNo(${baris})" name="no_wo[]" style="width: 125px">
                        <option value="" selected>-- Pilih No WO --</option>
                        <?php $cek_wo = true;
                        foreach ($data_wo as $dw) { ?>
                            <option value="<?= trim($dw['PDNO']) ?>"><?= trim($dw['PDNO']) ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="type_battery[]" id="type_battery_${baris}" value="" style="width: 225px" readonly>
                </td>
                <td>
                    <input type="text" class="form-control" name="hasil[]" id="hasil_${baris}" value"" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="tersangkut[]" id="tersangkut_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="terbakar[]" id="terbakar_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="lug_lepas[]" id="lug_lepas_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="strap_tipis[]" id="strap_tipis_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_1[]" id="dross_1_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_2[]" id="dross_2_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="dross_3[]" id="dross_3_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_1[]" id="timbangan_strap_1_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_2[]" id="timbangan_strap_2_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_3[]" id="timbangan_strap_3_${baris}" value="" style="width:100px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="timbangan_strap_4[]" id="timbangan_strap_4_${baris}" value="" style="width:100px;">
                </td>
            </tr>
      `);
        $('.select2').select2();
    }

    function delete_cos() {
        const baris = document.querySelectorAll('.form');
        const element = document.getElementById('form_' + (baris.length - 1));
        element.parentNode.removeChild(element);
    }

    function getPartNo(i) {
        let no_wo = $('#no_wo_' + i).val();
        if (no_wo != '') {
            $('#loading-modal').modal('show');
            $.ajax({
                url: '<?= base_url() ?>cos/getPartNo',
                type: 'POST',
                data: {
                    no_wo: no_wo
                },
                dataType: 'json',
                success: function(data) {
                    $('#type_battery_' + i).val(data[0].ITEM.trim());
                    $('#loading-modal').modal('hide');
                }
            });
        } else {
            $('#type_battery_' + i).val('');
        }
    }
</script>
<?= $this->endSection(); ?>