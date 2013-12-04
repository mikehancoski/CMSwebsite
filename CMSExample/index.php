<?php
    $includes = true;
    require_once('includes/connection.php');
    require_once('includes/functions.php');
    
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
                        <?php if($sel_subject != NULL){
                                echo $sel_subject['menu_name'];
                                if (isset($con_page['menu_name'])){
                                 echo   " - " . $con_page['menu_name'];
                                }
                        
                        }else{
                            echo $con_page['menu_name'];
                        } ?>
                    </h2>
                    <div class="pagecontent">
                        <?php
                            //echo 'Subject: ' . $sel_subj . ' <br/>';
                            //echo 'Page: ' . $sel_page . ' <br/>';
                            echo $con_page['content'] . ' <br/>';
                            if($con_page['content'] != 'CMS example site'){
                                //echo '<br/><a href="edit_page.php?page='. $con_page['id'] .'">Edit page</a>';
                            }
                            
                        ?>
                    </div>
                </td>
            </tr>
            
        </table>
<?php
    require('includes/footer.php');
    
?>