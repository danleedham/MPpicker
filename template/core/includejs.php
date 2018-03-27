  <!-- Bootstrap & jQuery core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Bootcards JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootcards/1.1.2/js/bootcards.min.js"></script>

    <!-- FTLabs FastClick library-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.3/fastclick.min.js"></script>

    <script type="text/javascript">

	// Initialize Bootcards.
       
	bootcards.init( {
		offCanvasBackdrop : true,
		offCanvasHideOnMainClick : true,
		enableTabletPortraitMode : true,
		disableRubberBanding : true,
		disableBreakoutSelector : 'a.no-break-out'
	});

	//enable FastClick
	window.addEventListener('load', function() {
	  FastClick.attach(document.body);
	}, false);

	//activate the sub-menu options in the offcanvas menu
	$('.collapse').collapse();

    </script>