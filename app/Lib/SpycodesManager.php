<?php

namespace App\Lib;

class SpycodesManager
{
    public function generateWords()
    {

        $rawWords = $this->_prepareAndGetRandomWords();

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

    private function _prepareAndGetRandomWords()
    {
        $wordsIdsUsed = unserialize(\Redis::get('allUsedWordsIds'));

        if(!is_array($wordsIdsUsed)) {
            $wordsIdsUsed = [];
        }

        $words = $this->_getRandomWords($wordsIdsUsed);

        if(count($words) == 25) {

            $wordsIds = array_column($words, 'id');

            $newWordsIdsUsed = array_merge($wordsIdsUsed, $wordsIds);

            \Redis::set('allUsedWordsIds', serialize($newWordsIdsUsed));

            return array_column($words, 'word');
        }

        $words = $this->_getRandomWords();

        \Redis::set('allUsedWordsIds', serialize(array_column($words, 'id')));

        return array_column($words, 'word');
    }

    private function _getRandomWords(array $idsToSkip = [])
    {
        $queryBuilder = \DB::table('spycodes_words')
            ->inRandomOrder();

        if(count($idsToSkip) > 0) {
            $queryBuilder->whereNotIn('id', $idsToSkip);
        }

        $words = $queryBuilder->take(25)
            ->get()
            ->toArray();

        return $words;
    }


}