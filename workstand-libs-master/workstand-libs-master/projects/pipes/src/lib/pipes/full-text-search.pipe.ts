import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-full-text-search'
})
export class FullTextSearchPipe implements PipeTransform {

  constructor() {
  }

  transform(value: any, query: string, field: string, field2?: string): any {
    return query ? value.reduce((prev: any[], next: {[x: string]: string | any;}) => {
      if (field2) {
        if (next[field][field2].includes(query)) {
          prev.push(next);
        }
      } else if (next[field].includes(query)) {
        prev.push(next);
      }
      return prev;
    }, []) : value;
  }

}
