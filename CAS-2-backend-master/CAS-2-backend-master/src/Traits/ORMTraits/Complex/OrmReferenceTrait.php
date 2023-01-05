<?php

namespace App\Traits\ORMTraits\Complex;

use OpenApi\Annotations as SWG; // не удалять - почему-то не работают вложенные трейты
use App\Traits\ORMTraits\ {
    OrmIdTrait,
    OrmNameTrait,
    OrmDeletedTrait
};

trait OrmReferenceTrait {

    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait;

    /**
     * @param $sourceObject
     */
    public function fill($sourceObject) {
        foreach ($sourceObject as $key=>$value){
            if($key != "id" && !is_null($sourceObject->$key) && (property_exists($this, $key))) {
                $this->$key = $sourceObject->$key;
            }
        }
    }
}
