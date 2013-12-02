<?php
    $includes = true;
    require_once('includes/session.php');
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    confirm_login();
    $message = '';
    $errors = '';
    if(intval($_GET['subj'] == 0)){
        redirect_to('content.php');
    }
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Edit Subject' ){
        $errors = check_form();
        
        
        if(empty($errors)){ //Perform update
            $id = mysql_prep($_GET['subj']);
            $menu_name = htmlentities(mysql_prep($_POST['menu_name']));
            $position = mysql_prep($_POST['position']);
            $visible = mysql_prep($_POST['visible']);
            
            $query = "UPDATE subjects SET
                        menu_name = '{$menu_name}',
                        position = {$position},
                        visible = {$visible}
                    WHERE id = {$id}";
            $result = mysql_query($query,$connection);
            if(mysql_affected_rows() == 1){
                //success
                $message = 'The Form was successfully posted.';
            }else{
                //failed
                $message .= get_errors($errors, $_POST['submit']);
            }
             
        }else{
            // errors occured
            $message .= get_errors($errors,$_POST['submit']);
        }
        

    }
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Delete Subject' ){
        
        $id = mysql_prep($_GET['subj']);
        if($subject = get_subject_by_id($id)){
            $query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
            $result = mysql_query($query,$connection);
            
            if (mysql_affected_rows() == 1){
                redirect_to('content.php');
            }else {
                // delete failed
                $message .= get_errors($errors,$_POST['submit']);
                
            }
        }else{
            //subject did not exist
            $message .= get_errors($errors,$_POST['submit']);
             
        }
    }
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Add Page' ){
        $errors = check_form();
        
        if(empty($errors)){
            $subject_id = mysql_prep($_GET['subj']);
            $menu_name = htmlentities(mysql_prep($_POST['menu_name']));
            $position = mysql_prep($_POST['position']);
            $visible = mysql_prep($_POST['visible']);
            $content = htmlentities(mysql_prep($_POST['content']));
            


            $query = "INSERT INTO pages (
            subject_id, menu_name, position, visible, content
            ) VALUES (
            {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'
            )";
            mysql_query($query,$connection);
            if(mysql_affected_rows() == 1){
                $message = '<p>Page was added successfully</p>';
            }else{
                $message .= get_errors($errors,$_POST['submit']);
            }
        }else{
            // errors occured
            $message .= get_errors($errors,$_POST['submit']);
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
                   <h2>Edit Subject: <?php echo $sel_subject['menu_name']; ?></h2>
                   <?php
                        if(!empty($message)){
                            echo  $message;
                            
                        }
                   ?>
                   <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
                    <p>Subject name:
                        <input type="text" name="menu_name" value="<?php echo $sel_subject['menu_name'];?>" id="menu_name" />
                    </p>
                    <p>Position:
                    <select name="position">
                    
                    <?php
                        $subject_set = get_all_subjects();
                        $subject_count = mysql_num_rows($subject_set);
                        $selected = '';
                        for($count = 1; $count <= ($subject_count+1); $count++){
                            if($count == $sel_subject['position']){
                                $selected = 'selected="selected" ';
                            }else{
                                $selected = '';
                            }
                                echo '<option ' . $selected . 'value"' . $count . '">' . $count . '</option>';
                        }
                    ?>
                    </select>
                    </p>
                    <p>Visible:
                        <input type="radio" <?php if($sel_subject['visible'] == 0){echo 'checked=""'; } ?> name="visible" value="0" /> No
                        &nbsp;
                        <input type="radio" <?php if($sel_subject['visible'] == 1){echo 'checked=""'; } ?> name="visible" value="1" /> Yes
                    </p>
                    <input type="submit" name="submit" value="Edit Subject" />
                    &nbsp;&nbsp;
                    <input type="submit" name="submit" onclick="return confirm('Are you sure you want to delete <?php echo $sel_subject['menu_name'] ?>');" value="Delete Subject" />

                   </form>
                   <br />
                   
                   <br />
                   <h2>Add Page to: <?php echo $sel_subject['menu_name']; ?></h2>
                   <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
                    <p>Page name:
                        <input type="text" name="menu_name" value="" id="menu_name" />
                    </p>
                    <p>Position:
                    <select name="position">
                    <?php
                        $pages_set = get_pages_for_subject($sel_subject['id']);
                        $pages_count = mysql_num_rows($pages_set);
                        for($count = 1; $count <= ($pages_count+1); $count++){
                            echo '<option value"' . $count . '">' . $count . '</option>';
                        }
                    ?>
                    </select>
                    </p>
                    <p>Visible:
                        <input type="radio" name="visible" value="0" /> No
                        &nbsp;
                        <input type="radio" name="visible" value="1" checked="" /> Yes
                    </p>
                    <p><textarea cols="40" rows="5" name="content"  id="content">Input your content here</textarea></p>
                    <input type="submit" name="submit" value="Add Page" />
                   </form>
                   <br />
                   <a href="content.php">Cancel</a>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>