
import moment from 'moment';
import { formatDate } from './formatters/dateFormatters';
// import { Official } from '@/types/Official';

export const formatPositionsDate = (positions: any): any[] => {
  return positions.map(position => {
    const newPosition = {
      ...position,
      // appointmentDate: moment(position.appointmentDate).isValid() ? formatDate(position.appointmentDate as string) : null,
      // withdrawalDate: moment(position.withdrawalDate).isValid() ? formatDate(position.withdrawalDate as string) : null,
    };

    return newPosition;
  });
};
