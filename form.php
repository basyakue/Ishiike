<?php

//すべての曜日がこのコードになってます


//書き込まれたテキストの関数と何も書き込まれなかった場合のエラーメッセージの関数を最初に定義
$err_msg1 = "";
$err_msg2 = "";
$err_msg3 = "";
$err_msg4 = "";
$message ="";
$class_name = ( isset( $_POST["class_name"] ) === true ) ?$_POST["class_name"]: "";
$timetable = ( isset( $_POST["timetable"] ) == true ) ?$_POST["timetable"]: "";
$teacher_name = ( isset( $_POST["teacher_name"] ) == true ) ?$_POST["teacher_name"]: "";
$comment  = ( isset( $_POST["comment"] )  === true ) ?  trim($_POST["comment"])  : "";

//改行を削除
//テキストデータを１行ずつ取得して表示するため改行不可(改善したい）
$comment = str_replace(array("\r\n", "\r", "\n"), '', $comment);

//すべての項目に書き込まれたか判定
//足りている場合テキストファイルに書き込む
//足りない場合エラーメッセージを代入して表示させる
if (  isset($_POST["send"] ) ===  true ) {
    if ( $class_name   === "" ) $err_msg1 = "授業の名前を入力してください"; 

    if ( $timetable === "" ) $err_msg2 = "何時間目か入力してください";

    if ( $teacher_name === "" ) $err_msg3 = "先生の名前を入力してください";
 
    if ( $comment  === "" )  $err_msg4 = "コメントを入力してください";
 
    if( $err_msg1 === "" && $err_msg2 === "" && $err_msg3 === "" && $err_msg4 === "" ){
        $fp = fopen( "mon_data.txt" ,"a" );
        fwrite( $fp ,  $class_name."\t".$timetable."\t".$teacher_name."\t".$comment."\n");
        $message = "書き込みに成功しました。";
        OutPutlog($comment); //ログデータを書き込み
    }
 
}
//テキストファイルの読み込み
$fp = fopen("mon_data.txt","r");
 
//テキストデータを１行ずつ取得し空白で分けて配列に
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
 
//書き込まれたときのログを保存する関数
function OutPutlog($string)
{
	
	$filename = "mon_log.txt"; //ログファイル名
	$time = date("Y/m/d H:i"); //アクセス時刻
	$ip = getenv("REMOTE_ADDR"); //IPアドレス
	$host = getenv("REMOTE_HOST"); //ホスト名
	$referer = getenv("HTTP_REFERER"); //リファラ（遷移元ページ）
	$uri = getenv("REQUEST_URI"); //URI取得
	$requestbrowser=$_SERVER['HTTP_USER_AGENT'];//ブラウザ情報の取得
	$requestMethod=$_SERVER['REQUEST_METHOD'];//リクエストメソッドの取得
	
	//ログ本文
	$log = "\n---------------------------------".
			"\nDATE:".$time .
			"\nIP:". $ip .
			"\nHOST:". $host. 
			"\nURI:". $uri.
			"\nREFERER:". $referer.
			"\nBROWSER:". $requestbrowser. 
            "\nMETHOD:". $requestMethod.
            "\nCOMMENT:". $string;
	
	//ログ書き込み
	$fp = fopen($filename, "a");
	fputs($fp, $log);
	fclose($fp);
	
	//echo $log;
}
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="mon.css">
        <title>石池｜Ishiike 月曜日</title>
        <link rel="icon" href="ishiike_logo.png" type="image/png">
    </head>
    <body>
        <header>
        <h1><a href="http://tmu-minamiosawa.sakura.ne.jp/ishiike/home.html"><img src="ishiike_logo.png" width="210px" height="100px"></a>【月曜日】</h1>
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
            <!-- 配列をそれぞれ表示 -->
         <?php foreach( $dataArr as $data ):?>
         <p>★<span><?php echo htmlspecialchars($data["class_name"]); ?></span> : <span><?php echo htmlspecialchars($data["timetable"]); ?></span> : <span><?php echo htmlspecialchars($data["teacher_name"]); ?></span><br>
         <span><?php echo htmlspecialchars($data["comment"]); ?></span></p>
        <?php endforeach;?>
</dl>
<br>
        <a href="https://ishiike.matrix.jp/">ホーム</a>
    </body>
</html>
