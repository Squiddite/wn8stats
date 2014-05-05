<?
class wn8Calculator {

   private $db = null;
   public $baselineHandler;

   public function wn8Calculator( $database = null ) {
//      $this->attachDatabaseHandle( $database );
      $this->baselineHandler = new baselineHandler( $database );

   }

/*
   public function attachDatabaseHandle( $database ) {
      if( !( $database instanceof database )) throw new Exception( "No database connection" );
      $this->db = $database;

   }
*/


}

?>
