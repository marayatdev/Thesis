<?php
include '../components/connect.php';
$id = $_POST['id'];
$name = $_POST['name'];
$msg = $_POST['msg'];
$purl = $_POST['purl'];
if($name != "" && $msg != "" && $purl != ""){
	$sql = $conn->query("INSERT INTO discussion (parent_comment, student, post,purl )
			VALUES ('$id', '$name', '$msg','$purl')");
	echo json_encode(array("statusCode"=>200));
}
else{
	echo json_encode(array("statusCode"=>201));
}
$conn = null;

?>