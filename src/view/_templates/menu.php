<?php

use Mini\core\Database;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-mini">
  <div class="sidebar pt-5">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-legacy" data-widget="treeview" role="" data-accordion="false">
        <?php
        $pdo = Database::getInstance()->getPdo();

        if ($_SESSION['user']->id_user_type != 3) {

          if ($_SESSION['user']->id_user_type == 1) {

            $sql = " SELECT * FROM menus WHERE status = 1 AND id_menu_father is null AND id in (8) ORDER BY name ASC ";
          } elseif ($_SESSION['user']->id_user_type == 2) {

            $sql = " SELECT * FROM menus WHERE status = 1 AND id_menu_father is null AND id NOT IN (13, 2, 3, 7) ORDER BY name ASC ";
          } else {

            $sql = "SELECT * FROM menus WHERE status = 1 AND id_menu_father is null AND id NOT IN (13) ORDER BY name ASC ";
          }

          $stmtMenu = $pdo->prepare($sql);
          $stmtMenu->execute();
          $menuPais = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

          foreach ($menuPais as $menuPai) {

            if ($_SESSION['user']->id_user_type == 1) {

              $sql2 = " SELECT * FROM menus WHERE status = 1 AND id_menu_father = {$menuPai['id']} AND id = 11 ORDER BY name ASC ";
            } elseif ($_SESSION['user']->id_user_type == 2) {

              $sql2 = " SELECT * FROM menus WHERE status = 1 AND id_menu_father = {$menuPai['id']} AND id NOT IN (9) ORDER BY name ASC ";
            } else {

              $sql2 = " SELECT * FROM menus WHERE status = 1 AND id_menu_father = {$menuPai['id']} ORDER BY name ASC ";
            }

            $stmtMenu2 = $pdo->prepare($sql2);
            $stmtMenu2->execute();
            $countMenuFilho = $stmtMenu2->rowCount();
            $menuFilhos = $stmtMenu2->fetchAll(PDO::FETCH_ASSOC);

            if ($countMenuFilho > 0) {

              $stmtActvPai = $pdo->prepare(" SELECT * FROM menus WHERE status = 1 AND page = '{$_GET['pg']}' ");
              $stmtActvPai->execute();
              $openActv = $stmtActvPai->fetch(PDO::FETCH_ASSOC);

        ?>
              <li class="nav-item <?php if ($menuPai['id'] == $openActv['id_menu_father'] && $_GET['pg'] != "home") {
                                    echo "-is-opening -open";
                                  } ?>">
                <a href="" class="nav-link cursor-pointer <?php if ($openActv['id_menu_father'] == $menuPai['id'] && $_GET['pg'] != "home") {
                                                            echo "active";
                                                          } ?>">
                  <i class="nav-icon <?= $menuPai['icon']; ?>"></i>
                  <p>
                    <?= $menuPai['name']; ?>
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php foreach ($menuFilhos as $menuFilho) { ?>
                    <li class="nav-item">
                      <a href="<?= "./{$menuFilho['page']}"; ?>" class="nav-link <?php if ($_GET['pg'] == $menuFilho['page']) {
                                                                                      echo "active";
                                                                                    } ?>">
                        <i class="<?= $menuFilho['icon']; ?> nav-icon"></i>
                        <p><?= $menuFilho['name']; ?></p>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
            <?php } else { ?>
              <li class="nav-item">
                <a href="<?= $menuPai['page']; ?>" class="nav-link <?php if ($_GET['pg'] == $menuPai['page']) {
                                                                        echo "active";
                                                                      } ?>">
                  <i class="nav-icon <?= $menuPai['icon']; ?>"></i>
                  <p><?= $menuPai['name']; ?></p>
                </a>
              </li>
        <?php
            }
          }
        }
        ?>
      </ul>
    </nav>
  </div>
</aside>