<?php

namespace App\Services;

use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\SelectCustomFieldModel;

class CustomFieldService
{

    function makeSelectField(): SelectCustomFieldModel
    {
        $model = new SelectCustomFieldModel();
        $model->setName('Test select field with enums codes');
        $enums = new CustomFieldEnumsCollection();
        $first = new EnumModel();
        $first->setSort(1);
        $first->setCode('first');
        $first->setValue('Первый вариант');
        $enums->add($first);
        $second = clone $first;
        $second->setSort(2);
        $second->setCode('second');
        $second->setValue('Второй вариант');
        $enums->add($second);
        $model->setEnums($enums);
        return $model;
    }
}