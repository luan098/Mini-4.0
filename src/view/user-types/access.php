<?php

use Mini\controller\UserTypesController;

$permissionsFullRouteArray = array_column($permissions, 'route_full');

?>

<div class="tab-pane show active" id="access" role="tabpanel">
    <table id="access_table" class="table table-striped table-bordered col-xs-12">
        <thead>
            <tr>
                <th>Permission</th>
                <th>Route</th>
                <th>Sub-Route</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routes as $route) : ?>
                <?php
                $permissionKey = array_search("$route->route/$route->sub_route", $permissionsFullRouteArray);
                $checked = !$permissionKey && $permissionKey !== 0 ? '' : 'checked';
                ?>

                <tr>
                    <td class="col-2">
                        <div class="custom-control custom-switch d-flex justify-content-center">
                            <input <?= $checked ?> type="checkbox" class="custom-control-input check-permission" id="customSwitch<?= "$route->route/$route->sub_route" ?>" value="<?= !$route->sub_route ? $route->route : "$route->route/$route->sub_route" ?>" name="permission">
                            <label class="custom-control-label" for="customSwitch<?= "$route->route/$route->sub_route" ?>"></label>
                        </div>
                    </td>
                    <td><?= $route->route_name ?></td>
                    <td><?= $route->sub_route_name ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<script>
    $("#access_table").DataTable({
        buttons: ['colvis', 'csv', 'pdf', 'print'],
        order: [
            [0, 'desc'],
            [1, 'asc'],
        ],
        autoColumns: true,
        autoWidth: false,
        responsive: true,
        ordering: true,
        bLengthChange: true,
        bInfo: true,
        dom: "<'row d-flex align-items-end'<'col-sm-6'l><'col-sm-6 text-right'B<'datatable-search-container d-inline-block'f>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row d-flex align-items-start'<'col-sm-5'i><'col-sm-7'p>>",
        language: {
            lengthMenu: "_MENU_ results",
            search: '<div class="custom-search"><div class="search-input-container"><input type="search" class="form-control form-control-sm" aria-label="Search"><label class="search-icon"><i class="fa fa-search"></i></label></div></div>',
            searchPlaceholder: "Search",
        },
    });


    $("#access_table").on('change', '.check-permission', async (e) => {
        const route = e.currentTarget.value;
        const checked = e.currentTarget.checked;

        const result = await Ajax.post(`<?= UserTypesController::ROUTE ?>/handleEditAccess/<?= $idUserType ?>`, {
            route,
            checked: checked ? 1 : 0
        });

        Toast.fire({
            icon: result.error ? 'error' : 'success',
            title: result.message
        })
    });
</script>