<?php

include '../includes/db.php';

if (isset($_POST['search'])) {
  if ($_POST['search'] > 2) {
    $search = htmlspecialchars($_POST['search']);
    $query = $db->prepare('SELECT name, id FROM ACTIVITY WHERE name LIKE :search');
    $query->execute([
      'search' => '%' . $search . '%',
    ]);
    $name = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; count($name) >= 4 ? $i < 4 : $i < count($name); $i++) {
      echo '<a href="activity.php?id=' .
        $name[$i]['id'] .
        '">
            <div class="search-suggestion">' .
        $name[$i]['name'] .
        '</div>
        </a>';
    }
  }
}

//

?>
