 <?php
    session_start();
    require_once "./connection.php";

    require_once "./aws/aws-autoloader.php";

    use Aws\S3\S3Client;
    use Aws\S3\Exception\S3Exception;

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
    foreach ($files['tmp_name'] as $tmp_name) {
        $id = Database::insert_id('image');
        $file_name = "copy_bin/" . md5(time() . uniqid()) . ".png";

        $s3->putObject(
            array(
                'Bucket' => $bucketName,
                'Key' =>  $file_name,
                'SourceFile' => $tmp_name,
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'ACL'   => 'public-read'
            )
        );
        // https://picturebin.s3.amazonaws.com/copy_bin/572754defefb0a1dba80c5731932521c.png
        // move_uploaded_file($tmp_name, $file_name);
        $img_url = "https://" . $bucketName . ".s3.amazonaws.com/" . $file_name;
        $query3 = Database::$connection->prepare("INSERT INTO `image` (`id`,`img_name`,`user_id`) values (?,?,?)");
        $query3->bind_param("isi", $id, $img_url, $_SESSION['user']['id']);
        $query3->execute();
    }

    echo 1;
