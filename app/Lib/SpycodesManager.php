<?php

namespace App\Lib;

class SpycodesManager
{
    public function generateWords()
    {

        $rawWords = [
            'hollywood', 'pantalla', 'jugar', 'dinosaurio', 'gato', 'grecia', 'pico', 'centro',
            'aspiradora', 'unicornio', 'enterrador', 'calcetin', 'Lago Ness', 'caballo', 'Berlin',
            'Ornitorrinco', 'Puerto', 'pecho', 'caja', 'compuesto', 'barco', 'reloj', 'espacio',
            'flauta', 'torre'
        ];

        $words = [];
        $cardsStack = [
            'red', 'red', 'red', 'red', 'red', 'red', 'red', 'red',
            'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue',
            'bystander','bystander','bystander','bystander','bystander','bystander','bystander',
            'assassin'
        ];
        // 0 is red, 1 is blue
        $rand = rand(0,1);
        if($rand === 0) {
            $cardsStack[] = 'red';
        }
        else {
            $cardsStack[] = 'blue';
        }


        $x = 'A';
        $y = 1;
        foreach($rawWords as $word) {

            $cardKey = array_rand($cardsStack, 1);

            $card = $cardsStack[$cardKey];

            unset($cardsStack[$cardKey]);

            $words[$x.$y] = [
                'word' => $word,
                'card' => $card,
                'facedown' => true
            ];
            $y++;

            if($y == 6) {
                $y = 1;
                $x++;
            }
        }

        \Redis::set('playing', true);
        \Redis::set('words', serialize($words));

        return $words;
    }

    public function isPlayInCourse()
    {
        return (bool) \Redis::get('playing');
    }

    public function getGeneratedWordsAsArray()
    {
        return unserialize(\Redis::get('words'));
    }
}