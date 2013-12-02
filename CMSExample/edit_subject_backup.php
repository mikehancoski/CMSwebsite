<?php
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    
    $message = '';
    if(intval($_GET['subj'] == 0)){
        redirect_to('content.php');
    }
    
    if(isset($_POST['submit'])){
        $errors = check_form();
        
        if(empty($errors)){ //Perform update
            $id = mysql_prep($_GET['subj']);
            $menu_name = mysql_prep($_POST['menu_name']);
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
                $message = 'The Form post failed';
                $message .= '<br/>' . mysql_error();
            }
             
        }else{
            // errors occured
            $message = 'There were ' . count($errors) . ' errors in the form.';
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
                            echo '<p class="message">' . $message . '</p>';
                        }
                        
                        if(!empty($errors)){
                            echo '<p class="errors">';
                            echo 'Please Review the Following fields: <br/>';
                            
                            foreach($errors AS $error){
                                echo '- ' . $error . '<br/>';
                            }
                            echo '</p>';
                        }
                   ?>
                   <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
                    <p>Subject name:
                        <input type="text" name="menu_name" value="<?php echo $sel_subject['menu_name']; ?>" id="menu_name" />
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
                        <input type="radio" <?php if($sel_subject['visible'] == 0){echo 'checked="checked"'; } ?> name="visible" value="0" /> No
                        &nbsp;
                        <input type="radio" <?php if($sel_subject['visible'] == 1){echo 'checked="checked"'; } ?> name="visible" value="1" /> Yes
                    </p>
                    <input type="submit" name="submit" value="Edit Subject" />
                    &nbsp;&nbsp;
                    <a href="delete_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" onclick="return confirm('Are you sure');">Delete Subject</a>
                   </form>
                   <br />
                   <a href="content.php">Cancel</a>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>