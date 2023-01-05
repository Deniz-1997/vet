import {Component, EventEmitter, forwardRef, Input, Output} from '@angular/core';
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from '@angular/forms';
import {MatDatepickerInputEvent} from '@angular/material/datepicker';

@Component({
  selector: 'app-datepicker-overview',
  templateUrl: './datepicker-overview.component.html',
  styleUrls: ['./datepicker-overview.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => DatepickerOverviewComponent),
      multi: true
    }
  ]
})
export class DatepickerOverviewComponent implements ControlValueAccessor {
  @Input()
  dataError: boolean;

  @Input()
  isDisabled: boolean;

  @Input()
  _dateValue;

  @Input() maxDate;
  @Input() minDate;
  innerValue;

  @Output() changed: EventEmitter<any> = new EventEmitter();

  public mask = {
    guide: true,
    showMask: true,
    mask: [/\d/, /\d/, '.', /\d/, /\d/, '.', /\d/, /\d/, /\d/, /\d/]
  };

  get dateValue() {
    return this.innerValue;
  }

  set dateValue(val) {
    this._dateValue = val;
    this.propagateChange(this._dateValue);
  }


  addEvent(type: string, event: MatDatepickerInputEvent<string>) {

    const eTgEl = event.targetElement['value'];

    if (eTgEl.length > 9 || eTgEl.length < 11 && eTgEl.includes('_') > 0) {

      if (eTgEl.length === 10 && !eTgEl.includes('_')) {
        this.dateValue = eTgEl;
      } else if (eTgEl.length > 10) {
        this.dateValue = eTgEl.slice(0, 10);
      } else {
        this.dateValue = null;
      }

    } else {
      this.dateValue = null;
    }

    if (type === 'change') {
      this.changed.emit(event);
    }
  }

  writeValue(value: any) {
    if (value) {
      const dateParts = value.split(' ');
      const dayParts = dateParts[0].split('.');
      const timeParts = dateParts[1] ? dateParts[1] : '00:00:00';
      this.innerValue = new Date('' + dayParts[2] + '-' + dayParts[1] + '-' + dayParts[0] + 'T' + timeParts);
    }
  }

  propagateChange = (_: any) => {

  }

  registerOnChange(fn) {
    this.propagateChange = fn;
  }

  registerOnTouched() {
  }

}
