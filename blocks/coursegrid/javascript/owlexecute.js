$(document).ready(function() {
 
  $("#owl-example").owlCarousel( {
  items : 4,
  itemsScaleUp : false,
  autoPlay : true,
  //Pagination
    pagination : true,
    paginationNumbers: false,
	// Navigation
    navigation : true,
    navigationText : ["prev","next"],
    rewindNav : true,
    scrollPerPage : false,
  
  } );
 
});