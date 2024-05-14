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
                    <a href="<?= base_url() ?>dashboard/assy/home" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/production_efficiency.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Production Efficiency</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>dashboard_plate_rejection/reject_plate_cutting" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/logo_plate_rejection.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Plate Rejection</h3>
                    </a>
                </div>
                <div class="col">
                    <a href="<?= base_url() ?>dashboard_rework/saw_repair" target="_blank">
                        <img src="<?= base_url() ?>assets/images/icon-dashboard-produksi2/logo_rework.png" alt="" style="max-height: 150px; width: 30%;">
                        <br>
                        <h3 class="fs" style="color: white">Rework</h3>
                    </a>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>