<?php
require '../Tinyfier/autoloader.php';

function format_size($size, $kilobyte = 1024, $format = '%size% %unit%') {

    $size = $size / $kilobyte; // Convertir bytes a kilobyes
    $units = array('KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    foreach ($units as $unit) {
        if ($size > $kilobyte) {
            $size = $size / $kilobyte;
        } else {
            break;
        }
    }

    return strtr($format, array(
        '%size%' => round($size, 2),
        '%unit%' => $unit
    ));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Tinyfier image compression test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="../tinyfier/tinyfier.php/test/assets/bootstrap.css?recache" rel="stylesheet">  
        <link href="../tinyfier/tinyfier.php/test/assets/bootstrap-responsive.css?recache" rel="stylesheet">  

        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>

        <script type="text/javascript" src="../tinyfier/tinyfier.php/test/assets/js/jquery-dev.js,test.js,bootstrap.js"></script>
    </head>

    <body>        

        <div class="container">
            <?php
            $out_path = dirname(__FILE__) . '/assets/compressed';
            if (!is_dir($out_path)) {
                mkdir($out_path);
            }

            foreach (new DirectoryIterator(dirname(__FILE__) . '/assets/images') as $file) :
                if ($file->isDir() || $file->isDot()) {
                    continue;
                }
                $dest = $out_path . '/' . $file->getFilename();

                if (file_exists($dest)) {
                    unlink($dest);
                }

                copy($file->getPathname(), $dest);
                Tinyfier_Image_Optimizer::process($dest, array(
                    Tinyfier_Image_Optimizer::VERBOSE => TRUE,
                    Tinyfier_Image_Optimizer::MODE => Tinyfier_Image_Optimizer::MODE_LOSSY,
                 //   Tinyfier_Image_Optimizer::LEVEL => Tinyfier_Image_Optimizer::LEVEL_XTREME
                ));
                ?>

                <h2><?= $file->getFilename() ?></h2>
                <div class="row-fluid">
                    <div class="span6"><h4>Original <small><?= format_size(filesize($file->getPathname())) ?></small></h4><img src="<?= str_replace(dirname(__FILE__) . '/', '', $file->getPathname()) ?>" /></div>
                    <div class="span6"><h4>Optimized <small><?= format_size(filesize($dest)) ?></h4><img  src="<?= str_replace(dirname(__FILE__) . '/', '', $dest) ?>" /></div>
                </div>
            <?php endforeach; ?>
        </div>


    </body>
</html>