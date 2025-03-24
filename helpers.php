<?php

/** 
 * get the base path
 * 
 * @param string $path
 * @return string
 */

 function basePath($path = '') {
    return __DIR__ . '/' . $path;
 }

 /**
  * load a view
  * @param string $name
  *@return void
  */

  function loadView($name) {
   $viewPath = basePath("views/{$name}.php");

   if(file_exists($viewPath)) {
      require $viewPath;
   } else {
      echo "View '{$name}' not found!";
   }
  }

   /**
  * load a partial
  * @param string $name
  *@return void
  */

  function loadPartial($name) {
   $partialPath = basePath("views/partials/{$name}.php");

   if(file_exists($partialPath)) {
      require $partialPath;
   } else {
      echo "Partial '{$name}' not found!";
   }
  }