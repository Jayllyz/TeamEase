<?php session_start(); ?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
  <?php
  $linkCss = 'css-js/style.css';
  $linkLogo = 'images/logo.png';
  $title = 'Accueil';
  include 'includes/head.php';
  ?>
  <body>
    <main>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown button
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li>
            <a class="dropdown-item" href="#">Action</a>
          </li>
          <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#" id="nestedDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Nested Dropdown
            </a>
            <ul class="dropdown-menu" aria-labelledby="nestedDropdown">
              <li>
                <a class="dropdown-item" href="#">Submenu item 1</a>
              </li>
              <li>
                <a class="dropdown-item" href="#">Submenu item 2</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        $('.dropdown-submenu > a').on('click', function(e) {
          var $el = $(this);
          var $parent = $(this).offsetParent(".dropdown-menu");
          if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
          }
          var $submenu = $(this).next(".dropdown-menu");
          $submenu.toggleClass('show');

          $(this).parent("li").toggleClass('show');

          $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show");
          });

          if (!$parent.parent().hasClass('navbar-nav')) {
            $el.next().css({
              "top": $el[0].offsetTop,
              "left": $parent.outerWidth() - 4
            });
          }

          return false;
        });
      });
    </script>
  </body>
</html>
