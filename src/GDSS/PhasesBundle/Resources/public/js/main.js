$("document").ready(function () {
    var id = {{ id }}
    setInterval(function(){
        $(".divscript").load('http://www.myproject.com:8080/app_dev.php/platform/sujet_vue/sujet/3')
    }, 2000);
});