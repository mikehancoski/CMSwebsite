<?php
    $includes = true;
    require_once('includes/session.php');
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    confirm_login();
/**
 * @author Michael Hancoski
 * @copyright 2013
 */
$message = '';
$errors = '';
if(isset($_POST['submit'])){
    $errors = check_user_form();
    
    $username = strtolower(trim(mysql_prep($_POST['username'])));
    $password = trim(mysql_prep($_POST['password']));
    $hashed_password = sha1($password);
    $dup_query = "SELECT username FROM users WHERE username='{$username}'";
    $dup = mysql_query($dup_query,$connection);
    if($found_dup = mysql_fetch_array($dup)){
        $message .= 'Found duplicate user, Please select a diffrent username <br/><br/>';
    }
    
    
    if(empty($errors) && empty($message)){
        $query = "INSERT INTO users (
                    username, hashed_password
                    ) VALUES (
                    '{$username}', '{$hashed_password}'
                    )";
        mysql_query($query,$connection);
        if(mysql_affected_rows() == 1){
            $message .= 'The user was created. <br/>';
        }else{
            $message .= 'The user could not be created <br/>';
            $message .= get_errors($errors,$_POST['submit']);
        }
    }
    
}else{
    $username = '';
    $password = '';
}

include('includes/header.php');
?>

        <table id="structure">
            <tr>
                <td id="navigation">
                        <a href="index.php">Return to menu</a><br />
                        <br />
                </td>
                <td id="page">
                    <h2>
                        Create new user: <br />
                    </h2>
                    <div class="pagecontent">
                        <?php
                        if(!empty($message)){
                            echo  $message;
                            
                        }
                        ?>
                        <form action="new_user.php" method="post">
                            <table>
                                <tr>
                                    <td>User Name:</td><td><input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" /> <br /></td>
                                </tr>
                                <tr>
                                    <td>Password:</td><td><input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" /> <br /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" name="submit" value="Create user" /></td>
                                </tr>
                            </table>
                        </form>
                            
                        
                    </div>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>