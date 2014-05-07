$( function() {
   $( "#form1 :submit" ).click( function( event ) {
      console.log( $( "#form1" ).serializeObject() );
      event.preventDefault();
   });
});

$( function() {
   $.getJSON( "ajax.php?get=tankList", function( result ) {
      $.each( result, function( tank ) {
         $( "#tank-list" ).append( $( "<option />" ).val( result[tank].id ).text( result[tank].name ));
      });

      var blankRow = $( ".test-row" ).clone();
      $( "#add-row" ).click( function() {
         event.preventDefault();

         blankRow.clone().insertBefore( $( "#add-row" ));
         x = $( "#form1 div" ).last().prev().find( "select :selected" ).val();
         $( "#form1 div" ).last().find( "select" ).val( x ).prop( "selected", true );
      });

   });
});
