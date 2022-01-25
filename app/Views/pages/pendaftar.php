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
        <!-- END BREADCRUMB  ->getBody()-->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo session()->getFlashdata('success'); ?>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" class="form-horizontal" action="/pendaftar/proses" method="POST">

                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">--Select--</option>
                                    <?php foreach ($listFakultas as $rows) : ?>
                                        <option value="<?= $rows->fakNamaSingkat ?>"><?= $rows->fakNamaResmi ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo " selected" ?>><?= $rows->Term_Year_Name ?></option> -->
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Angkatan</label>
                                <select class="form-control select" name="tahunAngkatan">
                                    <option value="">--Select--</option>
                                    <?php for ($i = date("Y"); $i >= 2016; $i--) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $entryYear) echo " selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-arrow-circle-right"></span>
                                    Proses</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <center>
                            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>