import _, {isArray, isNull, isUndefined} from "lodash";

/**
 * Настройка фильтров находится в Models у каждого компонента свои правила фильтрации
 *
 * @param thisRef
 */
export function parseFiltersForRequests(thisRef): void {
    const filters = thisRef.model.available_filters;
    _(thisRef.model).pick(filters.map(v => v.name))
        .forIn((value, name) => {
            if (validateValueForFilter(value)) {
                const field = filters.filter(v => v.name === name).shift();

                // фильтр для второго уровня, например поиск СДИЗов по партиям
                if (field.type === 'objects') {

                    _(field.child).forIn((childArray, prefix) => {

                        const filters = _(value[prefix]).pick(childArray.map(v => v.name)).value();
                        childArray.forEach(child => {

                            const name = child.key ?? child.name;
                            const valueChild = filters[child.name];

                            if (validateValueForFilter(valueChild)) {
                                thisRef.request.filter.options.push({
                                    field: `${prefix}.${name}`,
                                    operator: child.operator ?? '=',
                                    value: valueChild
                                });
                            }
                        });
                    })
                } else {
                    thisRef.request.filter.options.push({
                        field: field.key ?? field.name,
                        operator: field.operator ?? '=',
                        value: typeof field.value === 'undefined' ? value : field.value(value, thisRef)
                    });
                }
            }
        });
}

/**
 * Валидация значений для фльтров
 * @param value
 */
export function validateValueForFilter(value): boolean {
    return isArray(value) ? value.length > 0 : !isUndefined(value) && !isNull(value) && value !== '';
}
