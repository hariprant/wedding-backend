<?php
session_start();
include 'koneksi.php';
// include 'csrf.php';
 
$query = "
SELECT * FROM tbl_comment 
WHERE parent_comment_id = '0' 
ORDER BY comment_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
 $output .= '
 <div class="card card-default mb-2" style="font-family: Amiri, serif;">
  <div class="card-header">
    <div style="color: #b38126;">
        <div class="row">
            <div class="col-4"><b>'.$row["comment_sender_name"].'</b></div>
            <div class="col-8 text-right"><small class="text-mute">'.date("H:i, d-m-Y", strtotime($row["date"])).'</small></div>
        </div>
    </div>
  </div>
  <div class="card-body" style="color: #726558;">'.$row["comment"].'</div>
 </div>
 ';
 // <div class="card-footer" align="right"><button type="button" class="btn btn-primary reply" id="'.$row["comment_id"].'">Reply</button></div>
 $output .= get_reply_comment($connect, $row["comment_id"]);
}
echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
 $query = "
 SELECT * FROM tbl_comment WHERE parent_comment_id = '".$parent_id."'
 ";
 $output = '';
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $count = $statement->rowCount();
 if($parent_id == 0)
 {
  $marginleft = 0;
 }
 else
 {
  $marginleft = $marginleft + 48;
 }
 if($count > 0)
 {
  foreach($result as $row)
  {
   $output .= '
   <div class="card card-default mb-4" style="margin-left:'.$marginleft.'px">
    <div class="card-header">By <b>'.$row["comment_sender_name"].'</b> on <i>'.$row["date"].'</i></div>
    <div class="card-body">'.$row["comment"].'</div>
   </div>
   ';
  //  <div class="card-footer" align="right"><button type="button" class="btn btn-primary reply" id="'.$row["comment_id"].'">Reply</button></div>
   $output .= get_reply_comment($connect, $row["comment_id"], $marginleft);
  }
 }
 return $output;
}
?>