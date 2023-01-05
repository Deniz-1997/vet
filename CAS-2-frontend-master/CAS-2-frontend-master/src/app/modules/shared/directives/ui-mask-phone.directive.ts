import {Directive, ElementRef, Input, Optional} from '@angular/core';
import {NgControl} from '@angular/forms';

declare var $: any;

@Directive({
  selector: '[appUiMaskPhone]'
})
export class UiMaskPhoneDirective {

  @Input() maskPhoneMin = 1;
  @Input() maskPhoneMax = 10;

  constructor(private element: ElementRef, @Optional() private control: NgControl) {
    // .inp-namber
    const that = this;
    $(this.element.nativeElement).inputmask({
      mask: '+79{' + this.maskPhoneMin + ',' + this.maskPhoneMax + '}',
      placeholder: '',
      onKeyValidation: (key, result) => {
        if (!control) {
          return;
        }
        if (result) {
          control.control.setValue($(this).val());
        }
      },
      onKeyDown: () => {
        if (!control) {
          return;
        }
        const len = $(that.element.nativeElement).val().length;
        const that2 = this;
        if (len < that.maskPhoneMin) {
          control.control.setValue('');
        } else if ($(that2).val() !== control.control.value) {
          setTimeout(() => {
            control.control.setValue($(that2).val());
          }, 50);
        }
      }
    });
  }
}
