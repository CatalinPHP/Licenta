// (function ($) {
//     function openNav() {
//         $('.openMenu').click(function () {
//             $('.settings_sidebar').css('width', '250px')
//         })
//     }
//
//     function closeNav(){
//         $('.closebtn').click(function () {
//             $('.settings_sidebar').css('width', '0px')
//         })
//
//     }
//     $(document).ready(function () {
//         openNav();
//         closeNav();
//     })
// })
// (jQuery);



var aContainer = document.getElementById('settingsMenu');
var menuitems = aContainer.getElementsByClassName('a');
for (var i = 0; i < menuitems.length ; i++){
    console.log(menuitems[i]);
    menuitems[i].addEventListener('click',function () {
        var current = document.getElementsByClassName('active');
        current[0].className = current[0].className.replace('active',"");
        this.className += " active";
        this.id;
        displayForm(this.id);
        closeNav();
    })
}

/* ============== functions ========================== */

function displayForm(idItemMenuClick) {
    var settingsContent = document.getElementById('setting_content');
    var forms = settingsContent.getElementsByClassName("form");
    console.log(settingsContent);
    for (var i = 0; i < forms.length ; i++) {
        forms[i].style.display = "none";
        }
        settingsContent.getElementsByClassName(idItemMenuClick)[0].style.display = "block";

}

function openNav(){
    var menuSide = document.getElementsByClassName('settings_sidebar');
    menuSide[0].style.width = '250px';
}
function closeNav(){
    var menuSide = document.getElementsByClassName('settings_sidebar');
    menuSide[0].style.width = '0px';
}
