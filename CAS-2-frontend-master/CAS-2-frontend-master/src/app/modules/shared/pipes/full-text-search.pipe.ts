import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'fullTextSearch'
})
export class FullTextSearchPipe implements PipeTransform {
  transform(value: any, query: string, field: string, field2?: string): any {
    return query ? value.reduce((prev, next) => {
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
