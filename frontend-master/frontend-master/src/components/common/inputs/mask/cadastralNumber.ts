/**
 * Generate a cadastral mask based on input value.
 * @param {string} value
 */
export const cadastralMask = (value) => {
  const mask = [/\d/, /\d/, ':', /\d/, /\d/, ':'];
  const chunks = value.replace(/_/g, '').split(':');
  let i;

  if (chunks[2]) {
    const chunk = chunks[2].split('');
    for (i = 0; i < chunk.length && i < 7; i++) {
      if (chunk[i] === ':') {
        mask.push(':');
        break;
      }
    }
  }

  for (i = 0; (!chunks[2] || i < chunks[2].length) && i < 7; i++) {
    mask.push(/\d/);
  }

  if (!chunks[3] && (!chunks[2] || chunks[2].length < 7)) {
    mask.push(/\d|:/);
  } else {
    mask.push(':');
  }

  for (i = 0; (!chunks[3] || i < chunks[3].length) && i < 5; i++) {
    mask.push(/\d/);
  }

  return mask;
};
