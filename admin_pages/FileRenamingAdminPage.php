<?php

require_once dirname(__FILE__)."/../libs/com/karzin_wp_oop_dev/wordpress/CustomMenuPage.php";

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileRenamingAdminPage
 *
 * @author USUARIO
 */
class FileRenamingAdminPage extends CustomMenuPage{
    //put your code here
    
    public function __construct($parent_slug="",$page_title="Título da pagina customizada", $menu_title="Pagina customizada", $capability="administrator", $menu_slug="unique_page_name", $icon_url="", $position=6) {
        parent::__construct($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $icon_url, $position);
        
        $this->handleFormSubmit();
    }
    
    private function handleFormSubmit(){
        $fileRenamingOptions = array();        
        
        if (isset($_POST['file_renaming_opt'])){
            $fileRenamingOptions = array();
            
            if(isset($_POST["post_title_renaming"])){
                $fileRenamingOptions['post_title_renaming'] = $_POST["post_title_renaming"];
            }
             
            if(isset($_POST["site_url"])){
                $fileRenamingOptions['site_url'] = $_POST["site_url"];
            }
            if(isset($_POST["remove_tlds"])){
                $fileRenamingOptions['remove_tlds'] = $_POST["remove_tlds"];
            }
            if(isset($_POST["tlds_to_remove"])){                
                $array = explode(",", $_POST["tlds_to_remove"]);
                $array = array_filter(array_map('trim', $array));
                $fileRenamingOptions['tlds_to_remove'] = $array;
            }
            if(isset($_POST["date_format"])){
                $fileRenamingOptions['date_format'] = $_POST["date_format"];
            }
            if(isset($_POST["lowercase"])){
                $fileRenamingOptions['lowercase'] = $_POST["lowercase"];
            }
            if(isset($_POST["accents"])){
                $fileRenamingOptions['accents'] = $_POST["accents"];
            }
            if(isset($_POST["special_chars"])){
                $fileRenamingOptions['special_chars'] = $_POST["special_chars"];
            }
            update_option('f_renaming_opt',$fileRenamingOptions);
        }
    }
    
    private function getCheckboxOptionsStr($fileRenamingOptions){
        $checkboxOptStr=array();
        $checkboxOptStr['site_url']='';
        $checkboxOptStr['date_format']='';
        $checkboxOptStr['lowercase']='';
        $checkboxOptStr['accents']='';
        $checkboxOptStr['special_chars']='';
        $checkboxOptStr['remove_tlds']='';
        
        if(isset($fileRenamingOptions['post_title_renaming'])){
            $siteURL = $fileRenamingOptions['post_title_renaming'];
            if($siteURL==true){
                $checkboxOptStr['post_title_renaming']='checked="checked"';
            }
        }else{
            $checkboxOptStr['post_title_renaming']='';
        }
        
        if(isset($fileRenamingOptions['site_url'])){
            $siteURL = $fileRenamingOptions['site_url'];
            if($siteURL==true){
                $checkboxOptStr['site_url']='checked="checked"';
            }
        }
        
        if(isset($fileRenamingOptions['remove_tlds'])){
            $siteURL = $fileRenamingOptions['remove_tlds'];
            if($siteURL==true){
                $checkboxOptStr['remove_tlds']='checked="checked"';
            }
        }
        
        if(isset($fileRenamingOptions['date_format'])){
            $siteURL = $fileRenamingOptions['date_format'];
            if($siteURL==true){
                $checkboxOptStr['date_format']='checked="checked"';
            }
        }        
        
        if(isset($fileRenamingOptions['lowercase'])){
            $siteURL = $fileRenamingOptions['lowercase'];
            if($siteURL==true){
                $checkboxOptStr['lowercase']='checked="checked"';
            }
        }
        
        if(isset($fileRenamingOptions['accents'])){
            $siteURL = $fileRenamingOptions['accents'];
            if($siteURL==true){
                $checkboxOptStr['accents']='checked="checked"';
            }
        }
        
        if(isset($fileRenamingOptions['special_chars'])){
            $siteURL = $fileRenamingOptions['special_chars'];
            if($siteURL==true){
                $checkboxOptStr['special_chars']='checked="checked"';
            }
        }
        return $checkboxOptStr;
    }
    
    public function showPage() {
        //parent::showPage();        
        $fileRenamingOptions = get_option('f_renaming_opt');
        $checkboxOptStr = $this->getCheckboxOptionsStr($fileRenamingOptions);
        
        $siteDomainsToRemoveStr = implode($fileRenamingOptions['tlds_to_remove'],', ');
        
        /*$siteDomainsToRemoveRegex = implode($fileRenamingOptions['tld_to_remove'],'|');
        
        $url = 'www.meusite.com.br';
        $finalFileName = preg_replace('/'.$siteDomainsToRemoveRegex.'/', '', $url);
        echo $finalFileName;*/
        ?>

        

        <form action="" method="POST">
            <input type="hidden" id="file_renaming_opt" name="file_renaming_opt" value="1"/>
            <div class="wrap">
                <div id="icon-upload" class="icon32"><br /></div>
                <h2>File Renaming on Upload - Config options</h2>
                
                <br />
                <input <?php echo $checkboxOptStr['site_url']; ?> type="checkbox" id="site_url" name="site_url" />
                <label for="site_url"><b>Add Site url:</b></label>
                <div class="description">
                    <?php $wordpressSiteURL = FileRenamingOnUpload::getWordpressSiteURL(); ?>
                    Inserts "<?php echo $wordpressSiteURL ?>" at the beggining of the file name. Ex:<span style="color:#47a76c"> <?php echo $wordpressSiteURL ?>_filename.jpg</span>
                </div>
                <br />
                
                <input <?php echo $checkboxOptStr['remove_tlds']; ?> type="checkbox" id="remove_tlds" name="remove_tlds" />
                <label for="remove_tlds"><b>Remove string parts from site url:</b></label>
                <input style="width:250px" type="text" name="tlds_to_remove" id="tlds_to_remove" value="<?php echo $siteDomainsToRemoveStr ?>"/><br />                    
                <div class="description"> 
                    Can be used to remove top-level domains(tld), www, subdomains or whatever you want.<br /><br />                    
                    Result: <span style="color:#47a76c"><?php echo FileRenamingOnUpload::removeStringPartsFromURL($wordpressSiteURL, $fileRenamingOptions); ?>_filename.jpg</span>
                </div>
                <br />
                
                <hr />
                
                <br />                
                <input <?php echo $checkboxOptStr['post_title_renaming']; ?> type="checkbox" id="post_title_renaming" name="post_title_renaming" />
                <label for="post_title_renaming"><b>File renaming based on post title:</b></label>
                <div class="description">                                      
                    The postTitle is for example:"I like icecream". You upload an featured ICE image.
                    And it will be renamed to "I-like-icecream.jpg"<br />
                    (Suggestion made by <a href="http://wordpress.org/support/profile/oomskaap">oomskaap</a> and <a href="http://wordpress.org/support/profile/deniscgn">DenisCGN</a> on <a href="http://wordpress.org/support/topic/change-filename-to-post-title">this post</a>)
                </div> 
                
                <br />                
                <input <?php echo $checkboxOptStr['date_format']; ?> type="checkbox" id="date_format" name="date_format" />      
                <label for="date_format"><b>Replaces file name by datetime:</b></label>
                <div class="description">
                    <?php $datetime = date('Y-m-d_H-i-s'); ?>
                    Replaces filename by datetime, like "<?php echo $datetime ?>"
                </div>                
                
                <br />
                <input <?php echo $checkboxOptStr['lowercase']; ?> type="checkbox" id="lowercase" name="lowercase" />
                
                <label for="lowercase"><b>Lowercase:</b></label>
                <div class="description">
                    Converts all characters to lowercase
                </div>
                <br />
                
                <input <?php echo $checkboxOptStr['accents']; ?> type="checkbox" id="accents" name="accents" />
                <label for="accents"><b>Remove accents:</b></label>

                <br /><br />
                <input <?php echo $checkboxOptStr['special_chars']; ?> type="checkbox" id="special_chars" name="special_chars" />
                <label for="special_chars"><b>Remove special chars:</b></label>
                <div class="description">
                    Removes these special chars:
                    <?php
                    $special_chars = array("?", "+", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
                    echo '<span style="color:#ff0000">' . implode($special_chars, ' ') . '</span>';
                    ?>
                </div>

            
            <?php submit_button( 'Save', 'primary'); ?>
        </form>
        <!--<input class='button-primary' type='submit' name='Save' value='<?php //_e('Save Options'); ?>' id='submitbutton' />-->

        <?php       
    }
}

?>
