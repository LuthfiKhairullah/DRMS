<?= $this->extend('template/dashboard/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left:0;">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
            <div style="margin-top:150px; margin-left:50px;">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6" style="margin:auto">
                                <div class="row">
                                    <div class="col-12 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/assy" target="_blank" class="btn btn-primary btn-lg btn-main-dashboard">Assy AMB</a >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/assy/amb1" target="_blank" class="btn btn-secondary btn-sub-dashboard">Assy AMB 1</a>
                                    </div>
                                    <div class="col-6 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/assy/amb2" target="_blank" class="btn btn-secondary btn-sub-dashboard">Assy AMB 2</a >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="margin:auto">
                                <div class="row">
                                    <div class="col-12 mb-0" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/assy/mcb" target="_blank" class="btn btn-info btn-lg btn-main-dashboard">Assy MCB</a >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" style="margin-left:-100px;">
                        <div class="row">
                            <div class="col-6" style="margin:auto">
                                <div class="row">
                                    <div class="col-12 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/wet_finishing" target="_blank" class="btn btn-warning btn-lg btn-main-dashboard">WET Finishing</a >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/wet_finishing/wet_a" target="_blank" class="btn btn-secondary btn-sub-dashboard">WET A</a >
                                    </div>
                                    <div class="col-6 mb-15" style="display: grid;">
                                        <a href="<?=base_url()?>dashboard/wet_finishing/wet_f" target="_blank" class="btn btn-secondary btn-sub-dashboard">WET F</a >
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
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script></script>
<?= $this->endSection(); ?>