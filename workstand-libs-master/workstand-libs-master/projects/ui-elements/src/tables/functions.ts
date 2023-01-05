export function CloneArray<T>(array: Array<T>): Array<T> {
  let newArray: Array<T> = new Array<T>();
  for (let item of array) {
    newArray.push(item);
  }
  return newArray;
}

export function GetRandomString(): string {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    var r = Math.random() * 16 | 0,
      v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

export const isBoolean = (val: any) => 'boolean' === typeof val;
