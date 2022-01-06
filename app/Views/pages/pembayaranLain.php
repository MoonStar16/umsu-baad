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
            <li><a href="/pembayaranDetail"><?= $breadcrumb[1]; ?></a></li>
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
                <?php if ($validation->hasError('jenis')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('jenis'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tanggalAwal')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tanggalAwal'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('tanggalAkhir')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tanggalAkhir'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/pembayaranLain" method="POST">
                            <div class="col-md-2">
                                <label>Jenis Tagihan</label>
                                <select class="form-control select" name="jenis">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($jenis as $jns) : ?>
                                        <option value="<?= $jns->value ?>" <?php if ($jns->value == $tagihan) echo "selected" ?>><?= $jns->text ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($startDate != null) ? $startDate : "now"));  ?>" name="tanggalAwal" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($endDate != null) ? $endDate : "+1 week"));  ?>" name="tanggalAkhir" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-search"></span>
                                    Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($prodi != null) : ?>
                            <?php if ($tagihan != null) : ?>
                                <form action="/pembayaranLainProdi/cetak" method="post">
                                    <input type="hidden" name="jenis" value="<?= $tagihan; ?>">
                                    <input type="hidden" name="tanggalAwal" value="<?= $startDate; ?>">
                                    <input type="hidden" name="tanggalAkhir" value="<?= $endDate; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export Prodi</button></ul>
                                </form>
                                <span>
                                    <form action="/pembayaranLainSeluruh/cetak" method="post">
                                        <input type="hidden" name="jenis" value="<?= $tagihan; ?>">
                                        <input type="hidden" name="tanggalAwal" value="<?= $startDate; ?>">
                                        <input type="hidden" name="tanggalAkhir" value="<?= $endDate; ?>">
                                        <ul class="panel-controls"><button style="display: inline-block; margin-right: 11px; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                                Export Seluruh</button></ul>
                                    </form>
                                </span>
                            <?php endif ?>
                            <?php foreach ($prodi as $prd) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= $prd ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-actions">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>NPM</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Angkatan</th>
                                                        <th>Nama Biaya</th>
                                                        <th>Mata Kuliah</th>
                                                        <th>Bank</th>
                                                        <th>Tanggal Pembayaran</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $total = 0;
                                                    $no = 1;
                                                    if (count($pembayaran) > 0) : ?>
                                                        <?php foreach ($pembayaran as $rows) : ?>
                                                            <?php if ($rows->PRODI == $prd) : $total = $total + $rows->NOMINAL ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td><?= $rows->Npm ?></td>
                                                                    <td><?= $rows->NAMA_LENGKAP ?></td>
                                                                    <td><?= $rows->ANGKATAN ?></td>
                                                                    <td><?= $rows->NAMA_BIAYA ?></td>
                                                                    <td><?= ($rows->MATA_KULIAH == null) ? "-" : $rows->MATA_KULIAH ?></td>
                                                                    <td><?= ($rows->BANK_NAMA == null) ? "Biro Keuangan UMSU" : $rows->BANK_NAMA ?></td>
                                                                    <td><?= date_format(date_create($rows->TANGGAL_BAYAR), "d/m/Y") ?></td>
                                                                    <td><?= number_to_currency($rows->NOMINAL, 'IDR') ?></td>
                                                                </tr>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td colspan=8 style="text-align: center;"><strong>Total Amount</strong></td>
                                                            <td><strong><?= number_to_currency($total, 'IDR') ?></strong></td>
                                                        </tr>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan=8 style="text-align:center">Tidak ada data</td>
                                                        </tr>
                                                    <?php endif ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            <?php endforeach ?>
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