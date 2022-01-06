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
            <li><a href="/krsAktif"><?= $breadcrumb[1]; ?></a></li>
            <li><a href="active"><?= $breadcrumb[2]; ?></a></li>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/krsAktif" method="POST">
                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">Semua Fakultas</option>
                                    <?php foreach ($fakultasFilter as $rows) : ?>
                                        <option value="<?= $rows->Faculty_Acronym ?>" <?php if ($rows->Faculty_Acronym == $filter) echo " selected" ?>><?= $rows->Faculty_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo "selected" ?>><?= $rows->Term_Year_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-search"></span>
                                    Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($krsAktif != null) : ?>
                            <?php if ($termYear != null && $filter != null) : ?>
                                <form action="/krsAktif/cetak" method="post">
                                    <input type="hidden" name="fakultas" value="<?= $filter; ?>">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export</button></ul>
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Jumlah KRS Aktif</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="text-align:center" valign="center">No.</th>
                                                    <th style="text-align:center">Fakultas / Prodi</th>
                                                    <th colspan=<?= count($angkatan) ?> style="text-align:center">Stambuk</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <?php $a = [];
                                                    foreach ($angkatan as $ang) : ?>
                                                        <?php
                                                        $a[$ang] = 0;
                                                        ?>
                                                        <th style="text-align:center"><?= $ang ?></th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($fakultas as $fak) : ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><strong><?= $fak ?></strong></td>
                                                        <?php foreach ($angkatan as $ang) : ?>
                                                            <td></td>
                                                        <?php endforeach ?>
                                                    </tr>
                                                    <?php $no = 1;
                                                    foreach ($prodi as $prd) : ?>
                                                        <?php if ($fak == $prd['fakultas']) : ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $prd['prodi'] ?></td>
                                                                <?php foreach ($angkatan as $ang) : ?>

                                                                    <?php $nilai = 0;
                                                                    foreach ($krsAktif as $krsAkt) : ?>
                                                                        <?php ($ang == $krsAkt->ANGKATAN && $prd['prodi'] == $krsAkt->NAMA_PRODI) ? $nilai = $krsAkt->JUMLAH : $nilai = $nilai ?>
                                                                        <?php if ($ang == $krsAkt->ANGKATAN && $prd['prodi'] == $krsAkt->NAMA_PRODI) {
                                                                            $a[$ang] = $a[$ang] + $krsAkt->JUMLAH;
                                                                        }
                                                                        ?>
                                                                    <?php endforeach ?>
                                                                    <td><?= $nilai ?></td>
                                                                <?php endforeach ?>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><strong>Jumlah Per Fakultas</strong></td>
                                                        <?php foreach ($angkatan as $ang) : ?>
                                                            <td><strong><?= $a[$ang] ?></strong></td>
                                                            <?php $a[$ang] = 0; ?>
                                                        <?php endforeach ?>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <center>
                                <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_qozq0stb.json" background="transparent" speed="1" style="width: 700px; height: 700px;" loop autoplay></lottie-player>
                            </center>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>