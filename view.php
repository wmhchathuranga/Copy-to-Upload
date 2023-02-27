 <?php
    require_once "./connection.php";
    Database::setUpConnection();
    session_start();

    $StorageServer = "127.0.0.1";
    $ImagePath = "/CTU/";

    if (!isset($_SESSION['user']))
        echo 0;

    $query1 = Database::$connection->prepare("SELECT * from `image` where `user_id` = ?");
    $query1->bind_param("i", $_SESSION['user']['id']);
    $query1->execute();
    $res = $query1->get_result();
    $img_count = $res->num_rows;
    $image_list = [];
    for ($i = 0; $i < $img_count; $i++) {
        $img = new stdClass();
        $row = $res->fetch_assoc();
        $img->id = $row['id'];
        $img->src = "https://" . $StorageServer . $ImagePath . $row['img_name'];
        array_push($image_list, $img);
    }

    $JsonRes = json_encode($image_list);
    echo $JsonRes;
