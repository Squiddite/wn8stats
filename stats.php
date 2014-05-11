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
   <script src="js/sitefunctions.js"></script>

   <div class="prototype-row">
      <select id="tank-list" name=tankid required>
         <option value=0 selected>----------</option>
      </select>
      <input type=text name=kills size=3 placeholder="kills" />
      <input type=text name=damage size=3 placeholder="damage" />
      <input type=text name=detections size=3 placeholder="spots" />
      <input type=text name=defense size=3 placeholder="defense" />
      <input type=text name=assist size=3 placeholder="assist" />
      <label>Victory<input type=checkbox name=victory size=3 /></label>
      <label>Survived<input type=checkbox name=survived size=3 /></label>
   </div>

   <form action="" method=post id="form1">
      <div class=stats-header">
      </div>

      <div id="stats-footer" class=stats-footer">
      </div>

      <input type=button id=add-row name=add-row value="Add Row" />
      <input type=button id=calculate name=calculate value="Calculate" />
      <input type=submit id=submit name=submit value="Submit" />
   </form>


</body>
</html>
