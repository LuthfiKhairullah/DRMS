<aside class="main-sidebar">
	<!-- sidebar-->
	<section class="sidebar position-relative">
		<div class="multinav">
			<div class="multinav-scroll" style="height: 100%;">
				<!-- sidebar menu-->
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">Menu</li>
					<?php if ((session()->get('departemen') == 'produksi2' or session()->get('departemen') == '' or session()->get('departemen') == 32) and session()->get('level') < 5) { ?>
					<li class="treeview">
						<a href="#">
							<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
							<span>Dashboard</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?= base_url() ?>dashboard"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Produksi 2</a></li>
						</ul>
					</li>
					<?php } ?>
					<?php if ((session()->get('departemen') == 'produksi2' or session()->get('departemen') == '' or session()->get('departemen') == 32) and session()->get('level') >= 5) { ?>
					<li class="treeview">
						<a href="#">
							<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
							<span>Laporan Produksi 2</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?= base_url() ?>platecutting"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Plate Cutting</a></li>
							<li><a href="<?= base_url() ?>envelope"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Envelope</a></li>
							<li><a href="<?= base_url() ?>cos"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>COS</a></li>
							<li><a href="<?= base_url() ?>pw"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>PW</a></li>
							<li><a href="<?= base_url() ?>wet_finishing/"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>WET Finishing</a></li>
							<li><a href="<?= base_url() ?>lhp"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Assy</a></li>
							<li><a href="<?= base_url() ?>mcb"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>MCB</a></li>
							<li><a href="<?= base_url() ?>potong_battery"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Potong Battery</a></li>
							<li><a href="<?= base_url() ?>saw_repair"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>SAW Repair</a></li>
						</ul>
					</li>
					<?php } ?>
					<?php if ((session()->get('departemen') == 'produksi2' or session()->get('departemen') == '' or session()->get('departemen') == 32) and session()->get('level') < 5) { ?>
						<li class="treeview">
							<a href="#">
								<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
								<span>Data Master</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-right pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?= base_url() ?>master_plate"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Master Plate</a></li>
								<li><a href="<?= base_url() ?>master_group_leader"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Master Group Leader</a></li>
								<li><a href="<?= base_url() ?>master_operator"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Master Operator</a></li>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</section>
</aside>