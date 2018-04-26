<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
'cache' => false
));

$directories = "root";


if (isset($_GET['dir'])) {
  $directories = $_GET['dir'];
}



if(substr($directories, 0, 4) === 'root' && !strpos($directories, '..') && file_exists($directories)){
  // var_dump($directories);
  $a = array_diff(scandir($directories), array('..', '.'));
  //$a = scandir($directories);

  $folders = [];
  $files = [];

  foreach ($a as $dir) {
    if (is_dir($directories.'/'.$dir)) {
      // echo ('<a href="index.php?dir='.$directories.'/'.$dir.'">'.$dir.'</a><br>');

      array_push($folders, ['name' => $dir, 'path' => $directories.'/'.$dir]);

    }
    else {
      //echo ('<a href="index.php?dir=./'.$dir.'"download="$dir">'.$dir.'</a><br>');
      // echo ('<a href="telecharger.php?Fichier_a_telecharger='.$dir.'&chemin="index.php?dir=./'.$dir.'"download="$dir"/">'.$dir.'</a><br>');

      array_push($files, ['name' => $dir, 'path' => $directories.'/'.$dir]);

    }
  }

  echo $twig->render("demo.html", array(

    'folders' => $folders,
    'files'   => $files,

  ));

} else {
  // echo ('<p>dossier inexistant</p>');

  echo $twig->render("demo.html", array(

    'error' => 'dossier inexistant',

  ));
}




?>
