
<?php

  $filename_helper = json_encode($_REQUEST['sonaid']); 


  $filename = "dataJuly/". $_REQUEST["sonaid"] . "_feedback.csv";


  $fileexists = file_exists($filename);
  $index = 0;
  while($fileexists) {
    $index = $index + 1;
    $addToFilename =  $index . "_";
    $filename =  "dataJuly/" . $addToFilename . $_REQUEST["sonaid"] . "_feedback.csv";;
    $fileexists = file_exists($filename);
  }

  
  //$filename = "data/11123SONA_feedback.csv";

  $csvcontents = "";
  foreach ($_REQUEST as $key => $value) {
    $csvcontents = $csvcontents . $key . "," ;
  }
  $csvcontents = $csvcontents . "\n";
  foreach ($_REQUEST as $key => $value) {
    $csvcontents = $csvcontents . $value . "," ;
  }
  //htmlspecialchars removes the html tags that a participant may have put in their responses (e.g. if they wrote "<script></script>" it will convert it to something harmless that html doesnt recognize)
  file_put_contents($filename, htmlspecialchars($csvcontents));

  if (TRUE) { //wow i dont remember putting this pointless if statement lmao
    //this link comes from SONA- you copy the link under "Completion URLs" for "(client-side)" and remove the XXXX at the end
    header("Location:https://uwaterloo.sona-systems.com/webstudy_credit.aspx?experiment_id=5128&credit_token=6cf36c8967f94980bfab2d5f6320980d&survey_code=". $_REQUEST['sonaid']);
 
    exit();
  }
 ?>
