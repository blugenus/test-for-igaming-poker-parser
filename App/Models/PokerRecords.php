<?php
namespace App\Models;

class PokerRecords extends \App\Model {

    const BULKINSERTQTY = 100;
    private $uploadId = 0;
    private $bulkInsertArray = [];

    /**
     * Constructor to store the poker records
     *
     * @param array $userId  The user which uploaded the file.
     *
     * @return void
     */    
    public function __construct( $userId ){
        $result = static::execute( "INSERT INTO `uploads` ( `userId`, `startDateTime` ) VALUES ( $userId, NOW() );" );
        $this->uploadId = $result['insertId'];
    }

    /**
     * Adds items to the bulk insert queue. 
     *
     * @param array $handId  the current hand Id
     * @param array $cardId  the current card Id
     * @param array $player  the current player 
     * @param array $face  the current card's face
     * @param array $suit  the current card's suit
     *
     * @return void
     */    
    public function queueForBulkInsert( $handId, $hand1, $rank1, $hand2, $rank2, $result ){
        $tmp = [ 
            $this->uploadId, 
            $handId, 
            '\'' . $hand1 . '\'', 
            $rank1, 
            '\'' . $hand2 . '\'', 
            $rank2, 
            $result
        ];
        $this->bulkInsertArray[] = '(' . implode( ',', $tmp ) . ')';
        if( sizeOf( $this->bulkInsertArray ) >= self::BULKINSERTQTY ){
            $this->executeBulkInsertQueue();
        }
    }

    /**
     * Executes and resets bulk insert queue. 
     *     *
     * @return void
     */ 
    private function executeBulkInsertQueue(){
        if( sizeOf( $this->bulkInsertArray ) > 0 ){
            static::execute( "
                INSERT INTO `uploads_hands` 
                (`uploadId`, `handId`, `player1hand`, `player1Rank`, `player2hand`, `player2Rank`, `result`) 
                VALUES" . implode( ',',$this->bulkInsertArray )
            );
            $this->bulkInsertArray = [];
        }
    }

    /**
     * Completes the Poker Records storage.
     *     *
     * @return void
     */ 
    public function complete(){
        $this->executeBulkInsertQueue();
        static::execute( "
            UPDATE `uploads` 
            SET 
                `hands` = (
                    SELECT COUNT(1) 
                    FROM `uploads_hands` 
                    WHERE `uploadId` = $this->uploadId
                ), 
                `completed` = 1, 
                `completedDateTime` = NOW() 
            WHERE `uploadId` = $this->uploadId
        " );        
    }


}