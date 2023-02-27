 <?php
    session_start();
    require_once "./connection.php";
    Database::setUpConnection();

    $files = $_FILES['file_array'];
    if (isset($_COOKIE['key'])) {
        $userId = $_COOKIE['key'];
    } else {
        $userId = $_COOKIE['PHPSESSID'];
    }

    $query1 = Database::$connection->prepare("SELECT * FROM `user` WHERE `key` = ? ");
    $query1->bind_param("s", $userId);

    $query1->execute();
    $res = $query1->get_result();

    if ($res->num_rows) {
        $user = $res->fetch_assoc();
        $_SESSION['user'] = $user;
    } else {
        $query2 = Database::$connection->prepare("INSERT INTO `user` (`id`,`key`) VALUES (?,?)");
        $id = Database::insert_id('user');
        $query2->bind_param("is", $id, $userId);
        $query2->execute();
    }
    foreach ($files['tmp_name'] as $tmp_name) {
        $id = Database::insert_id('image');
        $file_name = "copy_bin/" . md5(time() . uniqid()) . ".png";
        move_uploaded_file($tmp_name, $file_name);
        $query3 = Database::$connection->prepare("INSERT INTO `image` (`id`,`img_name`,`user_id`) values (?,?,?)");
        $query3->bind_param("isi", $id, $file_name, $_SESSION['user']['id']);
        $query3->execute();
    }

    echo 1;
