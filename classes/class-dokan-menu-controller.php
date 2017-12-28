<?php
/**
 * Dokan_Menu_Controller Class
 *
 * @since 0.1
 */
class Dokan_Menu_Controller extends WP_Customize_Control {
  protected $number_of_menus  = 0;
  protected $all_menus        = array();
  public $html                = array();

  public function set_all_menus() {
    $this->all_menus = get_option( 'dokan_customized_menus' );
    $this->number_of_menus = count( get_option( 'dokan_customized_menus' ) );
  }

  public function render_content() {
      ?>
    <div class="menu-item-bar">
      <div class="menu-item-handle ui-sortable-handle">
        <span class="item-type" aria-hidden="true" style="float: left" >Menu Title</span>
          <span class="item-title" aria-hidden="true">
            <span class="spinner"></span>
            <span class="menu-item-title"></span>
          </span>
          <span class="item-controls">
            <button type="button" class="button-link item-edit" aria-expanded="false"><span class="screen-reader-text">Edit menu item: (no label) (Page)</span><span class="toggle-indicator" aria-hidden="true"></span></button>
            <button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text">Remove Menu Item: (no label) (Page)</span></button>
          </span>
      </div>
    </div>
    <div class="menu-item-settings" id="menu-item-settings" style="height: 0; padding: 0; transition: all .5s ease">          
    <?php foreach( $this->settings as $key => $value ) { ?>
      <p class="description description-thin">
        <label for="edit-menu-item-title">
          Title<br>
          <input type="text" placeholder="Menu Title" class="widefat edit-menu-item-title" value="<?php echo $this->settings[$key]->value(); ?>" <?php echo $this->get_link( $key ) ?>>
        </label>
      </p>
      <?php } ?>
      </div>     
 <?php }
}
