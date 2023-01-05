import { Component, Input, OnInit } from '@angular/core';
import {FormControl} from '@angular/forms';
import {ErrorStateMatcher} from '@angular/material/core';
import {MatFormFieldAppearance} from '@angular/material/form-field';


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

@Component({
  selector: 'app-text-field',
  templateUrl: './text-field.component.html',
  styleUrls: ['./text-field.component.css']
})
export class TextFieldComponent implements OnInit {

  @Input() type: InputType = 'text';
  @Input() control: FormControl;
  @Input() required = false;
  @Input() prefix!: string;
  @Input() suffixIcon!: string;
  @Input() hint!: string;
  @Input() placeholder!: string;
  @Input() label!: string;
  @Input() appearance: MatFormFieldAppearance = 'fill';
  @Input() matcher!: ErrorStateMatcher;
  @Input() maxLength!: number;
  @Input() readonly!: boolean;
  @Input() disabled!: boolean;
  @Input() clearable!: boolean;

  constructor() { }

  ngOnInit(): void {
  }

}
