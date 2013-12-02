<?php
    $includes = true;
    require_once('includes/session.php');
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    confirm_login();
    $message = '';
    $errors = '';
if(isset($_POST['submit'])){
    
    $errors = check_form();
    if(!empty($errors)){
        $message .= get_errors($errors,$_POST['submit']);
    }else{
    
        $menu_name = htmlentities(mysql_prep($_POST['menu_name']));
        $position = mysql_prep($_POST['position']);
        $visible = mysql_prep($_POST['visible']);
    
    
    
        $query = "INSERT INTO subjects (
                menu_name, position, visible
                ) VALUES (
                '{$menu_name}', {$position}, {$visible}
                )";
        mysql_query($query,$connection);
                
        if(mysql_affected_rows() == 1){
        //Success!
        redirect_to('edit_subject.php?subj=' . mysql_insert_id());
    
        }else{
            // Error message
            $message .= get_errors($errors,$_POST['submit']);
        
        }
    }
}   
    
    
    
    find_selected_page();
    
    include('includes/header.php');
?>
        <table id="structure">
            <tr>
                <td id="navigation">
                <?php
                    echo navigation($sel_subject,$con_page);
                ?>
        
                </td>
                <td id="page">
                   <h2>Add Subject</h2>
                   <?php 
                        if(!empty($message)){
                            echo  $message;
                        }
                    ?>
                   <form action="new_subject.php" method="post">
                    <p>Subject name:
                        <input type="text" name="menu_name" value="" id="menu_name" />
                    </p>
                    <p>Position:
                    <select name="position">
                    <?php
                        $subject_set = get_all_subjects();
                        $subject_count = mysql_num_rows($subject_set);
                        for($count = 1; $count <= ($subject_count+1); $count++){
                            echo '<option value"' . $count . '">' . $count . '</option>';
                        }
                    ?>
                    </select>
                    </p>
                    <p>Visible:
                        <input type="radio" name="visible" value="0" /> No
                        &nbsp;
                        <input type="radio" name="visible" checked="" value="1" /> Yes
                    </p>
                    <input type="submit" name="submit" value="Add Subject" />
                   </form>
                   <br />
                   <a href="content.php">Cancel</a>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>