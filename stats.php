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
<head>
   <link rel=stylesheet type="text/css" href="css/selectize.css">
</head>
<body>
   <script src="js/jquery-2.1.0.js"></script>
   <script src="js/sitefunctions.js"></script>
   <script src="js/selectize.js"></script>

   <div class="prototype-row">
      <select id="tank-list" name=tankid required>
         <option value="" selected>Select Tank</option>
      </select>
      <input type=text name=kills size=3 placeholder="kills" />
      <input type=text name=damage size=3 placeholder="damage" />
      <input type=text name=detections size=3 placeholder="spots" />
      <input type=text name=defense size=3 placeholder="defense" />
      <input type=text name=assist size=3 placeholder="assist" />
      <label>Victory<input type=checkbox id=victory class=victory name=victory size=3 /></label>
      <label>Survived<input type=checkbox id=survived class=survival name=survived size=3 /></label>
      <label class=wn8value></label>
   </div>

   <form action="" method=post id="form1">
      <div class=stats-header">
      </div>

      <div id="stats-footer" class=stats-footer">
      </div>

      <input type=button id=go name=go value="Go" />
      <input type=button id=update name=update value="Recalculate" />
      <input type=button id=reset name=reset value="Reset" />
   </form>

   <br />
   <label id=wn8aggregate></label>
   </br />
   <label id=wn8average></label>
   </br />
   <label id=winrate></label>


<!--
<hr>

<div id=spambox style="width:420">
   <select id="select-tank" placeholder="Tank" class="selectized">
      <option value="">Tank</option>
      <option value=1>asdf</option>
      <option value=3>sausages</option>
      <option value=2>pasta</option>
   </select>
</div>
<script>
   $( "#select-tank" ).selectize({
      create: false,
      sortField: { field: "text" }
   });
</script>
-->

</body>
</html>
