import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {CountriesEditComponent} from '../countries-edit/countries-edit.component';

@Component({
  selector: 'app-countries-list',
  templateUrl: './countries-list.component.html',
  styleUrls: ['./countries-list.component.css']
})
export class CountriesListComponent implements OnInit {
  type = CrudType.ReferenceCountries;
  component = CountriesEditComponent;
  code = 'reference-countries';

  constructor() { }

  ngOnInit(): void {
  }

}
