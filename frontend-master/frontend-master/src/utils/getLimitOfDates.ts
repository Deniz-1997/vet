import moment from 'moment';

export const limitTo = (date: string): string => {
  const currDate = moment().format('YYYY-MM-DD');

  if (date) {
    const limit = moment(date, 'YYYY-MM-DD').isBefore(currDate)
      ? moment(date, 'YYYY-MM-DD').format('YYYY-MM-DD')
      : currDate;

    return limit;
  }

  return currDate;
};

export const limitFrom = (date: string): string => {
  // const limit = date !== ''
  const limit = date
    ? moment(date, 'YYYY-MM-DD').format('YYYY-MM-DD')
    : '';

  return limit;
};
