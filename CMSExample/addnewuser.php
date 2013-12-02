<?php
    require_once('includes/connection.php');
    require_once('includes/functions.php');
/**
 * @author Michael Hancoski
 * @copyright 2013
 */

find_selected_page();
include('includes/header.php');
?>

        <table id="structure">
            <tr>
                <td id="navigation">
                    <?php
                        echo navigation_public($sel_subject,$con_page);
                    ?>
        
                </td>
                <td id="page">
                    <h2>
                        Create new user: <br />
                    </h2>
                    <div class="pagecontent">
                        
                        <form method="post">
                            
                            User Name:<input type="text" name="username" /> <br />
                            Password:&nbsp;<input type="password" name="password" /> <br />
                            <input type="submit" name="Create user" value="create user" />
                        </form>
                            
                        
                    </div>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>