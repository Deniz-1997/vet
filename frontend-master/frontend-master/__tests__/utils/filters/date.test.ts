import date from '@/utils/global/filters/date';

const fixtures: [string | Date, any?][] = [
  ['12.12.2012 12:12'],
  ['12.12.2012 12:12', { inputFormat: 'DD.MM.YYYY', outputFormat: 'ddd' }],
  [new Date(999999999999)],
  [''],
  ['Invalid Date'],
  [new Date('invalid')],
];

const expected = [
  '12.12.2012 12:12',
  'Wed',
  '09.09.2001 05:46',
  '',
];

describe('date', () => {
  test('returns date in proper format', () => {
    expect(date.apply(null, fixtures[0])).toBe(expected[0]);
    expect(date.apply(null, fixtures[1])).toBe(expected[1]);
    expect(date.apply(null, fixtures[2])).toBe(expected[2]);
  });

  test('returns empty on invalid date', () => {
    expect(date.apply(null, fixtures[3])).toBe(expected[3]);
    expect(date.apply(null, fixtures[4])).toBe(expected[3]);
    expect(date.apply(null, fixtures[5])).toBe(expected[3]);
  });
});
