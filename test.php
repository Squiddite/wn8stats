<?
spl_autoload_register( function( $c ) {
   if( file_exists( "classes" )) $path = "classes"; else $path = ".";
   $namespace = explode( "\\", $c );
   $class = strtolower( $namespace[ sizeof( $namespace ) - 1 ] );
   include "{$path}/{$class}.class.php";
});

try {
   $db = database::getInstance( ".config" );
   $api = new wargamingAPIHandler( ".config", $db );
   $b = new baselineHandler( $db );
} catch( Exception $e ) {
   die( $e );
}

//$api->updateTankList( $api->getTankList() );
//$b->updateBaselines();


/*
$b = new baselineHandler( $db );
$b->fetchNewBaselines();
var_dump( $b->baselineData->header->version );
foreach( $b->baselineData->data as $data ) {
   var_dump( $data->IDNum );
}
*/
?>
