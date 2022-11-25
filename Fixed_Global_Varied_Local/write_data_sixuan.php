<?php
$post_data = json_decode(file_get_contents('php://input'), true);
// the directory "data" must be writable by the server
//$name = $post_data['protocol']. "/data/" .$post_data['filename'] . ".csv";
$name = "dataJuly/" .$post_data['filename'] . ".csv";
echo $name;

$fileexists = file_exists($name);
$index = 0;
while($fileexists) {
  $index = $index + 1;
  $addToFilename =  $index . "_";
  $name =  "dataJuly/" . $addToFilename . $post_data['filename'].".csv";
  $fileexists = file_exists($name);
}

// while($fileexists) {
//   $index = $index + 1;
//   $addToFilename =  $index . "_";
//   $name =  $post_data['protocol']."/data/" . $addToFilename . $post_data['filename'].".csv";
//   $name = $post_data['filepath'] . $addToFilename . $post_data['filename'];
//   $fileexists = file_exists($name);
// }


$data = $post_data['filedata'];
// write the file to disk
$file   = fopen($name, "w");

$pieces = str_split($data, 1024 * 4);
foreach ($pieces as $piece) {
    fwrite($file, $piece, strlen($piece));
}
fclose($file);
file_put_contents($name, $data);
?>
