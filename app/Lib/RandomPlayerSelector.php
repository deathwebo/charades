<?php
/*
 * (c) Copyright Sperantus SA de CV 2011
 *  info@sperantus.com
 */

namespace App\Lib;


class RandomPlayerSelector
{
    public function getRandomPlayer(array $playersStack)
    {
        $randomPlayerKey = array_rand($playersStack, 1);

        $player = $playersStack[$randomPlayerKey];

        return $player;

    }
}