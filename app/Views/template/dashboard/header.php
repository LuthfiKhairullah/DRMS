<header class="main-header">
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<div class="app-menu">
			<ul class="header-megamenu nav">
			</ul>
		</div>

		<?php
		$uri = current_url(true);
		if ($uri->getSegment(3) == 'assy') {
			$judul = 'PERFORMANCE';
			$judul_a = 'ASSEMBLING';
		} else if ($uri->getSegment(3) == 'reject') {
			$judul = 'REJECTION';
			$judul_a = 'ASSEMBLING';
		} else if ($uri->getSegment(3) == 'reject_wet') {
			$judul = 'REJECTION';
			$judul_a = 'WET BATTERY';
		} else if ($uri->getSegment(3) == 'pending_wet') {
			$judul = 'PENDING';
			$judul_a = 'WET BATTERY';
		} else if ($uri->getSegment(3) == 'line_stop') {
			$judul = 'LINE STOP';
			$judul_a = 'ASSEMBLING';
		} else if ($uri->getSegment(3) == 'rejectCutting') {
			$judul = 'CUTTING REJECTION';
			$judul_a = 'ASSEMBLING';
		} else if ($uri->getSegment(3) == 'rejectMCB') {
			$judul = 'CUTTING REJECTION';
			$judul_a = 'ASSEMBLING';
		} else if ($uri->getSegment(3) == 'wet_finishing') {
			$judul = 'PERFORMANCE';
			$judul_a = 'FINISHING CHARGING';
		} else if ($uri->getSegment(3) == 'wet_charging') {
			$judul = 'PERFORMANCE';
			$judul_a = 'CHARGING';
		} else {
			$judul = 'REPORT';
			$judul_a = '';
		}

		if ($uri->getSegment(4) == '') {
			if ($child_filter == 0) {
				if ($uri->getSegment(3) == 'rejectMCB') {
					$sub_judul = '(MCB)';
				} else {
					$sub_judul = '';
				}
			} else {
				if ($child_filter == 8) {
					$sub_judul = '(WET A)';
				} else if ($child_filter == 9) {
					$sub_judul = '(WET F)';
				} else if ($child_filter == 10) {
					$sub_judul = '(MCB)';
				} else {
					$sub_judul = '(LINE ' . $child_filter . ')';
				}
			}
		} else if ($uri->getSegment(4) == 'amb1') {
			if ($child_filter == 0) {
				$sub_judul = '(AMB 1)';
			} else {
				$sub_judul = '(LINE ' . $child_filter . ')';
			}
		} else if ($uri->getSegment(4) == 'amb2') {
			if ($child_filter == 0) {
				$sub_judul = '(AMB 2)';
			} else {
				if ($child_filter == '8') {
					$sub_judul = '(WET A)';
				} else if ($child_filter == '9') {
					$sub_judul = '(WET F)';
				} else if ($child_filter == '10') {
					$sub_judul = '(MCB)';
				} else {
					$sub_judul = '(LINE ' . $child_filter . ')';
				}
			}
		} else if ($uri->getSegment(4) == 'mcb') {
			$sub_judul = '(MCB)';
		} else if ($uri->getSegment(4) == 'wet_a') {
			$sub_judul = '(WET A)';
		} else if ($uri->getSegment(4) == 'wet_f') {
			$sub_judul = '(WET F)';
		} else if ($uri->getSegment(4) == 'home') {
			$judul_a = '';
			$sub_judul = '';
		} else {
			$sub_judul = '';
		}
		?>

		<div style="margin-left:-230px; text-align:center; margin-top:-3px;">
			<h1 class="judul_dashboard"><?= $judul ?> <?= $dashboard ?? 'DASHBOARD' ?></h1>
			<span class="sub_judul_dashboard"><?= $judul_a ?> <?= $sub_judul ?></span>
		</div>

		<div class="navbar-custom-menu r-side">
			<ul class="nav navbar-nav">
			</ul>
		</div>
	</nav>
</header>