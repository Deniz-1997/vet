import moment from 'moment';

export function formatDate(date: string, format = 'DD.MM.YYYY'): string {
  return moment(date).format(format);
}
