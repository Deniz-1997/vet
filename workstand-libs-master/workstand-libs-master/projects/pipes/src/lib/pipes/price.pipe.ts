import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'k-price'
})
export class PricePipe implements PipeTransform {

  transform(value: string | number, currency: string = '₽'): string {
    const data = value ? Math.round(parseFloat(value.toString()) * 100) / 100 : 0;
    return (isNaN(data) ? 0 : data).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') +
      (currency ? ' ' + currency : '');
  }

}
