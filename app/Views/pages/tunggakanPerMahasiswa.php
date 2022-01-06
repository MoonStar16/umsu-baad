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
            <li><a href="/detailTunggakan"><?= $breadcrumb[1]; ?></a></li>
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
                <?php if (!empty(session()->getFlashdata('failed'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo session()->getFlashdata('failed'); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty(session()->getFlashdata('successBiodata'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo session()->getFlashdata('successBiodata'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('filter')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('filter'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/tunggakanPerMahasiswa" method="POST">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label>NPM / Nama Mahasiswa</label>
                                        <input type="text" class="form-control" value="<?= $filter; ?>" name="filter" placeholder="Type here..." />
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary"><span class="fa fa-search"></span>
                                                cari</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($student != null) : ?>
                            <div class="col-md-2">
                                <div class="panel panel-default">
                                    <div class="panel-body profile">
                                        <div class="profile-image"><img height="100" src=<?= (filter_var("https://mahasiswa.umsu.ac.id/FotoMhs/" . $student[0]->Entry_Year_Id . "/" . $student[0]->Nim . ".jpg", FILTER_VALIDATE_URL)) ? "https://mahasiswa.umsu.ac.id/FotoMhs/" . $student[0]->Entry_Year_Id . "/" . $student[0]->Nim . ".jpg" : "theme/assets/images/users/no-image.jpg"; ?> alt="Foto Mahasiswa" />
                                        </div>
                                        <div class="profile-data">
                                            <div class="profile-data-name"><?= $student[0]->Full_Name ?></div>
                                            <div class="profile-data-title"><?= $student[0]->Nim ?></div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="contact-info">
                                            <p><small>Fakultas</small><br /><?= $student[0]->Faculty_Name ?></p>
                                            <p><small>Prodi</small><br /><?= $student[0]->Department_Name ?></p>
                                            <p><small>Kelas</small><br /><?= $student[0]->Class_Name ?> <?= $student[0]->Class_Program_Name ?></p>
                                            <p><small>Program Kuliah</small><br /><?= $student[0]->Class_Program_Name ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-2">
                                <div class="panel panel-default">
                                    <div class="panel-body profile">
                                        <div class="profile-image"><img src="theme/assets/images/users/no-image.jpg" alt="Foto Mahasiswa" />
                                        </div>
                                        <div class="profile-data">
                                            <div class="profile-data-name">-</div>
                                            <div class="profile-data-title">-</div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="contact-info">
                                            <p><small>Fakultas</small><br />-</p>
                                            <p><small>Prodi</small><br />-</p>
                                            <p><small>Kelas</small><br />-</p>
                                            <p><small>Program Kuliah</small><br />-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif  ?>
                        <?php if ($tunggakan != null) : ?>
                            <div class="col-md-10">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Tunggakan <strong>Mahasiswa</strong></h3>
                                        <form action="/tunggakanPerMahasiswa/cetak" method="post">
                                            <input type="hidden" name="filter" value="<?= $student[0]->Nim  ?>">
                                            <ul class="panel-controls"><button style="display: inline-block; " type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                                    Export</button></ul>
                                        </form>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-actions">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Tahun</th>
                                                        <th>Nama Tagihan</th>
                                                        <th>Tahap</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $total = 0;
                                                    $no = 1;
                                                    foreach ($tunggakan as $rows) : $total = $total + $rows->Amount ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= $rows->Term_Year_Bill_id; ?></td>
                                                            <td><?= $rows->Cost_Item_Name; ?></td>
                                                            <td><?= $rows->Payment_Order; ?></td>
                                                            <td><?= number_to_currency($rows->Amount, 'IDR') ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                    <tr>
                                                        <td style="text-align:center" colspan="4"><strong>Total</stong>
                                                        </td>
                                                        <td><strong><?= number_to_currency($total, 'IDR') ?></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-10">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Tunggakan <strong>Mahasiswa</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-actions">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Tahun</th>
                                                        <th>Nama Tagihan</th>
                                                        <th>Tahap</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align:center" colspan="5">Tidak ada tagihan</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->



        <?= $this->endSection(); ?>