<?= $this->extend('template/layout'); ?>
<?= $this->section('style'); ?>
<style>
    .fs {
        font-size: 30px;
    }

    @media screen and (max-width: 768px) {
        .fs {
            font-size: 14px;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<div class="content-wrapper" style="background-image: url('<?= base_url() ?>assets/images/1.jpg');">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12" style="text-align:center;">
                    <h1 class="fw-bold" style="color: white">PRODUCTION 2 DEPARTMENT DASHBOARD</h1>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <br>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3" style="text-align:center;">
                <div class="col">
                    <a href="<?= base_url() ?>platecutting" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Plate Cutting</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>envelope" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Envelope</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>cos" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Cos</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>pw" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">PW</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>wet_finishing" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Wet Finishing</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>lhp" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Assy</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>mcb" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">MCB</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>potong_battery" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Potong Battery</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>saw_repair" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/realtime_performance.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Saw Repair</h3>
                    </a>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>