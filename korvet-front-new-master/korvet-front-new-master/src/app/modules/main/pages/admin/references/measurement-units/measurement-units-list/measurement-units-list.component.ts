import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {MeasurementUnitsEditComponent} from '../measurement-units-edit/measurement-units-edit.component';

@Component({
  selector: 'app-measurement-units-list',
  templateUrl: './measurement-units-list.component.html',
  styleUrls: ['./measurement-units-list.component.css']
})
export class MeasurementUnitsListComponent implements OnInit {
  type = CrudType.ReferenceMeasurementUnits;
  component = MeasurementUnitsEditComponent;
  code = 'reference-measurement-units';

  constructor() { }

  ngOnInit(): void {
  }

}
