<?php

use Mini\controller\HomeController;
use Mini\core\Application;
use Mini\core\PermissionChecker;
use Mini\core\Router;
use Mini\model\Menus;

$selectedMenu = (new Menus)->getMenuByRoute($_GET['pg']);
if (!$selectedMenu) $selectedMenu = (new Menus)->getMenuByRoute($_GET['pg'] . "/" . $_GET['pg2']);
$menus = (new Menus)->getTree(null, 1);

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-mini">
  <div class="sidebar pt-5">
    <nav class="mt-2">
      <?php new MenuComponent($menus ?? [], $selectedMenu->id ?? 0) ?>
    </nav>
  </div>
</aside>

<?php

class MenuComponent
{
  function __construct(array $menus, $menuSelected = 0)
  {
    $li = $this->recursiveOption($menus);

?>
    <!-- <div class="form-inline">
      <div class="input-group">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" name="filter-menus" id="filter-menus" autocomplete="off">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->

    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-legacy" data-widget="treeview" role="" data-accordion="false">
      <li class="nav-header">Navigation</li>
      <li class="nav-item page" data-find="Home">
        <a href="<?= HomeController::ROUTE ?>" class="nav-link">
          <i class="fa fa-home"></i>
          <p class="notranslate">Home</p>
        </a>
      </li>
      <?= implode('', $li); ?>
    </ul>

    <script>
      function selectMenuActive() {
        const currentPage = $(`#menu-code-<?= $menuSelected ?>`);
        currentPage.parents("li.nav-item").addClass("menu-is-opening menu-open");
        currentPage.addClass("active");
      }

      function removeSpecialCharsMenu(str) {
        return str.replace(/[^\w\s]/gi, '');
      }

      // $("#filter-menus").keyup((e) => {
      //   const currentText = removeSpecialCharsMenu($(e.currentTarget).val().toLowerCase());
      //   const target = $(".main-sidebar li.page");

      //   if (currentText) {
      //     target.hide();
      //     target.filter(function() {
      //       const menuItemText = removeSpecialCharsMenu($(this).text().toLowerCase());
      //       return menuItemText.includes(currentText); 
      //     }).show().parents('.nav-treeview').show().addClass('active');
      //   } else {
      //     target.show().parents('.nav-treeview').removeClass('active');
      //     selectMenuActive();
      //   }
      // });

      selectMenuActive();
    </script>
<?php

  }

  private function recursiveOption(array $menus, array $selectedId = null, $ownId = "", $showChildren = true, $indexes = ""): array
  {
    $index = 1;
    $liArray = [];

    foreach ($menus as $menu) {
      if ($menu->data->page) {
        $router = new Router($menu->data->page);

        $route = $router->getRoute();
        $subRoute = $router->getSubRoute() ?: 'index';

        $route = ucfirst($route ? Application::formatUrlPart($route) : 'Home');
        $subRoute = lcfirst($subRoute ? Application::formatUrlPart($subRoute) : 'index');

        if (!PermissionChecker::havePermission($route, $subRoute)) continue;
      } 
      
      $strIndex = $indexes . (!empty($indexes) ? "." : "")  . "$index";

      if ($ownId != $menu->id) {
        $angle = "";
        $merge = "";
        if (!empty($menu->children)) {
          $angle = '<i class="right fas fa-angle-left"></i>';

          if (!empty($menu->children)) {
            $children = $this->recursiveOption($menu->children, $selectedId, $menu->id, $showChildren, $strIndex);

            if (sizeof($children) <= 0 && !$menu->data->page) continue;

            $merge = '<ul class="nav nav-treeview">' . implode('', $children) . '</ul>';
          }
        }

        $href = $menu->data->page ? $menu->data->page . "/" . ($menu->data->page != '#' ? ""  : null) : '#';

        $liArray[] = "
                <li class='page nav-item' data-find='$menu->text'>
                    <a class='nav-link' id='menu-code-$menu->id' href='$href'>
                        <i class='{$menu->data->icon}'></i>
                        <p>$menu->text</p>
                        $angle
                    </a>
                    $merge
                </li>";
      }

      $index++;
    }

    return $liArray;
  }
}
