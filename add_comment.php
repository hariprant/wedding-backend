<?php
session_start();
include 'koneksi.php';
// include 'csrf.php';
 
function filter($text){
	return $id = stripslashes(strip_tags(htmlspecialchars($text, ENT_QUOTES)));
}
 
$error = '';
$comment_name = '';
$comment_content = '';

if(empty($_POST["comment_name"]))
{
 $error .= '<p class="text-danger">Nama harap diisi</p>';
}
else
{
 $comment_name = filter($_POST["comment_name"]);
}

if(empty($_POST["comment_content"]))
{
 $error .= '<p class="text-danger">Ucapan harap diisi</p>';
}
else
{
 $comment_content = filter($_POST["comment_content"]);
}

if($error == '')
{
 $query = "
 INSERT INTO tbl_comment 
 (parent_comment_id, comment, comment_sender_name) 
 VALUES (:parent_comment_id, :comment, :comment_sender_name)
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':parent_comment_id' => filter($_POST["comment_id"]),
   ':comment'    => $comment_content,
   ':comment_sender_name' => $comment_name
  )
 );
 $error = '<label class="text-success">Terima Kasih ^_^</label>';
}

$data = array(
 'error'  => $error
);

echo json_encode($data);