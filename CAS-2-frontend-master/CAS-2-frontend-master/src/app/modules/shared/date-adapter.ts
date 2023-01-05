import {MatDateFormats, NativeDateAdapter} from '@angular/material/core';
import {Injectable} from '@angular/core';

@Injectable()
export class AppDateAdapter extends NativeDateAdapter {
  parse(value: string | number): Date | null {
    if ((typeof value === 'string') && (value.indexOf('.') > -1)) {
      const str: Array<string> = value.split('.');
      if (str.length < 2 || isNaN(+str[0]) || isNaN(+str[1]) || isNaN(+str[2])) {
        return null;
      }

      return new Date(Number(str[2]), Number(str[1]) - 1, Number(str[0]));
    }

    const timestamp: number = typeof value === 'number' ? value : Date.parse(value);
    return isNaN(timestamp) ? null : new Date(timestamp);
  }

  getFirstDayOfWeek(): number {
    return 1;
  }
}


export const APP_DATE_FORMATS: MatDateFormats = {
  parse: {
    dateInput: {month: 'short', year: 'numeric', day: 'numeric'}
  },
  display: {
    dateInput: 'customInput',
    monthYearLabel: {year: 'numeric', month: 'short'},
    dateA11yLabel: {year: 'numeric', month: 'long', day: 'numeric'},
    monthYearA11yLabel: {year: 'numeric', month: 'long'},
  }
};
