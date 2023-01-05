import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {VaccineSeriesModel} from '../../../../../../../models/dictionary/vaccine-series.model';


@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  type = CrudType.DictionaryVaccineSeries;
  titleName = 'Серия вакцины';
  title = 'Создать';

  constructor() {
  }

  ngOnInit(): void {
  }


}
