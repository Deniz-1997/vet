import {Directive, HostListener, Input} from '@angular/core';
import {FormGroup} from '@angular/forms';

@Directive({
  selector: '[k-form-focus]'
})
export class FormFocusDirective {


  constructor() {
  }

  @HostListener('keyup', ['$event'])
  onEnter(e: KeyboardEvent) {
    e.preventDefault();
    try {
      if (e.key === 'Enter') {
        const target = (<any>e.target);
        let ariaOwns = null;
        let i = 0;
        while (i < target.attributes.length) {
          if (target.attributes[i].localName === 'aria-owns') {
            ariaOwns = target.attributes[i].value;
            break;
          }
          i++;
        }
        if (!ariaOwns || !/^mat-autocomplete/.test(ariaOwns)) {
          const elements = document.querySelectorAll('button, [href], input, textarea, select, [tabindex]:not([tabindex="-1"])');
          const forbidden = document.querySelectorAll('textarea, select, mat-select');
          i = 0;
          while (i < elements.length) {
            const el = elements.item(i) as any;
            if (el.isEqualNode(e.target as Node)) {
              let cond = true, j = 0;
              while (j < forbidden.length) {
                if (el.isEqualNode(forbidden.item(j))) {
                  cond = false;
                  break;
                }
                j++;
              }
              if (cond) {
                el.blur();
                const element = elements.item(i + 1) || elements.item(0) as any;
                (<any>element).focus();
                e.preventDefault();
                break;
              }
            }
            i++;
          }
        }

      }
    } catch (e) {
    }
  }

}
