<?php
require_once('includes/connection.php');
require_once('includes/functions.php');
/**
 * @author Michael Hancoski
 * @copyright 2013
 */
 
$message = '';
$errors = '';
 /*
 $errors = array();
if(!isset($_POST['menu_name']) || empty($_POST['menu_name'])){
    $errors[] = 'menu_name';
}
if(!isset($_POST['postion']) || empty($_POST['position'])){
    $errors[] = 'position';
}
*/
$errors = check_form();
if(!empty($errors)){
    $message .= get_errors($errors,$_POST['submit']);
    redirect_to('new_subject.php?errors=' . urlencode($message));
}

$menu_name = htmlentities(mysql_prep($_POST['menu_name']));
$position = mysql_prep($_POST['position']);
$visible = mysql_prep($_POST['visible']);



$query = "INSERT INTO subjects (
            menu_name, position, visible
            ) VALUES (
            '{$menu_name}', {$position}, {$visible}
            )";
            
if (mysql_query($query,$connection)){
    //Success!
    redirect_to('edit_subject.php?subj=' . mysql_insert_id());

}else{
    // Error message
    $message .= get_errors($errors,$_POST['submit']);
    redirect_to('new_subject.php?errors=' . urlencode($message));
}

mysql_close($connection);
?>