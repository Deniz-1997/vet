import moment from 'moment';

// export const years = (end = 30, start = 30): Array<any | number> => {
//   const years = [];
//   const dateStart = moment().subtract(start, 'year');
//   const dateEnd = moment().add(end, 'year');
//   console.log(dateStart.format('YYYY'))
//   while (dateEnd.diff(dateStart, 'years') >= 0) {
//     years.push(+dateStart.format('YYYY'));
//     dateStart.add(1, 'year');
//   }
//
//   return years;
// };

export const monthsNames = (locale = 'ru'): string[] => {
  moment.locale(locale);

  return moment()
    .localeData()
    .months()
    .reduce<string[]>((months, monthName) => {
      return [...months, monthName.replace(/^./, monthName[0].toUpperCase())];
    }, []);
};

export const getCurrentMonthName = (locale = 'ru'): string => {
  const monthIndex = moment().month();

  return monthsNames(locale)[monthIndex];
};

export const getCurrentYear = (): string => {
  return moment().format('YYYY');
};

export const previousDay = (format = 'DD.MM.YYYY'): string => {
  return moment().subtract(1, 'day').format(format);
};

export const currentDay = (format = 'DD.MM.YYYY'): string => {
  return moment().format(format);
};

export const tomorrow = (): Date => {
  return moment().add(1, 'd').toDate();
};

export const dateFrom = (date: string, addDays?: number): Date => {
  if (!date) {
    return moment().add(-1, 'd').toDate();
  }

  return addDays !== undefined
    ? moment(date, 'DD.MM.YYYY').add(addDays, 'd').toDate()
    : moment(date, 'DD.MM.YYYY').toDate();
};

export const dateArray = (): Array<object> => {
  const arr: Array<object> = [];
  let minDate = 2010;
  const maxDate = new Date().getFullYear();
  while (minDate <= maxDate) {
    arr.push({ id: minDate, name: minDate });
    minDate += 1;
  }
  return arr.reverse();
};

export const formatDate = (date, formatFrom = 'DD.MM.YYYY', formatTo = 'DD.MM.YYYY'): string => {
  return moment(date, formatFrom).format(formatTo);
};

export const checkDate = (date): boolean => {
  return date > currentDay();
};

/**
 * Получить Date объект из строки с датой в виде "DD.MM.YYYY"
 * @param dateString
 */
export const getDateObject = (dateString: string): Date => {
  const dateParts = dateString.split('.');
  return new Date(+dateParts[2], +dateParts[1] - 1, +dateParts[0]);
};
