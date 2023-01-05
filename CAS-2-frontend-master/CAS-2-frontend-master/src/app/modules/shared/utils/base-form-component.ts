import {FormGroup, NgForm} from '@angular/forms';
import {Directive, EventEmitter, Injectable, Input, OnChanges, Output, SimpleChanges, ViewChild} from '@angular/core';

@Directive()
export class BaseFormComponent implements OnChanges {

  @Input() loading: boolean;
  @Input() model: any;
  @Output() submitForm: EventEmitter<any>;
  @Output() cancelForm: EventEmitter<void>;
  @ViewChild('form', {static: true}) form: NgForm;

  formGroup: FormGroup;

  constructor() {
    this.submitForm = new EventEmitter();
    this.cancelForm = new EventEmitter();
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.formGroup) {

      if (changes['model'] && changes['model'].currentValue) {
        this.resetForm(changes['model'].currentValue);
      }

      if (this.onChanges) {
        this.onChanges(changes);
      }

    }
  }

  onChanges(changes: SimpleChanges): void {
  }

  resetForm(model: any): void {
    this.formGroup.reset(model);
  }

  submit(): void {
    if (this.formGroup && this.formGroup.valid) {
      this.submitForm.emit(this.formGroup.value);
    }
  }
}
