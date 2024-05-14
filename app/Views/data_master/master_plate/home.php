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
                  <h4 class="box-title">Data Master Plate</h4>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".modal_update_plate" onclick="edit_data_master_plate('', '')">
                    Tambah
                  </button>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="data_plate" class="table table-bordered table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>Plate</th>
                          <th>Berat</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $index = 1;
                          foreach ($data_master_plate as $dp) { ?>
                          <tr>
                            <td id="plate_<?= $index ?>"><?= $dp['plate'] ?></td>
                            <td id="berat_<?= $index ?>"><?= $dp['berat'] ?></td>
                            <td>
                              <div class="d-flex">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=".modal_update_plate" onclick="edit_data_master_plate('<?= $index ?>', '<?= $dp['id'] ?>')">Edit</button>
                                <form action="<?= base_url() ?>master_plate/delete" method="POST">
                                  <input type="hidden" name="id_plate" id="id_plate_<?= $index ?>" value="<?= $dp['id'] ?>">
                                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                                </form>
                              </div>
                            </td>
                          </tr>
                        <?php $index++;
                          } 
                        ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Plate</th>
                          <th>Berat</th>
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
<div class="modal fade modal_update_plate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Tambah Plate</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal_body_update_plate">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary float-end" id="btn_add_plate" onclick="update_data_master_plate()">Tambah</button>
      </div>
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
    $('#data_plate').DataTable({
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

  function add_plate(index) {
    let plateElement = document.querySelector('.plate');
    plateElement.innerHTML = `
      <input type="text" class="form-control" id="plate" name="plate">
      <button type="button" class="btn btn-danger p-1 mt-1" onclick="batal_plate(${index})">Cancel</button>
    `
  }
  
  function batal_plate(index) {
    let plateElement = document.querySelector('.plate');
    let data_plate = generate_data_plate(index);
    plateElement.innerHTML = `
      <select name="plate[]" id="plate" class="form-select">
        <option value="">Pilih plate</option>
        ${data_plate}
      </select>
      <button type="button" class="btn btn-primary p-1 mt-1" onclick="add_plate(${index})">Tambah</button>
    `;
  }

  function edit_data_master_plate(index, id_plate) {
    let data_plate = generate_data_plate(index);
    let plate = document.querySelector('#plate_' + index);
    let berat = document.querySelector('#berat_' + index);
    document.querySelector('#modal_body_update_plate').innerHTML = `
      <input type="hidden" name="id_plate_modal" id="id_plate_modal" value="${id_plate}">
      <div class="row">
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Plate</label>
            <div class="plate">
              <input type="text" id="plate" name="plate" class="form-control" list="suggestions_plate" value="${index != '' ? plate.textContent : ''}" oninput="checkPlate()">
              <input type="hidden" id="old_plate" name="old_plate" class="form-control" value="${index != '' ? plate.textContent : ''}">
              <datalist id="suggestions_plate">
                ${data_plate}
              </datalist>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Berat</label>
            <input type="number" class="form-control" id="berat" name="berat" value="${index != '' ? berat.textContent : ''}">
          </div>
        </div>
      </div>
    `;
  }

  function generate_data_plate(index) {
    let data_plate = <?= json_encode($data_plate) ?>;
    // let current_plate = '';
    // if(index != '')
    //   current_plate = document.querySelector('#plate_' + index).textContent;
    let data = '';
    data_plate.forEach((djp) => {
      data += `<option value="${djp['plate']}">${djp['plate']}</option>`
      // data += `<option value="${djp['plate']}" ${current_plate == djp['plate'] ? 'selected' : ''}>${djp['plate']}</option>`
    })
    return data;
  }

  function update_data_master_plate() {
    let id_plate = document.querySelector('#id_plate_modal').value;
    let plate = document.querySelector('#plate').value;
    let berat = document.querySelector('#berat').value;
    if(plate == '' || berat == 0) {
      alert('Data Belum Lengkap, Lengkapi Data Terlebih Dahulu');
    } else {
      $('#loading-modal').modal('show');
      $.ajax({
        url: '<?= base_url() ?>master_plate/edit',
        type: 'POST',
        data: {
          id_plate: id_plate,
          plate: plate,
          berat: berat,
        },
        dataType: 'json',
        success: function (data) {
          if(data != 'Success') {
            alert('Tambah Data Gagal, Data Sudah Tersedia');
            $('#loading-modal').modal('hide');
            $('.modal_update_plate').modal('hide');
          } else {
            window.location.reload();
            $('.modal_update_plate').modal('hide');
          }
        }
      })
    }
  }

  function checkPlate() {
    var input = document.getElementById('plate').value.toLowerCase();
    var oldInput = document.getElementById('old_plate').value.toLowerCase();
    var datalist = document.getElementById('suggestions_plate');
    var options = datalist.getElementsByTagName('option');
    let exist = false;
    let btn_add_plate = document.querySelector('#btn_add_plate');
    for (var i = 0; i < options.length; i++) {
      if(oldInput !== input) {
        if (input === options[i].value.toLowerCase()) {
          exist = true;
          // btn_add_plate.classList.add('btn-outline');
          btn_add_plate.setAttribute('onclick', "alert('Data Plate Sudah Tersedia')");
          return;
        }
      }
    }
    if(exist == false) {
      // btn_add_plate.classList.remove('btn-outline');
      btn_add_plate.setAttribute('onclick', 'update_data_master_plate()');
    }
  }
</script>
<?= $this->endSection(); ?>