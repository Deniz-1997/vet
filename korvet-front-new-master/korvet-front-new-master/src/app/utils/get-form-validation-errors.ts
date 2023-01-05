import {AbstractControl, FormArray, FormControl, FormGroup, ValidationErrors} from '@angular/forms';

export function getFormErrorsObject(form: AbstractControl): ValidationErrors {
  if (form instanceof FormGroup) {
    const result = Object.keys(form.controls)
      .reduce((acc, key) => {
        const errors = getFormErrorsObject(form.controls[key]);
        return errors ? {...acc, [key]: errors} : acc;
      }, {});
    return Object.keys(result).length ? result : null;
  } else if (form instanceof FormArray) {
    const result = form.controls.reduce((acc, control, i) => {
      const errors = getFormErrorsObject(control);
      return errors ? [...acc, errors] : acc;
    }, []);
    return result.length ? result : null;
  } else if (form instanceof FormControl) {
    return form.errors;
  }
}

export function getFormErrors(form: AbstractControl, name = ''): ValidationErrors | null {
  if (form instanceof FormGroup) {
    const result = Object.keys(form.controls)
      .reduce((acc, key) => {
        const errors = getFormErrors(form.controls[key], name ? name + '.' + key : key);
        return errors ? {...acc, ...errors} : acc;
      }, {});
    return Object.keys(result).length ? result : null;
  } else if (form instanceof FormArray) {
    const result = form.controls.reduce((acc, control, i) => {
      const errors = getFormErrors(control, name ? name + '.' + i : i.toString());
      return errors ? {...acc, ...errors} : acc;
    }, {});
    return Object.keys(result).length ? result : null;
  } else if (form instanceof FormControl) {
    return form.errors ? {[name]: form.errors} : null;
  }
}

export function getFormValidationErrors(form: AbstractControl): ValidationErrors {
  return getFormErrors(form) || {};
}
