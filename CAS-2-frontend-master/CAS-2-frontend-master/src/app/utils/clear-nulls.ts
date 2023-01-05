import * as _ from 'lodash';

export function clearNulls(o: object): void {
  Object.keys(o).forEach(key =>
    (_.isNil(o[key]) || _.isNaN(o[key]) || o[key] === '')
    && delete o[key]);
}

export function clearNullsDeep(o: object): void {
  Object.keys(o).forEach(key => {
    if (o[key] instanceof Object) {
      if (o[key] instanceof Array && o[key].length === 0) {
        delete o[key];
      } else {
        clearNullsDeep(o[key]);
        if (Object.keys(o[key]).length === 0) {
          delete o[key];
        }
      }
    } else if (_.isNil(o[key]) || _.isNaN(o[key]) || o[key] === '') {
      delete o[key];
    } else if (o[key] === 'isNull') {
      o[key] = null;
    }
  });
}
