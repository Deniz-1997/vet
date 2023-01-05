import moment from 'moment';

import { getSortParameterName } from './getSortParameterName';
import { query, queryData, queryRepeatKey } from './query';
import { currentDay, monthsNames, previousDay } from './date';
import { formatNumber } from './formatters/numberFormatters';
import { formatDate } from './formatters/dateFormatters';
export * from './global';

type StatusIcon = {
  name: string;
  color?: string;
};

const getStatusIcon = (status: string): StatusIcon => {
  switch (status) {
    case 'NEW':
      return {
        name: 'mdi-checkbox-blank-circle-outline',
        color: '#63d149',
      };
    case 'PROCESSING':
      return {
        name: 'mdi-circle-half-full',
        color: '#63d149',
      };
    case 'READY':
      return {
        name: 'mdi-checkbox-marked-circle',
        color: '#63d149',
      };
    case 'APPROVED':
      return {
        name: 'mdi-circle',
        color: '#63d149',
      };
    case 'NOT_SENT':
      return {
        name: 'mdi-close-circle-outline',
        color: '#c1c1c1',
      };
    default:
      return {
        name: 'mdi-checkbox-blank-circle-outline',
        color: '#63d149',
      };
  }
};

const getFormattedDate = (date: string): string => {
  return moment(date).format('DD.MM.YYYY');
};

const getColumnToSort = (text: string[], sortDesc = true): string => {
  const result = text?.length
    ? text[0]
        .split('.')
        .map((item, index) => (index ? item.charAt(0).toUpperCase() + item.substr(1) : item))
        .join('')
    : '';

  return sortDesc ? result : `-${result}`;
};

const timeout = (ms = 0): Promise<void> => {
  return new Promise((resolve) => setTimeout(resolve, ms));
};

export {
  currentDay,
  //years,
  queryRepeatKey,
  queryData,
  query,
  previousDay,
  monthsNames,
  getSortParameterName,
  formatNumber,
  formatDate,
  getStatusIcon,
  getFormattedDate,
  timeout,
  getColumnToSort,
};
