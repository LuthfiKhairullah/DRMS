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
			if ($uri->getSegment(3) == 'reject_plate_cutting') {
				$judul = 'PLATE CUTTING';
			} elseif ($uri->getSegment(3) == 'envelope') {
				$judul = 'ENVELOPE';
			} elseif ($uri->getSegment(3) == 'cos') {
				$judul = 'COS';
			} elseif ($uri->getSegment(3) == 'potong_battery') {
				$judul = 'POTONG BATTERY';
			} elseif ($uri->getSegment(3) == 'saw') {
				$judul = 'SAW';
			} elseif ($uri->getSegment(3) == 'resume') {
				$judul = 'RESUME';
			}
		?>

		<div style="margin-left:-250px; text-align:center; margin-top:-5px;">
			<h1 class="judul_dashboard">PLATE REJECTION DASHBOARD</h1>
			<span class="sub_judul_dashboard"><?= $judul ?></span>
		</div>

		<div class="navbar-custom-menu r-side">
			<ul class="nav navbar-nav">
			</ul>
		</div>
	</nav>
</header>