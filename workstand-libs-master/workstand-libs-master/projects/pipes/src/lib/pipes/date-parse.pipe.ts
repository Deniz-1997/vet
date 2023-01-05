import {Pipe, PipeTransform} from '@angular/core';
import {DatePipe} from '@angular/common';

@Pipe({
  name: 'k-date-parse'
})
export class DateParsePipe implements PipeTransform {

  constructor(private date: DatePipe) {
  }

  transform(value: string | Date, args?: any): any {
    let result = value;
    try {
      if (!(value instanceof Date)) {
        const datetime = value.split(' ');
        result = datetime[0].split('.').reverse().join('-') + (datetime[1] ? ('T' + datetime[1]) : '');
      }
    } catch (e) {
    }
    return this.date.transform(result, args);
  }

}
