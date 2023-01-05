import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class BirthdayService {

  constructor() { }

  public getBirthdayFromYearsAndMonth(years, months) {
    if (!years || years < 0) {
      years = 0;
    }
    if (!months || months < 0) {
      months = 0;
    }
    const currentDate = new Date();
    const birthdayYears = currentDate.getFullYear() - years;
    const birthdayMonth = currentDate.getMonth() - months;
    currentDate.setFullYear(birthdayYears);
    currentDate.setMonth(birthdayMonth);
    return currentDate;
  }

  
  public getValueByDate(date) {
    let month = (date.getMonth() + 1) + '';
    let day = date.getDate() + '';
    const year = date.getFullYear() + '';
    if (month.length < 2) {
      month = '0' + month;
    }
    if (day.length < 2) {
      day = '0' + day;
    }
    return [day, month, year].join('.');
  }

  public getYearsAndMonthsSum(years, months) {
    const monthInYear = 12;
    const yearsInMonth = Math.floor(months / monthInYear);
    return years + yearsInMonth;
  }

  public getYearsAndMonthsFromBirthday(date, dateEnd = '') {
    const startYear = 1970;
    const birthdayDate = this.getDateByValue(date);
    if (!birthdayDate) {
      return {years: 0, months: 0};
    }
    let currentDate = this.getDateByValue(dateEnd);
    if (!currentDate) {
      currentDate = new Date();
    }
    if (birthdayDate.getTime() > currentDate.getTime()) {
      return false;
    }
    const difference = currentDate.getTime() - birthdayDate.getTime();
    const differenceDate = new Date(difference);
    const years = Math.abs(differenceDate.getUTCFullYear() - startYear);
    const months = differenceDate.getUTCMonth();
    return {years: years, months: months};
  }

  public getDateByValue(date) {
    const pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    if (!date || !date.match(pattern)) {
      return false;
    }
    return new Date(date.replace(pattern, '$3-$2-$1'));
  }
}
