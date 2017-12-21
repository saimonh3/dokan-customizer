(function($){
	$('.dokan-menu-customizer').on('click', function() {
		console.log('I\' being changed');
	});
	console.log('I am loaded');
	$('.button-link.item-edit').on('click', function() {
		$('dokan-menu-customizer').addClass('menu-item-edit-active');
		console.log('I\' being changed');
	});
})(jQuery)