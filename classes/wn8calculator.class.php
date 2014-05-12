<?
class wn8Calculator {

   private $db = null;
   public $baselineHandler;

   public function wn8Calculator( $database = null ) {
      $this->baselineHandler = new baselineHandler( $database );

   }

   public function calculateWN8( $statObj ) {
      if( !is_array( $statObj )) $statObjArr = array( $statObj ); else $statObjArr = $statObj;

      foreach( $statObjArr as $statObj ) {
         $baseline = $this->baselineHandler->checkBaseline( $statObj->tankid );

         $rDamage  = (float) $statObj->damage     / (float) $baseline->expectedDamage;
         $rSpots   = (float) $statObj->detections / (float) $baseline->expectedDetections;
         $rFrags   = (float) $statObj->kills      / (float) $baseline->expectedKills;
         $rDefense = (float) $statObj->defense    / (float) $baseline->expectedDefense;
         $rWinrate = (float) $statObj->victory    / (float) $baseline->expectedWinrate;
         $rWinrate *= 100;

         $cWinrate = max( 0, (( $rWinrate - 0.71 ) / ( 1 - 0.71 )));
         $cDamage  = max( 0, (( $rDamage - 0.22 ) / ( 1 - 0.22 )));
         $cFrags   = max( 0, ( min( $cDamage + 0.2, (( $rFrags - 0.12 ) / ( 1 - 0.12 )))));
         $cSpots   = max( 0, ( min( $cDamage + 0.1, (( $rSpots - 0.38 ) / ( 1 - 0.38 )))));
         $cDefense = max( 0, ( min( $cDamage + 0.1, (( $rDefense - 0.1 ) / ( 1 - 0.1 )))));

         $wn8[] = ( 980 * $cDamage ) + ( 210 * $cDamage * $cFrags ) + ( 155 * $cFrags * $cSpots ) + ( 75 * $cDefense * $cFrags ) + ( 145 * min( 1.8, $cWinrate ));
      }
      return $wn8;
   }


}

?>
