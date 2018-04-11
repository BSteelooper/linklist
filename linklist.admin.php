<?php
//This is a module for pluck, an opensource content management system
//Website: http://www.pluck-cms.org

//MODULE NAME: Linklist
//LICENSE: MIT

//Make sure the file isn't accessed directly
defined('IN_PLUCK') or exit('Access denied!');

function linklist_pages_admin() {
	global $lang;

	$module_page_admin[] = array(
		'func'  => 'Main',
		'title' => $lang['linklist']['main']
	);
	$module_page_admin[] = array(
		'func'  => 'activate',
		'title' => $lang['linklist']['adminpage']
	);
	$module_page_admin[] = array(
		'func'  => 'settings',
		'title' => $lang['linklist']['adminset']
	);
	
	return $module_page_admin;
}

function linklist_page_admin_Main() {
	global $lang;

	showmenudiv($lang['linklist']['adminpage'],$lang['linklist']['adminpage'],'data/image/note.png','admin.php?module=linklist&amp;page=activate',false);
	showmenudiv($lang['linklist']['adminset'],$lang['linklist']['adminset'],'data/image/options.png','admin.php?module=linklist&amp;page=settings',false);
//	showmenudiv($lang['linklist']['edit_info'],$lang['linklist']['edit_info_info'],'data/modules/linklist/images/theme.png','admin.php?module=editor&page=Info',false);

    if (!file_exists('data/settings/modules/linklist')) {
            mkdir('data/settings/modules/linklist', 0775, true);
    }

    if (!file_exists('data/settings/modules/linklist/new')) {
            mkdir('data/settings/modules/linklist/new', 0775, true);
    }

    if (isset($_GET['delete'])) {
        unlink ('data/settings/modules/linklist/'.$_GET['delete']);
        echo $file . $lang['linklist']['deleted'];
        redirect ('?module=linklist','0');
    }

    $dir = opendir('data/settings/modules/linklist/');
    while (false !== ($file = readdir($dir))) {
        if(($file !== ".") and ($file !== "..") and ($file != "new")) {
        include ('data/settings/modules/linklist/'.$file);
        echo '
        <div class="menudiv" style="margin: 10px;">
            <table width="100%">
                <tr>
                    <td width="20"><img src="data/image/website_small.png"></td>
                    <td>
                        <span><a href="'.$sitelink.'" target="_blank">'.$sitename.'</a></span>
                    </td>
                    <td align="right">
                        <a href="?module=linklist&delete='.$file.'"><img src="data/image/trash_small.png" border="0" title="'.$lang['linklist']['delete'].'" alt="'.$lang['linklist']['delete'].'"></a>
                    </td>
                </tr>
            </table>
        </div>';

        }
    }

}

function linklist_page_admin_activate(){
	global $lang;
    	showmenudiv($lang['linklist']['backlink'],false,'data/image/restore.png','?module=linklist',false);

    $dir = opendir('data/settings/modules/linklist/new/');
    while (false !== ($file = readdir($dir))) {
           if(($file !== ".") and ($file !== "..")) {
           include ('data/settings/modules/linklist/new/'.$file);
            echo '
            <div class="menudiv" style="margin: 10px;">
                <table width="100%">
                    <tr>
                        <td width="20"><img src="data/image/website_small.png"></td>
                        <td>
                            <span><a href="'.$sitelink.'" target="_blank">'.$sitename.'</a></span>
                        </td>
                        <td align="right">
                            <a href="?module=linklist&page=activate&activate='.$file.'"><img src="data/image/add_small.png" border="0" title='.$lang['linklist']['activate'].'" alt="'.$lang['linklist']['activate'].'"></a>
                            <a href="?module=linklist&page=activate&delete='.$file.'"><img src="data/image/trash_small.png" border="0" title="'.$lang['linklist']['delete'].'" alt="'.$lang['linklist']['delete'].'"></a>
                        </td>
                    </tr>
                </table>
            </div>';
           }
       }
    
    if (isset($_GET['delete'])) {
        unlink ('data/settings/modules/linklist/new/' . $_GET['delete']);
        echo $file . $lang['linklist']['deleted'];
        redirect('?module=linklist','0');
    }
    
    if (isset($_GET['activate'])) {
        copy('data/settings/modules/linklist/new/'.$_GET['activate'],'data/settings/modules/linklist/'.$_GET['activate']);
        unlink ('data/settings/modules/linklist/new/'.$_GET['activate']);
        redirect('?module=linklist&amp;page=activate','0');
    }
    
}

function linklist_page_admin_settings(){
	global $lang;
    	showmenudiv($lang['linklist']['backlink'],false,'data/image/restore.png','?module=linklist',false);
    if (file_exists('data/settings/modules/linklist_settings.php')){
    include ("data/settings/modules/linklist_settings.php");
    }
    else {
        $disclaimer="You are leaving this page. Please note that I am not responsible for the content of the following site.";
    }
    
    echo '<form action="" method="post">
    <p>'.$lang['linklist']['distext'].':<br>
    <textarea name="disclaimer" cols="50" rows="5" class="mceNoEditor">'.$disclaimer.'</textarea></p>
    <p><input type="submit" name="Submit" value="'.$lang['linklist']['save'].'"></p>
    </form>';
    
    if(isset($_POST['Submit'])) {
        $text = $_POST['disclaimer'];
        $text = str_replace("\n", '<br>',$text);
        $fp = fopen ('data/settings/modules/linklist_settings.php',"w");
        fputs ($fp, '<?php'."\n"
                .'$disclaimer = "'.$text.'";'."\n"
                .'');
        fclose ($fp);
        
        redirect('?module=linklist&amp;page=settings','0');
    }
}

?>
