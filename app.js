Vue.component('file',{
  props : ['file'],
  template : '<div class="item-container file"><img src="///png.icons8.com/file/office/100" class="w50"><br /><span>{{file.name}}</span></div>',
});

Vue.component('folder',{
  props : ['folder'],
  template : '<div class="item-container folder" v-on:click="setPathFromFile(folder.name)"><img src="///png.icons8.com/folder/office/100" class="w50"><br /><span>{{folder.name}}</span></div>',
});

Vue.component('v-footer',{
  template : '<div class="linkback"><br /><a href="https://github.com/MooseTheCoder">By Moose</a> | <a href="https://icons8.com/">Icons by icons8</a></div>',
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
    url : 'server.php?a=scan&dir='+window.vueFiles.currentPath,
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
