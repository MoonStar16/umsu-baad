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
            <li><a href="/ubahProdiNonKedokteran"><?= $breadcrumb[1]; ?></a></li>
            <li><a href="/ubahProdiNonKedokteran"><?= $breadcrumb[2]; ?></a></li>
            <li class="active"><?= $breadcrumb[3]; ?></li>
        </ul>
        <!-- END BREADCRUMB  ->getBody()-->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('prodi')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('prodi'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tahunAjar')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahunAjar'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tahap')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahap'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tahapTanggalAwal')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahapTanggalAwal'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tahapTanggalAkhir')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahapTanggalAkhir'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" class="form-horizontal" action="/ubahProdiNonKedokteran" method="POST">
                            <div class="col-md-2">
                                <label>Pilih Prodi</label>
                                <select class="form-control select" name="prodi">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($prodi as $rows) : ?>
                                        <option value="<?= $rows->Department_Acronym ?>" <?php if ($rows->Department_Acronym == $filter) echo " selected" ?>><?= $rows->Department_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo " selected" ?>><?= $rows->Term_Year_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Angkatan</label>
                                <select class="form-control select" name="tahunAngkatan">
                                    <option value="">Semua Angkatan</option>
                                    <?php for ($i = date("Y"); $i >= 2016; $i--) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $entryYear) echo " selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Pembayaran Tahap</label>
                                <select class="form-control select" name="tahap">
                                    <option value="">-- Select --</option>
                                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $paymentOrder) echo " selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($startDate != null) ? $startDate : "now"));  ?>" name="tahapTanggalAwal" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($endDate != null) ? $endDate : "+1 week"));  ?>" name="tahapTanggalAkhir" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px; margin-right: 9px;" type="submit" class="btn btn-success"><span class="fa fa-arrow-circle-right"></span>
                                    Proses</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <center>
                            <lottie-player src="<?= $icon; ?>" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>