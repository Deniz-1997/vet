import {Component, ContentChild, Input, OnInit, TemplateRef} from '@angular/core';
import {NgForOfContext} from '@angular/common';
import {CrudDataInterface, CrudDataType} from 'src/app/api/api-connector/crud/crud.config';

@Component({
  selector: 'app-form',
  templateUrl: './component.html',
})
export class FormComponent implements OnInit {
  @Input() formGroup = null;
  @Input() submit;
  @ContentChild('contentForm', {static: true}) contentForm: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;

  constructor() {
  }

  ngOnInit() {
  }

}
