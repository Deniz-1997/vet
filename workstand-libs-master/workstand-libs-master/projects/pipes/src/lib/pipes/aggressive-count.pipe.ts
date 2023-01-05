import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-aggressive-count-transform'
})
export class AggressiveCountPipe implements PipeTransform {

  transform(value?: number, args?: any): any {
    let result = '';
    if (value) {
      for (let i = 0; i < value; i++) {
        result += '*';
      }
    }
    return result;
  }
}
