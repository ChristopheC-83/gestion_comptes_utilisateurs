<!--  Template, structure commune à toutes les pages -->
<!-- attention, tous les liens partent de index.php ! -->
<!-- Ce fichier, index.php, sert de routage -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=URL?>public/style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="description" content="<?= $page_description; ?>">
    <title><?= $page_title; ?></title>
</head>

<body>

    <?php require_once("views/commons/header.php"); ?>

    <div class="app">

        <?php 
            if(!empty($_SESSION['alert'])) {
                foreach($_SESSION['alert'] as $alert){
                    echo "<div class='alert ". $alert['type'] ."' role='alert'>
                        ".$alert['message']."
                    </div>";
                }
                unset($_SESSION['alert']);
            }
        ?>

        <?= $page_content; ?>

    </div>

    <?php require_once("views/commons/footer.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <!-- pour choisir les fichiers js actifs par page qui seront dans un tableau dans les controllers-->
    <?php if(!empty($js)) : ?>
        <?php foreach($js as $fichier_javascript) : ?>
            <script src="<?= URL?>public/javaScript/<?= $fichier_javascript ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?> 

</body>

</html>