<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomMenuPage
 *
 * @author USUARIO
 */
if(!class_exists('WPAdminPage')) {
    class WPAdminPage {
        //put your code here

        protected $page_title;
        protected $menu_title;
        protected $capability;
        protected $menu_slug;
        protected $icon_url;
        protected $position;
        protected $parent_slug;

        /**
         *
         * @param type $parent_slug Slug do menu pai. Usado somente para SubMenu Pages!!!
         * @param type $page_title
         * @param type $menu_title
         * @param type $capability
         * @param type $menu_slug
         * @param type $icon_url
         * @param type $position 
         */
        public function __construct($parent_slug="",$page_title="Título da pagina customizada", $menu_title="Pagina customizada", $capability="administrator", $menu_slug="unique_page_name", $icon_url="", $position=6) {
            $this->setParentSlug($parent_slug);
            $this->setPageTitle($page_title);
            $this->setMenuTitle($menu_title);
            $this->setCapability($capability);
            $this->setMenuSlug($menu_slug);
            $this->setIconUrl($icon_url);
            $this->setPosition($position);

            add_action('admin_menu', array($this,'adminMenu'));
        }

        public function adminMenu(){
            $this->addMenuPage();
        }

        public function addMenuPage(){
            $parentSlug = $this->getParentSlug();
            if($parentSlug=='' || $parentSlug==null){
                add_menu_page( $this->getPageTitle(), $this->getMenuTitle(), $this->getCapability(), $this->getMenuSlug(), array($this,'showPage'), $this->getIconUrl(), $this->getPosition() );
            }else{
                add_submenu_page($parentSlug, $this->getPageTitle(), $this->getMenuTitle(), $this->getCapability(), $this->getMenuSlug(), array($this,'showPage'));
            }
        }

        public function showPage(){
            ?>

            <div class="wrap">
                <div id="icon-users" class="icon32"><br /></div>
                <h2>Custom Page</h2>
            </div>


            <?php
        }

        public function getPageTitle(){
                return $this->page_title;
        }

        public function setPageTitle($page_title){
                $this->page_title = $page_title;
        }

        public function getMenuTitle(){
                return $this->menu_title;
        }

        public function setMenuTitle($menu_title){
                $this->menu_title = $menu_title;
        }

        public function getCapability(){
                return $this->capability;
        }

        public function setCapability($capability){
                $this->capability = $capability;
        }

        public function getMenuSlug(){
                return $this->menu_slug;
        }

        public function setMenuSlug($menu_slug){
                $this->menu_slug = $menu_slug;
        }

        public function getIconUrl(){
                return $this->icon_url;
        }

        public function setIconUrl($icon_url){
                $this->icon_url = $icon_url;
        }

        public function getPosition(){
                return $this->position;
        }

        public function setPosition($position){
                $this->position = $position;
        }

        public function getParentSlug(){
                return $this->parent_slug;
        }

        public function setParentSlug($parent_slug){
                $this->parent_slug = $parent_slug;
        }

    }
}
?>
