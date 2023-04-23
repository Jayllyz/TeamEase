<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?= $linkCss ?>">
    <link rel="shortcut icon" type="image/png" href="<?= $linkLogo ?>" />
    <title><?= $title ?></title>
    <?php if ($title == 'Inscription' || $title == 'Reservation' || $title == 'Modification de réservation') {
      echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    } ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <!-- <script src="css-js/js/searchBar.js"></script> -->
</head>