import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-first-letter'
})
export class FirstLetterPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    return value ? value[0].toUpperCase() + value.slice(1) : value;
  }

}
