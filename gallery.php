 <?php
    require_once "./connection.php";
    Database::setUpConnection();
    session_start();
    $userCookie = "";
    $uid = "";

    $StorageServer = "127.0.0.1";
    $ImagePath = "/CTU/";

    if (isset($_COOKIE['key'])) {
        $userCookie = $_COOKIE['key'];
    } else {
        $userCookie = $_COOKIE['PHPSESSID'];
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

    $query1 = Database::$connection->prepare("SELECT * from `image` where `user_id` = ?");
    $query1->bind_param("i", $uid);
    $query1->execute();
    $res = $query1->get_result();
    $img_count = $res->num_rows;

    if ($img_count) {
        $image_list = [];
        for ($i = 0; $i < $img_count; $i++) {
            $img = new stdClass();
            $row = $res->fetch_assoc();
            $img->id = $row['id'];
            $img->name = str_replace("copy_bin/","",$row['img_name']);
            $img_data = file_get_contents($row['img_name']);
            $type = pathinfo($row['img_name'], PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);
            $img->src = $base64;
            // $img->src = "http://" . $StorageServer . $ImagePath . $row['img_name'];
            // list($width, $height, $type, $attr) = getimagesize($row['img_name']);
            // $img->width = $width;
            // $img->height = $height;
            array_push($image_list, $img);
        }
        $JsonRes = json_encode($image_list);
        echo $JsonRes;
    } else {
        echo 0;
    }
