<?php

const CLIENT = '?p=client';
const SERVER = '?p=server';
const APP = '?p=app';
const CSS = '?p=css';
//Compiled version of VueFiles

$p = 'client';

if(isset($_GET['p'])){
	$p = $_GET['p'];
}

//client
if($p == 'client'){
	echo '<html>
  <head>
    <title>vueFiles</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="'.CSS.'" />
    <script src="http://cdn.markamations.com/vue"></script>
    <script src="http://cdn.markamations.com/jquery"></script>
  </head>
  <body>
    <div id="vueFiles">
      <input type="text" v-bind:value="currentPath" class="path" id="path" v-on:keyup="setPathFromTextEnter" /><input type="button" value="Go" v-on:click="setPathFromText" class="buttonGo"><br />
      <folder v-for="folder in folders" v-bind:folder="folder"></folder>
      <file v-for="file in files" v-bind:file="file"></file>
    </div>
    <div id="app-footer">
      <v-footer></v-footer>
    </div>
    <script src="'.APP.'"></script>
  </body>
</html>
';
}
//style.css
if($p == 'css'){
	echo "@import url('https://fonts.googleapis.com/css?family=Poppins:300,400');

.item-container{
  width:150px;
  height:150px;
  border:2px solid rgba(0,0,0,0.2);
  border-radius: 10px;
  text-align: center;
  float: left;
  display: inline;
  margin: 5px;
  transition: 0.5s;
  cursor: pointer;
  -webkit-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
  -moz-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
  box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
}

.item-container span{
  font-family: 'Poppins',sans-serif;
}

.item-container img{
  margin-top: 25px;
}
.w50{
  width:50%;
}

.path{
  margin-left: 5px;
  margin-bottom: 5px;
  padding: 5px;
  border: 1px solid rgba(0,0,0,0.5);
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  font-family: 'Poppins',sans-serif;
}

.buttonGo{
  margin-bottom: 5px;
  padding: 5px;
  border: 1px solid rgba(0,0,0,0.5);
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
  font-family: 'Poppins',sans-serif;
  cursor: pointer;
}

#app-footer{
  clear: both;
  width: 100%;
  text-align: center;
}

.linkback,a{
  font-family: 'Poppins',sans-serif;
  font-weight: 300;
  font-size: 10px;
  color:grey;
  text-decoration: none;
}
";
}
//app.js
if($p == 'app'){
	echo "Vue.component('file',{
  props : ['file'],
  template : '<div class=\"item-container file\"><img src=\"///png.icons8.com/file/office/100\" class=\"w50\"><br /><span>{{file.name}}</span></div>',
});

Vue.component('folder',{
  props : ['folder'],
  template : '<div class=\"item-container folder\" v-on:click=\"setPathFromFile(folder.name)\"><img src=\"///png.icons8.com/folder/office/100\" class=\"w50\"><br /><span>{{folder.name}}</span></div>',
});

Vue.component('v-footer',{
  template : '<div class=\"linkback\"><br /><a href=\"https://github.com/MooseTheCoder\">By Moose</a> | <a href=\"https://icons8.com/\">Icons by icons8</a></div>',
});

var appFooter = new Vue({
  el : '#app-footer',
});

var vueFiles = new Vue({
  el : '#vueFiles',
  data:{
    currentPath : '/',
    theme : 'color',
    files : [
    ],
    folders : [
    ],
  },
  methods : {
    setPathFromText : function(){
      var p = $('#path').val();
      var lastChar = p[p.length - 1];
      var rpcc = '';
      if(lastChar != '/'){
        rpcc = '/';
      }
      this.currentPath = p+rpcc;
      clearArrays();
      processPath();
    },
    setPathFromTextEnter : function(event){
      if(event.key == 'Enter'){
        var p = $('#path').val();
        var lastChar = p[p.length - 1];
        var rpcc = '';
        if(lastChar != '/'){
          rpcc = '/';
        }
        this.currentPath = p+rpcc;
        clearArrays();
        processPath();
      }
    },
  }
});

function setPathFromFile(p){
  window.vueFiles.currentPath+=p+'/';
  clearArrays();
  processPath();
}

function clearArrays(){
  window.vueFiles.folders = [];
  window.vueFiles.files = [];
}

function processPath(){
  $.ajax({
    url : '".SERVER."&a=scan&dir='+window.vueFiles.currentPath,
    success : function(data){
      var res = $.parseJSON(data);
      if(res.ack == 'true'){
        var folders = $.parseJSON(res.folders);
        var files = $.parseJSON(res.files);
        $.each(files,function(i){window.vueFiles.files.push({name : files[i]});});
        $.each(folders,function(i){window.vueFiles.folders.push({name : folders[i]});});
      }
    }
  });
}

$(document).ready(function(){
  processPath();
});
";
}
//server.php

if($p == 'server'){
	
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
}