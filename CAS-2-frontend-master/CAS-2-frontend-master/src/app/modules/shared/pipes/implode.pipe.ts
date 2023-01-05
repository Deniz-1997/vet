import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'implode'
})
export class ImplodePipe implements PipeTransform {

  static getProp(obj: object | [], desc: string): any {
    const arr = desc.split('.');
    return obj;
  }

  transform(arr: [], field: any, count?: number): string | number {
    count = count || 2;
    if (arr.length > count) {
      return arr.length;
    }
    let result = '';
    for (const item of arr) {
      if (result) {
        result += '\n';
      }
      result += ImplodePipe.getProp(item, field);
    }
    return result;
  }

}
