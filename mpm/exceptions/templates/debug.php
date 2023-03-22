<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <main>
      <?php
        echo "<h1>{$mpm_exception['title']}</h1>";
        echo "<h2>{$mpm_exception['target']}</h2>";
        echo "<p>{$mpm_exception['extra']['title']}</p>";
        echo "<p>{$mpm_exception['extra']['data']}</p>";
       
      ?>
    </main>
  </body>
</html>
