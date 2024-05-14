
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/favicon.ico">

    <title>PT. Century Batteries Indonesia</title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/vendors_css.css">
	  
    <!-- Style-->  
    <link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/style.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/template/main/css/skin_color.css">

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url(<?=base_url()?>assets/images/download1.jpg)">
	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<!-- <h2 class="text-primary">Production Control V2</h2> -->
								<img src="<?= base_url()?>assets/images/logo_cbi_tulisan.png" alt="">
								<!-- <p class="mb-0">Sign in to continue to WebkitX.</p>					 -->
							</div>
							<div class="p-40">
								<form action="<?=base_url()?>login/proses_login" method="post">
									<div class="form-group">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
											<input type="text" class="form-control ps-15 bg-transparent" name="username" placeholder="Username">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
											<input type="password" class="form-control ps-15 bg-transparent" name="password" placeholder="Password">
										</div>
									</div>
									  <div class="row">
											<div class="col-12 text-center">
												<button type="submit" class="btn btn-primary mt-10">LOGIN</button>
											</div>
											<!-- /.col -->
									  </div>
								</form>
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Vendor JS -->
	<script src="<?=base_url()?>assets/template/main/js/vendors.min.js"></script>
	<script src="<?=base_url()?>assets/template/main/js/pages/chat-popup.js"></script>
	<script src="<?=base_url()?>assets/template/assets/icons/feather-icons/feather.min.js"></script>
	<script>
		<?php if (session()->has('error')) { ?>
			window.alert('<?= session()->getFlashdata('error') ?>');
		<?php } ?>
	</script>

</body>
</html>
