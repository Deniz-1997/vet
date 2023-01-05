export function findElementInArray(items: Array<any>, value: string | number | null, bySortVarName: string = 'id'): object | null {
    const afi = items.filter(i => i[bySortVarName] === value);
    if(!afi.length) return null;
    return afi[0];
}