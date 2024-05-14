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
									<h4 class="box-title">Laporan Harian Produksi</h4>
									<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".modal_tambah_lhp">
										Tambah LHP
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
											<button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target=".modal_download_lhp">
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
										<table id="data_lhp2" class="table table-bordered table-striped" style="width:100%">
											<thead>
												<tr>
													<th>Tanggal</th>
													<th>Shift</th>
													<th>Line</th>
													<th>Kasubsie</th>
													<th>Grup</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($data_lhp as $lhp) :
													if ($lhp['line'] != 8 && $lhp['line'] != 9) { ?>
														<tr>
															<td><?= $lhp['tanggal_produksi'] ?></td>
															<td><?= $lhp['shift'] ?></td>
															<td><?= ($lhp['line'] == 10) ? 'MCB' : $lhp['line'] ?></td>
															<td><?= $lhp['kasubsie'] ?></td>
															<td><?= $lhp['nama_pic'] ?></td>
															<td>
																<a href="<?= base_url() ?>lhp/detail_lhp/<?= $lhp['id_lhp_2'] ?>" class="btn btn-primary btn-sm" target="_blank">Detail</a>
																&nbsp
																<a href="<?= base_url() ?>lhp/hapus_lhp/<?= $lhp['id_lhp_2'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
															</td>
														</tr>
												<?php }
												endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<th>Tanggal</th>
													<th>Shift</th>
													<th>Line</th>
													<th>Kasubsie</th>
													<th>Grup</th>
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

<div class="modal fade modal_tambah_lhp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Tambah LHP Produksi 2</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="<?= base_url() ?>lhp/add_lhp" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Tanggal Produksi</label>
								<input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Line</label>
								<select class="form-select" id="line" name="line" required>
									<option selected disabled>-- Pilih Data --</option>
									<?php foreach ($data_line as $line) : ?>
										<option value="<?= $line['id_line'] ?>"><?= $line['nama_line'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Shift</label>
								<select class="form-select" id="shift" name="shift" required>
									<option selected disabled>-- Pilih Data --</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
								</select>
							</div>
						</div>
						<!-- <div class="col-3">
							<div class="form-group">
								<label class="form-label">Kasubsie</label>
								<select class="form-control select2" id="kasubsie" name="kasubsie" style="width: 100%;">
									<option selected disabled>-- Pilih Data --</option>
									<option value="Yusuf Slamet Pelita">Yusuf Slamet Pelita</option>
									<option value="Edi Suwito">Edi Suwito</option>
									<option value="Masruri">Masruri</option>
									<option value="Parwadi">Parwadi</option>
									<option value="Iim Arwisman">Iim Arwisman</option>
								</select>
							</div>
						</div> -->
					</div>
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Kasubsie</label>
								<select class="form-select" id="kasubsie" name="kasubsie" style="width: 100%;" required>
									<option selected disabled>-- Pilih Data --</option>
									<?php foreach ($data_kasubsie as $kasubsie) : ?>
										<option value="<?= ucwords($kasubsie['nama']) ?>"><?= ucwords($kasubsie['nama']) ?></option>
									<?php endforeach; ?>
									<!-- <option value="Yusuf Slamet Pelita">Yusuf Slamet Pelita</option>
									<option value="Edi Suwito">Edi Suwito</option>
									<option value="Masruri">Masruri</option>
									<option value="Parwadi">Parwadi</option>
									<option value="Iim Arwisman">Iim Arwisman</option>
									<option value="Supianto">Supianto</option>
									<option value="Yanto">Yanto</option> -->
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Grup</label>
								<select class="form-select" id="grup" name="grup" style="width: 100%;" required>
									<option selected disabled>-- Pilih Data --</option>
									<?php foreach ($data_grup as $grup) :
										if ($grup['status'] != 'Non Aktif') { ?>
											<option value="<?= $grup['id_pic'] ?>"><?= $grup['nama_pic'] ?></option>
									<?php }
									endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">MP + PIC</label>
								<input type="number" class="form-control" id="mp" name="mp" required>
							</div>
						</div>
						<!-- <div class="col-3">
							<div class="form-group">
								<label class="form-label">Absen</label>
								<input type="number" class="form-control" id="absen" name="absen">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label class="form-label">Cuti</label>
								<input type="number" class="form-control" id="cuti" name="cuti">
							</div>
						</div> -->
					</div>
					<!-- <div class="row">
						<div class="col-4">
							<div class="form-group">
								<label class="form-label">Status (Normal/OT)</label>
								<select class="form-select" id="status" name="status" style="width: 100%;" required>
									<option selected disabled>-- Pilih Data --</option>
									<option value="Normal">Normal</option>
									<option value="Overtime">Overtime</option>
								</select>
							</div>
						</div>
					</div> -->
				</div>
				<div class="modal-footer" style="float: right;">
					<!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button> -->
					<input type="submit" class="btn btn-primary float-end" value="Tambah">
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade modal_download_lhp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Download Assy</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<!-- <a href="/cos/download" class="btn btn-danger mb-2">Download</a> -->
			<form action="<?= base_url() ?>lhp/download" method="post">
				<div class="modal-body">
					<!-- <label for="date" class="form-label">Bulan</label>
                    <input type="month" class="form-control" id="date" name="date" value="<?= date('Y-m') ?>"> -->
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
		var table = $('#data_lhp2').DataTable({
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

		$('.modal .select2').select2({
			dropdownParent: $('.modal')
		});
	});

	function filter_month() {
		var bulan = $('#filter_month').val();

		window.location.replace('<?= base_url() ?>lhp/month/' + bulan);
	}
</script>
<?= $this->endSection(); ?>