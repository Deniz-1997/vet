<?php

namespace App\Packages\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Используется для отключения функции каскадной сериализации вложенных объектов
 * При обновлении, добавлении сущности
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class SerializeNestedIgnore
{

}
