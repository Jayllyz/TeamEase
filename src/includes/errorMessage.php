<?php
if (isset($_GET['message'])) {
  echo '<div class="alert alert-danger mt-4" role="alert">';
  echo $_GET['message'];
  echo '</div>';
}
