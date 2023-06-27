<?php

$server = $_SERVER['SERVER_ADDR'];
$username = 'root';
$password = '';
$dbname = 'imgupl';
$charset = 'utf8';

$connection = new mysqli($server, $username, $password, $dbname);

if($connection->connect_error){
    die("Ошибка соединения".$connection->connect_error);
}

if(!$connection->set_charset($charset)){
    echo "Ошибка установки кодировки UTF8";
}

// Upload or update photo
if(isset($_POST['upload'])){
    $img_type = substr($_FILES['img_upload']['type'], 0, 5);
    $img_size = 2*1024*1024;
    if(!empty($_FILES['img_upload']['tmp_name']) and $img_type === 'image' and $_FILES['img_upload']['size'] <= $img_size){ 
        $img = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));
        $connection->query("INSERT INTO images (img) VALUES ('$img')");
    }
    header("Location: yourphoto.php"); // Redirect to the page after the insert operation
    exit(); // Terminate the script execution
}
elseif(isset($_POST['change'])) {
    $photo_id = $_POST['photo_id'];
    $img_type = substr($_FILES['new_img_upload']['type'], 0, 5);
    $img_size = 2*1024*1024;
    if(!empty($_FILES['new_img_upload']['tmp_name']) and $img_type === 'image' and $_FILES['new_img_upload']['size'] <= $img_size){ 
        $new_img = addslashes(file_get_contents($_FILES['new_img_upload']['tmp_name']));
        $connection->query("UPDATE images SET img = '$new_img' WHERE id = $photo_id");
    }
    header("Location: yourphoto.php"); // Redirect to the page after the update operation
    exit(); // Terminate the script execution
}
elseif(isset($_POST['delete'])) {
    $photo_id = $_POST['photo_id'];
    $connection->query("DELETE FROM images WHERE id = $photo_id");
    header("Location: yourphoto.php"); // Redirect to the page after the delete operation
    exit(); // Terminate the script execution
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/fcs.jpg" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <title>WebTech</title>
    <style>
        .photo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .photo-container img {
            display: block;
            margin: 0 auto;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
            background-color: #ccc;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-small {
            font-size: 12px;
        }
    </style>
</head>

<body bgcolor="#ae3e27">
    <div>
        <center>
            <h1>Ваши автомобили</h1>
        </center>
    </div>

    <!-- Upload form -->
    <form action="yourphoto.php" method="post" enctype="multipart/form-data">
        <p align="center">Сюда вы можете загружать то, на чём ездите сами)</p>
        <input type="file" name="img_upload">
        <input type="submit" name="upload" value="Загрузить" class="btn">
    </form>

    <!-- Photo display and change/delete form -->
    <?php
    $query = $connection->query("SELECT * FROM images ORDER BY id DESC");
    while($row = $query->fetch_assoc()){
        $show_img = base64_encode($row['img']);
        ?>
        <div class="photo-container">
            <img src="data:image/jpeg;base64, <?=$show_img ?>" alt="<?=$row['id']?>">
            <form action="yourphoto.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="photo_id" value="<?=$row['id']?>">
                <input type="file" name="new_img_upload">
                <input type="submit" name="change" value="Изменить" class="btn btn-small">
                <input type="submit" name="delete" value="Удалить" class="btn btn-small">
            </form>
        </div>
    <?php } ?>

</body>

</html>



