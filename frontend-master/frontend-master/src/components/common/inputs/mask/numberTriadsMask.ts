/**
 * Generate number triads mask based on input value
 *
 * @param value
 */
export const numberTriadsMask = value => {
    const numbers = value.replace(/[^0-9]/g, '').replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
    return [numbers];
}