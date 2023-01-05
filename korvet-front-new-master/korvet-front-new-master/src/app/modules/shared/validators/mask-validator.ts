import {AbstractControl} from '@angular/forms';

export function maskValidator(control: AbstractControl) {
  if (control.value && control.value.includes('_')) {
    return {validUrl: true};
  }
  return null;
}
