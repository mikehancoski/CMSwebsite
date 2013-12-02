<?php
    $includes = true;
    require_once('includes/session.php');
    require_once('includes/connection.php');
    require_once('includes/functions.php');
/**
 * @author Michael Hancoski
 * @copyright 2013
 */
$username = '';
$password = '';
$message = '';

if(logged_in()){
    redirect_to('staff.php');
}

if(isset($_POST['submit'])){
    $errors = check_user_form();
    
    $username = strtolower(trim(mysql_prep($_POST['username'])));
    $password = trim(mysql_prep($_POST['password']));
    $hashed_password = sha1($password);
    
    if(empty($errors)){
        $query = "SELECT id, username FROM users 
                WHERE username='{$username}' AND hashed_password='{$hashed_password}'
                 LIMIT 1";
        $result = mysql_query($query,$connection);
        confirm_query($result);
        
        $found_user = mysql_fetch_array($result);
        
            if(mysql_num_rows($result) == 1){
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['username'] = $found_user['username'];
                redirect_to('staff.php');
            /*elseif(mysql_num_rows($result) > 1){
                $message .= 'Duplicate user error. <br/>';
                $message .= get_errors($errors,$_POST['submit']);
            }*/
        }else{
            $found_user = '';
            $message .= 'Bad Username and/or Password, Please try again <br/>';
            $message .= get_errors($errors,$_POST['submit']);
        }
    }
    
}else{
    if(isset($_GET['logout']) && $_GET['logout'] == 1){
        $message .= 'You are now logged out <br/><br/>';
    }
    $username = '';
    $password = '';
}

//find_selected_page();
include('includes/header.php');
?>

        <table id="structure">
            <tr>
                <td id="navigation">
                    <?php
                        echo '<a href="index.php">Return to public site</a>';
                    ?>
        
                </td>
                <td id="page">
                    <h2>
                        Login Screen: <br />
                    </h2>
                    <div class="pagecontent">
                        <?php
                        if(!empty($message)){
                            echo  $message;
                        }
                        
                        ?>
                        
                        <form action="login.php" method="post">
                            <table>
                                <tr>
                                    <td>User Name:</td><td><input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" /> <br /></td>
                                </tr>
                                <tr>
                                    <td>Password:</td><td><input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" /> <br /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" name="submit" value="Login" /></td>
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