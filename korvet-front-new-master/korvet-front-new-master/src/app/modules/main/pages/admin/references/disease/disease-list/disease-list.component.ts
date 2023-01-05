import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {DiseaseEditComponent} from '../disease-edit/disease-edit.component';

@Component({
  selector: 'app-disease-list',
  templateUrl: './disease-list.component.html',
  styleUrls: ['./disease-list.component.css']
})
export class DiseaseListComponent implements OnInit {
  type = CrudType.ReferenceDisease;
  component = DiseaseEditComponent;
  code = 'reference-disease';

  constructor() { }

  ngOnInit(): void {
  }

}
