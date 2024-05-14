<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Produksi</title>

	<!-- Icons -->
	<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
	<link rel="shortcut icon" href="<?= base_url(). 'assets/images/favicons/favicon.png'?>">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= base_url() . 'assets/images/favicons/favicon-192x192.png'?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url() . 'assets/images/favicons/apple-touch-icon-180x180.png'?>">
	<!-- END Icons -->

    <!-- Vendors Style-->
	<link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/vendors_css.css">
	  
    <!-- Style-->  
    <link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/style.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/skin_color.css">
	
	<!-- Highcharts -->
	<script src="<?= base_url() . 'assets/js/highcharts/highcharts.js'?>"></script>
	<script src="<?= base_url() . 'assets/js/highcharts/modules/series-label.js'?>"></script>
	<script src="<?= base_url() . 'assets/js/highcharts/modules/exporting.js'?>"></script>
	<script src="<?= base_url() . 'assets/js/highcharts/modules/export-data.js'?>"></script>
	<script src="<?= base_url() . 'assets/js/highcharts/modules/accessibility.js'?>"></script>

	<style>
		@font-face {
			font-family: azonix;
			src: url("<?= base_url()?>fonts/azonix.otf") format("opentype");
		}

		.judul_dashboard {
			font-size: 45px;
			color: #fff;
			font-weight:600;
			letter-spacing: 4px;	
		}

		.sub_judul_dashboard {
			font-size: 37px;
			color: #fff;
			font-weight:500;	
		}

		input {
			color-scheme: dark;
		}

		body {
			background-image: url("<?= base_url()?>assets/images/1.jpg");
		}

		.main-header {
			background-color: transparent !important;
			background: transparent !important;
			border-color: transparent !important;
		}

		.btn-nav {
			font-size: 18px;
			font-weight: 600;
		}

		.btn-main-dashboard {
			font-size: 34px;
			font-weight: 600;
		}

		.btn-sub-dashboard {
			font-size: 24px;
			font-weight: 600;
			color: black !important;
		}
		
		.modal {
			overflow-y:auto;
		}

	</style>
</head>
<body class="hold-transition dark-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
    <?= $this->include('template/dashboardPlateRejection/header') ?>
    <?= $this->renderSection('content') ?>
    

  <!-- Control Sidebar -->
  <aside class="control-sidebar">
	  
	<div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger"><i class="ion ion-close text-white" data-toggle="control-sidebar"></i></span> </div>  <!-- Create the tabs -->
    <ul class="nav nav-tabs control-sidebar-tabs">
      <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab" class="active"><i class="mdi mdi-message-text"></i></a></li>
      <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i class="mdi mdi-playlist-check"></i></a></li>
    </ul>
    <!-- Tab panes -->	
    <div class="tab-content">
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  
</div>
<!-- ./wrapper -->
	<!-- Vendor JS -->
	<script src="<?=base_url()?>assets/template/main/js/vendors.min.js"></script>
	<script src="<?=base_url()?>assets/template/main/js/pages/chat-popup.js"></script>
    <script src="<?=base_url()?>assets/template/assets/icons/feather-icons/feather.min.js"></script>

	<script src="<?=base_url()?>assets/template/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
	<script src="<?=base_url()?>assets/template/assets/vendor_components/moment/min/moment.min.js"></script>
	
	<!-- EduAdmin App -->
	<script src="<?=base_url()?>assets/template/main/js/template.js"></script>
	<script src="<?=base_url()?>assets/template/main/js/pages/dashboard.js"></script>
	<script src="<?=base_url()?>assets/template/main/js/pages/calendar.js"></script>
	<script src="<?=base_url()?>assets/template/main/js/pages/advanced-form-element.js"></script>
	<script src="<?=base_url()?>assets/template/assets/vendor_components/echarts/dist/echarts-en.min.js"></script>
	<?= $this->renderSection('script') ?>
</body>
</html>