import { Mapper } from '@/utils';

export const checkMapper = <T extends Mapper<any>>(
  TestMapper: new (...args) => T,
  data: T extends Mapper<infer U> ? Partial<U> : never,
  expected: ReturnType<T['toJSON']>
) => {
  const actual = new TestMapper(data).toJSON();
  expect(actual).toEqual(expected);
};
