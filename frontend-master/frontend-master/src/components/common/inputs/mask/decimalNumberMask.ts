import isNull from 'lodash/isNull';
import isUndefined from 'lodash/isUndefined';

const formatChunks = (data: string[]) => {
  let chunks = [...data];

  if (chunks.length > 1) {
    chunks[1] = chunks[1].slice(0, 3);
    chunks = chunks.slice(0, 2);
  }

  return chunks;
};

/**
 * Целое число, либо дробное с запятой
 * @param v
 * @param addTrailingZeroes
 */
export const decimalNumberMask = (v: number | string | null, addTrailingZeroes?: boolean): string[] => {
  if (isNull(v) || isUndefined(v) || v === '') {
    return ['0'];
  }

  if (Number.isInteger(v)) {
    return [v.toString()];
  }

  if (typeof v === 'number') v = v.toString();

  v = v.replace('.', ',').replace(/[^0-9,]/g, '');

  let chunks = v.split(',');

  if (addTrailingZeroes === true) {
    chunks = correctChunks(chunks);
  }

  return [formatChunks(chunks).join(',')];
};

export const decimalNumberUnmask = (v: string | number | null): any => {
  if (isNull(v) || v === '') {
    return null;
  }

  if (typeof v === 'number') return v;

  const chunks = (v || '').split(',');

  const data = parseFloat(formatChunks(chunks).join('.'));

  return Number.isNaN(data) ? 0 : data;
};

export const applyMask = (value: number | string | null, addTrailingZeroes?: boolean): string => {
  return decimalNumberMask(value, addTrailingZeroes)[0];
};

export const validate = (v: any) => {
  if (typeof v === 'number') v = v.toString();

  const chunks = (v || '').replace('.', ',').split(',');

  return (chunks.length > 1 && chunks[1].length < 3) || chunks[1] === '000';
};

function correctChunks(chunks: string[]) {
  if (chunks.length > 1) {
    const decimal = chunks[1];

    for (let i = 0; i < 3 - decimal.length; i++) {
      chunks[1] = chunks[1] + '0';
    }
  }

  return chunks;
}
