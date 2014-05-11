$( function() {
   $( "#form1 :submit" ).click( function( event ) {
      event.preventDefault();
      json = serializeForm( $( "#form1" ));
      json = JSON.stringify( json );
      $.getJSON( "ajax.php?get=calculateWN8&json="+json, function( result ) {
         console.log( result );
      });
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

function serializeForm( form ) {
   json = [];
   $( form ).find( "div.test-row" ).each( function() {
      line = {};
      row = $( this );
      row.children().each( function() {
         element = $( this );
         if( element.is( "select" )) { type = "s"; }
         if( element.is( "input" ))  { type = "i"; }
         if( element.is( "label" ))  { type = "l"; }
         switch( type ) {
          case "s":
          case "i":
            line[ element.attr( "name" )] = element.val();
            break;
          case "l":
            element = $( element[0].childNodes[1] );
            line[ element.attr( "name" )] = element.prop( "checked" );
            break;
         }
      });
      json.push( line );
   });
   return json;
};
