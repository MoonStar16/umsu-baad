<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">
    <?= view('layout/templateSidebar',['menus'=>$menu]); ?>
    <!-- PAGE CONTENT -->
    <div class="page-content">
        <?= $this->include('layout/templateHead'); ?>
        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            <li><a href="/home"><?= $breadcrumb[0]; ?></a></li>
            <li class="active"><?= $breadcrumb[1]; ?></li>
        </ul>
        <!-- END BREADCRUMB -->
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3>UNIVERSITAS MUHAMMADIYAH SUMATERA UTARA</h3>
                                <span>Aplikasi Laporan Keuangan</span>
                            </div>
                        </div>
                        <div class="panel-body panel-body-table">
                            <center>
                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_tljjahng.json" background="transparent" speed="1" style="width: 800px; height: 800px;" loop autoplay></lottie-player>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->


<?= $this->endSection(); ?>