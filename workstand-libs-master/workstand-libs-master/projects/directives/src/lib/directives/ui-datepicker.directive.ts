import {Directive, ElementRef, Optional} from '@angular/core';
import {NgControl} from '@angular/forms';
import {DatePipe} from '@angular/common';

declare var $: any;

@Directive({
  selector: '[k-ui-datepicker]'
})
export class UiDatepickerDirective {

  constructor(
    private element: ElementRef,
    @Optional() private control: NgControl,
    private datePipe: DatePipe,
  ) {
    const el = $(element.nativeElement),
      isReadonly = el.attr('readonly');

    el.datepicker({
      onSelect: (dateText: string, obj: any) => {
        const value = datePipe.transform(new Date(
          obj['selectedYear'],
          obj['selectedMonth'],
          obj['selectedDay']
        ), 'dd.MM.yyyy');
        if (control && control.control) {
          control.control.setValue(value);
        }
        element.nativeElement.value = value;
        el.focus();
      }
    });


    if (!isReadonly) {
      el.attr('readonly', 'readonly');
    }
    el.inputmask({
      mask: '99.99.9999',
      placeholder: '_',
      oncomplete: function () {
        const val = $.trim($(this).val());
        const date = new Date(val.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
        if (date.getFullYear() < 1910 || isNaN(date.getTime())) {
          control.control!.setValue('');
        } else if (control) {
          control.control!.setValue(val);
        }
      },
      onincomplete: function () {
        const val = $.trim($(this).val());
        const date = new Date(val.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
        if (control && $.trim(val).length !== 10 || date.getFullYear() < 1970 || isNaN(date.getTime())) {
          control.control!.setValue('');
        }
      }
    });
    if (!isReadonly) {
      setTimeout(() => {
        el.removeAttr('readonly');
      }, 10);
    }
  }

}
