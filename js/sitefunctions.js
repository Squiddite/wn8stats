$( function() {
   $( "#form1 :submit" ).click( function( event ) {
      console.log( $( "#form1" ).serializeObject() );
      event.preventDefault();
   });
});

$( function() {
   $.getJSON( "ajax.php?get=tankList", function( result ) {
      $.each( result, function( tank ) {
         $( ".prototype-row #tank-list" ).append( $( "<option />" ).val( result[tank].id ).text( result[tank].name ));
      });
   });
   $( ".prototype-row" ).toggle();
});


$( function() {
   $( "#add-row" ).click( function() {
      var blankRow = $( ".prototype-row" ).clone();
      event.preventDefault();

      blankRow.toggleClass( "prototype-row test-row" );
      blankRow.toggle();
      blankRow.clone().insertBefore( "#stats-footer" );
      x = $( "#form1 .test-row" ).last().prev().find( "select :selected" ).val();
      if( typeof x != "undefined" ) $( "#form1 .test-row" ).last().find( "select" ).val( x ).prop( "selected", true );
   });

});
