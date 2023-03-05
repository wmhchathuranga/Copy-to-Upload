 <?php
    require_once "./connection.php";
    Database::setUpConnection();
    session_start();
    $userCookie = "";
    $uid = "";

    $StorageServer = "127.0.0.1";
    $ImagePath = "/CTU/";
    $namePrefix = "copy_bin/";
    $imgName = "";

    if (isset($_COOKIE['key'])) {
        $userCookie = $_COOKIE['key'];
    } else {
        $userCookie = $_COOKIE['PHPSESSID'];
    }

    if (!isset($_POST['img'])) {
        return 0;
    } else {
        $imgName = "%{$_POST['img']}";
    }

    $query1 = Database::$connection->prepare("SELECT `id` FROM `user` WHERE `key` = ? ");
    $query1->bind_param("s", $userCookie);

    $query1->execute();
    $res = $query1->get_result();

    if ($res->num_rows) {
        $user = $res->fetch_assoc();
        $uid = $user['id'];
    } else {
        $query2 = Database::$connection->prepare("INSERT INTO `user` (`id`,`key`) VALUES (?,?)");
        $uid = Database::insert_id('user');
        $query2->bind_param("is", $uid, $userCookie);
        $query2->execute();
    }

    $query1 = Database::$connection->prepare("SELECT * from `image` where `user_id` = ? and `img_name` like ?");
    $query1->bind_param("is", $uid, $imgName);
    $query1->execute();
    $res = $query1->get_result();
    if ($res->num_rows) {
        $row = $res->fetch_assoc();
        $imgObj = new stdClass();
        $img_data = file_get_contents($row['img_name']);
        $type = pathinfo($row['img_name'], PATHINFO_EXTENSION);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);
        $imgObj->src = $base64;
        $JsonRes = json_encode($imgObj);
        echo $JsonRes;
    }
    else{
        echo 0;
    }

