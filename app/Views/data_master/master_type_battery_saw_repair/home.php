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
                  <h4 class="box-title">Data Master Type Battery Saw Repair</h4>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".modal_update_type_battery" onclick="edit_data_master_type_battery('', '')">
                    Tambah Data
                  </button>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="data_type_battery" class="table table-bordered table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>Type Battery</th>
                          <th>Type Positif</th>
                          <th>Type Negatif</th>
                          <th>Pasangan Positif</th>
                          <th>Pasangan Negatif</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $index = 1;
                          foreach ($data_master_type_battery as $dp) { ?>
                          <tr>
                            <td id="type_battery_<?= $index ?>"><?= $dp['type_battery'] ?></td>
                            <td id="type_positif_<?= $index ?>"><?= $dp['type_positif'] ?></td>
                            <td id="type_negatif_<?= $index ?>"><?= $dp['type_negatif'] ?></td>
                            <td id="pasangan_positif_<?= $index ?>"><?= $dp['pasangan_positif'] ?></td>
                            <td id="pasangan_negatif_<?= $index ?>"><?= $dp['pasangan_negatif'] ?></td>
                            <td>
                              <div class="d-flex">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=".modal_update_type_battery" onclick="edit_data_master_type_battery('<?= $index ?>', '<?= $dp['id'] ?>')">Edit</button>
                                <form action="<?= base_url() ?>master_type_battery_saw_repair/delete" method="POST">
                                  <input type="hidden" name="id_type_battery" id="id_type_battery_<?= $index ?>" value="<?= $dp['id'] ?>">
                                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')">Delete</button>
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
                          <th>Type Battery</th>
                          <th>Type Positif</th>
                          <th>Type Negatif</th>
                          <th>Pasangan Positif</th>
                          <th>Pasangan Negatif</th>
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
<div class="modal fade modal_update_type_battery" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Tambah Type Battery</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal_body_update_type_battery">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary float-end" id="btn_add_type_battery" onclick="update_data_master_type_battery()">Add</button>
        <button type="button" class="btn btn-secondary float-end" data-bs-dismiss="modal">Close</button>
        <!-- <input type="submit" class="btn btn-primary float-end" value="Tambah"> -->
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
    $('#data_type_battery').DataTable({
        "order": [],
        initComplete: function() {
          this.api()
            .columns()
            .every(function() {
              var column = this;
              var select = $('<select class="form-select select2"><option value=""></option></select>')
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

  function edit_data_master_type_battery(index, id_type_battery) {
    let data_type_battery = generate_data_type_battery(index);
    let data_plate_pos = generate_data_plate_pos(index);
    let data_plate_neg = generate_data_plate_neg(index);
    let type_battery = document.querySelector('#type_battery_' + index);
    let pasangan_positif = document.querySelector('#pasangan_positif_' + index);
    let pasangan_negatif = document.querySelector('#pasangan_negatif_' + index);
    document.querySelector('#modal_body_update_type_battery').innerHTML = `
      <input type="hidden" name="id_type_battery_modal" id="id_type_battery_modal" value="${id_type_battery}">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Type Battery</label>
            <div class="type_battery">
              <input type="text" id="type_battery" name="type_battery" class="form-control" list="suggestions_type_battery" value="${index != '' ? type_battery.textContent : ''}" oninput="checkTypeBattery()">
              <input type="hidden" id="old_type_battery" name="old_type_battery" class="form-control" value="${index != '' ? type_battery.textContent : ''}">
              <datalist id="suggestions_type_battery">
                ${data_type_battery}
              </datalist>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Plate Positif</label>
            <select id="type_positif" name="type_positif" class="form-select select2" style="width: 100%">
              <option value="" selected disabled>Pilih Plate Positif</option>
              ${data_plate_pos}
            </select>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Plate Negatif</label>
            <select id="type_negatif" name="type_negatif" class="form-select select2" style="width: 100%">
              <option value="" selected disabled>Pilih Plate Negatif</option>
              ${data_plate_neg}
            </select>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Pasangan Positif</label>
            <input type="number" class="form-control" id="pasangan_positif" name="pasangan_positif" value="${index != '' ? pasangan_positif.textContent : ''}">
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label">Pasangan Negatif</label>
            <input type="number" class="form-control" id="pasangan_negatif" name="pasangan_negatif" value="${index != '' ? pasangan_negatif.textContent : ''}">
          </div>
        </div>
      </div>
    `;

    $('.modal_update_type_battery .select2').select2({
      dropdownParent: $('.modal_update_type_battery')
    });
  }

  function generate_data_type_battery(index) {
    let data_type_battery = <?= json_encode($data_type_battery) ?>;
    // let current_type_battery = '';
    // if(index != '')
    //   current_type_battery = document.querySelector('#type_battery_' + index).textContent;
    let data = '';
    data_type_battery.forEach((djp) => {
      data += `<option value="${djp['type_battery']}">${djp['type_battery']}</option>`
      // data += `<option value="${djp['type_battery']}" ${current_type_battery == djp['type_battery'] ? 'selected' : ''}>${djp['type_battery']}</option>`
    })
    return data;
  }

  function generate_data_plate_pos(index) {
    let data_plate_pos = <?= json_encode($data_plate_pos) ?>;
    let current_type_positif = '';
    if(index != '')
      current_type_positif = document.querySelector('#type_positif_' + index).textContent;
    let data = '';
    data_plate_pos.forEach((djp) => {
      data += `<option value="${djp['plate']}" ${current_type_positif == djp['plate'] ? 'selected' : ''}>${djp['plate']}</option>`
    })
    return data;
  }

  function generate_data_plate_neg(index) {
    let data_plate_neg = <?= json_encode($data_plate_neg) ?>;
    let current_type_negatif = '';
    if(index != '')
      current_type_negatif = document.querySelector('#type_negatif_' + index).textContent;
    let data = '';
    data_plate_neg.forEach((djp) => {
      data += `<option value="${djp['plate']}" ${current_type_negatif == djp['plate'] ? 'selected' : ''}>${djp['plate']}</option>`
    })
    return data;
  }

  function update_data_master_type_battery() {
    let id_type_battery = document.querySelector('#id_type_battery_modal').value;
    let type_battery = document.querySelector('#type_battery').value;
    let type_positif = document.querySelector('#type_positif').value;
    let type_negatif = document.querySelector('#type_negatif').value;
    let pasangan_positif = document.querySelector('#pasangan_positif').value;
    let pasangan_negatif = document.querySelector('#pasangan_negatif').value;
    if(type_battery == '' || type_positif == '' || type_negatif == '' || pasangan_positif == 0 || pasangan_negatif == 0) {
      alert('Data Belum Lengkap, Lengkapi Data Terlebih Dahulu');
    } else {
      if(Number.isInteger(parseFloat(pasangan_positif)) == false || Number.isInteger(parseFloat(pasangan_negatif)) == false) {
        alert('Pasangan Tidak Dapat Bernilai Desimal');
      } else {
        $('#loading-modal').modal('show');
        $.ajax({
          url: '<?= base_url() ?>master_type_battery_saw_repair/edit',
          type: 'POST',
          data: {
            id_type_battery: id_type_battery,
            type_battery: type_battery,
            type_positif: type_positif,
            type_negatif: type_negatif,
            pasangan_positif: pasangan_positif,
            pasangan_negatif: pasangan_negatif,
          },
          dataType: 'json',
          success: function (data) {
            if(data != 'Success') {
              alert('Tambah Data Gagal, Data Sudah Tersedia');
              $('#loading-modal').modal('hide');
              $('.modal_update_type_battery').modal('hide');
            } else {
              window.location.reload();
              $('.modal_update_type_battery').modal('hide');
            }
          }
        })
      }
    }
  }

  function checkTypeBattery() {
    var input = document.getElementById('type_battery').value.toLowerCase();
    var oldInput = document.getElementById('old_type_battery').value.toLowerCase();
    var datalist = document.getElementById('suggestions_type_battery');
    var options = datalist.getElementsByTagName('option');
    let exist = false;
    let btn_add_type_battery = document.querySelector('#btn_add_type_battery');
    for (var i = 0; i < options.length; i++) {
      if(oldInput !== input) {
        if (input === options[i].value.toLowerCase()) {
          exist = true;
          // btn_add_type_battery.classList.add('btn-outline');
          btn_add_type_battery.setAttribute('onclick', "alert('Data Type Battery Sudah Tersedia')");
          return;
        }
      }
    }
    if(exist == false) {
      // btn_add_type_battery.classList.remove('btn-outline');
      btn_add_type_battery.setAttribute('onclick', 'update_data_master_type_battery()');
    }
  }
</script>
<?= $this->endSection(); ?>