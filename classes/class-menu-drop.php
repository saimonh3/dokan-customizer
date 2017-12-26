<?php
/**
 * Customize API: WP_Customize_Nav_Menu_Control class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.4.0
 */

/**
 * Customize Nav Menu Control Class.
 *
 * @since 4.3.0
 */
class Menu_Dropdown_Custom_control extends WP_Customize_Control {
  protected $number_of_menus = 0;
  protected $all_menus = array();

  public function set_all_menus() {
    $this->all_menus = get_option( 'dokan_customized_menus' );
    $this->number_of_menus = count( get_option( 'dokan_customized_menus' ) );
  }
// substr( $this->link(), 43, -2 );
  public function render_content() { 
    $this->value = $this->value() == 'Settings <i class="fa fa-angle-right pull-right"></i>' ? 'Settings' : $this->value();
    ?>
    <div class="menu-item-bar">
      <div class="menu-item-handle ui-sortable-handle">
        <span class="item-type" aria-hidden="true"></span>
          <span class="item-title" aria-hidden="true"><?php echo $this->value(); ?></span>
            <span class="spinner"></span>
            <span class="menu-item-title"><?php 'menu title' ?></span>
          </span>
          <span class="item-controls">
            <button type="button" class="button-link item-edit" aria-expanded="false"><span class="screen-reader-text">Edit menu item: (no label) (Page)</span><span class="toggle-indicator" aria-hidden="true"></span></button>
            <button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text">Remove Menu Item: (no label) (Page)</span></button>
          </span>
      </div>
    </div>

    <div class="menu-item-settings" id="menu-item-settings" style="height: 0; padding: 0; transition: all .5s ease">
      <p class="description description-thin">
        <label for="edit-menu-item-title">
          Title<br>
          <input type="text" id="" placeholder="Menu Title" class="widefat edit-menu-item-title" name="<?php echo 'menu-item-title' ?>" value="<?php echo $this->value; ?>" <?php echo $this->link() ?>>
        </label>
      </p>      
      <p class="description description-thin">
        <label for="edit-menu-item-title">
          Icon<br>
          <input type="text" id="edit-menu-item-title" placeholder="Menu Title" class="widefat edit-menu-item-title" name="menu-item-title">
        </label>
      </p>

      <p class="description description-thin">
        <label for="edit-menu-item-title">
          Position<br>
          <input type="text" id="edit-menu-item-title" placeholder="Menu Title" class="widefat edit-menu-item-title" name="menu-item-title" value="<?php $this->value() ?>">
        </label>
      </p>

      <p class="field-link-target description description-thin">
        <label for="edit-menu-item-target-118">
          <input type="checkbox" id="edit-menu-item-target-118" class="edit-menu-item-target" value="_blank" name="menu-item-target">
          Open link in a new tab        </label>
      </p>
    </div>
  <?php }
}
