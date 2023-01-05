/**
 * Generate number capacity mask based on input value
 *
 * @param value
 */
import isNull from 'lodash/isNull';
import isNumber from 'lodash/isNumber';

export const numberThousandsMask = (value: number | string | null | undefined): Array<string> => {
  if (isNull(value) || value === '' || value === undefined) {
    return ['*'];
  }

  const mask = /\B(?=(\d{3})+(?!\d))/g;

  if (typeof value === 'string') {
    const numbers = value.replace(/[^0-9]/g, '');

    if (numbers.replace(/\s+/, '') === '') {
      return [''];
    }

    return [numbers.split(' ').join('').toString().replace(mask, ' ')];
  }

  return [value.toString().replace(mask, ' ')];
};

export const numberThousandsUnmask = (value: number | string | null, toNumber?: boolean): any => {
  if (isNull(value) || value === '') {
    return null;
  }

  const unmaskedValue = isNumber(value) ? value : value.split(' ').join('');

  if (toNumber) {
    return Number(unmaskedValue);
  }

  return unmaskedValue;
};

export const applyMask = (value: number | string | null): string => {
  return numberThousandsMask(value)[0];
};
