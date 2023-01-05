import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({
  selector: 'app-nomenclature-list',
  templateUrl: './nomenclature-list.component.html',
  styleUrls: ['./nomenclature-list.component.css']
})
export class NomenclatureListComponent implements OnInit {
  type = CrudType.ReferenceNomenclature;

  constructor() { }

  ngOnInit(): void {
  }

}
