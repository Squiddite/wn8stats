<?
class wargamingAPIHandler {

   private $db;
   private $applicationId;
   private $apiLocation = "https://api.worldoftanks.com/";

   public function wargamingAPIHandler( $configFile, $database ) {
      try {
         if( !file_exists( $configFile )) throw new Exception( "Unable to load configuration" );
         $config = explode( ",", trim( file_get_contents( $configFile )));
         if( empty( $config )) throw new Exception( "Unable to load configuration" );
         $this->applicationId = $config[4];
         $this->attachDatabaseHandle( $database );
      } catch( Exception $e ) { throw $e; }
   }

   public function attachDatabaseHandle( $database ) {
      if( !( $database instanceof database )) throw new Exception( "No database connection" );
      $this->db = $database;

   }

   public function getTankList() {
      $apiFunction = "wot/encyclopedia/tanks/";
      $json = file_get_contents( "{$this->apiLocation}{$apiFunction}?application_id={$this->applicationId}" );
      $tankList = json_decode( $json );
      if( $tankList->status != "ok" ) return false;
      return $tankList;

   }

   public function updateTankList( $tankList ) {
      $tanks = $tankList->data;

      $sql = "insert into tanks ( id, name, tier, class, premium ) values ";
      $comma = "";
      foreach( $tanks as $id => $tank ) {
         switch( $tank->type ) {
          case "heavyTank":
            $class = 0;
            break;
          case "mediumTank":
            $class = 1;
            break;
          case "lightTank":
            $class = 3;
            break;
          case "AT-SPG":
            $class = 4;
            break;
          case "SPG":
            $class = 5;
            break;
         }
         if( $tank->is_premium ) $premium = 1; else $premium = 0;
         $sql .= "{$comma} ( {$tank->tank_id}, '{$tank->name_i18n}', {$tank->level}, {$class}, {$premium} )";
         $comma = ",";
      }
      $this->db->query( "delete from tanks" );
      $this->db->query( $sql );

   }



}

?>
