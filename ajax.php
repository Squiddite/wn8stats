<?
spl_autoload_register( function( $c ) {
   if( file_exists( "classes" )) $path = "classes"; else $path = ".";
   $namespace = explode( "\\", $c );
   $class = strtolower( $namespace[ sizeof( $namespace ) - 1 ] );
   include "{$path}/{$class}.class.php";
});

/*
try {
   $db = database::getInstance( ".config" );
   $c = new wn8calculator( $db );
   $b = $c->baselineHandler;
   $api = new wargamingAPIHandler( ".config", $db );
} catch( Exception $e ) {
   die( $e );
}
*/

if( isset( $_REQUEST["get"] )) echo $_REQUEST["get"]();

function tankList() {
   $db = database::getInstance( ".config" );
   $tanks = $db->instantQuery( "select id, name, class from tanks order by name" );
   $json = json_encode( $tanks );
   return $json;
}

function calculateWN8() {
   $db = database::getInstance( ".config" );
   $c = new wn8calculator( $db );
   $b = $c->baselineHandler;
   $json = $_REQUEST["json"];
   $statArray = json_decode( $_REQUEST["json"] );

   $w = $c->calculateWN8Aggregate( $statArray );
   return json_encode( $w );

}

function updateBaselines() {
   $db = database::getInstance( ".config" );
   $c = new wn8calculator( $db );
   $b = $c->baselineHandler;

   $v = $b->updateBaselines();
   return $v;
}
?>
