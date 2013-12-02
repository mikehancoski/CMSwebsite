<?php
require_once('includes/connection.php');
require_once('includes/functions.php');
    
if(intval($_GET['subj'] == 0)){
    redirect_to('content.php');
}

$id = mysql_prep($_GET['subj']);
if($subject = get_subject_by_id($id)){
    $query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
    $result = mysql_query($query,$connection);
    
    if (mysql_affected_rows() == 1){
        redirect_to('content.php');
    }else {
        // delete failed
        echo '<p>Subject delete failed</p>';
        echo '<p>' . mysql_error() . '<p/>';
        echo '<a href="content.php">Return to main page</a>'; 
    }
}else{
    //subject did not exist
    redirect_to('content.php');
}
/**
 * @author Michael Hancoski
 * @copyright 2013
 */


mysql_close($connection);
?>