(function($){
	setTimeout(function() {
	  $('li.customize-control').addClass('menu-item-edit-inactive');
	  
	  $('.menu-item-bar').on('click', function(e) {
	    var self = $(this);

	    if ( self.closest('li').hasClass('menu-item-edit-inactive') ) {
	      self.closest('li').addClass('menu-item-edit-active');
	      self.closest('li').removeClass('menu-item-edit-inactive');
	      self.closest('li').find('.menu-item-settings').css({
	      	'height': 'auto',
	      	'padding': '10px'
	      });
	    } else {
	      self.closest('li').removeClass('menu-item-edit-active');
	      self.closest('li').addClass('menu-item-edit-inactive');    
	      self.closest('li').find('.menu-item-settings').css({
	      	'height': '0',
	      	'padding': '0'
	      });
	    }
	  });
	}, 1000);
})(jQuery)