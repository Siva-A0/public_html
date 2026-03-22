<?php require_once(__DIR__ . '/../../config.php');?>
 
 
<?php
session_start();

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbGallery = TB_GALLERY;

if (!isset($_SESSION['adminId'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['image'])) {

    $imageId = intval($_GET['image']);

    $imageData = $fcObj->dbObj->getAllPrepared(
        "SELECT image_name FROM `".$tbGallery."` WHERE id = :image_id",
        array(':image_id' => $imageId)
    );

    if (!empty($imageData)) {

        $fileName = $imageData[0]['image_name'];

        // Delete DB record
        $delete = $fcObj->deleteGallery($tbGallery, $imageId);

        if ($delete) {

            $filePaths = array(
                __DIR__ . '/' . $fileName,
                dirname(__DIR__) . '/../gallery/' . $fileName
            );

            foreach ($filePaths as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}

header("Location: gallery.php");
exit;
?>
