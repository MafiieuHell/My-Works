<?php

function isEvil(string $morality): bool
{
    switch ($morality) {
        case 'good':
            return false;
        case 'evil':
            return true;
        case 'random':
            $random = mt_rand(1, 3);
            return $random == 1;
    }
    
    // Version équivalente
    // if ($morality === 'good') {
    //     return false;
    // } elseif ($morality === 'evil') {
    //     return true;
    // } elseif ($morality === 'random') {
    //     $random = mt_rand(1, 3);
    //     return $random == 1;
    // }
}

function getRandomName(int $totalLetters = 2, int $totalNumbers = 4): string
{
    $numbers = '';
    
    for ($i = 0; $i < $totalNumbers; $i++) {
        $numbers .= mt_rand(0, 9);      // Raccourci de : $numbers = $numbers . mt_rand(0, 9);
    }
    
    $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $randomLetters = '';
    
    for ($i = 0; $i < $totalLetters; $i++) {
        $randomLetters .= $letters[mt_rand(0, 25)];
    }
    
    return $randomLetters . '-' . $numbers;
}