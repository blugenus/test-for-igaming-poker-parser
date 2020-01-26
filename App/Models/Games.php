<?php
namespace App\Models;

use App\Models\PokerWinnings;
use App\Models\Hand;

class Games extends \App\Model {

    /**
     * Process the file with player hands to read.
     * All validation have been ignored and stated in the test description 
     * that all the hands are valid.
     *
     * @param string $file  the full path and filename of the file. 
     *
     * @return array  
     */
    public static function processFile( $file ){
        // preparing the data structure for the output to the browser
        $data = [
            'hands' => [],
            'ranks' => PokerRanks::RANKS,
            'outcome' => [0,0,0]
        ];

        $pokerRecords = new PokerRecords( Auth::getCurrentUser() );

        $handNo = 0;

        $hands = static::readFileGenerator( $file );
        foreach( $hands as $hand ){
            $handNo++;

            $cards = explode( ' ', $hand );
            if( sizeof( $cards ) == 10){
                
                // split the array for cards in multiple array of 5 cards each.
                // since 2 players it should be 2 arrays of 4 cards each.
                $players = array_chunk($cards, 5);

                // propare the players' hands in the required format
                $hand1 = new Hand( $players[0] );
                $hand2 = new Hand( $players[1] );

                // Determine the outcome of this hand.
                $winner = PokerWinnings::getWinner( $hand1, $hand2 );

                $data['hands'][] = [
                    'player1' => [
                        'cards' => $players[0],
                        'rank' => $hand1->getRank()
                    ],
                    'player2' => [
                        'cards' => $players[1],
                        'rank' => $hand2->getRank()
                    ],
                    'result' => $winner
                ];

                $pokerRecords->queueForBulkInsert( $handNo, implode( ' ', $players[0] ), $hand1->getRank(), implode( ' ', $players[1] ), $hand2->getRank(), $winner );

                $data['outcome'][ $winner ]++;
            }
        }
        // save the poker records still in the bulk insert queue
        $pokerRecords->complete();

        return $data;
    }

    /**
     * Open and reads the file and yields every line to be processed.
     *
     * We don't want to read to whole file at once to avoid memory issues, so line by line :)
     *
     * @param array $file  the full path and filename of the file. 
     *
     * @return void
     */    
    private static function readFileGenerator( $file ){
        $handle = fopen( $file, 'r' );
        while( !feof( $handle ) ) {
            yield trim( fgets( $handle ) );
        }
        fclose( $handle );        
    }

}