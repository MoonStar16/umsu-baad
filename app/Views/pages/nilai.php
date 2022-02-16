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
                            <ul class="panel-controls">
                                <li>
                                    <a href="/home" data-toggle="tooltip" data-placement="left" title data-original-title="Refresh">
                                        <span class=" fa fa-refresh"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-colorful">
                        <div class="panel-body">
                            <?php if (session()->getFlashdata('message')) : ?>
                                <div class="alert alert-info">
                                    <?= session()->getFlashdata('message') ?>
                                </div>
                            <?php endif ?>
                            <form method="post" action="/nilai/prosesExcel" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>File Excel</label>
                                    <input type="file" name="fileexcel" class="form-control" id="file" required accept=".xls, .xlsx" /></p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-colorful">
                        <div class="panel-body">
                            <?php if (count($nilai) < 1) : ?>
                                <center>
                                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json" background="transparent" speed="1" style="width: 100%; height: 500px;" loop autoplay></lottie-player>
                                </center>
                            <?php else : ?>
                                <center>
                                    <? //php if ($filter != null  && $termYear != null  && $entryYear != null) : 
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions table datatable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>NPM</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($nilai as $idx => $row) : ?>
                                                    <?php if ($idx <= 3) {
                                                        continue;
                                                    } ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row['B']; ?></td>
                                                        <td><?= $row['C']; ?></td>
                                                        <td><?= $row['I']; ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <? //php endif 
                                    ?>
                                </center>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->


    <?= $this->endSection(); ?>