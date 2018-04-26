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

    $path = $directories.'/'.$dir;

    if (is_dir($path)) {
      // echo ('<a href="index.php?dir='.$directories.'/'.$dir.'">'.$dir.'</a><br>');
      $date = "a été modifié le : " . date ("d F Y H:i:s.", filemtime($path));
      $filetype = mime_content_type($path);
      $ownerinfo = posix_getpwuid(fileowner($path));
      $owner = $ownerinfo['name'];
      array_push($folders, ['name' => $dir, 'path' => $path, 'date' => $date, 'filetype' => $filetype, 'owner' => $owner]);

    }
    else {
      //echo ('<a href="index.php?dir=./'.$dir.'"download="$dir">'.$dir.'</a><br>');
      // echo ('<a href="telecharger.php?Fichier_a_telecharger='.$dir.'&chemin="index.php?dir=./'.$dir.'"download="$dir"/">'.$dir.'</a><br>');
      $date = "a été modifié le : " . date ("d F Y H:i:s.", filemtime($path));
      $filetype = mime_content_type($path);
      $ownerinfo = posix_getpwuid(fileowner($path));
      $owner = $ownerinfo['name'];
      array_push($files, ['name' => $dir, 'path' => $path, 'date' => $date, 'filetype' => $filetype, 'owner' => $owner]);


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
