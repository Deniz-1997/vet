import address from '@/utils/global/filters/address';

const fixtures: any[] = [
  { address: 'Тест 11', country: { name_full: 'Россия', code_alpha2: 'RU' } },
  { address: 'Тест 12', country: { name_full: 'РФ', code_alpha2: 'RU' } },
  { address: 'Тест 13', country: { name_full: 'Российская Федерация', code_alpha2: 'RU' } },
  { address: 'Тест 21', country: { name_full: 'Украина', code_alpha2: 'UA' } },
  { address: 'Тест 22', country: { name_full: 'Республика Беларусь', code_alpha2: 'BE' } },
  { address: 'Тест 23', country: { name_full: 'Казахстан', code_alpha2: 'KZ' } },
  { address: 'Тест 31', country: null },
  { address: 'Тест 32', country: null },
  { address: '', country: null },
];

const expected = [
  'Тест 11',
  'Тест 12',
  'Тест 13',
  'Украина, Тест 21',
  'Республика Беларусь, Тест 22',
  'Казахстан, Тест 23',
  'Тест 31',
  'Тест 32',
  '',
];

describe('address', () => {
  test('returns address if russia', () => {
    expect(address(fixtures[0])).toBe(expected[0]);
    expect(address(fixtures[1])).toBe(expected[1]);
    expect(address(fixtures[2])).toBe(expected[2]);
  });

  test('returns address with country if foreign', () => {
    expect(address(fixtures[3])).toBe(expected[3]);
    expect(address(fixtures[4])).toBe(expected[4]);
    expect(address(fixtures[5])).toBe(expected[5]);
  });

  test('returns address if no country', () => {
    expect(address(fixtures[6])).toBe(expected[6]);
    expect(address(fixtures[7])).toBe(expected[7]);
    expect(address(fixtures[8])).toBe(expected[8]);
  });
});
