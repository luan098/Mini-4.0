<?php

?>

<div class="tab-pane show active" id="data" role="tabpanel">
    <form method="post" action="<?= "$this->route/" . (($idUserType ?? false) ? "handle-edit/$idUserType" : 'handle-add') ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?= $userType->name ?? '' ?>" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <?php if (isset($userType)) : ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" value="1" name="status" <?= $userType->status ? "checked" : '' ?>>
                            <label class="custom-control-label" for="customSwitch1">Status</label>
                        </div>
                    <?php endif ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" value="1" name="is_admin" <?= $userType->is_admin ?? false ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch2">Is Admin</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <a href="<?= "$this->route" ?>" class="btn btn-default">Back</a>
                <div class='float-right'>
                    <a href="<?= "$this->route/add" ?>" class="btn btn-success">New</a>
                    <button type="submit" class="btn btn-primary"><?= $userType->id ?? '' ? "Update" : "Register" ?></button>
                </div>
            </div>
        </div>
    </form>
</div>