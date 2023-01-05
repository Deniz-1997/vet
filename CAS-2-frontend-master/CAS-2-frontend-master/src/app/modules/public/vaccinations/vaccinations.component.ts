import {DatePipe} from '@angular/common';
import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {Params} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {PersonModel} from 'src/app/models/auth/person.model';
import {VaccineSeriesModel} from 'src/app/models/dictionary/vaccine-series.model';
import {VaccinationModel} from 'src/app/models/vaccination/vaccination.model';
import {getCrudModelGetListLoading} from '../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-vaccinations',
  templateUrl: './vaccinations.component.html',
  styleUrls: ['./vaccinations.component.css']
})
export class VaccinationsComponent implements OnInit {
  displayedColumns = ['owner', 'name', 'chip', 'kind', 'breed', 'vaccineSeries', 'people', 'date'];
  private baseNullFilter = {'~!name': 'null'};
  vaccinations: Array<VaccinationModel> = [];
  isEmptyInformation = 'Отсутствуют данные за данный период.';
  formGroup: FormGroup;
  loading$: Observable<boolean>;
  crudType = CrudType;
  filter = this.baseNullFilter;
  filterAppend = {};
  type = CrudType.Vaccination;
  fields: { '0': 'animals', '1': 'vaccineSeries', '2': 'people', '3': 'date' };
  order = { date: 'DESC' };

  constructor(protected store: Store<CrudState>, private fb: FormBuilder, private datePipe: DatePipe) {
    const currentDate = new Date();
    this.formGroup = this.formGroup = this.fb.group({
      petType: new FormControl(''),
      breed: new FormControl(''),
      station: new FormControl(''),
      vaccine: new FormControl(''),
      dateFrom: new FormControl(this.datePipe.transform(new Date(currentDate.setMonth(currentDate.getMonth() - 1)), 'dd.MM.yyyy')),
      dateTo: new FormControl(this.datePipe.transform(new Date(), 'dd.MM.yyyy')),
      chip: new FormControl(''),
      tagNumber: new FormControl(''),
      owner: new FormControl(''),
      nickName: new FormControl(''),
    });
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

    this.formGroup.controls['vaccine'].valueChanges.subscribe((val) => {
      this.filter = val ? null : this.baseNullFilter;
    });
  }

  ngOnInit(): void {
    this.getData();
  }

  /*
    onChangeReference($event, formField, i) {

    } */

  getVaccines(vaccineSeries: Array<VaccineSeriesModel>): string {
    let vaccines = '';
    if (vaccineSeries) {
      for (const item of vaccineSeries) {
        vaccines += item.vaccine.name + ' ,';
      }
      vaccines = vaccines.substring(0, vaccines.length - 1);
    }
    return vaccines;
  }

  getData(): void {
    const params: Params = {};
    params.filter = {
      '>=date': this.formGroup.controls['dateFrom'].value,
      '<=date': this.formGroup.controls['dateTo'].value
    };
    if (this.formGroup.controls['vaccine'].value) {
      params.filter.vaccineSeries = {vaccine: this.formGroup.controls['vaccine'].value};
    }
    if (this.formGroup.controls['station'].value) {
      params.filter.station = this.formGroup.controls['station'].value;
    }
    if (this.formGroup.controls['petType'].value) {
      params.filter.animals = {kind: this.formGroup.controls['petType'].value};
    }
    if (this.formGroup.controls['nickName'].value) {
      params.filter.animals = {'~name': this.formGroup.controls['nickName'].value};
    }
    if (this.formGroup.controls['breed'].value) {
      params.filter.animals = {breed: this.formGroup.controls['breed'].value};
    }
    if (this.formGroup.controls['owner'].value) {
      params.filter.animals = {'~owner': this.formGroup.controls['owner'].value};
    }
    if (this.formGroup.controls['chip'].value) {
      params.filter.animals = {'~chip': this.formGroup.controls['chip'].value};
    }
    if (this.formGroup.controls['tagNumber'].value) {
      params.filter.animals = {animalStamps: {'~name': this.formGroup.controls['tagNumber'].value}};
    }
    this.filterAppend = params.filter;

    params.fields = this.fields;
    params.order = this.order;
    params.offset = 0;
    params.limit = 50;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: params,
      onSuccess: (res) => {
        if (res.status === true && res.response.items) {
          this.vaccinations = res.response.items;
        }
      }
    }));
  }

  submit(): void {
    this.getData();
  }
}
