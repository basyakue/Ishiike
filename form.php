<?php

//すべての曜日がこのコードになってます

$err_msg1 = "";
$err_msg2 = "";
$err_msg3 = "";
$err_msg4 = "";
$message ="";
$class_name = ( isset( $_POST["class_name"] ) === true ) ?$_POST["class_name"]: "";
$timetable = ( isset( $_POST["timetable"] ) == true ) ?$_POST["timetable"]: "";
$teacher_name = ( isset( $_POST["teacher_name"] ) == true ) ?$_POST["teacher_name"]: "";
$comment  = ( isset( $_POST["comment"] )  === true ) ?  trim($_POST["comment"])  : "";


if (  isset($_POST["send"] ) ===  true ) {
    if ( $class_name   === "" ) $err_msg1 = "授業の名前を入力してください"; 

    if ( $timetable === "" ) $err_msg2 = "何時間目か入力してください";

    if ( $teacher_name === "" ) $err_msg3 = "先生の名前を入力してください";
 
    if ( $comment  === "" )  $err_msg4 = "コメントを入力してください";
 
    if( $err_msg1 === "" && $err_msg2 === "" && $err_msg3 === "" && $err_msg4 === "" ){
        $fp = fopen( "mon_data.txt" ,"a" );
        fwrite( $fp ,  $class_name."\t".$timetable."\t".$teacher_name."\t".$comment."\n");
        $message = "書き込みに成功しました。";
    }
 
}
 
$fp = fopen("mon_data.txt","r");
 
$dataArr = array();
while( $res = fgets( $fp)){
    $tmp = explode("\t",$res);
    $arr = array(
        "class_name"=>$tmp[0],
        "timetable"=>$tmp[1],
        "teacher_name"=>$tmp[2],
        "comment"=>$tmp[3]
    );
    $dataArr[]= $arr;
} 
 
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="mon.css">
        <title>投稿フォーム</title>
    </head>
    <body>
        <header>
            <h1>月曜日</h1>
        </header>
        <?php echo $message; ?>
        <form method="post" action="">
        授業名：<input type="text" name="class_name" value="<?php echo $class_name; ?>" >
            <?php echo $err_msg1; ?><br>
        時間：<input type="text" name="timetable" value="<?php echo $timetable; ?>" >
            <?php echo $err_msg2; ?><br>
        先生の名前：<input type="text" name="teacher_name" value="<?php echo $teacher_name; ?>" >
            <?php echo $err_msg3; ?><br>
        コメント(※改行するとバグが発生する報告があります)：<textarea  name="comment" rows="4" cols="40"><?php echo $comment; ?></textarea>
            <?php echo $err_msg2; ?><br>
<br>
          <input type="submit" name="send" value="クリック" >
        </form>
        <dl>
         <?php foreach( $dataArr as $data ):?>
         <p>★<span><?php echo $data["class_name"]; ?></span> : <span><?php echo $data["timetable"]; ?></span> : <span><?php echo $data["teacher_name"]; ?></span><br>
         <span><?php echo $data["comment"]; ?></span></p>
        <?php endforeach;?>
</dl>
<br>
        <a href="http://tmu-minamiosawa.sakura.ne.jp/ishiike/home.html">ホーム</a>
    </body>
</html>
