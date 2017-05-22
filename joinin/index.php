<?php

    require('../dbconnect.php');
    session_start();

    //重複アカウントチェックするよー！
    if (empty($error)) {
    $sql = sprintf('SELECT count(*) as cnt FROM member WHERE email="%s"',
    mysqli_real_escape_string($db, $_POST['email']));
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $table = mysqli_fetch_assoc($record);        
    if ($table['cnt'] > 0) {
        $error['email'] = 'duplicate';
        }
    }

    if (!empty($_POST)) {
//エラー項目の確認
    if ($_POST['name'] == ''){
        $error["name"] = 'blank';
    }
    if ($_POST['email'] == ''){
        $error['email'] = 'blank';
    }
    if (strlen($_POST{'password'}) < 4){
        $error['password'] = 'length';
    }
    if ($_POST['password'] == ''){
        $error['password'] = 'blank';
    }
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: check.php');        
        exit();
    }
    
    if ($_REQUEST['action'] == 'rewrite') {
        $_POST = $_SESSION['join'];
        $error['rewrite'] = true;
        }
    }
    
    
    //書き直し
    if ($_REQUEST['action'] == 'rewrite'){
        $_POST = $_SESSION['join'];    
        $error['rewrite'] = true;
    }
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
    </head>

<h2>登録フォーム</h2>
<form action="" method="post" enctype="multipart/form-data">
    <dl>
        <dt>ニックネーム<span class="required">*必須</span></dt>
        <dd><input type="text" name="name" size="35" maxlength="255">
        <?php echo htmlspecialchars($_POST['name'], ENT_QUOTES,
        'UTF-8'); ?>
        <?php if ($error['name'] == 'blank'): ?>
        <p class="error">*ニックネームを入力してください</p>
        <?php endif; ?>
        </dd>
        <dt>メールアドレス<span class='required'>必須</span></dt>
        <dd><input type="text" name="email" size="35" maxlength="255">
        <?php echo htmlspecialchars($_POST['email'], ENT_QUOTES,
        'UTF-8'); ?>
        <?php if ($error['email'] == 'blank'): ?>
        <p class="error">*メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if ($error['email'] == 'duplicate'): ?>
        <p class="error">*指定されたメールアドレスはすでに登録されています</p>
        <?php endif; ?>
        </dd>
        
        <dt>パスワード<span class="required">*必須</span></dt>
        <dd><input type="passwprd" name="password" size="10" maxlength="20"
        "<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES,
        'UTF-8'); ?>"
        <?php if ($error['password'] == 'blank'): ?>
        <p class="error">*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($error['password']==length): ?>
        <p class="error">*パスワードは4文字以上で登録してください</p>
        <?php endif; ?>
        </dd>
        <dt>アイコン</dt>
        <dd><input type="file" name="image" size="35" /></dd>
    </dl>
    <div><input type="submit" value="入力内容を確認する"></div>
</form>
        
        