import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-gender'
})
export class GenderPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    return value === 'MALE' ? 'Самец' : 'Самка';
  }

}
