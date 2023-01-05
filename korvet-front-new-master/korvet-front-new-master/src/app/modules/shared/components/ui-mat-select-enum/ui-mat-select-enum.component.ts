import {Component, Input, OnInit, Optional, Self} from '@angular/core';
import {EnumModel} from '../../../../models/enum .models';
import {ControlValueAccessor, NgControl} from '@angular/forms';

@Component({
  selector: 'app-ui-mat-select-enum',
  templateUrl: './ui-mat-select-enum.component.html',
  styleUrls: ['./ui-mat-select-enum.component.css'],
})
export class UiMatSelectEnumComponent implements ControlValueAccessor, OnInit {
  @Input() disabled = false;

  constructor(@Optional() @Self() public ngControl: NgControl) {
    if (ngControl) {
      ngControl.valueAccessor = this;
    }
  }

  private _options: EnumModel[] = [];

  get options() {
    return this._options;
  }

  @Input() set options(options: EnumModel[]) {
    this._options = options;
    this.checkOptions();
  }

  private _modelValue: any;

  get modelValue() {
    return this._modelValue;
  }

  @Input() set modelValue(modelValue: any) {
    this._modelValue = modelValue;
    this.checkOptions();
  }

  private _value: any = null;

  get value() {
    return this._value;
  }

  set value(value) {
    this._value = value;
    this.valueChange(value);
  }

  ngOnInit() {
  }

  writeValue(obj: any): void {
    this.value = obj;
    this.checkOptions();
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(fn: any): void {
    this.onTouch = fn;
  }

  setDisabledState(isDisabled: boolean): void {
  }

  valueChange(value: any): void {
    if (this.ngControl && this.ngControl.control) {
      if (this.onChange) {
        this.onChange(value);
      }
      if (this.onTouch) {
        this.onTouch();
      }
    }
  }

  private onChange: any = (value) => {
  }

  private onTouch: any = () => {
  }

  private checkOptions() {
    if (this._modelValue) {
      const id = this._modelValue.code || this._modelValue.id;
      const name = this._modelValue.name || this._modelValue.title;
      if (id && this._options && this._options instanceof Array && !this._options.some(o => o.id === id)) {
        this._options = [...this.options, {id, name, disabled: true}];
      }
    }
  }
}
