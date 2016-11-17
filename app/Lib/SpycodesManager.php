<?php

namespace App\Lib;

class SpycodesManager
{
    public function generateWords()
    {

        $rawWords = $this->_getRandomWordsFromDB();

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
        $this->setWords($words);

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

    public function revealWord($wordKey)
    {
        $words = $this->getGeneratedWordsAsArray();

        if(!isset($words[$wordKey])) {
            throw new Exception("The key {$wordKey} was not found");
        }

        $words[$wordKey]['facedown'] = false;

        $this->setWords($words);

        return $words[$wordKey];
    }

    public function setWords(array $words)
    {
        \Redis::set('words', serialize($words));
    }

    private function _getRandomWordsFromDB()
    {
        $ids = \DB::table('spycodes_words')->pluck('id')->toArray();
        $randomIds = array_rand($ids, 25);

        $words = \DB::table('spycodes_words')->whereIn('id', $randomIds)->pluck('word')->toArray();

        shuffle($words);

        return $words;
    }
}