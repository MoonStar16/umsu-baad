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
            <li><a href="/totalPembayaran"><?= $breadcrumb[1]; ?></a></li>
            <li class="active"><?= $breadcrumb[2]; ?></li>
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
                        <form autocomplete="off" action="/pembayaranTotal" method="POST">
                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo "selected" ?>><?= $rows->Term_Year_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Pembayaran Tahap</label>
                                <select class="form-control select" name="tahap">
                                    <option value="">-- Select --</option>
                                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $paymentOrder) echo "selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Bank</label>
                                <select class="form-control select" name="bank">
                                    <option value="">Keseluruhan</option>
                                    <?php foreach ($listBank as $rows) : ?>
                                        <option value="<?= $rows->Bank_Acronym ?>" <?php if ($rows->Bank_Acronym == $bank) echo "selected" ?>><?= $rows->Bank_Name ?></option>
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
                        <?php if ($prodi != null) : ?>
                            <?php if ($termYear != null  && $paymentOrder != null) : ?>
                                <form action="/pembayaranTotal/cetak" method="post">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <input type="hidden" name="tahap" value="<?= $paymentOrder; ?>">
                                    <input type="hidden" name="bank" value="<?= $bank; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export</button></ul>
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Rekap Pembayaran <?= ($bank == '') ? "Keseluruhan" : $bank ?></h3>
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

                                                                    <!-- pembayaran total tabel pivot salah -->

                                                                    <?php $nilai = 0;
                                                                    foreach ($pembayaran as $pemb) : ?>
                                                                        <?php ($ang == $pemb->ANGKATAN && $prd['prodi'] == $pemb->PRODI) ? $nilai = $pemb->NOMINAL + $nilai : $nilai = $nilai ?>
                                                                        <?php if ($ang == $pemb->ANGKATAN && $prd['prodi'] == $pemb->PRODI) {
                                                                            $a[$ang] = $a[$ang] + $pemb->NOMINAL;
                                                                        }
                                                                        ?>
                                                                    <?php endforeach ?>
                                                                    <td><?= number_to_currency($nilai, 'IDR') ?></td>
                                                                <?php endforeach ?>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                <?php endforeach ?>
                                                <tr>
                                                    <td></td>
                                                    <td><strong>Pembayaran Per Angkatan</strong></td>
                                                    <?php foreach ($angkatan as $ang) : ?>
                                                        <td><strong><?= number_to_currency($a[$ang], 'IDR') ?></strong></td>
                                                    <?php endforeach ?>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><strong>Total Pembayaran</strong></td>
                                                    <?php $totalPembayaran = 0;
                                                    foreach ($angkatan as $ang) : ?>
                                                        <?php $totalPembayaran = $totalPembayaran + $a[$ang]; ?>
                                                    <?php endforeach ?>
                                                    <td colspan="<?= count($angkatan); ?>" style="text-align:center"><strong><?= number_to_currency($totalPembayaran, 'IDR') ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <center>
                                <lottie-player src="<?= $icon ?>" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
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