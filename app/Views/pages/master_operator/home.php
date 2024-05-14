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
                  <div class="d-flex align-items-center">
                    <h4 class="box-title">Data Master Operator</h4>
                    <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target=".modal_tambah_operator">Tambah</button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="data_operator" class="table table-bordered table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th class="text-center fs-5">Nama</th>
                          <th class="text-center fs-5">NPK</th>
                          <th class="text-center fs-5">Mesin</th>
                          <th class="text-center fs-5">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $index_data_operator = 0;
                        foreach ($data_operator as $d_op) { ?>
                          <tr>
                            <td class="fs-5 text-center" id="nama_<?= $index_data_operator ?>"><?= $d_op['nama'] ?></td>
                            <td class="fs-5 text-center" id="npk_<?= $index_data_operator ?>"><?= sprintf('%04d', $d_op['npk']) ?></td>
                            <td class="fs-5 text-center"><?= $d_op['mesin'] ?></td>
                            <td>
                              <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary me-2" onclick="editOperator(<?= $index_data_operator ?>)" data-bs-toggle="modal" data-bs-target=".modal_edit_operator">Edit</button>
                                &nbsp
                                <form action="<?= base_url() ?>master_operator/delete_operator" method="POST">
                                  <input type="hidden" name="npk_delete" value="<?= sprintf('%04d', $d_op['npk']) ?>">
                                  <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                                <input type="hidden" id="mesin_<?= $index_data_operator ?>" value="<?= $d_op['mesin'] ?>">
                              </div>
                            </td>
                          </tr>
                        <?php $index_data_operator++;
                        } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Nama</th>
                          <th>NPK</th>
                          <th>Mesin</th>
                          <th>Action</th>
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

<div class="modal fade modal_tambah_operator" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Tambah Operator</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url() ?>master_operator/add_operator" method="post">
        <div class="modal-body">
          <div class="row row-cols-sm-2 row-cols-1">
            <div class="col">
              <div class="form-group">
                <label class="form-label">Nama Operator</label>
                <select class="form-select select2" name="nama" id="nama" style="width: 100%" required>
                  <option value="" selected disabled>-- Pilih Operator --</option>
                  <?php foreach ($data_karyawan as $value) { ?>
                    <option value="<?= sprintf('%04d', $value['npk']) ?>"><?= sprintf('%04d', $value['npk']) . ' - ' . ucwords(strtolower($value['nama'])) ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label class="form-label">Mesin</label>
                <select class="form-select" id="mesin" name="mesin" required>
                  <option value="" selected disabled>-- Pilih Mesin --</option>
                  <?php foreach ($data_mesin as $mesin) : ?>
                    <option value="<?= $mesin ?>"><?= $mesin ?></option>
                  <?php endforeach; ?>
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

<div class="modal fade modal_edit_operator" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Edit Operator</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url() ?>master_operator/update_operator" method="post">
        <div class="modal-body">
          <div class="row row-cols-sm-2 row-cols-1">
            <div class="col">
              <div class="form-group">
                <label class="form-label">Nama Operator</label>
                <input type="text" class="form-control" id="nama_edit" name="nama_edit" readonly>
                <input type="hidden" class="form-control" id="npk_edit" name="npk_edit" readonly>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label class="form-label">Mesin</label>
                <select class="form-select" id="mesin_edit" name="mesin_edit" required>
                  <option value="" selected disabled>-- Pilih Mesin --</option>
                  <?php foreach ($data_mesin as $mesin) : ?>
                    <option value="<?= $mesin ?>"><?= $mesin ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="float: right;">
          <input type="submit" class="btn btn-primary float-end" value="Edit">
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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
  $(document).ready(function() {
    <?php if (session()->has('success')) { ?>
      window.alert('<?= session()->getFlashdata('success') ?>');
    <?php } ?>
    <?php if (session()->has('failed')) { ?>
      window.alert('<?= session()->getFlashdata('failed') ?>')
    <?php } ?>
    $('.modal_tambah_operator .select2').select2({
      dropdownParent: $('.modal_tambah_operator')
    });
    $('.modal_edit_operator .select2').select2({
      dropdownParent: $('.modal_edit_operator')
    });
  });
  $(document).ready(function() {
    $('#data_operator').DataTable({
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
  });

  function editOperator(index) {
    let nama = document.querySelector('#nama_' + index).innerText;
    let npk = document.querySelector('#npk_' + index).innerText;
    let mesin = document.querySelector('#mesin_' + index).value;

    document.querySelector('#nama_edit').value = npk + ' - ' + nama;
    document.querySelector('#npk_edit').value = npk;
    document.querySelector('#mesin_edit').value = mesin;
  }
</script>
<?= $this->endSection(); ?>