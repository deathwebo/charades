<?php

namespace App\Lib;

class SpycodesManager
{
    public function generateWords($gameId)
    {

        $rawWords = $this->_prepareAndGetRandomWords($gameId);

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

        $this->setWords($gameId, $words);

        return $words;
    }

    public function getGeneratedWordsAsArray($gameId)
    {
        return unserialize(\Redis::get("{$gameId}.words"));
    }

    public function revealWord($gameId, $wordKey)
    {
        $words = $this->getGeneratedWordsAsArray($gameId);

        if(!isset($words[$wordKey])) {
            throw new Exception("The key {$wordKey} was not found");
        }

        $words[$wordKey]['facedown'] = false;

        $this->setWords($gameId, $words);

        return $words[$wordKey];
    }

    public function setWords($gameId,array $words)
    {
        \Redis::set("{$gameId}.words", serialize($words));
    }

    private function _prepareAndGetRandomWords($gameId)
    {
        $wordsIdsUsed = unserialize(\Redis::get("{$gameId}.allUsedWordsIds"));

        if(!is_array($wordsIdsUsed)) {
            $wordsIdsUsed = [];
        }

        $words = $this->_getRandomWords($wordsIdsUsed);

        if(count($words) == 25) {

            $wordsIds = array_column($words, 'id');

            $newWordsIdsUsed = array_merge($wordsIdsUsed, $wordsIds);

            \Redis::set("{$gameId}.allUsedWordsIds", serialize($newWordsIdsUsed));

            return array_column($words, 'word');
        }

        $words = $this->_getRandomWords();

        \Redis::set("{$gameId}.allUsedWordsIds", serialize(array_column($words, 'id')));

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