<?php

$contributors = json_decode(file_get_contents('https://api.github.com/repos/zendframework/zf2/contributors'), true);

$tot = count($contributors);

foreach ($contributors as $i => $contributor) {
    $user_info = json_decode(file_get_contents("https://api.github.com/users/{$contributor['login']}"), true);
    echo "Processing $i/$tot\r";
    $contributors[$i]['user_info'] = $user_info;
}

echo 'Writing File ... ';
file_put_contents(__DIR__ . '/../../../data/contributors/contributors.pson', serialize($contributors));
echo PHP_EOL . PHP_EOL;
