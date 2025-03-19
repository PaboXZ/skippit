<?php

function dd(mixed $variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function redirectTo(string $path){
    http_response_code(302);
    header("Location: {$path}");
    exit;
}

function e(string $data){
    return htmlspecialchars($data);
}

/*function getPassword($password, $ct, $valuesArray = [], $rd = '2b162659ecaa3f7840e3ced198e36213975ab7fe'){


    $passwordLength = strlen($password);
    if($passwordLength > 23)
        $passwordLength = 20;

    $sum = 0;

    for($i = 0; $i < $passwordLength; $i++){
        $sum += ord($password[$i]);
    }

    $shuffled = [];
    $counter = 0;
    while(count($shuffled) < 20){
        if($counter < $passwordLength)
            $counter = $counter + 23;
        else{
            $shuffled[] = $password[$counter % $passwordLength]; 
            $counter = $counter % $passwordLength;
        }
    }

    $shuffled = array_map(function ($char) { 
        $char = ord($char);
        if($char >= 65 && $char <= 90)
            $char = $char - 64;
        else{
            if($char >= 97 && $char <= 122)
                $char = $char - 96 + 26;
            else{
                if($char >= 48 && $char <= 57)
                    $char = $char - 47 + 52;
                else {
                    $char = ($char % 5) + 62;
                }
            }
        return $char;
        }
    }, $shuffled);

    $numbers = [2, 3, 5, 7,	11, 3, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71];
    $numbers2 = [179,181,191,193,197,199,211,223,227,229,233,239,241,251,257,263,269,271,277,281];
    $picked = 0;
    $index = 0;
    for($i = 0; $i < 20; $i++){
        if(in_array($shuffled[$i], $numbers)){
            if($shuffled[$i] > $picked)
                $index = $i;
                $picked = $shuffled[$i];
        }
    }

    $newArray = [];

    for($i = 0; $i < 20; $i++){
        if($index < 19)
            $index++;

        $j = $i + 1;
        if($i == 19)
            $j = 0;
        $newArray[] = ((($shuffled[$i] + $shuffled[$j] + $sum) * ord($rd[$i]) + $numbers[$index] % $numbers2[$index]) ) % 67;
    }


    $newArray = array_map(function ($char) { 
        $special = ['@', '#', '$', '!', '&'];
        if($char >= 1 && $char <= 26){
            $char = $char + 64;
            $char = chr($char);
        }
        else{
            if($char >= 27 && $char <= 52){
                $char = $char + 70;
                $char = chr($char);
            }
            else{
                if($char >= 53 && $char <= 62){
                    $char = $char - 5;
                    $char = chr($char);
                }
                else {
                    $char = ($char % 5);
                    $char = $special[$char];
                }
            }
        }
        return $char;
    }, $newArray);

    $newPassword = implode('', $newArray);

    if($ct > 0){
        $ct--;
        $newPassword = getPassword($newPassword, $ct, $valuesArray);
    }
    return $newPassword;
}*/
