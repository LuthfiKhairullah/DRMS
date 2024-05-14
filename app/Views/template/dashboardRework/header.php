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
			if ($uri->getSegment(3) == 'saw_repair') {
				$judul = 'SAW REWORK';
			} elseif ($uri->getSegment(3) == 'resume') {
				$judul = 'RESUME';
			}
		?>

		<div style="margin-left:-250px; text-align:center; margin-top:-5px;">
			<h1 class="judul_dashboard">REWORK DASHBOARD</h1>
			<span class="sub_judul_dashboard"><?= $judul ?></span>
		</div>

		<div class="navbar-custom-menu r-side">
			<ul class="nav navbar-nav">
			</ul>
		</div>
	</nav>
</header>