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

var_dump( $b->loadStoredBaselines() );

?>
