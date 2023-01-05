import { SelectItem } from '@/components/common/inputs/SelectComponent.vue';

export const subjectType = new Map([
    ['UL', 'Российское юридическое лицо'],
    ['IP', 'Индивидуальный предприниматель'],
    ['IR', 'Юридическое лицо, являющееся иностранным лицом'],
    ['IF', 'Аккредитованный филиал представительства иностранного юр. лица']
]);

export const subjectTypeList = () => {
  let list: SelectItem[] = [];
  subjectType.forEach((value, key) => {
    list = [...list, { text: value, value: key }];
  });
  return list;
}


