<?php

    use SimplePie\Parse\Date;

    require_once "lib/util.php";
    $gobackURL = "index.html";

    // 文字エンコードの検証
    if (! cken($_POST)) {
        header("Location:{$gobackURL}");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="discription" content="Modern Roomへのお問い合わせページです。なんでもお気軽にお問い合わせください。">
    <meta name="robots" content="noindex, nofollow">
    <title>お問い合わせ | Modern Room</title>

    <!-- <link rel="stylesheet" href="css/normalize.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

    <!-- drawer.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.2/css/drawer.min.css"
        media="screen and (max-width:768px)">
    <link rel="stylesheet" href="../css/style.css">

    <!-- フォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poiret+One&family=Zen+Kaku+Gothic+New&display=swap"
        rel="stylesheet">

    <!-- ファビコン -->
    <link rel="icon" href="../images/favicon.ico">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="../js/common.js"></script>
</head>

<body>
    <header class="header">
        <div class="header-container">
            <h1><a href="../index.html" class="font">Modern Room</a></h1>
            <nav class="NavMenu">
                <ul>
                    <li><a href="../gallery/index.html">Gallery</a></li>
                    <li><a href="../company/index.html">Company</a></li>
                    <li><a href="./index.html">Contact</a></li>
                    <li><a href="http://localhost/my_site/blog/">Blog<i class="fa-solid fa-arrow-up-right-from-square"
                                style="color: #fff;"></i></a></li>
                    <li><a href="#">Online Shop<i class="fa-solid fa-arrow-up-right-from-square"
                                style="color: #fff;"></i></a></li>
                </ul>
            </nav>
            <div class="Toggle">
                <span></span><span></span><span></span>
            </div>
        </div>
    </header>

    <section>
        <div>
            <?php
            // 簡単なエラー処理
            $errors = [];
            if (! isset($_POST["name"]) || ($_POST["name"] === "")) {
                $errors[] = "名前が空です。";
            }
            if (! isset($_POST["furigana"]) || ($_POST["furigana"] === "")) {
                $errors[] = "フリガナが空です。";
            }
            if (! isset($_POST["mail"]) || ($_POST["mail"] === "")) {
                $errors[] = "メールアドレスが空です。";
            }
            if (! isset($_POST["messageType"]) || ! in_array($_POST["messageType"], ["商品について", "ご注文・配送について", "その他"])) {
                $errors[] = "お問い合わせ種別を選択してください。";
            }
            if (! isset($_POST["content"]) || ($_POST["content"] === "")) {
                $errors[] = "お問い合わせ内容が空です。";
            }

            // エラーがあったとき
            if (count($errors) > 0) {
                echo '<ol class="error">';
                foreach ($errors as $value) {
                    echo "<li>", $value, "</li>";
                }
                echo "</ol>";
                echo "<hr>";
                echo '<a href="', $gobackURL, '">戻る</a>';
                exit();
            }

            // データベースユーザ
            $user     = 'wpuser';
            $password = 'pw4wpuser';
            // 利用するデータベース
            $dbName = 'contact';
            // MySQLのサーバ
            $host = 'localhost';
            // MySQLのDSN文字列
            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

            $to       = "to@example.com";
            $from     = $_POST['mail'];
            $title    = $_POST['messageType'];
            $name     = $_POST['name'];
            $furigana = $_POST['furigana'];
            $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
            $content  = $_POST['content'];
            $headers  = "From: from@example.com";
            $date     = date("Y-m-d");

            $message = "名前　　: {$name}" . PHP_EOL .
                "フリガナ: {$furigana}" . PHP_EOL .
                "電話番号: {$tel}" . PHP_EOL .
                "お問い合わせ内容: {$content}" . PHP_EOL;

            // MySQLデータベースに接続する
            try {
                $pdo = new PDO($dsn, $user, $password);
                // プリペアードステートメントのエミュレーションを無効にする
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                // 例外がスローされる設定にする
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                $err = '<span class="error">エラーがありました。</span><br>';
                $err .= $e->getMessage();
                exit($err);
            }

            try {
                // トランザクションを開始する（仮の状態で進めていく）
                $pdo->beginTransaction();
                // SQL文を作る
                $sql = "INSERT INTO
                content
            VALUES (:date, :name, :furigana, :from, :tel, :title, :content)";
                // プリペアードステートメントを作る
                $stm = $pdo->prepare($sql);
                // プレースホルダに値をバインドする
                $stm->bindValue(':date', $date, PDO::PARAM_STR);
                $stm->bindValue(':name', $name, PDO::PARAM_STR);
                $stm->bindValue(':furigana', $furigana, PDO::PARAM_STR);
                $stm->bindValue(':from', $from, PDO::PARAM_STR);
                $stm->bindValue(':tel', $tel, PDO::PARAM_STR);
                $stm->bindValue(':title', $title, PDO::PARAM_STR);
                $stm->bindValue(':content', $content, PDO::PARAM_STR);
                // SQL文を実行する
                $stm->execute();
                // トランザクション処理を完了する
                $pdo->commit();
                // 結果報告
                // echo "データを追加しました。";
            } catch (Exception $e) {
                // エラーがあったならば元の状態に戻す
                $pdo->rollBack();
                echo '<span class="error">登録エラーがありました。</span><br>';
                echo $e->getMessage();
            }

            if (mb_send_mail($to, $title, $message, $headers)) {
                echo '<p class="formResult">お問い合わせ内容を送信しました。</p>';
            } else {
                echo '<p class="formResult">送信失敗しました。恐れ入りますが、内容をご確認ください。</p>';
            }
        ?>
            <p><a href="<?php echo $gobackURL; ?>" class="more-btn">戻る</a></p>
        </div>
    </section>
</body>

</html>