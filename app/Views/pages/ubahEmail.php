<?= $this->extend('layout/templateHome'); ?>
<?= $this->section('content'); ?>
<div class="page-container">
    <?= view('layout/templateSidebar', ['menus' => $menu]); ?>
    <div class="page-content">
        <?= $this->include('layout/templateHead'); ?>
        <ul class="breadcrumb">
            <li><a href="/home"><?= $breadcrumb[0]; ?></a></li>
            <li class="active"><?= $breadcrumb[1]; ?></li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form class="form-horizontal" action="/ubahEmail/update" method="POST">
                                    <input type="hidden" name="tipe" value="mahasiswa">
                                    <input type="hidden" name="_method" value="put">
                                    <div class="panel panel-colorful">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Mahasiswa</h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (!empty(session()->getFlashdata('successMhs'))) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['success', "<strong>Sukses ! </strong>" . session()->getFlashdata('successMhs')]]); ?>
                                            <?php endif; ?>
                                            <?php if (!empty(session()->getFlashdata('failedMhs'))) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['failed', "<strong>Failed ! </strong>" . session()->getFlashdata('failedMhs')]]); ?>
                                            <?php endif; ?>
                                            <?php if ($validation->hasError('angkatan')) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('angkatan')]]); ?>
                                            <?php endif; ?>
                                            <?php if ($validation->hasError('npm')) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('npm')]]); ?>
                                            <?php endif; ?>
                                            <?php if ($validation->hasError('emailMhs')) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('emailMhs')]]); ?>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Pilih Angkatan</label>
                                                <div class="col-md-8">
                                                    <select name="angkatan" class="form-control select" data-live-search="true" onchange="getMahasiswa()">
                                                        <option value="">-- Select --</option>
                                                        <?php for ($i = date('Y'); $i >= 2016; $i--) : ?>
                                                            <option><?= $i ?></option>
                                                        <?php endfor ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Pilih Mahasiswa</label>
                                                <div class="col-md-8">
                                                    <select name="npm" class="form-control select" data-live-search="true">
                                                        <option value="">-- Select --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Email</label>
                                                <div class="col-md-8">
                                                    <input name="emailMhs" type="text" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <button type="submit" class="btn btn-primary pull-right">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form class="form-horizontal" action="/ubahEmail/update" method="POST">
                                    <input type="hidden" name="tipe" value="dosen">
                                    <input type="hidden" name="_method" value="put">
                                    <div class="panel panel-colorful">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Dosen</h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (!empty(session()->getFlashdata('successDosen'))) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['success', "<strong>Sukses ! </strong>" . session()->getFlashdata('successDosen')]]); ?>
                                            <?php endif; ?>
                                            <?php if (!empty(session()->getFlashdata('failedDosen'))) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['failed', "<strong>Failed ! </strong>" . session()->getFlashdata('failedDosen')]]); ?>
                                            <?php endif; ?>
                                            <?php if ($validation->hasError('dosen')) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('dosen')]]); ?>
                                            <?php endif; ?>
                                            <?php if ($validation->hasError('emailDosen')) : ?>
                                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('emailDosen')]]); ?>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pilih</label>
                                                <div class="col-md-9">
                                                    <select name="dosen" class="form-control select" data-live-search="true">
                                                        <option value="">-- Select --</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Email</label>
                                                <div class="col-md-9">
                                                    <input name="emailDosen" type="text" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <button type="submit" class="btn btn-primary pull-right">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection(); ?>