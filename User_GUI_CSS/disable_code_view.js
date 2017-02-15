/**
 * Created by Abhishek Divekar on 05-06-2016.
 */
// Source: http://stackoverflow.com/questions/24319786/how-to-hide-form-code-from-view-code-inspect-element-browser
// Also see: http://stackoverflow.com/questions/737022/how-do-i-disable-right-click-on-my-web-page

$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});

$(document).keydown(function(e){
    if(e.which === 123){
       return false;
    }
});
