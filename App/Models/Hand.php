<?php
namespace App\Models;

use App\Models\Cards;
use App\Models\PokerRanks;

class Hand {

    private $cards = []; // will be used to store data in db?
    private $nos = []; // card number/face
    private $stats = [ // statistics used to identiify the rank of the hand.
        'suits' => [],
        'nos' => []
    ];
    private $rank = 0;

    /**
     * class contructer 
     * 
     * @param array $cards  The card information for a hand
     *
     * @return void
     */
    public function __construct( $cards ) {
        foreach( $cards as $card ){
            $card = Cards::decode( $card );
            $this->cards[] = $card;
            $this->nos[] = $card['no'];

            $this->setStatsSuits( $card );
            $this->setStatsNos( $card );           
        }
        rsort( $this->nos ); // sorting the cards numbers/faces in descending order
        arsort( $this->stats['nos'] ); 
        $this->rank = PokerRanks::rank( $this->nos, $this->stats );
    }

    /**
     * Return the rank of the player's hand
     *
     * @return int
     */    
    public function getRank(){
        return $this->rank;
    }

    /**
     * Return the player's hand cards numbers/faces
     *
     * @return array
     */    
    public function getNos(){
        return $this->nos;
    }

    // *
    //  * Return the player's cards
    //  *
    //  * @return array         
    public function getCards(){
        return $this->cards;
    }

    /**
     * Return the rank of the player's stats
     *
     * @return array
     */    
    public function getStats(){
        return $this->stats;
    }

    /**
     * prepare statistics regarding suits
     *
     * @param array $card  The cards the player has
     *
     * @return void
     */    
    private function setStatsSuits( $card ){
        if( !isset( $this->stats['suits'][ $card['suit'] ] ) ) {
            $this->stats['suits'][ $card['suit'] ] = 0;
        }
        $this->stats['suits'][ $card['suit'] ] += 1;
    }

    /*
     * prepare statistics regarding numbers/faces
     *
     * @param array $card  The cards the player has
     *
     * @return void
     */    
    private function setStatsNos( $card ){
        if( !isset( $this->stats['nos'][ $card['no'] ] ) ) {
            $this->stats['nos'][ $card['no'] ] = 0;
        }
        $this->stats['nos'][ $card['no'] ] += 1;
    }

}