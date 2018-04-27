<?php
//Chargement de twig
require_once __DIR__ . '/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
'cache' => false
));
//indiquer le nom du dossier à explorer
$directories = "root";
//si dir existe attribuer sa valeur à la variable directories
if (isset($_GET['dir'])) {
  $directories = $_GET['dir'];
}
//si directories contient la string root et ne contient pas la string '..' et si la cible existe
if(substr($directories, 0, 4) === 'root' && !strpos($directories, '..') && file_exists($directories)){
//attribuer à la variable le resultat de scandir en retirant les éléments '.' et '..'
  $a = array_diff(scandir($directories), array('..', '.'));
//créer les variable en indiquant que ce sont des tableaux
  $folders = [];
  $files = [];
//pour chaque éléments inclus dans la variable '$a'
  foreach ($a as $dir) {
//créer la variable '$path'qui correspond au chemin d'accés de chaque éléments
    $path = $directories.'/'.$dir;
//si l'élément est un dossier
    if (is_dir($path)) {
//créer une variable date qui récupère la date de derniere modification du dossier
      $date = "Dernière modification le :        " . date ("d F Y H:i:s.", filemtime($path));
//créer une variable '$filetype' qui récupère le type de fichier
      $filetype = "Type de fichier : " . mime_content_type($path);
//ajouter àla variable le résultat de la fonction qui récupère les infos du propriétaire du fichier sous forme de tableau
      $ownerinfo = posix_getpwuid(fileowner($path));
//de ce tableau extraire le premier élément qui correspond au nom du propriétaire et ajouter cette string à la variable
      $owner = "Propriétaire: " . $ownerinfo['name'];
//envoyer dans la variable '$folders' les variables name path date filetype owner
      array_push($folders, ['name' => $dir, 'path' => $path, 'date' => $date, 'filetype' => $filetype, 'owner' => $owner,]);
    }
//sinon (si l'élément n'est pas un dossier)
    else {
//créer une variable date qui récupère la date de derniere modification du dossier
      $date = "Dernière modification le :       " . date ("d F Y H:i:s.", filemtime($path));
//créer une variable '$filetype' qui récupère le type de fichier
      $filetype = "Type de fichier : " . mime_content_type($path);
//ajouter à la variable le résultat de la fonction qui récupère les infos du propriétaire du fichier sous forme de tableau
      $ownerinfo = posix_getpwuid(fileowner($path));
//de ce tableau extraire le premier élément qui correspond au nom du propriétaire et ajouter cette string à la variable
      $owner = "Propriétaire: " . $ownerinfo['name'];
//envoyer dans la variable '$files' les variables name path date filetype owner
      array_push($files, ['name' => $dir, 'path' => $path, 'date' => $date, 'filetype' => $filetype, 'owner' => $owner]);
    }
  }
//indiquer à twig vers quel template html on veut envoyer les données
  echo $twig->render("demo.html", array(
//nommage des variables pour permettre à twig de les utiliser
    'folders' => $folders,
    'files'   => $files,
    'path'    => $directories,
  ));
//si directories ne contient pas la string root ou contient la string '..' ou si la cible n'existe pas
} else {
//indiquer à twig vers quel template html on veut envoyer les données
  echo $twig->render("demo.html", array(
//créer une string erreur
    'error' => 'dossier inexistant',
    'path'    => $directories,
  ));
}
?>
