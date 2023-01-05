import {Directive, ElementRef, Optional} from '@angular/core';
import {NgControl} from '@angular/forms';

declare var $: any;

@Directive({
  selector: '[appUiMaskTime]'
})
export class UiMaskTimeDirective {

  constructor(
    private element: ElementRef,
    @Optional() private control: NgControl,
  ) {
    // .time-mask
    const el = $(this.element.nativeElement),
      isReadonly = el.attr('readonly');
    if (!isReadonly) {
      el.attr('readonly', 'readonly');
    }
    $(this.element.nativeElement).inputmask({
      mask: '99:99',
      placeholder: '_',
      oncomplete: function () {
        const val = $.trim($(this).val());
        const h = parseInt(val.replace(/(\d+):(\d+)/, '$1'), 10);
        const m = parseInt(val.replace(/(\d+):(\d+)/, '$1'), 10);
        if (h > 23 || m > 59) {
          control.control.setValue('');
        } else if (control) {
          control.control.setValue(val);
        }
      },
    });
    if (!isReadonly) {
      setTimeout(() => { el.removeAttr('readonly'); }, 1);
    }
  }

}
