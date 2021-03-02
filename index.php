<?php
include 'array.php';

function getPartsFromFullname($fullName){
    $person_name=['surname','name','patronomyc'];
    return array_combine($person_name,explode(' ',$fullName));
}

function getFullnameFromParts($surname,$name,$potronomyc){
    return $surname.=' '.$name.' '.$potronomyc;
}

function getShortName($fullName){
    $parts=getPartsFromFullname($fullName);
    return $parts['surname'].' '.mb_substr($parts['name'],0,1).'.';
}

function getGenderFromName($fullName){
    $parts=getPartsFromFullname($fullName);
    $sexFlag=0;
    if(mb_substr($parts['patronomyc'],-3,3)=='вна')
        --$sexFlag;

    if(mb_substr($parts['patronomyc'],-2,2)=='ич')
        ++$sexFlag;

    if(mb_substr($parts['name'],-1,1)=='а')
        --$sexFlag;

    if(mb_substr($parts['name'],-1,1)=='й' || mb_substr($parts['name'],-1,1)=='н')
        ++$sexFlag;

    if(mb_substr($parts['surname'],-2,2)=='ва')
        --$sexFlag;

    if(mb_substr($parts['surname'],-1,1)=='в')
        ++$sexFlag;

    switch($sexFlag<=>0){
        case 1:
            return 'male';
            break;
        case -1:
            return 'female';
            break;
        default:
            return'undefined';
    }
}

function getGenderDescription($personArray){
    $arrMale=array_filter($personArray,function($person){
        return getGenderFromName($person['fullname'])=='male';
    });

    $arrFemale=array_filter($personArray,function($person){
        return getGenderFromName($person['fullname'])=='female';
    });
    
    $arrUndefined=array_filter($personArray,function($person){
        return getGenderFromName($person['fullname'])=='undefined';
    });
    
    $male=round(count($arrMale)*100/count($personArray),1);
    $female=round(count($arrFemale)*100/count($personArray),1);
    $undfined=round(count($arrUndefined)*100/count($personArray),1);

    echo <<<HEREDOCLETTER
    Гендерный состав аудитории:\n
    ---------------------------\n
    Мужчины - $male%\n
    Женщины - $female%\n
    Не удалось определить - $undfined%\n
    HEREDOCLETTER;
}

function getPerfectPartner($surname,$name,$potronomyc,$personArray){
   $fullName=mb_convert_case(getFullnameFromParts($surname,$name,$potronomyc),MB_CASE_TITLE_SIMPLE);
   $fullName1=$personArray[rand(0,count($personArray)-1)]['fullname'];
   while(1){
    if(getGenderFromName($fullName)!=getGenderFromName($fullName1)){
        $x=rand(50,100);
        $f=getShortName($fullName);
        $f1=getShortName($fullName1);
        echo<<<HEREDOCLETTER
        $f + $f1 = 
        ♡ Идеально на $x% ♡\n
        HEREDOCLETTER;
        break;
    }
    $fullName1=$personArray[rand(0,count($personArray)-1)]['fullname'];
   }
}
