<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел.

$surname = "Иванов";
$name = "Иван";
$patronomyc = "Иванович";

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}

echo (getFullnameFromParts($surname, $name, $patronomyc)) . PHP_EOL;
echo PHP_EOL;

//getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.

function getPartsFromFullname($name) {
    $a = ['surname', 'name', 'patronomyc'];
    $b = explode(' ', $name);
    return array_combine($a, $b);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    print_r(getPartsFromFullname($name));
} 

echo PHP_EOL;

//getShortName, принимающую как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович» и возвращающую строку вида «Иван И.», где сокращается фамилия и отбрасывается отчество.

function getShortName($name) {
    $shortName = getPartsFromFullname($name);
    $firstName = $shortName['name'];
    $surname = $shortName['surname'];
    return $firstName . ' ' . mb_substr($surname, 0, 1) . '.';
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getShortName($name) . PHP_EOL;
} 

echo PHP_EOL;

//getGenderFromName определение пола

function getGenderFromName($name) {
    $shortName = getPartsFromFullname($name);
    $surname = $shortName['surname'];
    $firstName = $shortName['name'];
    $patronomyc = $shortName['patronomyc'];
    $gender = 0;

    if (mb_substr($surname, -1, 1) === 'в') {
        $gender++;
    } elseif (mb_substr($surname, -2, 2) === 'ва') {
        $gender--;
    }

    if ((mb_substr($firstName, -1, 1) == 'й') || (mb_substr($firstName, -1, 1) == 'н')){
        $gender++;
    } elseif (mb_substr($firstName, -1, 1) === 'а') {
        $gender--;
    }

    if (mb_substr($patronomyc, -2, 2) === 'ич') {
        $gender++;
    } elseif (mb_substr($patronomyc, -3, 3) === 'вна') {
        $gender--;
    }

    return ($gender <=> 0);
    
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getGenderFromName($name) . PHP_EOL;
} 

echo PHP_EOL;

//getGenderDescription для определения полового состава аудитории

function getGenderDescription($persons) {
    
    $men = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderMen = getGenderFromName($fullname);
        if ($genderMen > 0) {
            return $genderMen;
        }
    });

    $women = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderWomen = getGenderFromName($fullname);
        if ($genderWomen < 0) {
            return $genderWomen;
        }
    });

    $indefiniteGender = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderIndefinite = getGenderFromName($fullname);   
        if ($genderIndefinite == 0) {                       
            return $genderIndefinite + 1;                   
        }
    });

    $allMan = count($men);                       
    $allWomen = count($women);                   
    $allIndefiniteGender = count($indefiniteGender);     
    $allPiople = count($persons);

    $percentMen = round((100 / $allPiople * $allMan), 0);
    $percentWomen = round((100 / $allPiople * $allWomen), 0);
    $percenIndefiniteGender = round((100 / $allPiople * $allIndefiniteGender), 0);

    return <<< HEREDOC
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $percentMen%
    Женщины - $percentWomen%
    Неудалось определить - $percenIndefiniteGender%
    HEREDOC;
}

echo getGenderDescription($example_persons_array) . PHP_EOL;

echo PHP_EOL;

?>