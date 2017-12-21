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

  public function render_content() {
    $this->set_all_menus();

    if ($this->number_of_menus > 0 ) {
      foreach ( $this->all_menus as $menu ) { ?>

    <li id="customize-control" class="dokan-menu-customizer customize-control customize-control-nav_menu_item menu-item menu-item-depth-0 menu-item-page move-left-disabled move-up-disabled move-right-disabled menu-item-edit-inactive" style="display: list-item;">
    <div class="menu-item-bar">
    <div class="menu-item-handle ui-sortable-handle">
      <span class="item-type" aria-hidden="true">Page</span>
        <span class="item-title" aria-hidden="true">
          <span class="spinner"></span>
          <span class="menu-item-title"><?php echo $menu['title']; ?></span>
        </span>
        <span class="item-controls">
          <button type="button" class="button-link item-edit" aria-expanded="false"><span class="screen-reader-text">Edit menu item: (no label) (Page)</span><span class="toggle-indicator" aria-hidden="true"></span></button>
          <button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text">Remove Menu Item: (no label) (Page)</span></button>
        </span>
      <div class="menu-item-reorder-nav">
        <button type="button" class="menus-move-up" tabindex="-1" aria-hidden="true">Move up</button><button type="button" class="menus-move-down" tabindex="0" aria-hidden="false">Move down</button><button type="button" class="menus-move-left" tabindex="-1" aria-hidden="true">Move one level up</button><button type="button" class="menus-move-right" tabindex="-1" aria-hidden="true">Move one level down</button>     </div>
    
      </div>
    </div>

    <div class="menu-item-settings" id="menu-item-settings-118" style="display: none;"><div class="customize-control-notifications-container" style="display: none;"><ul></ul></div>
      
      <p class="description description-thin">
        <label for="edit-menu-item-title-118">
          Title<br>
          <input type="text" id="edit-menu-item-title-118" placeholder="<?php echo $menu['title'] ?>" class="widefat edit-menu-item-title" name="menu-item-title" value="<?php $this->value() ?>" <?php $this->link() ?>>
        </label>
      </p>      
      <p class="description description-thin">
        <label for="edit-menu-item-title-118">
          Icon<br>
          <input type="text" id="edit-menu-item-title-118" placeholder="<?php echo $menu['icon'] ?>" class="widefat edit-menu-item-title" name="menu-item-title">
        </label>
      </p>

      <p class="description description-thin">
        <label for="edit-menu-item-title-118">
          Position<br>
          <input type="text" id="edit-menu-item-title-118" placeholder="<?php echo $menu['pos'] ?>" class="widefat edit-menu-item-title" name="menu-item-title">
        </label>
      </p>

      <p class="field-link-target description description-thin">
        <label for="edit-menu-item-target-118">
          <input type="checkbox" id="edit-menu-item-target-118" class="edit-menu-item-target" value="_blank" name="menu-item-target">
          Open link in a new tab        </label>
      </p>
      <input type="hidden" name="menu-item-db-id[118]" class="menu-item-data-db-id" value="118">
      <input type="hidden" name="menu-item-parent-id[118]" class="menu-item-data-parent-id" value="0">
    </div><!-- .menu-item-settings-->
    <ul class="menu-item-transport"></ul>
    </li>  
      <?php }
    } else {
      echo 'There is no menu to customize';
    }
    ?>

<script type="text/javascript">
(function($){
  $('.menu-item-bar').on('click', function(e) {
    var self = $(this);

    if ( self.hasClass('menu-item-edit-inactive') ) {
      self.addClass('menu-item-edit-active');
      self.removeClass('menu-item-edit-inactive');
      $('.menu-item-settings').css({
        'display': 'block'
      });
    } else {
      self.removeClass('menu-item-edit-active');
      self.addClass('menu-item-edit-inactive');
      $('.menu-item-settings').css({
        'display': 'none'
      });
    }
  });
})(jQuery)
</script>
  <?php }
}
