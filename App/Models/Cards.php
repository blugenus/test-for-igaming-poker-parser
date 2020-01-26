<?php
namespace App\Models;

class Cards {

    // assigning a power value to the number/face
    const CARDS = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14
    ];

    /**
     * Decods the 2 letter Card String into a usable object.
     * 
     * @param array $card  2 letter Card String
     *
     * @return array  
     */
    public static function decode( $card ) {
        return [ 
            'face' => substr( $card, 0, 1 ),
            'no' => static::CARDS[ substr( $card, 0, 1 ) ],
            'suit' => substr( $card, 1, 1 )
        ];
    }

}