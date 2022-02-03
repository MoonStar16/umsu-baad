<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">
    <?= view('layout/templateSidebar', ['menus' => $menu]); ?>
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
                                <span>Aplikasi UMSU BAAD</span>
                            </div>
                        </div>
                        <div class="panel-body ">
                            <blockquote class="blockquote-info">
                                <p>Dibawah ini merupakan jumlah data di tahun ajar berjalan</p>
                            </blockquote>
                            <div class="row">

                                <div class=" col-md-4">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-users"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Pendaftar</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-graduation-cap"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Calon Mahasiswa</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->


    <?= $this->endSection(); ?>