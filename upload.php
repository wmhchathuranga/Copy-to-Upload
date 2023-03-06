 <?php
    session_start();
    require_once "./connection.php";
    require_once "./aws/aws-autoloader.php";
    ini_set("upload_max_filesize", "10M");
    $singleLimit = 2097152;

    use Aws\S3\S3Client;
    // use Aws\S3\Exception\S3Exception;

    // AWS Info
    $bucketName = 'picturebin';
    $IAM_KEY = 'AKIAUQ2IZ2A7GNQ7WONA';
    $IAM_SECRET = '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';

    // AWS Connection
    try {
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
                'version' => 'latest',
                'region'  => 'us-east-1'
            )
        );
    } catch (Exception $e) {

        die("Error: " . $e->getMessage());
    }


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


    $query3 = Database::$connection->prepare("SELECT * FROM `image` WHERE `user_id` = ? ");
    $query3->bind_param("s", $_SESSION['user']['id']);
    $query3->execute();
    $res = $query3->get_result();

    echo "images : " . $res->num_rows;


    // foreach ($files['tmp_name'] as $tmp_name) {
    //     $id = Database::insert_id('image');
    //     $file_name = "copy_bin/" . md5(time() . uniqid()) . ".png";

    //     $s3->putObject(
    //         array(
    //             'Bucket' => $bucketName,
    //             'Key' =>  $file_name,
    //             'SourceFile' => $tmp_name,
    //             'StorageClass' => 'REDUCED_REDUNDANCY',
    //             'ACL'   => 'public-read'
    //         )
    //     );

    //     $img_url = "https://" . $bucketName . ".s3.amazonaws.com/" . $file_name;
    //     $img_size = $files['size'];
    //     $query3 = Database::$connection->prepare("INSERT INTO `image` (`id`,`img_name`,`user_id`,`size`) values (?,?,?,?)");
    //     $query3->bind_param("isii", $id, $img_url, $_SESSION['user']['id'], $img_size);
    //     $query3->execute();
    // }

    // echo 1;
