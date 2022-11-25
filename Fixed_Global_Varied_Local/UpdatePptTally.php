<?php
$post_data = json_decode(file_get_contents('php://input'), true);

$filename = $post_data['filename'];
$tracked = $post_data['track'];
$file = file_get_contents($filename);
$array = json_decode($file, true);
foreach($array as $condition => $option) {
  foreach($option as $key => $value) {
    if ($key == $tracked[$condition]) {
      $array[$condition][$key] = strval(intval($value) + 1);
    }
  }
}
file_put_contents($filename, json_encode($array));
echo json_encode($array);
?>
