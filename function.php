<?php 
require_once 'db.php';

if($dormcode = $_GET['dormcode']){
	echo readname($dormcode);
}
if($class = $_GET['class']){
	echo readdorm($class);
}
if($content = $_GET['content'] and $date = $_GET['date'] and $dormcode = $_GET['dorm']){
	submit($date,$dormcode,$content,"17国金");
}
if($_GET['display']){
	$date1 = date("Y-m-d");
	echo display($date1,"17国金");
}
if($_GET['yesterday']){
	$date = date("Y-m-d",strtotime("-1 day"));
	echo display($date,"17国金");
}

//根据班级查找宿舍
function readdorm($class="17国金"){
    $DB = new DB;
    $conn = $DB ->getDB();
	$sql = "select distinct dormcode from dorm where class='{$class}' order by dormcode ASC";
	$res = $conn->query($sql);
	while($info = $res-> fetch_assoc()){
		$infoarray[] = $info['dormcode'];
	}
    $conn->close();
	//echo "数据库已关闭";
	echo json_encode($infoarray,JSON_UNESCAPED_UNICODE);
}

//根据宿舍号查找宿舍成员
function readname($dormcode){
    $DB = new DB;
    $conn = $DB ->getDB();
	$sql = "select name from dorm where dormcode = '{$dormcode}'";
	$res = $conn -> query($sql);
	while($info = $res-> fetch_assoc()){
		$infoarray[] = $info['name'];
	}
	
	echo json_encode($infoarray,JSON_UNESCAPED_UNICODE);
	$conn->close();
	//echo "数据库已关闭";
}

//提交打卡信息
function submit($date,$dormcode,$content,$class){
	try {
		// 预处理及绑定
        $DB = new DB;
        $conn = $DB ->getDB();
		$stmt = $conn->prepare("INSERT INTO daka (date, dormcode, content, class) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $date, $dormcode, $content,$class);
		$stmt->execute();
		$conn->close();
		echo "success";
	}
	catch(PDOException $e)
	{
	    echo "Error: " . $e->getMessage();
	}
}

//展示班级当天打卡内容
function display($date,$class){
    $DB = new DB;
	$conn = $DB ->getDB();
	// 只显示最新的打卡情况
	$sql = "select dormcode,content from daka where id in (select max(id) from daka group by dormcode) and date = '{$date}' and class='{$class}' order by dormcode ASC";
	//$sql = "select distinct dormcode,content from daka where date = '{$date}' and class='{$class}' order by dormcode ASC";
	$res = $conn -> query($sql);
	while($info = $res-> fetch_assoc()){
		$infoarray[] = $info;
	}

	echo json_encode($infoarray,JSON_UNESCAPED_UNICODE);

	$conn->close();
}
 ?>