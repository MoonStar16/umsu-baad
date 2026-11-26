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

                        <div class="panel-body">
                            <?php if (!empty(session()->getFlashdata('success'))) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['success', "<strong>Sukses ! </strong>" . session()->getFlashdata('success')]]); ?>
                            <?php endif; ?>
                            <?php if (!empty(session()->getFlashdata('failed'))) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['failed', "<strong>Failed ! </strong>" . session()->getFlashdata('failed')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('angkatan')) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('angkatan')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('npm')) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('npm')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('tahun')) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('tahun')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('tagihan')) : ?>
                                <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('tagihan')]]); ?>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <form class="form-horizontal" action="/hapusTagihan/delete" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
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
                                                <select name="npm" class="form-control select" data-live-search="true" onchange="getTagihan()">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Pilih Tahun</label>
                                            <div class="col-md-8">
                                                <select name="tahun" class="form-control select" data-live-search="true">
                                                    <option value="">-- Select --</option>
                                                    <?php foreach ($tahunAjaran as $th) : ?>
                                                        <option value="<?= $th->Term_Year_Id ?>"><?= $th->Term_Year_Id ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Pilih Tagihan</label>
                                            <div class="col-md-8">
                                                <select name="tagihan" class="form-control select" data-live-search="true">
                                                    <option value="">-- Select --</option>
                                                    <?php foreach ($tagihanLain as $key => $value) : ?>
                                                        <option value="<?= $key ?>"><?= $value ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-danger pull-right">Delete</button>
                                    </form>
                                </div>
                                <div class="col-md-7">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th>Tahun</th>
                                                    <th>Tagihan</th>
                                                    <th>Tahap</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataTagihan">
                                                <tr>
                                                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection(); ?>