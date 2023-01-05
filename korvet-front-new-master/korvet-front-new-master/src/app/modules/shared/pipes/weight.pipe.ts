import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'weight'
})
export class WeightPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    const kilos = Math.floor(value / 1000);
    const grams = value - kilos * 1000;
    let result = '';
    if (kilos) {
      result += kilos + ' кг';
    }
    if (grams) {
      if (result) {
        result += ' ';
      }
      result += grams + ' гр';
    }
    if (!result) {
      result = '-';
    }
    return result;
  }

}
