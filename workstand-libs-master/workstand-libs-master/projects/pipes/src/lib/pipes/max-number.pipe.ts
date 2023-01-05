import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'k-max-number'
})
export class MaxNumberPipe implements PipeTransform {
    transform(currentNumber: number, maxNumber: number = 10) {
        if (currentNumber >= maxNumber) {
            return (maxNumber - 1).toString() + '+';
        }
        else {
            return currentNumber;
        }
    }
}