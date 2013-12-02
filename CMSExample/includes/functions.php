<?php

/**
 * @author Michael Hancoski
 * @copyright 2013
 * functions
 */
 
 if(!$includes){die('Access Denied');}
 
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
function get_all_subjects($public = true){
    global $connection;
    $query = 'SELECT * 
        FROM subjects ';
    if($public){
        $query .= 'WHERE visible = 1 ';
    }
    $query .= 'ORDER BY position ASC';
    $subject_set = mysql_query($query, $connection);
    confirm_query($subject_set);
    return $subject_set;
}

function get_pages_for_subject($subject_id, $public = true){
    global $connection;
    $query = 'SELECT * 
        FROM pages ';
    $query .= 'WHERE subject_id = ' . $subject_id . ' ';
    if($public){ 
        $query .= 'AND visible = 1 ';
    }
    $query .= 'ORDER BY position ASC';
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
        $con_page =get_default_page($sel_subject['id']);
    }elseif (isset($_GET['page']) && intval($_GET['page'] != 0)){
        $con_page = get_page_by_id($_GET['page']);
        $sel_subject = NULL; 
    }else{
        $sel_subject = array('id'=>0,'menu_name'=>'Welcome');
        $con_page =array('id'=>0,'content'=>'CMS example site');
        
    }
    
}

function get_default_page($subject_id){
    $page_set = get_pages_for_subject($subject_id,true);
    if($first_page = mysql_fetch_array($page_set)){
        return $first_page;
    }else{
        return NULL;
    }
}

/* --- staff navigation */
function navigation($sel_subject,$con_page, $public = false){
    $output = '<ul class="subjects">';
    $subject_set = get_all_subjects($public = false);
                            
    for($subject = mysql_fetch_array($subject_set); isset($subject[1]) != false; $subject = mysql_fetch_array($subject_set)){
        if($subject['id'] == $sel_subject['id']){
            $selected = ' class="selected"';
            }else{
                $selected = '';
            }
            $output .= '<li' . $selected .'><a href="edit_subject.php?subj='. urlencode($subject['id']) . '">' . $subject['menu_name'] . '</a></li>';
            $page_set = get_pages_for_subject($subject['id'], $public = false);
            
            $output .= '<ul class="pages">';
            for($page = mysql_fetch_array($page_set); isset($page[1]) != false; $page = mysql_fetch_array($page_set)){
                $output .= '<li';
                if($page['id'] == $con_page['id']){
                    $output .= ' class="selected"';
                }
                $output .= '><a href="content.php?page=' . urlencode($page['id']) .'">' . $page['menu_name'] . '</a></li>';
            }
            $output .= '</ul>';
        }
    $output .= '</ul>';
    $output .= ' <br />';
    $output .= '<a href="new_subject.php">+ Add a new subject</a>';
    $output .= ' <br />';
    $output .= '<a href="logout.php?logout=1">Logout</a>';
    return $output;
}

/* --- Public navigation */
function navigation_public($sel_subject,$con_page, $public = true){
    $output = '<ul class="subjects">';
    $subject_set = get_all_subjects($public = true);
                            
    for($subject = mysql_fetch_array($subject_set); isset($subject[1]) != false; $subject = mysql_fetch_array($subject_set)){
        if($subject['id'] == $sel_subject['id']){
            $selected = ' class="selected"';
            }else{
                $selected = '';
            }
            $output .= '<li' . $selected .'><a href="index.php?subj='. urlencode($subject['id']) . '">' . $subject['menu_name'] . '</a></li>';
            //$output .= 'The subject id: ' . $subject['id'] . ' The selected subject id is: ' . $sel_subject['id'] . 'the con page id is: ' . $con_page['subject_id'];
            if(!isset($con_page['subject_id'])){
                $con_page['subject_id'] = 0;
            }
            if($subject['id'] == $con_page['subject_id']){
                $page_set = get_pages_for_subject($subject['id'], $public = true);
                
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
    $output .= '<a href="login.php">Login</a>';
    return $output;
}

/* --- Form Checks for validation */
/* This one is for create and edit menus */
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
                $errors[] = $fieldname . ' is to long please enter less then 30 charaters.';
            }
        }
    }
    return $errors;
}
/* This validation is for login and password create and login */
function check_user_form(){
    $check_array = array('username', 'password');// list of requiered fields
    $length_check = array('username'=>30, 'password'=>30);// filed name => max length
    $errors = array();
    foreach($check_array AS $fieldname){
        if(!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]))){
            $errors[] = $fieldname;
        }
    }
    foreach($length_check as $fieldname => $maxlength){
        if(isset($_POST[$fieldname])){
            if(strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength){
                $errors[] = $fieldname . ' is to long please enter less then 30 charaters.';
            }
        }
    }
    return $errors;
}

function get_errors($errors,$action){
    $message = '';
    if(!empty($errors)){
        if(count($errors) == 1){
            $message = 'There was ' . count($errors) . ' error in the form. When trying to ' . $action . '. <br/>';
        }else{
            $message = 'There were ' . count($errors) . ' errors in the form. When trying to ' . $action . '. <br/>';
        }
        $message = '<p class="message">' . $message . '</p>';
        $message .='<p class="errors">';
        if(count($errors) == 1){
            $message .= 'Please Review the field below: <br/>';
        }else{
            $message .= 'Please Review the Following fields: <br/>';
        }
                            
        foreach($errors AS $error){
            $message .= '- ' . $error . '<br/>';
        }
        $message .= '</p>';
        
    }else{
        if($action != 'Login'){
            $message = 'The ' . $action . ' action failed.';
        }
        $message .= '<br/>' . mysql_error();
        if(mysql_error() == NULL){
            if(strstr($action, 'Edit') != false){
                $message .= 'You must change at least one item on the form. <br/>';
            }
            if(strstr($action, 'Delete') != false){
                $message .= 'The item was not found. <br/>';
            }
            if(strstr($action, 'Add') != false){
                $message .= 'The item was not added. <br/>';
            }
        }
    }
    return $message;
}

?>