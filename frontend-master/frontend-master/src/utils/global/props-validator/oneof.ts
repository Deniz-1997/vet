export const oneof =
  <T>(list: ReadonlyArray<T>) =>
  (value: T) =>
    list.includes(value);
