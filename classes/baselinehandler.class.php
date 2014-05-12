<?
class baselineHandler {

   private $db = null;
   private $baselineData = null;
   private $baselineDataURL = "http://www.wnefficiency.net/exp/expected_tank_values_latest.json";

   public function baselineHandler( $database = null ) {
      if( !is_null( $database )) $this->attachDatabaseHandle( $database );

   }

   public function attachDatabaseHandle( $database ) {
      if( !( $database instanceof database )) throw new Exception( "No database connection" );
      $this->db = $database;

   }

   public function loadStoredBaselines() {
      if( $this->db === null ) return false;
      $baselineData = $this->db->instantQuery( "select * from wn8baselines where version = ( select max( version ) from wn8baselines )" );

      foreach( $baselineData as $tank ) {
         $tank->version = (int) $tank->version;
         $tank->tankid = (int) $tank->tankid;
         $tank->expectedKills = (float) $tank->expectedKills;
         $tank->expectedDamage = (float) $tank->expectedDamage;
         $tank->expectedDetections = (float) $tank->expectedDetections;
         $tank->expectedDefense = (float) $tank->expectedDefense;
         $tank->expectedWinrate = (float) $tank->expectedWinrate;
         $data[$tank->tankid] = clone $tank;
      }

      $this->baselineData = $data;
      return $data;
   }

   public function updateBaselines() {
      if( $this->db === null ) return false;

      try {
         $json = file_get_contents( $this->baselineDataURL );
         if( empty( $json )) throw new Exception( "Unable to retrieve baseline data from {$this->baselineDataURL}" );
      } catch( Exception $e ) {
         die( $e );
      }
      $baselineData = json_decode( $json );

      // insert new baseline data if detected
      $sourceVersion = (int) $baselineData->header->version;
      $currentVersion = $this->db->instantQuery( "select max( version ) as version from wn8baselines" );
      $currentVersion = (int) $currentVersion[0]->version;

      if( $sourceVersion > $currentVersion ) {
         $sql = "insert into wn8baselines ( version, tankid, ex_kills, ex_damage, ex_detections, ex_defense, ex_winrate ) values ";
         $comma = "";
         foreach( $baselineData->data as $tank ) {
            $tankid = (int) $tank->IDNum;
            $kills = (float) $tank->expFrag;
            $damage = (float) $tank->expDamage;
            $detections = (float) $tank->expSpot;
            $defense = (float) $tank->expDef;
            $winrate = (float) $tank->expWinRate;
            $sql .= "{$comma} ( {$sourceVersion}, {$tankid}, {$kills}, {$damage}, {$detections}, {$defense}, {$winrate} )";
            $comma = ",";
         }
         $this->db->query( $sql );
      }
   }

   public function checkBaseline( $tankid ) {
      if( $this->baselineData === null ) $this->loadStoredBaselines();
      return $this->baselineData[ $tankid ];
   }
}

?>
