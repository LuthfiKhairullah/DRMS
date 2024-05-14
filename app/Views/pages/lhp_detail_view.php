<?= $this->extend('template/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<?php
date_default_timezone_set('Asia/Jakarta');
$datetime_now = date('Y-m-d H:i:s');

if ($data_lhp[0]['shift'] == 1) {
	$jam_selesai = "16:45:00";
} elseif ($data_lhp[0]['shift'] == 2) {
	$jam_selesai = "00:30:00";
} elseif ($data_lhp[0]['shift'] == 3) {
	$jam_selesai = "07:45:00";
}

if (date('Y-m-d H:i:s', strtotime($data_lhp[0]['tanggal_produksi'] . ' ' . $jam_selesai)) < $datetime_now) {
	$readonly = "readonly";
	$disabled = "disabled";
} else {
	$readonly = "";
	$disabled = "";
}

if (session()->get('level') <= 4 && (session()->get('departemen') == 'quality' || session()->get('departemen') == 'produksi2')) {
	$readonly = "";
	$disabled = "";
}
?>
<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<form action="<?= base_url() ?>lhp/update_lhp" method="post">
				<input type="hidden" name="id_lhp" id="id_lhp" value="<?= $id_lhp ?>">
				<div class="box">
					<div class="box-header with-border">
						<h4>Detail Laporan Harian Produksi</h4>
					</div>
					<div class="box-body" style="padding-top:5px; padding-bottom:0px;">
						<div class="row">
							<div class="col-10">
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">Tanggal Produksi</label>
											<input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" value="<?= $data_lhp[0]['tanggal_produksi'] ?>">
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">PIC</label>
											<select class="form-control select2" id="grup" name="grup" style="width: 100%;">
												<option value="" selected>-- Pilih Data --</option>
												<?php $selected = false;
												foreach ($data_all_grup as $key => $value) {
													if ($value['id_pic'] == $data_lhp[0]['grup']) {
														$nama_pic = $value['nama_pic'];
													}
													if ($value['status'] != 'Non Aktif') { ?>
														<option value="<?= $value['id_pic'] ?>" <?php if ($value['id_pic'] == $data_lhp[0]['grup']) {
																																			$selected = true;
																																			echo "selected";
																																		} ?>><?= $value['nama_pic'] ?></option>
													<?php }
												}
												if ($selected == false) { ?>
													<option value="<?= $data_lhp[0]['grup'] ?>" selected><?= $nama_pic ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">MP + PIC</label>
											<input type="number" class="form-control" id="mp" name="mp" value="<?= $data_lhp[0]['mp'] ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">Shift</label>
											<select class="form-select" id="shift" name="shift">
												<option value="" selected>-- Pilih Data --</option>
												<option value="1" <?php if ($data_lhp[0]['shift'] == 1) {
																						echo "selected";
																					} ?>>Shift 1</option>
												<option value="2" <?php if ($data_lhp[0]['shift'] == 2) {
																						echo "selected";
																					} ?>>Shift 2</option>
												<option value="3" <?php if ($data_lhp[0]['shift'] == 3) {
																						echo "selected";
																					} ?>>Shift 3</option>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">Line</label>
											<select class="form-select" id="line" name="line" style="width: 100%;">
												<option value="" selected>-- Pilih Data --</option>
												<?php foreach ($data_all_line as $key => $value) { ?>
													<option value="<?= $value['id_line'] ?>" <?php if ($value['id_line'] == $data_lhp[0]['line']) {
																																			echo "selected";
																																		} ?>><?= $value['nama_line'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label class="form-label">Kasubsie</label>
											<select class="form-control select2" id="kasubsie" name="kasubsie" style="width: 100%;">
												<option value="" selected>-- Pilih Data --</option>
												<?php $selected = false;
												foreach ($data_all_kasubsie as $key => $value) { ?>
													<option value="<?= ucwords($value['nama']) ?>" <?php if (strtolower($value['nama']) == strtolower($data_lhp[0]['kasubsie'])) {
																																						$selected = true;
																																						echo "selected";
																																					} ?>><?= ucwords($value['nama']) ?></option>
												<?php }
												if ($selected == false) { ?>
													<option value="<?= $data_lhp[0]['kasubsie'] ?>" selected><?= $data_lhp[0]['kasubsie'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-2" style="text-align:center;display:flex; justify-content:space-evenly; flex-direction:column-reverse;"><input type="submit" class="btn btn-success" value="Save"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12 col-12">
						<div class="box">
							<div class="box-body">
								<div class="table-responsive">
									<table id="" class="table table-striped mb-0">
										<thead>
											<tr>
												<th>#</th>
												<th>Jam Start</th>
												<th>Jam End</th>
												<th>Menit Terpakai</th>
												<th>No WO</th>
												<th>Type Battery</th>
												<th>CT</th>
												<th>Plan Cap</th>
												<th>Actual</th>
												<th>Total Menit Line Stop</th>
												<th>Line Stop</th>
												<th>Reject</th>
											</tr>
										</thead>
										<tbody id="tbody">
											<?php
											if ($data_lhp[0]['shift'] == '1') {
												$jam_start = ['07.30', '08.50', '09.50', '11.00', '12.00 ', '14.00', '15.00', '16.15'];
												$jam_end = ['08.50', '09.50', '11.00', '12.00', '14.00', '15.00', '16.15', '16.30'];
												$menit_tersedia = ['80', '60', '70', '60', '120', '60', '75', '15'];
												$menit_aktual = [70, 60, 60, 60, 60, 60, 60, 10];
											} elseif ($data_lhp[0]['shift'] == '2') {
												$jam_start = ['16.30', '17.50', '19.35', '20.35', '21.35', '22.45', '23.45'];
												$jam_end = ['17.50', '19.35', '20.35', '21.35', '22.45', '23.45', '00.30'];
												$menit_tersedia = ['80', '105', '60', '60', '70', '60', '45'];
												$menit_aktual = [70, 60, 60, 60, 60, 60, 40];
											} elseif ($data_lhp[0]['shift'] == '3') {
												$jam_start = ['00.30', '01.50', '02.50', '03.50', '05.20', '06.20'];
												$jam_end = ['01.50', '02.50', '03.50', '05.20', '06.20', '07.30'];
												$menit_tersedia = ['80', '60', '60', '90', '60', '70'];
												$menit_aktual = [70, 60, 60, 60, 60, 60];
											}

											$temp_batch = '';
											for ($i = 0; $i < count($data_detail_lhp); $i++) {
												if ($i > 0) {
													$j = $i - 1;
													$temp_batch = $data_detail_lhp[$j]['batch'];
												}
											?>
												<tr>
													<?php if ($data_detail_lhp[$i]['batch'] != $temp_batch) { ?>
														<td>
															<button type="button" class="btn btn-sm btn-primary" onclick="add_rows_batch(<?= $i ?>)">
																Add
															</button>
														</td>
														<td>
															<div id="start_section_<?= $i ?>">
																<input type="time" class="form-control" name="start[]" id="start_<?= $i ?>" value="<?= date("H:i", strtotime($data_detail_lhp[$i]['jam_start'])) ?>" style="width: 100px;">
															</div>
														</td>
														<td>
															<div id="stop_section_<?= $i ?>">
																<input type="time" class="form-control" name="stop[]" id="stop_<?= $i ?>" value="<?= date("H:i", strtotime($data_detail_lhp[$i]['jam_end'])) ?>" style="width: 100px;">
															</div>
														</td>
													<?php
													} else { ?>
														<td><button type="button" class="btn btn-sm btn-danger" onclick="delete_rows(<?= $i ?>)">Remove</button></td>
														<td>
															<div id="start_section_<?= $i ?>">
																<input type="time" class="form-control" name="start[]" id="start_<?= $i ?>" value="<?= date("H:i", strtotime($data_detail_lhp[$i]['jam_start'])) ?>" style="width: 100px;">
															</div>
														</td>
														<td>
															<div id="stop_section_<?= $i ?>">
																<input type="time" class="form-control" name="stop[]" id="stop_<?= $i ?>" value="<?= date("H:i", strtotime($data_detail_lhp[$i]['jam_end'])) ?>" style="width: 100px;">
															</div>
														</td>
													<?php
													}
													?>

													<td>
														<input type="number" class="form-control menit_terpakai" name="menit_terpakai[]" id="menit_terpakai_<?= $i ?>" value="<?= $data_detail_lhp[$i]['menit_terpakai'] ?>" onkeyup="update_plan(<?= $i ?>)" style="width: 100px" readonly>
													</td>
													<td>
														<select class="form-control select2" id="no_wo_<?= $i ?>" name="no_wo[]" onchange="getPartNo(<?= $i ?>)" style="width: 200px;">
															<option value="" selected>-- Pilih No WO --</option>
															<?php $cek_wo = true;
															foreach ($data_wo as $dw) {
																if (substr($dw['PDNO'], 0, 4) == 'PKAS' || substr($dw['PDNO'], 0, 4) == 'PKAB' || substr($dw['PDNO'], 0, 4) == 'PKAV') {
																	$selected = ($dw['PDNO'] == $data_detail_lhp[$i]['no_wo']) ? 'selected' : '';
																	$cek_wo = ($dw['PDNO'] == $data_detail_lhp[$i]['no_wo']) ? false : true; ?>
																	<option value="<?= $dw['PDNO'] ?>" <?= $selected ?>><?= $dw['PDNO'] ?></option>
																<?php }
															}
															if ($cek_wo) { ?>
																<option value="<?= $data_detail_lhp[$i]['no_wo'] ?>" selected><?= $data_detail_lhp[$i]['no_wo'] ?></option>
															<?php
															}
															?>
															<option value="-">-</option>
														</select>
														<input type="hidden" name="batch[]" id="batch_<?= $i ?>" value="<?= $data_detail_lhp[$i]['batch'] ?>">
														<input type="hidden" name="id_detail_lhp[]" id="id_detail_lhp<?= $i ?>" value="<?= $data_detail_lhp[$i]['id_detail_lhp'] ?>">
													</td>
													<td>
														<input type="text" class="form-control" name="part_number[]" id="part_number_<?= $i ?>" value="<?= $data_detail_lhp[$i]['type_battery'] ?>" style="width: 250px" readonly>
													</td>
													<td>
														<input type="text" class="form-control" size="4" name="ct[]" id="ct_<?= $i ?>" value="<?= $data_detail_lhp[$i]['ct'] ?>" style="width: 100px" readonly>
													</td>
													<td>
														<input type="text" class="form-control" name="plan_cap[]" id="plan_cap_<?= $i ?>" value="<?= $data_detail_lhp[$i]['plan_cap'] ?>" style="width: 100px" readonly>
													</td>
													<td>
														<input type="text" class="form-control" name="actual[]" id="actual_<?= $i ?>" onkeyup="presentase_actual(<?= $i ?>)" value="<?= $data_detail_lhp[$i]['actual'] ?>" style="width: 100px">
													</td>
													<td>
														<input type="text" class="form-control" name="total_menit_breakdown[]" id="total_menit_breakdown_<?= $i ?>" value="<?= $data_detail_lhp[$i]['total_menit_breakdown'] ?>" style="width: 100px" readonly>
													</td>
													<td>
														<button type="button" class="btn btn-sm btn-primary" id="add_breakdown_<?= $i ?>" onclick="add_breakdown(<?= $i ?>)">Add</button>
													</td>
													<td>
														<button type="button" class="btn btn-sm btn-primary" id="add_reject_<?= $i ?>" onclick="add_reject(<?= $i ?>)">Add</button>
													</td>
												</tr>
												<?php
											}

											if (count($data_detail_lhp) <= count($jam_start)) {
												for ($i = count($data_detail_lhp); $i < count($jam_start); $i++) {
													$j = $i; ?>
													<tr>
														<td>
															<button type="button" class="btn btn-sm btn-primary" onclick="add_rows_batch(<?= $i ?>)">
																Add
															</button>
														</td>
														<!-- <td>
																<span style="display:block; width: 100px;"><?= $jam_start[$j] ?> - <?= $jam_end[$j] ?></span>
															</td>
															<td><?= $menit_tersedia[$j] ?></td>
															<td><?= $menit_aktual[$j] ?></td> -->
														<td>
															<div id="start_section_<?= $i ?>">
																<input type="time" class="form-control" name="start[]" id="start_<?= $i ?>" value="<?= date('H:i', strtotime(str_replace('.', ':', $jam_start[$j]))) ?>" style="width: 100px;">
															</div>
														</td>
														<td>
															<div id="stop_section_<?= $i ?>">
																<input type="time" class="form-control" name="stop[]" id="stop_<?= $i ?>" value="<?= date('H:i', strtotime(str_replace('.', ':', $jam_end[$j]))) ?>" style="width: 100px;">
															</div>
														</td>
														<td>
															<input type="number" class="form-control menit_terpakai" name="menit_terpakai[]" id="menit_terpakai_<?= $i ?>" value="<?= $menit_aktual[$j] ?>" onkeyup="update_plan(<?= $i ?>)" style="width: 100px" readonly>
														</td>
														<td>
															<select class="form-control select2" id="no_wo_<?= $i ?>" name="no_wo[]" onchange="getPartNo(<?= $i ?>)" style="width: 200px;">
																<option value="" selected>-- Pilih No WO --</option>
																<?php foreach ($data_wo as $dw) {
																	if (substr($dw['PDNO'], 0, 4) == 'PKAS' || substr($dw['PDNO'], 0, 4) == 'PKAB' || substr($dw['PDNO'], 0, 4) == 'PKAV') { ?>
																		<option value="<?= $dw['PDNO'] ?>"><?= $dw['PDNO'] ?></option>
																<?php }
																} ?>
																<option value="-">-</option>
															</select>
															<input type="hidden" name="batch[]" id="batch_<?= $i ?>" value="<?= $j + 1 ?>">
															<input type="hidden" name="id_detail_lhp[]" id="id_detail_lhp<?= $i ?>" value="">
														</td>
														<td>
															<input type="text" class="form-control" name="part_number[]" id="part_number_<?= $i ?>" style="width: 250px" readonly>
														</td>
														<td>
															<input type="text" class="form-control" size="4" name="ct[]" id="ct_<?= $i ?>" style="width: 100px" readonly>
														</td>
														<td>
															<input type="text" class="form-control" name="plan_cap[]" id="plan_cap_<?= $i ?>" style="width: 100px" readonly>
														</td>
														<td>
															<input type="text" class="form-control" name="actual[]" id="actual_<?= $i ?>" onkeyup="presentase_actual(<?= $i ?>)" style="width: 100px">
														</td>
														<td>
															<input type="text" class="form-control" name="total_menit_breakdown[]" id="total_menit_breakdown_<?= $i ?>" style="width: 100px" readonly>
														</td>
														<td>
															<button type="button" class="btn btn-sm btn-primary" id="add_breakdown_<?= $i ?>" onclick="add_breakdown(<?= $i ?>)">Add</button>
														</td>
														<td>
															<button type="button" class="btn btn-sm btn-primary" id="add_reject_<?= $i ?>" onclick="add_reject(<?= $i ?>)">Add</button>
														</td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="6"></td>
												<td>
													<h3>Total</h3>
												</td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_jks" id="" value="<?= $data_lhp[0]['total_plan'] ?>" style="width: 100px" readonly></td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_actual" id="" value="<?= $data_lhp[0]['total_aktual'] ?>" style="width: 100px" readonly></td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_presentase" id="" value="<?= $data_lhp[0]['total_line_stop'] ?>" style="width: 100px" readonly></td>
												<td colspan="2"></td>
											</tr>
										</tfoot>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>

				<!-- Detail Line Stop -->
				<div class="row" id="ls_section">
					<div class="col-xl-12 col-12">
						<div class="box">
							<div class="box-header with-border">
								<h4>Detail Line Stop</h4>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<table id="" class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Jam Start</th>
												<th>Jam Stop</th>
												<th>No WO</th>
												<th>Type Battery</th>
												<th>Line Stop Jenis</th>
												<th>Line Stop Proses</th>
												<th>Line Stop Uraian</th>
												<th>Line Stop Minute</th>
												<th>Line Stop Action</th>
											</tr>
										</thead>
										<tbody id="tbody_line_stop">
											<?php
											$model = new App\Models\M_Data();
											$index_breakdown = 0;
											foreach ($data_detail_breakdown as $d_detail_breakdown) { ?>
												<tr>
													<td>
														<input type="time" class="form-control" name="start_breakdown[]" id="start_breakdown_<?= $index_breakdown ?>" value="<?= date("H:i", strtotime($d_detail_breakdown['jam_start'])) ?>" style="width: 100px;">
													</td>
													<td>
														<input type="time" class="form-control" name="stop_breakdown[]" id="stop_breakdown_<?= $index_breakdown ?>" value="<?= date("H:i", strtotime($d_detail_breakdown['jam_end'])) ?>" style="width: 100px;">
													</td>
													<td>
														<input type="text" class="form-control" name="no_wo_breakdown[]" id="no_wo_breakdown_<?= $index_breakdown ?>" value="<?= $d_detail_breakdown['no_wo'] ?>" style="width: 125px">
														<input type="hidden" class="form-control" name="id_breakdown[]" id="id_breakdown_<?= $index_breakdown ?>" value="<?= $d_detail_breakdown['id_breakdown'] ?>" style="width: 250px">
													</td>
													<td>
														<input type="text" class="form-control" name="part_number_breakdown[]" id="part_number_breakdown_<?= $index_breakdown ?>" value="<?= $d_detail_breakdown['type_battery'] ?>" style="width: 225px">
													</td>
													<td>
														<select class="form-control select2" name="jenis_breakdown[]" id="jenis_breakdown_<?= $index_breakdown ?>" onchange="get_proses_breakdown(<?= $index_breakdown ?>)" style="width: 200px">
															<option value="">Pilih Jenis Line Stop</option>
															<?php
															foreach ($data_breakdown as $d_jenis_breakdown) { ?>
																<option value="<?= $d_jenis_breakdown['jenis_breakdown'] ?>" <?php if ($d_jenis_breakdown['jenis_breakdown'] == $d_detail_breakdown['jenis_breakdown']) {
																																																echo "selected";
																																															} ?>><?= $d_jenis_breakdown['jenis_breakdown'] ?></option>
															<?php
															}
															?>
														</select>
													</td>
													<td>
														<select class="form-control select2" id="proses_breakdown_<?= $index_breakdown ?>" name="proses_breakdown[]" style="width: 250px;">
															<option value="" selected>-- Pilih Proses Line Stop --</option>
															<?php
															$data_proses_breakdown = $model->getProsesBreakdown($d_detail_breakdown['jenis_breakdown']);
															$andon_proses = true;
															foreach ($data_proses_breakdown as $d_proses_breakdown) {
																$selected = ($d_proses_breakdown['proses_breakdown'] == $d_detail_breakdown['proses_breakdown']) ? 'selected' : '';
																if ($selected != '') {
																	$andon_proses = false;
																}
															?>
																<option value="<?= $d_proses_breakdown['proses_breakdown'] ?>" <?= $selected ?>><?= $d_proses_breakdown['proses_breakdown'] ?></option>
															<?php
															}

															if ($andon_proses) { ?>
																<option value="<?= $d_detail_breakdown['proses_breakdown'] ?>" selected><?= $d_detail_breakdown['proses_breakdown'] ?></option>
															<?php
															}
															?>
														</select>
													</td>
													<td>
														<textarea class="form-control" name="uraian_breakdown[]" id="uraian_breakdown_<?= $index_breakdown ?>" cols="20" rows="1" style="width: 200px;"><?= $d_detail_breakdown['uraian_breakdown'] ?></textarea>
													</td>
													<td>
														<input type="text" class="form-control" name="menit_breakdown[]" id="menit_breakdown_<?= $index_breakdown ?>" value="<?= $d_detail_breakdown['menit_breakdown'] ?>" style="width: 100px" required>
													</td>
													<td>
														<a type="button" class="btn btn-danger" href="<?= base_url() ?>lhp/delete_line_stop/<?= $d_detail_breakdown['id_breakdown'] ?>/<?= $id_lhp ?>" onclick="return confirm('Apakah anda yakin?')"><i class="fa fa-trash"></i></a>
													</td>
												</tr>

											<?php
												$index_breakdown++;
											}
											?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="6"></td>
												<td>
													<h3>Total</h3>
												</td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_menit_breakdown_aktual" id="total_menit_breakdown_aktual" value="<?= $total_menit_breakdown[0]['total_menit'] ?>" style="width: 100px" readonly></td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="box-footer" style="text-align: center;">
							</div>
						</div>
					</div>
				</div>

				<!-- Detail Reject -->
				<div class="row" id="reject_section">
					<div class="col-xl-12 col-12">
						<div class="box">
							<div class="box-header with-border">
								<h4>Detail Reject</h4>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<table id="" class="table table-striped mb-0">
										<thead>
											<tr>
												<th>No WO</th>
												<th>Type Battery</th>
												<th>Reject QTY</th>
												<th>Reject Jenis</th>
												<th>Reject Kategori</th>
												<th>Analisa Problem</th>
												<th>Reject Action</th>
											</tr>
										</thead>
										<tbody id="tbody_reject">
											<?php
											$index_reject = 0;
											foreach ($data_detail_reject as $d_detail_reject) { ?>
												<tr>
													<td>
														<input type="text" class="form-control" name="no_wo_reject[]" id="no_wo_reject_<?= $index_reject ?>" value="<?= $d_detail_reject['no_wo'] ?>" style="width: 200px">
														<input type="hidden" name="id_reject[]" id="id_reject_<?= $index_reject ?>" value="<?= $d_detail_reject['id_reject'] ?>">
													</td>
													<td>
														<input type="text" class="form-control" name="part_number_reject[]" id="part_number_reject_<?= $index_reject ?>" value="<?= $d_detail_reject['type_battery'] ?>" style="width: 250px">
													</td>
													<td>
														<input type="text" class="form-control" name="qty_reject[]" id="qty_reject_<?= $index_reject ?>" value="<?= $d_detail_reject['qty_reject'] ?>" style="width: 100px" <?= $readonly ?> required>
													</td>
													<td>
														<select class="form-control select2" name="jenis_reject[]" id="jenis_reject_<?= $index_reject ?>" onchange="get_kategori_reject(<?= $index_reject ?>)" style="width: 200px" required>
															<option value="">Pilih Jenis Reject</option>
															<?php
															foreach ($data_reject as $d_jenis_reject) { ?>
																<option value="<?= $d_jenis_reject['jenis_reject'] ?>" <?php if ($d_jenis_reject['jenis_reject'] == $d_detail_reject['jenis_reject']) {
																																													echo "selected";
																																												} ?>><?= $d_jenis_reject['jenis_reject'] ?></option>
															<?php
															}
															?>
														</select>
													</td>
													<td>
														<select class="form-control select2" id="kategori_reject_<?= $index_reject ?>" name="kategori_reject[]" style="width: 250px;" required>
															<option value="" selected>-- Pilih Kategori Reject --</option>
															<?php
															$data_kategori_reject = $model->getKategoriReject($d_detail_reject['jenis_reject']);
															foreach ($data_kategori_reject as $d_kategori_reject) { ?>
																<option value="<?= $d_kategori_reject['kategori_reject'] ?>" <?php if ($d_kategori_reject['kategori_reject'] == $d_detail_reject['kategori_reject']) {
																																																echo "selected";
																																															} ?>><?= $d_kategori_reject['kategori_reject'] ?></option>
															<?php
															}
															?>
														</select>
													</td>
													<td>
														<textarea class="form-control" name="remark_reject[]" id="remark_reject_<?= $index_reject ?>" cols="20" rows="1" style="width: 200px;"><?= $d_detail_reject['remark_reject'] ?></textarea>
													</td>
													<td>
														<a type="button" class="btn btn-danger" href="<?= base_url() ?>lhp/delete_reject/<?= $d_detail_reject['id_reject'] ?>/<?= $id_lhp ?>" onclick="return confirm('Apakah anda yakin?')" <?= (!empty($disabled)) ? 'style="pointer-events: none;"' : '' ?>><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php
												$index_reject++;
											}
											?>
										</tbody>
										<tfoot>
											<tr>
												<th></th>
												<td>
													<h3>Total</h3>
												</td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_reject" id="total_reject" value="<?= $data_lhp[0]['total_reject'] ?>" style="width: 100px" readonly></td>
												<td colspan="4"></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="box-footer" style="text-align: center;">
							</div>
						</div>
					</div>
				</div>

				<!-- Detail Pending -->
				<div class="row" id="pending_section">
					<div class="col-xl-12 col-12">
						<div class="box">
							<div class="box-header with-border d-flex align-items-center">
								<h4>Detail Pending</h4>
								<button type="button" class="btn btn-primary ms-3" onclick="add_pending()">Add</button>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<table id="" class="table table-striped mb-0">
										<thead>
											<tr>
												<th>No WO</th>
												<th>Type Battery</th>
												<th>QTY WO</th>
												<th>QTY Kurang</th>
												<th>Status Battery</th>
												<th>Jenis Pending</th>
												<th>Kategori Pending</th>
												<th>Uraian Pending</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="tbody_pending">
											<?php
											$index_pending = 0;
											foreach ($data_detail_pending as $d_detail_pending) { ?>
												<tr>
													<td>
														<input type="text" class="form-control" name="no_wo_pending[]" id="no_wo_pending_<?= $index_pending ?>" value="<?= $d_detail_pending['no_wo'] ?>" style="width: 200px" readonly>
														<input type="hidden" name="id_pending[]" id="id_pending_<?= $index_pending ?>" value="<?= $d_detail_pending['id_pending'] ?>">
													</td>
													<td>
														<input type="text" class="form-control" name="part_number_pending[]" id="part_number_pending_<?= $index_pending ?>" value="<?= $d_detail_pending['type_battery'] ?>" style="width: 250px" readonly>
													</td>
													<td>
														<input type="text" class="form-control" name="qty_wo_pending[]" id="qty_wo_pending_<?= $index_pending ?>" value="<?= $d_detail_pending['qty_wo_pending'] ?>" style="width: 100px" readonly>
													</td>
													<td>
														<input type="text" class="form-control" name="qty_pending[]" id="qty_pending_<?= $index_pending ?>" value="<?= $d_detail_pending['qty_pending'] ?>" style="width: 100px" <?= $readonly ?> required>
													</td>
													<td>
														<select class="form-control select2" name="status_battery[]" id="status_battery_<?= $index_pending ?>" style="width: 200px" required>
															<option value="">Pilih Status Battery</option>
															<option value="Pending Element" <?= $d_detail_pending['status_battery'] == 'Pending Element' ? 'selected' : '' ?>>Pending Element</option>
															<option value="After PW" <?= $d_detail_pending['status_battery'] == 'After PW' ? 'selected' : '' ?>>After PW</option>
															<option value="After HSM" <?= $d_detail_pending['status_battery'] == 'After HSM' ? 'selected' : '' ?>>After HSM</option>
															<option value="After Laser" <?= $d_detail_pending['status_battery'] == 'After Laser' ? 'selected' : '' ?>>After Laser</option>
															<option value="After Packing" <?= $d_detail_pending['status_battery'] == 'After Packing' ? 'selected' : '' ?>>After Packing</option>
															<option value="Before Packing" <?= $d_detail_pending['status_battery'] == 'Before Packing' ? 'selected' : '' ?>>Before Packing</option>
														</select>
													</td>
													<td>
														<select class="form-control select2" name="jenis_pending[]" id="jenis_pending_<?= $index_pending ?>" onchange="get_kategori_pending(<?= $index_pending ?>)" style="width: 200px" required>
															<option value="">Pilih Jenis Pending</option>
															<?php foreach ($data_pending as $d_jenis_pending) { ?>
																<option value="<?= $d_jenis_pending['jenis_pending'] ?>" <?php if ($d_jenis_pending['jenis_pending'] == $d_detail_pending['jenis_pending']) {
																																														echo "selected";
																																													} ?>><?= $d_jenis_pending['jenis_pending'] ?></option>
															<?php } ?>
														</select>
													</td>
													<td>
														<select class="form-control select2" id="kategori_pending_<?= $index_pending ?>" name="kategori_pending[]" style="width: 250px;" required>
															<option value="" selected>-- Pilih Kategori Pending --</option>
															<?php $data_kategori_pending = $model->getKategoriPending($d_detail_pending['jenis_pending']);
															foreach ($data_kategori_pending as $d_kategori_pending) { ?>
																<option value="<?= $d_kategori_pending['kategori_pending'] ?>" <?php if ($d_kategori_pending['kategori_pending'] == $d_detail_pending['kategori_pending']) {
																																																	echo "selected";
																																																} ?>><?= $d_kategori_pending['kategori_pending'] ?></option>
															<?php } ?>
														</select>
													</td>
													<td>
														<textarea class="form-control" name="remark_pending[]" id="remark_pending_<?= $index_pending ?>" cols="20" rows="1" style="width: 200px;"><?= $d_detail_pending['remark_pending'] ?></textarea>
													</td>
													<td>
														<a type="button" class="btn btn-danger" href="<?= base_url() ?>lhp/delete_pending/<?= $d_detail_pending['id_pending'] ?>/<?= $id_lhp ?>" onclick="return confirm('Apakah anda yakin?')" <?= (!empty($disabled)) ? 'style="pointer-events: none;"' : '' ?>><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php $index_pending++;
											} ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2"></td>
												<td>
													<h3>Total</h3>
												</td>
												<td style="text-align: right;"><input type="text" class="form-control" name="total_pending" id="total_pending" value="<?= $data_lhp[0]['total_pending'] ?>" style="width: 100px" readonly></td>
												<td colspan="5"></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="box-footer" style="text-align: center;">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-4"></div>
					<div class="col-4" style="text-align:center;"><input type="submit" class="btn btn-success" value="Save"></div>
					<div class="col-4"></div>
				</div>
			</form>
		</section>
		<!-- /.content -->
	</div>
</div>
<!-- /.content-wrapper -->

<!-- Modal Data Andon-->
<div class="modal fade" id="modal_data_andon" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="max-width: 1500px;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Data Andon</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="data_andon" class="table table-bordered table-striped" style="width:100%">
						<thead>
							<tr>
								<th>ID Ticket</th>
								<th>Permasalahan</th>
								<th>Shift</th>
								<th>Line</th>
								<th>Pelapor</th>
								<th>Waktu</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="tbody_andon">

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger text-start" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
	var total_row;
	$(document).ready(function() {
		var tbody = document.getElementById('tbody');
		total_row = tbody.rows.length - 1;

		$('#data_andon').DataTable();

		$('input[type="number"]').each(function() {
			if ($(this).val() == 0) {
				$(this).val('');
			}
		});

		$(window).keydown(function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

	function getPartNo(i) {
		var no_wo = $('#no_wo_' + i).val();
		$('#total_menit_breakdown_' + i).val($('#menit_terpakai_' + i).val());
		$.ajax({
			url: '<?= base_url() ?>lhp/getPartNo',
			type: 'POST',
			data: {
				no_wo: no_wo
			},
			dataType: 'json',
			success: function(data) {
				$('#part_number_' + i).val(data[0].ITEM.trim());

				$.ajax({
					url: '<?= base_url() ?>lhp/getCT',
					type: 'POST',
					data: {
						part_number: data[0].ITEM.trim(),
						line: $('#line').val()
					},
					dataType: 'json',
					success: function(data) {
						if (data[0] != undefined) {
							$('#ct_' + i).val(data[0].cycle_time);
							var plan_cap = ($('#menit_terpakai_' + i).val() * 60) / data[0].cycle_time;
							$('#plan_cap_' + i).val(plan_cap.toFixed(0));
						} else {
							$('#ct_' + i).val(0);
							var plan_cap = 0;
							$('#plan_cap_' + i).val(plan_cap.toFixed(0));
						}
					}
				});
			}
		});

		var menit_aktual = <?= json_encode($menit_aktual) ?>;

		if (no_wo == '-') {
			$('#menit_terpakai_' + i).val(0);
			$('#part_number_' + i).val('');
			$('#plan_cap_' + i).val(0);
			$('#total_menit_breakdown_' + i).val(0);
			$('#ct_' + i).val(0);
		} else {
			$('#menit_terpakai_' + i).val(menit_aktual[i]);
		}
	}

	function getPartNoPending(i) {
		let no_wo = $('#no_wo_pending_' + i).val();
		$.ajax({
			url: '<?= base_url() ?>lhp/getPartNo',
			type: 'POST',
			data: {
				no_wo: no_wo
			},
			dataType: 'json',
			success: function(data) {
				$('#part_number_pending_' + i).val(data[0].ITEM.trim());
				$('#qty_wo_pending_' + i).val(data[0].QTY);
			}
		});
	}

	function presentase_actual(i) {
		var plan_cap = $('#plan_cap_' + i).val();
		var actual = $('#actual_' + i).val();
		var presentase = (actual / plan_cap) * 100;

		var ct = $('#ct_' + i).val();
		var total_minute = (actual * ct) / 60;
		var efficiency_time = total_minute.toFixed(0);
		$('#efficiency_time_' + i).val(efficiency_time);


		var total_menit_breakdown = $('#menit_terpakai_' + i).val() - efficiency_time;
		$('#total_menit_breakdown_' + i).val(total_menit_breakdown);
	}

	function add_breakdown(i) {
		var data_breakdown = <?= json_encode($data_breakdown); ?>;

		var start_breakdown = $('#start_' + i).val();
		var stop_breakdown = $('#stop_' + i).val();
		var no_wo_breakdown = $('#no_wo_' + i).val();
		var part_number_breakdown = $('#part_number_' + i).val();

		var tbody = document.getElementById('tbody_line_stop');

		var j = tbody.rows.length;

		if(no_wo_breakdown != '' && no_wo_breakdown != null) {
			$('#tbody_line_stop').append(`
				<tr>
					<td>
						<input type="time" class="form-control" name="start_breakdown[]" id="start_breakdown_${j}" value="${start_breakdown}" style="width: 100px;">
					</td>
					<td>
						<input type="time" class="form-control" name="stop_breakdown[]" id="stop_breakdown_${j}" value="${stop_breakdown}" style="width: 100px;">
					</td>
					<td>
						<input type="text" class="form-control" name="no_wo_breakdown[]" id="no_wo_breakdown_${j}" value="${no_wo_breakdown}" style="width: 125px" readonly>
						<input type="hidden" class="form-control" name="id_breakdown[]" id="id_breakdown_${j}" value="" style="width: 100px">
					</td>
					<td>
						<input type="text" class="form-control" name="part_number_breakdown[]" id="part_number_breakdown_${j}" value="${part_number_breakdown}" style="width: 225px" readonly>
					</td>
					<td>
						<select class="form-control select2" name="jenis_breakdown[]" id="jenis_breakdown_${j}" onchange="get_proses_breakdown(${j})" style="width: 200px" required>
							<option value="">Pilih Jenis Line Stop</option>
							${data_breakdown.map((item) => {
								return `<option value="${item.jenis_breakdown}">${item.jenis_breakdown}</option>`;
							}).join('')}
						</select>
					</td>
					<td>
						<select class="form-control select2" id="proses_breakdown_${j}" name="proses_breakdown[]" style="width: 200px;" required>
							<option value="" selected>-- Pilih Proses Line Stop --</option>
						</select>
					</td>
					<td>
						<textarea class="form-control" name="uraian_breakdown[]" id="uraian_breakdown_${j}" cols="20" rows="1" style="width: 200px;"></textarea>
					</td>
					<td>
						<input type="text" class="form-control" name="menit_breakdown[]" id="menit_breakdown_${j}" style="width: 100px" required>
					</td>
					<td>
						<button type="button" class="btn btn-danger" onclick="delete_breakdown(${j})"><i class="fa fa-trash"></i></button>	
					</td>
				</tr>
			`);

			$('.select2').select2();

			$('html, body').animate({
				scrollTop: $('#ls_section').offset().top
			}, 500);
		} else {
			alert('No WO masih kosong');
		}
	}

	function add_reject(i) {
		var data_reject = <?= json_encode($data_reject); ?>;

		var start_reject = $('#start_' + i).val();
		var stop_reject = $('#stop_' + i).val();
		var no_wo_reject = $('#no_wo_' + i).val();
		var part_number_reject = $('#part_number_' + i).val();


		var tbody = document.getElementById('tbody_reject');

		var j = tbody.rows.length;

		if(no_wo_reject != '' && no_wo_reject != null) {
			$('#tbody_reject').append(`
				<tr>
					<td>
						<input type="text" class="form-control" name="no_wo_reject[]" id="no_wo_reject_${j}" value="${no_wo_reject}" style="width: 150px" readonly>
						<input type="hidden" class="form-control" name="id_reject[]" id="id_reject_${j}" value="" style="width: 100px">
					</td>
					<td>
						<input type="text" class="form-control" name="part_number_reject[]" id="part_number_reject_${j}" value="${part_number_reject}" style="width: 250px" readonly>
					</td>
					<td>
						<input type="number" class="form-control" name="qty_reject[]" id="qty_reject_${j}" style="width: 100px">
					</td>
					<td>
						<select class="form-control select2" id="jenis_reject_${j}" name="jenis_reject[]" onchange="get_kategori_reject(${j})" style="width: 200px;" required>
							<option value="" selected>-- Pilih Jenis Reject --</option>
							${data_reject.map((item) => {
								return `<option value="${item.jenis_reject}">${item.jenis_reject}</option>`;
							}).join('')}
						</select>
					</td>
					<td>
						<select class="form-control select2" id="kategori_reject_${j}" name="kategori_reject[]" style="width: 250px;" required>
							<option value="" selected>-- Pilih Kategori Reject --</option>
						</select>
					</td>
					<td>
						<textarea class="form-control" name="remark_reject[]" id="remark_reject_${j}" cols="20" rows="1" style="width: 200px;"></textarea>
					</td>
					<td>
						<button type="button" class="btn btn-danger" onclick="delete_reject(${j})"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			`);
			$('.select2').select2();

			$('html, body').animate({
				scrollTop: $('#reject_section').offset().top
			}, 500);
		} else {
			alert('No WO masih kosong');
		}
	}

	function add_pending() {
		let data_pending = <?= json_encode($data_pending); ?>;

		let tbody = document.getElementById('tbody_pending');

		let j = tbody.rows.length;

		$('#tbody_pending').append(`
			<tr>
				<td>
					<select class="form-control select2" id="no_wo_pending_${j}" name="no_wo_pending[]" onchange="getPartNoPending(${j})" style="width: 200px;" required>
						<option value="" selected>-- Pilih No WO --</option>
						<?php foreach ($data_wo as $dw) {
							if (substr($dw['PDNO'], 0, 4) == 'PKAS' || substr($dw['PDNO'], 0, 4) == 'PKAB' || substr($dw['PDNO'], 0, 4) == 'PKAV') { ?>
							<option value="<?= $dw['PDNO'] ?>"><?= $dw['PDNO'] ?></option>
						<?php }
						} ?>
						<option value="-">-</option>
					</select>
					<input type="hidden" class="form-control" name="id_pending[]" id="id_pending_${j}" value="" style="width: 100px">
				</td>
				<td>
					<input type="text" class="form-control" name="part_number_pending[]" id="part_number_pending_${j}" style="width: 250px" readonly>
				</td>
				<td>
					<input type="number" class="form-control" name="qty_wo_pending[]" id="qty_wo_pending_${j}" style="width: 100px" readonly>
				</td>
				<td>
					<input type="number" class="form-control" name="qty_pending[]" id="qty_pending_${j}" style="width: 100px">
				</td>
				<td>
					<select class="form-control select2" name="status_battery[]" id="status_battery_${j}" style="width: 200px">
						<option value="">Pilih Status Battery</option>
						<option value="Pending Element">Pending Element</option>
						<option value="After PW">After PW</option>
						<option value="After HSM">After HSM</option>
						<option value="After Laser">After Laser</option>
						<option value="After Packing">After Packing</option>
						<option value="Before Packing">Before Packing</option>
					</select>
				</td>
				<td>
					<select class="form-control select2" id="jenis_pending_${j}" name="jenis_pending[]" onchange="get_kategori_pending(${j})" style="width: 200px;" required>
						<option value="" selected>-- Pilih Jenis Reject --</option>
						${data_pending.map((item) => {
							return `<option value="${item.jenis_pending}">${item.jenis_pending}</option>`;
						}).join('')}
					</select>
				</td>
				<td>
					<select class="form-control select2" id="kategori_pending_${j}" name="kategori_pending[]" style="width: 250px;" required>
						<option value="" selected>-- Pilih Kategori Reject --</option>
					</select>
				</td>
				<td>
					<textarea class="form-control" name="remark_pending[]" id="remark_pending_${j}" cols="20" rows="1" style="width: 200px;"></textarea>
				</td>
				<td>
					<button type="button" class="btn btn-danger" onclick="delete_pending(${j})"><i class="fa fa-trash"></i></button>
				</td>
			</tr>
		`);
		$('.select2').select2();

		$('html, body').animate({
			scrollTop: $('#pending_section').offset().top
		}, 500);
	}

	function get_proses_breakdown(i) {
		var jenis_breakdown = $('#jenis_breakdown_' + i).val();

		if (jenis_breakdown == 'ANDON') {
			get_data_andon(i);
		} else {
			$.ajax({
				url: '<?= base_url() ?>lhp/get_proses_breakdown',
				type: 'POST',
				data: {
					jenis_breakdown: jenis_breakdown
				},
				dataType: 'json',
				success: function(data) {
					$('#proses_breakdown_' + i).html(`
						<option value="" selected>-- Pilih Proses Line Stop --</option>
						${data.map((item) => `<option value="${item.proses_breakdown}">${item.proses_breakdown}</option>`)}
					`);
					$('#uraian_breakdown_' + i).val('');
				}
			});
		}
	}

	function get_kategori_reject(i) {
		var jenis_reject = $('#jenis_reject_' + i).val();

		$.ajax({
			url: '<?= base_url() ?>lhp/get_kategori_reject',
			type: 'POST',
			data: {
				jenis_reject: jenis_reject
			},
			dataType: 'json',
			success: function(data) {
				$('#kategori_reject_' + i).html(`
					<option value="" selected>-- Pilih Kategori Reject --</option>
					${data.map((item) => `<option value="${item.kategori_reject}">${item.kategori_reject}</option>`)}
				`);
				$('#remark_reject_' + i).val('');
			}
		});
	}

	function get_kategori_pending(i) {
		var jenis_pending = $('#jenis_pending_' + i).val();

		$.ajax({
			url: '<?= base_url() ?>lhp/get_kategori_pending',
			type: 'POST',
			data: {
				jenis_pending: jenis_pending
			},
			dataType: 'json',
			success: function(data) {
				$('#kategori_pending_' + i).html(`
					<option value="" selected>-- Pilih Kategori Reject --</option>
					${data.map((item) => `<option value="${item.kategori_pending}">${item.kategori_pending}</option>`)}
				`);
				$('#remark_pending_' + i).val('');
			}
		});
	}

	function get_data_andon(j) {
		var tanggal_produksi = $('#tanggal_produksi').val();
		var line = <?= $data_lhp[0]['line'] ?>;
		var shift = <?= $data_lhp[0]['shift'] ?>;
		$.ajax({
			url: '<?= base_url() ?>lhp/get_data_andon',
			type: 'POST',
			data: {
				tanggal_produksi: tanggal_produksi,
				line: line,
				shift: shift
			},
			dataType: 'json',
			success: function(data) {
				$('#tbody_andon').html('');
				data.forEach((item, i) => {
					$('#tbody_andon').append(`
						<tr>
							<td>${item.id_ticket}</td>
							<td>${item.permasalahan}</td>
							<td>${item.shift}</td>
							<td>${item.id_line}</td>
							<td>${item.pelapor}</td>
							<td>${item.created_at}</td>
							<td><button class="btn btn-primary btn-sm" onclick="pilih_andon(${item.id_ticket}, ${j})">Pilih</button></td>
						</tr>
					`);
				});
			}
		})
		$('#modal_data_andon').modal('show');
	}

	function pilih_andon(id_ticket, i) {
		$.ajax({
			url: '<?= base_url() ?>lhp/pilih_andon',
			type: 'POST',
			data: {
				id_ticket: id_ticket
			},
			dataType: 'json',
			success: function(data) {
				$('#proses_breakdown_' + i).html(`
					${data.map((item) => `<option value="${item.id_ticket}-${item.tujuan}-${item.kategori_perbaikan}-${item.nama_mesin}" selected>${item.id_ticket}-${item.tujuan}-${item.kategori_perbaikan}-${item.nama_mesin}</option>`)}
				`);

				$('#uraian_breakdown_' + i).val(data[0].permasalahan);
				$('#menit_breakdown_' + i).val(data[0].total_min_reduce);

			}
		});
		$('#modal_data_andon').modal('hide');
	}

	function add_rows_batch(i) {
		var data_wo = <?php echo json_encode($data_wo); ?>;
		var data_breakdown = <?php echo json_encode($data_breakdown); ?>;

		var tbody = document.getElementById('tbody'),
			row, k;

		// ambil jam trakhir di row selanjutnya
		var k = i + 1;

		var jam_start = $('#start_' + k).val();
		var jam_stop = $('#stop_' + i).val();

		// Ambil total jumlah row untuk mengetahui row mana yang akan di tambahkan
		var count_row = tbody.rows.length;
		var j = (count_row - total_row) + i;

		// uniqid untuk id jenis_breakdown dan proses_breakdown
		var k = count_row * 2;

		row = tbody.insertRow(j);
		row.innerHTML = `
			<tr>
				<td><button type="button" class="btn btn-sm btn-danger" onclick="delete_rows(${j})">Remove</button></td>
				<td><input type="time" class="form-control" name="start[]" id="start_(${j})" value="${jam_stop}" style="width: 100px;"></td>
				<td><input type="time" class="form-control" name="stop[]" id="stop_(${j})" value="${jam_start}" style="width: 100px;"></td>
				<td>
					<input type="number" class="form-control menit_terpakai" name="menit_terpakai[]" id="menit_terpakai_${k}" onkeyup="update_plan(${k})" value="" style="width: 100px" readonly>
				</td>
				<td>
					<select class="form-control select2" id="no_wo_${k}" name="no_wo[]" onchange="getPartNo(${k})" style="width: 200px;">
						<option value="" selected>-- Pilih No WO --</option>
						${data_wo.map((item) => {
							if(item.PDNO.substring(0, 4) == 'PKAS' || item.PDNO.substring(0, 4) == 'PKAB' || item.PDNO.substring(0, 4) == 'PKAV') return `<option value="${item.PDNO}">${item.PDNO}</option>`
						})}
						<option value="-">-</option>
					</select>
					<input type="hidden" name="batch[]" id="batch_${k}" value="${i == 0 ? i+1 : i}">
				</td>
				<td>
					<input type="text" class="form-control" name="part_number[]" id="part_number_${k}" style="width: 250px" readonly>
				</td>
				<td>
					<input type="text" class="form-control" size="4" name="ct[]" id="ct_${k}" style="width: 100px" readonly>
				</td>
				<td>
					<input type="text" class="form-control" name="plan_cap[]" id="plan_cap_${k}" style="width: 100px" readonly>
				</td>
				<td>
					<input type="text" class="form-control" name="actual[]" id="actual_${k}" onkeyup="presentase_actual(${k})" style="width: 100px">
				</td>
				<td>
					<input type="text" class="form-control" name="total_menit_breakdown[]" id="total_menit_breakdown_${k}" style="width: 100px" readonly>
				</td>
				<td>
					<button type="button"class="btn btn-sm btn-primary" id="add_breakdown_${k}" onclick="add_breakdown(${k})">Add</button>
				</td>
				<td>
					<button type="button"class="btn btn-sm btn-primary" id="add_reject_${k}" onclick="add_reject(${k})">Add</button>
				</td>
			</tr>
		`;

		$('.select2').select2();
	}

	function delete_rows(i) {
		var tbody = document.getElementById('tbody');
		tbody.deleteRow(i);
	}

	function delete_breakdown(i) {
		var tbody = document.getElementById('tbody_line_stop');
		tbody.deleteRow(i);
	}

	function delete_reject(i) {
		var tbody = document.getElementById('tbody_reject');
		tbody.deleteRow(i);
	}

	function delete_pending(i) {
		var tbody = document.getElementById('tbody_pending');
		tbody.deleteRow(i);
	}

	function update_plan(i) {
		var menit_terpakai = $('#menit_terpakai_' + i).val() * 60;
		var ct = $('#ct_' + i).val();
		var plan_cap = Math.floor(menit_terpakai / ct);
		$('#plan_cap_' + i).val(plan_cap);

		presentase_actual(i);
	}

	function time_start(i) {
		var date = new Date();
		var currentTime = date.toLocaleString().substring(11, 16);
		$('#start_section_' + i).html(`
			<input type="time" class="form-control" name="start_${i}" id="start_${i}" value="${currentTime}" style="width: 100px;">
		`);

		$('#stop_section_' + i).html(`
			<button class="btn btn-danger" onclick="time_stop(${i})">Stop</button>
		`);
	}

	function time_stop(i) {
		var date = new Date();
		var currentTime = date.toLocaleString().substring(11, 16);
		$('#stop_section_' + i).html(`
			<input type="time" class="form-control" name="stop_${i}" id="stop_${i}" value="${currentTime}" style="width: 100px;">
		`);
	}
</script>
<?= $this->endSection(); ?>