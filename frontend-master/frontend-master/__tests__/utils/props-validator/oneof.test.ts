import { oneof } from '@/utils/global/props-validator/oneof';

const fixtures: [any[], any?][] = [
  [['test', 'test1', 'test2'], 'test1'],
  [[1, 2, 3, 4], 3],
  [['none', 'of', 'theese'], 'here'],
  [[100, 200, -5], 0],
  [[null, undefined, {}]],
];

const expected: boolean[] = [
  true,
  true,
  false,
  false,
  true,
];

describe('oneof props validator', () => {
  test('validates value', () => {
    expect(oneof(fixtures[0][0])(fixtures[0][1])).toBe(expected[0]);
    expect(oneof(fixtures[1][0])(fixtures[1][1])).toBe(expected[1]);
    expect(oneof(fixtures[2][0])(fixtures[2][1])).toBe(expected[2]);
    expect(oneof(fixtures[3][0])(fixtures[3][1])).toBe(expected[3]);
    expect(oneof(fixtures[4][0])(fixtures[4][1])).toBe(expected[4]);
  });
});
