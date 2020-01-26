<?php
namespace App\Models;

class PokerWinnings {

    /**
     * Rank. Returns the strenght of the players hand higher is better
     * 
     * @param array $hand1  The card information for player 1
     * @param array $hand2  The card information for player 2
     *
     * @return int
     */
    public static function getWinner( $hand1, $hand2 ){
        if( $hand1->getRank() > $hand2->getRank() ){
            return 1;
        } else if( $hand1->getRank() < $hand2->getRank() ){
            return 2;
        } else { // rank tie we need more processing :)
            switch( $hand1->getRank() ){
                case 10: // Royal Flush ... since we are not considering an suit order the result will always be a draw/tie.
                    return 0;
                    break;
                case 8: // Four of a kind
                case 7: // Full House
                    return static::winnerForFourOfAKindOrFullHouse( $hand1, $hand2 );
                    break;
                case 3: // two pair 
                    return static::winnerForTwoPair( $hand1, $hand2 );
                    break;
                case 2: // one pair
                case 4: // Three of a kind
                    return static::winnerForOnePairOrThreeOfAKing( $hand1, $hand2 );
                    break;
                default: 
                    // 1 High card 
                    // 6 Flush
                    // 9 Straight Flush
                    // 5 Straight
                    return static::winnerForHighCardOrFlush( $hand1, $hand2 );
                    break;
            }
        }
    }


    /**
     * prepare the array to determine the winner in Four of a kind/Full House vs
     * 
     * @param array $hand  The hand information for a player
     *
     * @return array  array of card power values.
     */
    private static function prepareArrayForFourOfAKindOrFullHouse( $hand ){
        // get hands statistics.
        $stats = $hand->getStats();
        // extract the cards face value.
        $order = \array_keys( $stats['nos'] );
        return $order;
    }

    /**
     * determine the winner if both players have a One Pair
     * 
     * @param array $hand1  The card information for player 1
     * @param array $hand2  The card information for player 2
     *
     * @return int
     */
    private static function winnerForFourOfAKindOrFullHouse( $hand1, $hand2 ){
        $order1 = static::prepareArrayForFourOfAKindOrFullHouse( $hand1 );
        $order2 = static::prepareArrayForFourOfAKindOrFullHouse( $hand2 );
        return static::higherWins( $order1, $order2 );
    }


    /**
     * prepare the array to determine the winner in Four of a kind/Full House vs
     * 
     * @param array $hand  The hand information for a player
     *
     * @return array  array of card power values.
     */
    private static function prepareArrayForOnePairOrThreeOfAKing( $hand ){
        // get hands statistics.
        $stats = $hand->getStats();
        // extract the cards face value.
        $order = \array_keys( $stats['nos'] );
        // remove the first item in the array (the one pair)
        $pair = static::getAndUnset( 0, $order );
        // reorder the remaing single cards by their value. 
        rsort( $order );
        // insert the pair value in the first place of the array. 
        array_unshift( $order, $pair );
        return $order;
    }

    /**
     * determine the winner if both players have a One Pair
     * 
     * @param array $hand1  The card information for player 1
     * @param array $hand2  The card information for player 2
     *
     * @return int
     */
    private static function winnerForOnePairOrThreeOfAKing( $hand1, $hand2 ){
        $order1 = static::prepareArrayForOnePairOrThreeOfAKing( $hand1 );
        $order2 = static::prepareArrayForOnePairOrThreeOfAKing( $hand2 );
        return static::higherWins( $order1, $order2 );
    }

    /**
     * prepare the array to determine the winnner in two pair vs
     * 
     * @param array $hand1  The card information for a player
     *
     * @return array  array of card power values.
     */
    private static function prepareArrayForTwoPair( $hand ){
        // get hands statistics.
        $stats = $hand->getStats();
        // extract the cards face value.
        $order = \array_keys( $stats['nos'] );
        // remove the last item in the array (the one single card)
        $single = static::getAndUnset( 2, $order );
        // reorder the remaing single cards by their value. 
        rsort( $order );
        // insert the single value in the last place of the array. 
        $order[] = $single;
        return $order;        
    }

    /**
     * determine the winner if both players have a Two Pair
     * 
     * @param array $hand1  The card information for player 1
     * @param array $hand2  The card information for player 2
     *
     * @return int
     */
    private static function winnerForTwoPair( $hand1, $hand2 ){
        $order1 = static::prepareArrayForTwoPair( $hand1 );
        $order2 = static::prepareArrayForTwoPair( $hand2 );
        return static::higherWins( $order1, $order2 );
    }

    /**
     * determine the winner if both players have a High card or Flush
     * 
     * @param array $hand1  The card information for player 1
     * @param array $hand2  The card information for player 2
     *
     * @return int
     */
    private static function winnerForHighCardOrFlush( $hand1, $hand2 ){
        $nos1 = $hand1->getNos();
        $nos2 = $hand2->getNos();
        return static::higherWins( $nos1, $nos2 );
    }

    /**
     * determine the winner based on the array. 
     * 
     * @param array $array1  The card information for player 1
     * @param array $array2  The card information for player 2
     *
     * @return int
     */
    private static function higherWins( $array1, $array2 ){
        $smaller = $array1 <=> $array2; 
        switch( $smaller ){
            case -1: return 2;
            case 0: return 0;
            case 1: return 1;
        }

        // could use the pre PHP7 way to go it :)
        // for( $coun=0; $coun<sizeof( $array1 ); $coun++ ){
        //     if( $array1[ $coun ] > $array2[ $coun ] ){
        //         return 1;
        //     }else if( $array1[ $coun ] < $array2[ $coun ] ){
        //         return 2;
        //     }
        // }
        // return 0;
    }

    /**
     * determine the winner based on the array. 
     * 
     * @param int $index  The index toe return and unset from the array
     * @param array &$array  The array
     *
     * @return int
     */
    private static function getAndUnset( $index, &$array ){
        $value = $array[ $index ];
        unset( $array[ $index ] );
        return $value;
    }

}
