
$this -> _form  -> addElement('select', 'displayStrategy', get_string('displayStrategy', 'xForum'), $displayStrategy, array('onchange' => 'javascript: function loadStrategy(selVal){

$.ajax({
 type: "POST",
 url: "../mod/xForum/action/displayStrategy.php",
 data: { class: selVal }
}).done(function( msg ) { 
 console.log("Strategy was executed");
}); }; loadStrategy(this.value);') ));

window.onload = init;

function init() {

    // When a select is changed, look for the students based on the department id
    // and display on the dropdown students select
    $('#id_courseid').change(function() {
        $('#id_testid').load('/report/lastaccess/getter.php?courseid=' + $('#id_courseid').val());                                
    });

}