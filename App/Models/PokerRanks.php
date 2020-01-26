<?php
namespace App\Models;

class PokerRanks {

    public const RANKS = [
        0 => 'Invalid',
        1 => 'High card',
        2 => 'One Pair',
        3 => 'Two Pair',
        4 => 'Three of a Kind',
        5 => 'Straight',
        6 => 'Flush',
        7 => 'Full house',
        8 => 'Four of a Kind',
        9 => 'Straight Flush',
        10 => 'Royal Flush'
    ];

    /**
     * Rank. Returns the strenght of the players hand higher is better
     * 
     * @param array $nos  The cards number/face the player has in descending order (no suit info)
     * @param array $stats  Basic Statistics about the cards the play hand.
     *
     * @return int
     */
    public static function rank( $nos, $stats ){
        if( sizeof( $stats['suits'] ) == 1 ){ // can be Royal Flush, Straight Flush or Flush
            return static::rankForRoyalFlushStraightFlushOrFlush( $nos );
        } else {
            $sizeOfNos = sizeof( $stats['nos'] );
            switch( $sizeOfNos ){
                case 2: // Four of a kind or Full house
                    return static::rankForFourOfAKindOrFullHouse( $stats );
                    break;
                case 3; // Three of a kind or Two Pair
                    return static::rankForThreeOfAKindOrTwoPair( $stats );
                    break;
                case 4; // can only be One Pair
                    return 2;
                    break;
                default: // Straight or High card
                    return static::rankForStraightOrHighCard( $nos );
                    break;
            }
        }
    }

    /**
     * Determines if the player hand is a Royal Flush, Straight Flush or Flush
     * as all 5 cards are of the same type.
     * 
     * @param array $nos  The cards number/face the player has in descending order (no suit info)
     *
     * @return int
     */
    private static function rankForRoyalFlushStraightFlushOrFlush( $nos ){
        // if differnce in card is 4 then it must be either Royal Flush or Straight Flush
        if( $nos[0] - $nos[4] == 4 ){
            if( $nos[0] == 14 ) { // must be Royal Flush if is Ace
                return 10;
            } else { // Straight Flush
                return 9;
            }
        } else {
            return 6; // seems like a Flush
        }
    }

    /**
     * Determines if the player hand is Four of a kind or Full house
     * 
     * @param array $stats  The stats the player has
     *
     * @return int
     */
    private static function rankForFourOfAKindOrFullHouse( $stats ){
        // will use the pre sorted nos stats in descending ... i.e. we will have 
        // 4,1 for Four of a kind or 
        // 3,2 for Full house :)
        if( $stats['nos'][ \array_keys( $stats['nos'] )[0] ] == 4 ){ //  Four of a kind
            return 8;
        } else { // Full house
            return 7;
        }
    }

    /**
     * Determines if the player hand is Three of a kind or Two Pair
     * 
     * @param array $stats  The stats the player has
     *
     * @return int
     */
    private static function rankForThreeOfAKindOrTwoPair( $stats ){
        // will use the pre sorted nos stats in descending ... i.e. we will have 
        // 3,1,1 for three of a kind or 
        // 2,2,1 for two pair :)
        // p.s. coiuld have used array_key_first but that would have needed php >= 7.3.0
        if( $stats['nos'][ \array_keys( $stats['nos'] )[0]  ] == 3 ){ //  Three of a kind
            return 4;
        } else { // Two Pair
            return 3;
        }
    }

    /**
     * Determines if the player hand is a Straight or High Card
     * 
     * @param array $nos  The cards number/face the player has in descending order (no suit info)
     *
     * @return int
     */
    private static function rankForStraightOrHighCard( $nos ){
        // if differnce in card is 4 then it must be a Straight 
        if( $nos[0] - $nos[4] == 4 ){
            return 5;
        } else {
            return 1;
        }
    }

}
