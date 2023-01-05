import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-title-case-name'
})
export class TitleCaseNamePipe implements PipeTransform {

  transform(value: string, args?: any): any {
    if (value) {
      value = value[0].toUpperCase() + value.slice(1);

      const n = value.search('-');
      if (n > 0 && value.length > n + 1) {
        value = value.slice(0, n + 1) + value[n + 1].toUpperCase() + value.slice(n + 2);
      }

      const y = value.search(' ');
      if (y > 0 && value.length > y + 1) {
        value = value.slice(0, y + 1) + value[y + 1].toUpperCase() + value.slice(y + 2);
      }
    }

    return value;
  }

}
