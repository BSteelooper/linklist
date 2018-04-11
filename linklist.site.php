<?php
//This is a module for pluck, an opensource content management system
//Website: http://www.pluck-cms.org

//Make sure the file isn't accessed directly.
defined('IN_PLUCK') or exit('Access denied!');

//Include language-items

function linklist_pages_site() {
	global $lang;

	$module_page_admin[] = array(
		'func'  => 'Main',
		'title' => $lang['linklist']['main']
	);
	$module_page_admin[] = array(
		'func'  => 'newlink',
		'title' => $lang['linklist']['newlink']
	);
	$module_page_admin[] = array(
		'func'  => 'extern',
		'title' => $lang['linklist']['extern']
	);
	
	return $module_page_admin;
}

function linklist_theme_Main() {
	global $lang;

    if (!file_exists('data/settings/modules/linklist')) {
        mkdir('data/settings/modules/linklist', 0775, true);
    }

    if (!file_exists('data/settings/modules/linklist/new')) {
            mkdir('data/settings/modules/linklist/new', 0775, true);
    }

    $dir = opendir('data/settings/modules/linklist');
    while (false !== ($file = readdir($dir))) {
        if(($file !== ".") and ($file !== "..") and ($file !== "new")) {
                include ('data/settings/modules/linklist/'.$file);
                echo '<h2><a href="'.SITE_URL.'/'.PAGE_URL_PREFIX.CURRENT_PAGE_SEONAME.'&amp;module=linklist&amp;page=extern&amp;link='.$file.'">'.$sitename.'</a></h2><div class="boxad">'.$post_content.'<br/><a href="'.SITE_URL.'/'.PAGE_URL_PREFIX.CURRENT_PAGE_SEONAME.'&amp;module=linklist&amp;page=extern&amp;link='.$file.'">'.$sitelink.'</a></div>';
        }
    }

    echo '<br/><a href="'.SITE_URL.'/'.PAGE_URL_PREFIX.CURRENT_PAGE_SEONAME.'&amp;module=linklist&amp;page=newlink">' . $lang['linklist']['newlink'] . '</a>';

}

function linklist_page_site_newlink(){
global $lang;
    ?>
    <div>
        <form method="post" action="" style="margin-top: 5px; margin-bottom: 15px;">
            <?php echo $lang['linklist']['title']; ?> <br /><input name="title" type="text" value="" /><br />
            <?php echo $lang['linklist']['link']; ?> <br /><input name="link" type="text" value="http://" /><br />
            <?php echo $lang['linklist']['descr']; ?> <br /><textarea name="description" rows="7" cols="45" class="mceNoEditor"></textarea><br />
            <input type="submit" name="Submit" value="<?php echo $lang['linklist']['send']; ?>" />
        </form>
    </div>
    
    <?php

    if(isset($_POST['Submit'])) {

        //Check if everything has been filled in
        if((!isset($_POST['title'])) || (!isset($_POST['link'])) || (!isset($_POST['description']))) { ?>
            <span style="color: red;"><?php echo $lang['linklist']['fillall']; ?></span>
        <?php
            // exit;
        }
        else {
            //Then fetch our posted variables
            $title = $_POST['title'];
            $sitelink = $_POST['link'];
            $description = $_POST['description'];

            //Check for HTML, and eventually block it
            if ((ereg('<', $title)) || (ereg('>', $title)) || (ereg('<', $sitelink)) || (ereg('>', $sitelink)) || (ereg('<', $description)) || (ereg('>', $description))) { ?>
                <span style="color: red;"><?php echo $lang['linklist']['nohtml']; ?></span>
            <?php }
        else {

            $description=str_replace("\n", '<br \>', $description);

            $file=str_replace(" ", "_", $title);
            $file=date ("dmY"). '-' . $file;
            
            $fp = fopen ('data/settings/modules/linklist/new/' . $file . '.php',"w");
            fputs ($fp, '<?php'."\n"
                .'$sitename = "'.$title.'";'."\n"
                .'$sitelink = "'.$sitelink.'";'."\n"
                .'$post_content = "'.$description.'";'."\n"
                .'');
            fclose ($fp);
            
            $message = $lang['linklist']['mail']."<br><br>".
            $lang['linklist']['mail_tit'].'<br><b>'.$title."</b><br>".
            $lang['linklist']['mail_dis'].'<br>'.$description."<br>".
            $lang['linklist']['mail_lnk'].'<br><a href="'.$sitelink.'">'.$sitelink.'</a>';
            
            mail ($site_email,$lang['linklist']['msubject'],$message,"From: ".$email." \n" . "Content-type: text/html; charset=utf-8");
            
            echo $lang['linklist']['wsend'];

            }
        }
    }

}

function linklist_page_site_extern(){
global $lang;    
	if (file_exists('data/settings/modules/linklist_settings.php')){
        include ("data/settings/modules/linklist_settings.php");
    } else {
        $disclaimer="You are leaving this page. Please note that I am not responsible for the content of the following site.";
    }
        
    $file=$_GET['link'];
    include ('data/settings/modules/linklist/'.$file);
    
    echo '<h2>'.$lang['linklist']['exhead'].'</h2>
    <p>'.$disclaimer.'</p>
    <p>'.$lang['linklist']['exredir'].' <a href="'.$sitelink.'">'.$sitelink.'</a></p>';
    
    echo '<script type="text/javascript">
    function Weiter() {
        window.open("'.$sitelink.'","_blank");
    }
    setTimeout("Weiter()",5000);
    </script>';
    
}
?>
