import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'age'
})
export class AgePipe implements PipeTransform {

  monthDiff(d1: Date, d2: Date): number {
    let months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth();
    months += d2.getMonth();
    return months <= 0 ? 0 : months;
  }

  transform(value: any, args?: any): any {
    if (!value) {
      return '-';
    }
    let today;
    let birth;

    if (value.indexOf(',') > -1) {
      const dates = value.split(',');
      birth = new Date(dates[0].replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
      if (dates[1] && dates[1] !== 'null') {
        today = new Date(dates[1].replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
      } else {
        return dates[0].split(' ')[0];
      }
    } else {
      birth = new Date(value.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
      today = new Date();
    }

    let month = this.monthDiff(birth, today);
    let year = Math.floor(month / 12);
    const titles = ['год', 'года', 'лет'];
    const titlesMonth = ['месяц', 'месяца', 'месяцев'];
    const cases = [2, 0, 1, 1, 1, 2];
    if (month < 1) {
      return 'меньше месяца';
    }
    if (year < 1) {
      return month + ' ' + titlesMonth[(month % 100 > 4 && month % 100 < 20) ? 2 : cases[(month % 10 < 5) ? month % 10 : 5]];
    }
    month -= year * 12;
    if (year < 2) {
      return year + ' ' + titles[(year % 100 > 4 && year % 100 < 20) ? 2 : cases[(year % 10 < 5) ? year % 10 : 5]] + ' ' + (month > 0 ? month + ' мес' : '');
    }
    year = birth.getFullYear();
    const age = today.getFullYear() - year - (today.getTime() < birth.setFullYear(year) ? 1 : 0);
    return age + ' ' + titles[(age % 100 > 4 && age % 100 < 20) ? 2 : cases[(age % 10 < 5) ? age % 10 : 5]];
  }

}
