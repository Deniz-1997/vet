import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'maxNumber'
})
export class MaxNumberPipe implements PipeTransform {
  transform(currentNumber: number, maxNumber: number = 10): string {
    if (currentNumber >= maxNumber) {
      return (maxNumber - 1).toString() + '+';
    } else {
      return String(currentNumber);
    }
  }
}
