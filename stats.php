<?
spl_autoload_register( function( $c ) {
   if( file_exists( "classes" )) $path = "classes"; else $path = ".";
   $namespace = explode( "\\", $c );
   $class = strtolower( $namespace[ sizeof( $namespace ) - 1 ] );
   include "{$path}/{$class}.class.php";
});

try {
   $db = database::getInstance( ".config" );
   $c = new wn8calculator( $db );
   $b = $c->baselineHandler;
   $api = new wargamingAPIHandler( ".config", $db );
} catch( Exception $e ) {
   die( $e );
}

?>
<html>
<body>
   <script src="js/jquery-2.1.0.js"></script>
   <script src="js/serializeObject.js"></script>
   <script type="text/javascript">

      $( function() {
         $( "#form1 :submit" ).click( function( event ) {
            console.log( "Submit" );
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
               console.log( "Add" );
               blankRow.clone().insertBefore( $( "#add-row" ));
               event.preventDefault();
            });

         });
      });

   </script>

   <form action="" method=post id="form1">
      <div class=test-row>
         <select id="tank-list" name=tankid required>
            <option value=0>----------</option>
         </select>
         <input type=text name=kills size=3 placeholder="kills" />
         <input type=text name=damage size=3 placeholder="damage" />
         <input type=text name=detections size=3 placeholder="spots" />
         <input type=text name=defense size=3 placeholder="defense" />
         <input type=text name=winrate size=3 placeholder="winrate" />
      </div>

      <input type=button id=add-row name=add-row value="Add Row" />
      <input type=submit id=submit name=submit value="Submit" />
   </form>


</body>
</html>
