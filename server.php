<?php

function getDir($dir){
  return array_diff(scandir($dir),['.','..']);
}

if(isset($_GET['a']) && $_GET['a'] == 'scan'){
  if(!isset($_GET['dir']) || $_GET['dir'] == ''){
    echo json_encode(['ack'=>'false']);
    exit;
  }
  $stuff = getDir($_GET['dir']);
  $files = [];
  $folders = [];
  foreach($stuff as $thing){
    if(is_dir($_GET['dir'].$thing)){
      $folders[]=$thing;
    }else{
      $files[]=$thing;
    }
  }
  echo json_encode(['ack'=>'true','files'=>json_encode($files),'folders'=>json_encode($folders)]);
}
