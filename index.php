    <?php
    session_start();
    require('dbconnect.php');
    
    if (isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();
    
    $sql = sprintf('SELECT * FROM member WHERE id=%d',
        mysqli_real_escape_string($db, $_SESSION['id'])
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $member = mysqli_fetch_assoc($record);
    } else {
        //ログインしていない
        header('Location: login.php');
        exit();
    }
    
    //投稿を記憶する
    if (!empty($_POST)) {
        if ($_POST['message'] != '') {
            $sql = sprintf('INSERT INTO posts SET member_id =%d,
            message="%s", replay_post_id =%d ,created=NOW()',
            mysqli_real_escape_string($db, $member['id']),
            mysqli_real_escape_string($db, $_POST['message']),
            mysqli_real_escape_string($db, $_POST['replay_post_id'])
            );
            mysqli_query($db, $sql) or die(mysqli_eror($db));
            header('Location: index.php');
            exit();        
            }
        }
        
        //投稿を取得する
        $sql = sprintf('SELECT m.name, m.picture, p.* FROM member m,
        posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
        $posts = mysqli_query($db, $sql) or die(mysqli_error($db));
        
        //返信の場合
        if (isset($_REQUEST['res'])) {
            $sql = sprintf('SELECT m.name, m.picture, p.* FROM member m,
            posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
            mysqli_real_escape_string($db, $_REQUEST['res'])
            );
            $record = mysqli_query($db, $sql) or die(mysqli_error($db));
            $table = mysqli_fetch_assoc($record);
            $message = '@'. $table['name']. ''. $table['message'];
        }
    ?>   
    
    
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>一言掲示板</title>
    </head>
    
    
<div class="container">
  <div class="wrapper col-md-8 col-md-offset-2 col-sm-10">    
    <div id = "content">
<form action="" method="post">
    <dl>
        <dt><?php echo htmlspecialchars($member['name']);?> 何してる？</dt>
    </dd>    
    <textarea name='message' col='50' rows="5">
    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></textarea>
    <input type="hidden" name="replay_post_id" 
    value="<?php echo htmlspecialchars($_REQUEST['res'], ENT_QUOTES, "UTF-8");?>" >
    </dd>
    </dl>
    </div>
    <input type="submit" value="投稿する" />
</form>
   
    
    
    <?php
    while($post = mysqli_fetch_assoc($posts)):
    ?>
    
        <div class="msg">
        <img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>" width="48" height="48"
        alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES, "UTF-8"); ?>
        <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'UTF-8'); ?><span class="name">
        (<?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>)</span></p>
        [<a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8'); ?>">Re</a>}</p>
        <p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'UTF-8'); ?></p>        
        </div>
     <?php
    endwhile;
    ?>
    </div>
    </div>