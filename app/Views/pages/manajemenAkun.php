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
        <?php if (!empty(session()->getFlashdata('success'))) : ?>
          <?= view('layout/templateAlert', ['msg' => ['success', session()->getFlashdata('success')]]); ?>
        <?php endif; ?>
        <?php if (!empty(session()->getFlashdata('failed'))) : ?>
          <?= view('layout/templateAlert', ['msg' => ['danger', session()->getFlashdata('failed')]]); ?>
        <?php endif; ?>
        <?php if ($validation->hasError('grup')) : ?>
          <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('grup')]]); ?>
        <?php endif; ?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-actions table datatable">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Grup</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($akun as $row) : ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td><?= $row->email; ?></td>
                      <td><?= $row->username; ?></td>
                      <td><?= ($row->grupDesc) ?? '-'; ?></td>
                      <td class="text-center"><span class="label label-<?= ($row->active == '1') ? 'success' : 'danger'; ?> "><?= ($row->active) ? 'Aktif' : 'Tidak Aktif' ?></span></td>
                      <td class="text-center"><button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit<?= $row->id; ?>"><i class="fa fa-edit"></i></button></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($akun as $edit) : ?>
    <div class="modal" id="edit<?= $edit->id; ?>" tabindex="-1" role="dialog" aria-labelledby="defModalHead" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="defModalHead">Edit Data</h4>
          </div>
          <form class="form-horizontal" method="POST" action="/manajemenAkun/edit/<?= $edit->id; ?>" role="form">
            <div class="modal-body">
              <input type="hidden" name="_METHOD" value="PUT" />
              <div class="form-group">
                <label class="col-md-2 control-label">Email</label>
                <div class="col-md-10">
                  <input name="email" type="email" class="form-control" value="<?= $edit->email; ?>" disabled />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Username</label>
                <div class="col-md-10">
                  <input name="username" type="text" class="form-control" value="<?= $edit->username; ?>" disabled />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Grup</label>
                <div class="col-md-10">
                  <select name="grup" class="form-control select">
                    <option value="">-- Select --</option>
                    <?php foreach ($grup as $gr) : ?>
                      <option value="<?= $gr->id; ?>" <?= ($edit->grupId == $gr->id) ? 'selected' : ''; ?>><?= $gr->description; ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Status</label>
                <div class="col-md-10">
                  <div class="material-switch pt-5">
                    <input id="switch<?= $edit->id ?>" name="active" type="checkbox" <?= ($edit->active == '1') ? 'checked' : ''; ?> />
                    <label for="switch<?= $edit->id ?>" class="label-primary"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach ?>

  <?= $this->endSection(); ?>