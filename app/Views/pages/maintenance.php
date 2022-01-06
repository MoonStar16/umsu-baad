<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">
    <?= view('layout/templateSidebar',['menus'=>$menu]); ?>
    <!-- PAGE CONTENT -->
    <div class="page-content">
        <?= $this->include('layout/templateHead'); ?>
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body panel-body-table">
                            <center>
                                <lottie-player src="https://assets7.lottiefiles.com/private_files/lf30_y9czxcb9.json" background="transparent" speed="1" style="width: 800px; height: 800px;" loop autoplay></lottie-player>
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