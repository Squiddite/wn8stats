<?
class wn8Calculator {

   private $db = null;
   public $baselineHandler;

   public function wn8Calculator( $database = null ) {
      $this->baselineHandler = new baselineHandler( $database );

   }

   public function calculateWN8Aggregate( $statObj, $wr50 = false ) {
      if( !is_array( $statObj )) $statObjArr = array( $statObj ); else $statObjArr = $statObj;

      $baselines = new stdClass();
      $stats = new stdClass();
      foreach( $statObjArr as $statObj ) {
         $baseline = $this->baselineHandler->checkBaseline( $statObj->tankid );

         $baselines->expectedDamage += (float) $baseline->expectedDamage;
         $baselines->expectedDetections += (float) $baseline->expectedDetections;
         $baselines->expectedKills += (float) $baseline->expectedKills;
         $baselines->expectedDefense += (float) $baseline->expectedDefense;
         $baselines->expectedWinrate += (float) $baseline->expectedWinrate;

         $stats->damage += (float) $statObj->damage;
         $stats->detections += (float) $statObj->detections;
         $stats->kills += (float) $statObj->kills;
         $stats->defense += (float) $statObj->defense;
         $stats->winrate += (float) $statObj->winrate;

         $individualWN8s[] = $this->calculateWN8( $statObj, $baseline, $wr50 );
      }
      $aggregateWN8 = $this->calculateWN8( $stats, $baselines, $wr50 );
      $averageWN8 = array_sum( $individualWN8s ) / sizeof( $individualWN8s );

      $wn8 = new stdClass();
      $wn8->aggregateWN8 = $aggregateWN8;
      $wn8->averageWN8 = $averageWN8;
      $wn8->individualWN8s = $individualWN8s;
      return $wn8;
   }

   public function calculateWN8( $statObj, $baselineObj, $wr50 = false ) {
      $rDamage  = (float) $statObj->damage     / (float) $baselineObj->expectedDamage;
      $rSpots   = (float) $statObj->detections / (float) $baselineObj->expectedDetections;
      $rFrags   = (float) $statObj->kills      / (float) $baselineObj->expectedKills;
      $rDefense = (float) $statObj->defense    / (float) $baselineObj->expectedDefense;
      $rWinrate = (float) $statObj->victory    / (float) $baselineObj->expectedWinrate;
      $rWinrate = .5 / (float) $baselineObj->expectedWinrate;
      $rWinrate *= 100;
      if( $wr50 ) $rWinrate = 1;

      $cWinrate = max( 0, (( $rWinrate - 0.71 ) / ( 1 - 0.71 )));
      $cDamage  = max( 0, (( $rDamage - 0.22 ) / ( 1 - 0.22 )));
      $cFrags   = max( 0, ( min( $cDamage + 0.2, (( $rFrags - 0.12 ) / ( 1 - 0.12 )))));
      $cSpots   = max( 0, ( min( $cDamage + 0.1, (( $rSpots - 0.38 ) / ( 1 - 0.38 )))));
      $cDefense = max( 0, ( min( $cDamage + 0.1, (( $rDefense - 0.1 ) / ( 1 - 0.1 )))));

      $wn8 = ( 980 * $cDamage ) + ( 210 * $cDamage * $cFrags ) + ( 155 * $cFrags * $cSpots ) + ( 75 * $cDefense * $cFrags ) + ( 145 * min( 1.8, $cWinrate ));
      return $wn8;

   }

}

?>
