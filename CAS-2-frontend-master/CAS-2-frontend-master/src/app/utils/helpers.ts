export function keys<O>(o: O): any {
  return Object.keys(o) as Array<(keyof O)>;
}

export function isCssColor(color?: string | false): boolean {
  return !!color && !!color.match(/^(#|var\(--|(rgb|hsl)a?\()/);
}

export function convertToUnit(str: string | number | null | undefined, unit: string = 'px'): string | undefined {
  if (str == null || str === '') {
    return undefined;
  } else if (isNaN(+str)) {
    return String(str);
  } else {
    return `${Number(str)}${unit}`;
  }
}

export function getTextColor(color: any, classes: object = {}): any {
  if (isCssColor(color)) {
    return {
      css: {
        color: `${color}`,
        'caret-color': `${color}`,
      }
    };
  } else if (color) {
    const [colorName, colorModifier] = Array(color.toString().trim().split(' ', 2) as (string | undefined));

    return {
      ...classes,
      [colorName + '--text']: true,
    };
  }
}
