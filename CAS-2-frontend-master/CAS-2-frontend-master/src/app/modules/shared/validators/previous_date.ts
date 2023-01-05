import { AbstractControl } from '@angular/forms';

export function previousDate(control: AbstractControl) {
  const today = new Date();
  if (control.value && today < new Date(control.value.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'))) {
    return {validUrl: true};
  }
  return null;
}
