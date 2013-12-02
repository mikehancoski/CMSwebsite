<?php

/**
 * @author Michael Hancoski
 * @copyright 2013
 * functions
 */
function mysql_prep($value){
    $magic_quotes_active = get_magic_quotes_gpc();
    $new_enough_php = function_exists('mysql_real_escape_string');
    
    if($new_enough_php){
        if($magic_quotes_active){
            $value = stripcslashes($value);
        }
        $value = mysql_real_escape_string($value);
    }else{
        if(!$magic_quotes_active){
            $value = addslashes($value);
        }
    }
    return $value;
}

function redirect_to($location = NULL){
    if($location != NULL){
        header('location: ' . $location);
        exit;
    }
}


function confirm_query($result_set){
    if(!$result_set){
        die('Database query failed: ' . mysql_error());
    }
}
function get_all_subjects(){
    global $connection;
    $query = 'SELECT * 
        FROM subjects 
        ORDER BY position ASC';
    $subject_set = mysql_query($query, $connection);
    confirm_query($subject_set);
    return $subject_set;
}

function get_pages_for_subject($subject_id){
    global $connection;
    $query = 'SELECT * 
        FROM pages 
        WHERE subject_id =' . $subject_id . ' 
        ORDER BY position ASC';
    $page_set = mysql_query($query, $connection);
    confirm_query($page_set);
    return $page_set;
}

function get_subject_by_id($subject_id){
    global $connection;
    $query = 'SELECT * ';
    $query .= 'FROM subjects ';
    $query .= 'WHERE id=' . $subject_id;
    $query .= ' LIMIT 1';
    
    $result_set = mysql_query($query,$connection);
    confirm_query($result_set);
    
    // if no rows are returned the fech array will reurn false 
    if($subject = mysql_fetch_array($result_set)){
        return $subject;
    }else{
        return NULL;    
    }
}

function get_page_by_id($page_id){
    global $connection;
    $query = 'SELECT * ';
    $query .= 'FROM pages ';
    $query .= 'WHERE id=' . $page_id;
    $query .= ' LIMIT 1';
    
    $result_set = mysql_query($query,$connection);
    confirm_query($result_set);
    
    // if no rows are returned the fech array will reurn false 
    if($page = mysql_fetch_array($result_set)){
        return $page;
    }else{
        return NULL;    
    }
}

function find_selected_page() {
    global $sel_subject;
    global $con_page;
    if(isset($_GET['subj']) && intval($_GET['subj'] != 0)){
        $sel_subject = get_subject_by_id($_GET['subj']);
        $con_page =array('id'=>0,'content'=>'');
    }elseif (isset($_GET['page']) && intval($_GET['page'] != 0)){
        $con_page = get_page_by_id($_GET['page']);
        $sel_subject = NULL; 
    }else{
        $sel_subject = array('id'=>0,'menu_name'=>'Welcome');
        $con_page =array('id'=>0,'content'=>'CMS example site');
        
    }
    
}

function navigation_public($sel_subject,$con_page, $public = true){
    $output = '<ul class="subjects">';
    $subject_set = get_all_subjects();
                            
    for($subject = mysql_fetch_array($subject_set); isset($subject[1]) != false; $subject = mysql_fetch_array($subject_set)){
        if($subject['id'] == $sel_subject['id']){
            $selected = ' class="selected"';
            }else{
                $selected = '';
            }
            $output .= '<li' . $selected .'><a href="index.php?subj='. urlencode($subject['id']) . '">' . $subject['menu_name'] . '</a></li>';
            if($subject['id'] == $sel_subject['id']){
                $page_set = get_pages_for_subject($subject['id']);
                
                $output .= '<ul class="pages">';
                for($page = mysql_fetch_array($page_set); isset($page[1]) != false; $page = mysql_fetch_array($page_set)){
                    $output .= '<li';
                    if($page['id'] == $con_page['id']){
                        $output .= ' class="selected"';
                    }
                    $output .= '><a href="index.php?page=' . urlencode($page['id']) .'">' . $page['menu_name'] . '</a></li>';
                }
                $output .= '</ul>';
            }
        }
    $output .= '</ul>';
    $output .= ' <br />';
    //$output .= '<a href="new_subject.php">+ Add a new subject</a>';
    return $output;
}

/* --- Form Checks for validation */
function check_form(){
    $check_array = array('menu_name', 'position','visible');// list of requiered fields
    $length_check = array('menu_name'=>30);// filed name => max length
    $errors = array();
    foreach($check_array AS $fieldname){
        if(!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && !is_numeric($_POST[$fieldname]))){
            $errors[] = $fieldname;
        }
    }
    foreach($length_check as $fieldname => $maxlength){
        if(isset($_POST[$fieldname])){
            if(strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength){
                $errors[] = $fieldname;
            }
        }
    }
    return $errors;
}
?>