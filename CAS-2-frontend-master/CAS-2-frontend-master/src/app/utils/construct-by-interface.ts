export function constructByInterface<T>(o: T, thisRef: any, types: { [key: string]: new(...args: Array<any>) => any } = {}): void {
  if (o) {
    Object.keys(o).forEach(key => {
      if (types[key]) {
        if (o[key] instanceof Array) {
          thisRef[key] = o[key].map(item => new types[key](item));
        } else {
          if (o[key]) {
            thisRef[key] = new types[key](o[key]);
          }
        }
      } else {
        thisRef[key] = o[key];
      }
    });
  }
}
