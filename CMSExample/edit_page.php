<?php
    $includes = true;
    require_once('includes/session.php');
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    
    confirm_login();
    
    $message = '';
    $errors = '';
    if(intval($_GET['page'] == 0)){
        redirect_to('content.php');
    }
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Edit Page' ){
        $errors = check_form();
        
        
        if(empty($errors)){ //Perform update
            $id = mysql_prep($_GET['page']);
            $menu_name = htmlentities(mysql_prep($_POST['menu_name']));
            $position = mysql_prep($_POST['position']);
            $visible = mysql_prep($_POST['visible']);
            $content = htmlentities(mysql_prep($_POST['content']));
            
            $query = "UPDATE pages SET
                        menu_name = '{$menu_name}',
                        position = {$position},
                        visible = {$visible},
                        content = '{$content}'
                    WHERE id = {$id}";
            mysql_query($query,$connection);
            if(mysql_affected_rows() == 1){
                //success
                $message = 'The Form was successfully posted.';
            }else{
                //failed
                $message .= get_errors($errors,$_POST['submit']);
                
            }
             
        }else{
            // errors occured
            $message .= get_errors($errors,$_POST['submit']);
        }
        

    }
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Delete Page' ){
        
        $id = mysql_prep($_GET['page']);
        if($page = get_page_by_id($id)){
            $query = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
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
                   <h2>Edit Subject: <?php echo $con_page['menu_name']; ?></h2>
                   <?php
                        if(!empty($message)){
                            echo  $message;
                        }
                   ?>
                   <form action="edit_page.php?page=<?php echo urlencode($con_page['id']); ?>" method="post">
                    <p>Page name:
                        <input type="text" name="menu_name" value="<?php echo $con_page['menu_name'];?>" id="menu_name" />
                    </p>
                    <p>Position:
                    <select name="position">
                    <?php
                        $pages_set = get_pages_for_subject($con_page['subject_id']);
                        $pages_count = mysql_num_rows($pages_set);
                        for($count = 1; $count <= ($pages_count+1); $count++){
                            if($count == $con_page['position']){
                                $selected = 'selected="selected" ';
                            }else{
                                $selected = '';
                            }
                            echo '<option ' . $selected . ' value"' . $count . '">' . $count . '</option>';
                        }
                    ?>
                    </select>
                    </p>
                    <p>Visible:
                        <input type="radio" <?php if($con_page['visible'] == 0){echo 'checked=""'; } ?> name="visible" value="0" /> No
                        &nbsp;
                        <input type="radio" <?php if($con_page['visible'] == 1){echo 'checked=""'; } ?> name="visible" value="1" /> Yes
                    </p>
                    <p><textarea cols="40" rows="5" name="content"  id="content"><?php echo $con_page['content'] ?></textarea></p>
                    <input type="submit" name="submit" value="Edit Page" />
                    &nbsp;&nbsp;
                    <input type="submit" name="submit" onclick="return confirm('Are you sure you want to delete <?php echo $con_page['menu_name'] ?>');" value="Delete Page" />

                   </form>
                   <br />
                   <a href="content.php">Cancel</a>
                   
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>