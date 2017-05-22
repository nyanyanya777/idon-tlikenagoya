<?php
    require('dbconnect.php');
    
    session_start();

    if ($_POST['email'] !='' && $_POST['password'] !='') {
        $sql = sprintf('SELECT * FROM member WHERE email="%s" AND password="%s"',
            mysqli_real_escape_string($db, $_POST['email']),
            mysqli_real_escape_string($db, sha1($_POST['password']))
        );
        $record = mysqli_query($db, $sql) or die(mysql_error($db));
        if($table = mysqli_fetch_assoc($record)){
            //ログイン成功
            $_SESSION['id'] = $table['id'];
            $_SESSION['time'] = time();
            header('Location: index.php');
            exit();
        }else{
            $error['join'] = 'failed';
        }
    }else{
        $error['login'] = 'blank';
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
    </head>

<div id="lead">
    <p>ログインフォーム</p>
    <p>入会手続きがまだの方はこちら</p>
    <p>&raquo;<a href="joinin/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
        <dl>
            <dt>メールアドレス</dt>
            <dd>
            <input type="text" name="email" size="35" maxlength="255" />
            </dd>
            <dt>パスワード</dt>
            <dd>
            <input type="password" name="password" size="15" maxlength="255" />
            </dd>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回からは自動的にログインする</label>
            </dd>
        </dl>
        <div><input type="submit" value="ログインする" /></div>
        </form>