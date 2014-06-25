// set up template row for cloning
$( function() {
   $( ".prototype-row" ).toggle();
   $.getJSON( "ajax.php?get=tankList", function( result ) {
      $.each( result, function( tank ) {
         $( ".prototype-row #tank-list" ).append( $( "<option />" ).val( result[tank].id ).text( result[tank].name ));
      });

      cloneRow();

      testWackySelectizeNonsense();
   });
});

function testWackySelectizeNonsense() {
   //
   // wacky test nonsense
   //
   blankRow = $( ".prototype-row" ).clone();
   event.preventDefault();

   blankRow.toggleClass( "prototype-row fake-row" );
   blankRow.toggle();
   blankRow.clone().appendTo( "#spambox" );
   $( "#spambox .fake-row select" ).last().selectize({
      create: false,
      sortField: { field: "text" }
   });
};

// button handlers
$( function() {
   $( "#go" ).click( function() {
      event.preventDefault();
      calculateWN8();
      calculateWinrate();
      cloneRow();
   });

   $( "#update" ).click( function() {
      event.preventDefault();
      $( "#form1 .test-row" ).last().remove();
      calculateWN8();
      calculateWinrate();
      cloneRow();
   });

   $( "#reset" ).click( function() {
      event.preventDefault();
      console.log( "reset" );
      $( "#form1 .test-row" ).remove();
      cloneRow();
   });
});

function calculateWN8() {
   json = serializeForm( $( "#form1" ));
   json = JSON.stringify( json );
   $.getJSON( "ajax.php?get=calculateWN8&json="+json, function( result ) {
      console.log( result );
      $( "#form1 .test-row" ).each( function( rownum ) {
         wn8 = result.individualWN8s[ rownum ];
         color = getColorScaleByWN8( wn8 );
         $(this).find( ".wn8value" ).text( wn8 ).css( "background-color", color ).css( "color", "ffffff" );
      });
      wn8 = result.aggregateWN8;
      color = getColorScaleByWN8( wn8 );
      $( "#wn8aggregate" ).text( wn8 ).css( "background-color", color ).css( "color", "ffffff" );

      wn8 = result.averageWN8;
      color = getColorScaleByWN8( wn8 );
      $( "#wn8average" ).text( wn8 ).css( "background-color", color ).css( "color", "ffffff" );
   });
}

// row cloner
function cloneRow() {
   blankRow = $( ".prototype-row" ).clone();
   event.preventDefault();

   blankRow.toggleClass( "prototype-row test-row" );
   blankRow.toggle();
   blankRow.clone().insertBefore( "#stats-footer" );
   $( "#form1 .test-row select" ).last().focus();
   x = $( "#form1 .test-row" ).last().prev().find( "select :selected" ).val();
   if( typeof x != "undefined" ) $( "#form1 .test-row" ).last().find( "select" ).val( x ).prop( "selected", true );
}

function calculateWinrate() {
   battles = 0;
   wins = 0;

   $( ".test-row #victory" ).each( function() {
      battles++;
      isChecked = $(this).is( ":checked" );
      if( isChecked ) { wins++; }
   });
   winrate = Math.round(( wins / battles ) * 100 );

   color = getColorScaleByWinrate( winrate );
   $( "#winrate" ).text( winrate ).css( "background-color", color ).css( "color", "ffffff" );
}

// form serializer for calculator submission
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
}

function getColorScaleByWinrate( winrate ) {
   winrates = [
      0,
      45,
      47,
      49,
      52,
      54,
      56,
      60,
      65
   ];

   for( i = 0; i < winrates.length; i++ ) {
      if( winrates[ i ] <= winrate ) { colorIndex = i; }
   }

   color = colorScale( colorIndex );
   return color;
}

function getColorScaleByWN8( wn8 ) {
   wn8values = [
      -9999,
      300,
      600,
      900,
      1250,
      1600,
      1900,
      2350,
      2900
   ];

   for( i = 0; i < wn8values.length; i++ ) {
      if( wn8values[ i ] <= wn8 ) { colorIndex = i }
   }

   color = colorScale( colorIndex );
   return color;
}

function colorScale( index ) {
   colors = [
      "#000000",
      "#5e0000",
      "#cd3333",
      "#d7b600",
      "#6d9521",
      "#4c762e",
      "#4a92b7",
      "#83579d",
      "#5a3175"
   ];
   return colors[ index ];
}
