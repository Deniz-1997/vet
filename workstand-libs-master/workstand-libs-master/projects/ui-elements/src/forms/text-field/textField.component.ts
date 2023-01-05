import {Component, ElementRef, HostBinding, OnInit, ViewChild, Input} from '@angular/core';
import {ErrorStateMatcher} from "@angular/material/core";
import {FormControl, FormControlName} from "@angular/forms";

type MatFormFieldAppearance = 'legacy' | 'standard' | 'fill' | 'outline';

type InputType =
  'color'
  | 'date'
  | 'datetime-local'
  | 'email'
  | 'month'
  | 'number'
  | 'password'
  | 'search'
  | 'tel'
  | 'text'
  | 'time'
  | 'url'
  | 'week';

const isBoolean = (val: any) => 'boolean' === typeof val;

@Component({
  selector: 'k-text-field',
  templateUrl: './textField.component.html',
  styleUrls: ['./textField.component.css']
})
export class TextFieldComponent implements OnInit {
  @ViewChild('input') private input!: ElementRef;
  @Input() type: InputType = 'text';
  @Input() textError = 'Обязательное поле';
  @Input() control!: FormControl;

  @Input() prefix!: string;
  @Input() suffixIcon!: string;

  @Input() hint!: string;

  @Input() placeholder!: string;
  @Input() label!: string;

  @Input() appearance: MatFormFieldAppearance = 'fill';

  @Input() matcher!: ErrorStateMatcher;

  _readonly!: boolean;
  _disabled!: boolean;
  _clearable!: boolean;
  _required!: boolean;

  _maxLength!: string | number;
  _value!: any;

  @Input() set value(value: any) {
    if (value !== undefined && value !== null && typeof value !== "undefined") {
      this._value = value.toString().trim();
    } else {
      this._value = '';
    }
  }

  @Input() set maxLength(value: string | number) {
    this._maxLength = Number(value) === 0 ? '' : Number(value);
  }

  @Input() set readonly(value: boolean) {
    this._readonly = isBoolean(value) ? value : true;
  }

  @Input() set disabled(value: boolean) {
    this._disabled = isBoolean(value) ? value : true;
  }

  @Input() set clearable(value: boolean) {
    this._clearable = isBoolean(value) ? value : true;
  }

  @Input() set required(value: boolean) {
    this._required = isBoolean(value) ? value : true;
  }

  get maxLength(): string | number {
    if (typeof this._maxLength === 'undefined') {
      return '10000';
    }
    return typeof this._maxLength === 'string' || typeof this._maxLength === 'number' && this._maxLength != 0 ? this._maxLength : '';
  }

  get readonly(): boolean {
    return isBoolean(this._readonly) ? this._readonly : false;
  }

  get disabled(): boolean {
    return isBoolean(this._disabled) ? this._disabled : false;
  }

  get clearable(): boolean {
    return isBoolean(this._clearable) ? this._clearable : false;
  }

  get required(): boolean {
    return isBoolean(this._required) ? this._required : false;
  }

  get value(): any {
    if (this._value !== undefined && this._value !== null && typeof this._value !== "undefined") {
      return this._value.toString().trim();
    } else {
      return '';
    }
  }

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-input-text-field',
  ];

  constructor() {
  }

  ngOnInit(): void {
    this.control?.valueChanges.subscribe(value => {
      if (this.type === 'number' && this.maxLength && (String(value)).length > Number(this.maxLength)) {
        value = value.substring(0, Number(this.maxLength));
        this.control.setValue(value);
      }
    })
  }

}

