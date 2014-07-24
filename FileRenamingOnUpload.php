<?php
require_once "admin_pages/FileRenamingAdminPage.php";

/*
Plugin Name: File Renaming on upload
Plugin URI: http://wordpress.org/extend/plugins/file-renaming-on-upload/
Description: Renames files on upload
Version: 1.2
Author: Origgami
Author URI: http://www.origgami.com.br
License: GPL2
*/

/**
 * Renames files on upload
 *
 * @author Pablo Pacheco Karzin
 */
class FileRenamingOnUpload {
    private $siteURL;
    private $fileRenamingOptions;
    
    public function __construct() {
        $this->createLogFunction();
        $this->addWPActions();
        $this->addWPFilters();
        register_activation_hook( __FILE__, array($this,'onPluginActivation') );
        $fileRenameAdminPage = new FileRenamingAdminPage('options-general.php','File Renaming on Upload','File Renaming','administrator','file_renaming_on_upload');
    }
    
    public function onPluginActivation(){        
        $fileRenamingOptions = $this->getFileRenamingOptions();
        if($fileRenamingOptions==null){
            $fileRenamingOptions['site_url'] = 'on';
            $fileRenamingOptions['remove_tlds'] = 'on';
            $fileRenamingOptions['tlds_to_remove'] = array('www.','.com','.br','.org','.pt');
            $fileRenamingOptions['lowercase'] = 'on';
            $fileRenamingOptions['accents'] = 'on';
            $fileRenamingOptions['special_chars'] = 'on';
            update_option('f_renaming_opt', $fileRenamingOptions);
        }
        
    }
    
    public static function getWordpressSiteURL(){
        $siteURL = get_site_url();
        $lastChar = substr($siteURL,strlen($siteURL)-1);
        $siteURL = ($lastChar == '/') ? substr($siteURL,0,strlen($siteURL)-1) : $siteURL;                
        $noProtocolSiteURL = preg_replace('/http:\/\/|https:\/\//', '', $siteURL);        
        $lastBarIndex = strrpos($noProtocolSiteURL, '/');
        if($lastBarIndex){
            $finalSiteURL = substr($noProtocolSiteURL,$lastBarIndex+1);            
        }else{
            $finalSiteURL = $noProtocolSiteURL;
        }
        return $finalSiteURL;
    }
    
    private function addWPActions(){
        
    }
    
    private function addWPFilters(){        
        add_filter('sanitize_file_name', array($this,'sanitizeFileName'), 10);
        //add_filter('wp_handle_upload_prefilter', array($this,'wpHandleUploadPrefilter'), 1, 1);
        //add_action( 'add_attachment', array($this,'addAttachment') );
        //add_filter('attachment_fields_to_save', array($this,'attachmentFieldsToSave'), 10, 2);
    }
    
    /*public function attachmentFieldsToSave($post, $attachment){
        _log(print_r($post,true));
        _log(print_r($attachment,true));
        return $post;
    }*/
    
    /*public function addAttachment($post_ID){
        $post = get_post($post_ID);
        $file = get_attached_file($post_ID);
        $path = pathinfo($file);
            //dirname   = File Path
            //basename  = Filename.Extension
            //extension = Extension
            //filename  = Filename

        $newfilename = "NEW FILE NAME HERE";
        $newfile = $path['dirname']."/".$newfilename.".".$path['extension'];

        rename($file, $newfile);    
        update_attached_file( $post_ID, $newfile );
    }*/
    
    public static function getFileExtension($filename) {
	if( !preg_match('/\./', $filename) ) return '';
	return preg_replace('/^.*\./', '', $filename);
    }
    
    public function wpHandleUploadPrefilter($arr){
        // Get the parent post ID, if there is one
        
        /*$post = FileRenamingOnUpload::getPost();
        if($post!=null){
            $fileExtension = FileRenamingOnUpload::getFileExtension($arr['name']);
            _log(print_r($arr,true));
            _log($post->post_title);
            $arr['name'] = $post->post_title.'.'.$fileExtension;
        }*/
        
        //_log(print_r($arr,true));
        
        /*if( isset($_REQUEST['post_id']) ) {
            $post_id = $_REQUEST['post_id'];
        } else {
            $post_id = false;
        }
        
        _log(print_r($arr,true));
        
        // Only do this if we got the post ID--otherwise they're probably in
        //  the media section rather than uploading an image from a post.
        if($post_id && is_numeric($post_id)) {

            // Get the post slug
            $post_obj = get_post($post_id); 
            $post_slug = $post_obj->post_name;

            // If we found a slug
            if($post_slug) {

                //$random_number = rand(10000,99999);
                $arr['name'] = $post_slug . '-' . $random_number . '.jpg';

            }

        }*/
    
        //_log(print_r($arr,true));
        
        return $arr;
    }
    
    public static function getPostSlug($postObj=null){
        if($postObj==null){
           $postObj = FileRenamingOnUpload::getPost(); 
        }
        
        $postSlug='';
        
        if($postObj!=null){
            $postSlug = $postObj->post_name;
        }
        
        return $postSlug;
    }
    
    public static function getPost(){
        if( isset($_REQUEST['post_id']) ) {
            $post_id = $_REQUEST['post_id'];
        } else {
            $post_id = false;
        }
        
        $postObj=null;
        if($post_id && is_numeric($post_id)) {
            $postObj = get_post($post_id);            
        }
        return $postObj;
    }
    
    public static function removeSpecialChars($string){
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
        $special_chars = apply_filters('sanitize_file_name_chars', $special_chars, $string);
        $string = str_replace($special_chars, '', $string);
        $string = preg_replace('/\+/', '', $string);
        return $string;
    }
    
    public function sanitizeFileName($filename){
        $fileRenamingOptions = $this->getFileRenamingOptions();     
        
        $info = pathinfo($filename);
        $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);        
        $finalFileName = $name;
        
        if(isset($fileRenamingOptions['post_title_renaming'])){
            $postSlug = $this->getPostSlug();
            if($postSlug!=''){
                $finalFileName = $postSlug;
            }
        }
        
        if(isset($fileRenamingOptions['accents'])){
            $finalFileName = remove_accents($finalFileName);
        }
        
        if(isset($fileRenamingOptions['lowercase'])){
            $finalFileName = strtolower($finalFileName);
        }
        
        if(isset($fileRenamingOptions['special_chars'])){            
            $finalFileName = FileRenamingOnUpload::removeSpecialChars($finalFileName);
        }
        
        if(isset($fileRenamingOptions['date_format'])){
            $dateTimeNow = date('Y-m-d_H-i-s');
            $finalFileName=$dateTimeNow;
        }        
        
        if(isset($fileRenamingOptions['site_url'])){
            $siteURL = $this->getSiteURL();
            
            if(isset($fileRenamingOptions['remove_tlds'])){                
                $siteURL = FileRenamingOnUpload::removeStringPartsFromURL($siteURL, $fileRenamingOptions);
            }
            
            $finalFileName=$siteURL.='_'.$finalFileName;
        }        
       
        return $finalFileName.$ext;
    }
    
    public static function removeStringPartsFromURL($url,$fileRenamingOptions=null){
        if(!$fileRenamingOptions){
            $fileRenamingOptions = $this->getFileRenamingOptions();
        }
        $siteDomainsToRemoveRegex = implode($fileRenamingOptions['tlds_to_remove'],'|');
        $siteDomainsToRemoveRegex = preg_replace('/\./', '\\.', $siteDomainsToRemoveRegex);        
        $url = preg_replace('/'.$siteDomainsToRemoveRegex.'/', '', $url);
        return $url;
    }
    
    private function createLogFunction(){
        if(!function_exists('_log')){
            function _log( $message ) {
                if( WP_DEBUG === true ){
                    if( is_array( $message ) || is_object( $message ) ){
                        error_log( print_r( $message, true ) );
                    } else {
                        error_log( $message );
                    }
                }
            }
        }
    }
    
    public function getSiteURL() {
        if(strlen($this->siteURL)<=0){
            $this->setSiteURL(FileRenamingOnUpload::getWordpressSiteURL());
        }
        
        return $this->siteURL;
    }

    public function setSiteURL($siteURL) {
        $this->siteURL = $siteURL;
    }
    
    public function getFileRenamingOptions() {
        if(!is_array($this->fileRenamingOptions)){
            $this->setFileRenamingOptions(get_option('f_renaming_opt'));
        }
            
        return $this->fileRenamingOptions;
    }

    public function setFileRenamingOptions($fileRenamingOptions) {
        $this->fileRenamingOptions = $fileRenamingOptions;
    }
}

$fileRenaming = new FileRenamingOnUpload();
?>
