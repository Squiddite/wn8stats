<?
class database extends mysqli {

   protected static $instance;
   private $configFile;

   private function database( $configFile ) {
      $this->configFile = $configFile;
      try {
         if( !file_exists( $this->configFile )) throw new Exception( "Unable to load configuration" );
         $config = explode( ",", trim( file_get_contents( $this->configFile )));
         if( empty( $config )) throw new Exception( "Unable to load configuration" );
         parent::__construct( $config[0], $config[2], $config[3], $config[1] );
         if( $this->connect_errno ) throw new Exception( "Unable to connect to database" );
      } catch( Exception $e ) { throw $e; }
   }

   public static function getInstance( $configFile ) {
      if( !( self::$instance instanceof database )) self::$instance = new self( $configFile );
      return self::$instance;
   }

   public function instantQuery( $query ) {
      $result = $this->query( $query );

      if( $result instanceof mysqli_result ) {
         while( $row = $result->fetch_object() ) {
            $rows[] = $row;
         }
         return $rows;
      } else {
         return $result;
      }
   }

}

?>
