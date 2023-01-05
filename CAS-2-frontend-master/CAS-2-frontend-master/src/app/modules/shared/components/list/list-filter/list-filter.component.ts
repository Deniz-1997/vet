import {AfterContentInit, Component, ElementRef, EventEmitter, Input, OnInit, Output, ViewChild} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ListFilterTypeEnum} from './list-filter.enum';
import {ListFilterElementInterface, ListFilterFieldInterface} from './list-filter.model';
import * as _ from 'lodash';
import {Router} from '@angular/router';
import {ListFilterService} from './list-filter.service';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../../common/crud-types';
import {clearNullsDeep} from '../../../../../utils/clear-nulls';

declare var $: any;

export interface SearchOption {
  type: CrudType;
  name: string;
  url: string;
}

@Component({
  selector: 'app-list-filter',
  templateUrl: './list-filter.component.html',
  styleUrls: ['./list-filter.scss']
})
export class ListFilterComponent implements OnInit, AfterContentInit {

  @Input() type: CrudType;
  @Input() placeholder = 'Поиск по ключевому слову';
  @Input() extended = false;
  @Input() filterFields: Array<Array<ListFilterFieldInterface>> = [];
  @Input() mutableSearch = false;
  @Output() outFilter = new EventEmitter();

  @ViewChild('searchDetails', {static: true}) searchDetails: ElementRef;
  @ViewChild('extendBtn', {static: true}) extendBtn: ElementRef;

  crudType = CrudType;
  formGroup: FormGroup;
  fieldTypes = ListFilterTypeEnum;

  mutableSearchOption: Array<SearchOption> = [
  ];

  users$: Observable<Array<{ id: number, fullName: string }>>;
  PaymentStateEnum: ListFilterElementInterface = {options: []};
  PaymentTypeEnum: ListFilterElementInterface = {options: []};

  constructor(
    private fb: FormBuilder,
    private router: Router,
    private listFilterService: ListFilterService,
  ) {
  }

  ngOnInit(): void {
    if (this.listFilterService.extended) {

      this.extended = this.listFilterService.extended;
      this.listFilterService.extended = !this.listFilterService.extended;
    }

    this.formGroup = this.fb.group({
      search: new FormControl(this.listFilterService.search),
      mutableSearch: new FormGroup({
        type: new FormControl(this.mutableSearch ? this.type : null),
      }),
      filter: this.fb.group({}),
    });
    this.setFilter();
    this.PaymentStateEnum.options = [
      {id: 'NOT_PAID', name: 'Не оплачено'},
      {id: 'PAID', name: 'Оплачено'}
    ];
    this.PaymentTypeEnum.options = [
      {id: 'NULL', name: 'Не выбрано…'},
      {id: 'CASH', name: 'Наличными'},
      {id: 'ELECTRONICALLY', name: 'Безналичными'}
    ];
  }

  setFilter(): void {
    this.filterFields.forEach(row => row.forEach(field => {
      let initialValue: any = '';
      const props = field.prop.split('.');
      switch (field.type) {

        case ListFilterTypeEnum.date:
          _.update(field, ['body', 'class'], value => (value || '') + ' form-body--date');
          _.update(field, ['class'], value => (value || '') + ' form-span--date');
          break;

        case ListFilterTypeEnum.select:
          _.update(field, ['class'], value => (value || '') + ' min-select-width');
          initialValue = (field.attributes.value) ? field.attributes.value : null;
          break;

        case ListFilterTypeEnum.multiSelect:
          initialValue = {id: []};
          break;
      }
      if (this.mutableSearch) {

        if (props.length === 2 && this.listFilterService.filter
          && this.listFilterService.filter[props[1]]) {

          initialValue = this.listFilterService.filter[props[1]];
          // console.log('initialValue/2: ', initialValue);
        }

        if (props.length === 3 && this.listFilterService.filter
          && this.listFilterService.filter[props[1]]
          && this.listFilterService.filter[props[1]][props[2]]) {

          initialValue = this.listFilterService.filter[props[1]][props[2]];
          // console.log('initialValue/3: ', initialValue);
        }

        if (props.length === 4 && this.listFilterService.filter
          && this.listFilterService.filter[props[1]]
          && this.listFilterService.filter[props[1]][props[2]]
          && this.listFilterService.filter[props[1]][props[2]][props[3]]) {

          initialValue = this.listFilterService.filter[props[1]][props[2]][props[3]];
          // console.log('initialValue/4: ', initialValue);
        }
        //
        if (props.length === 5 && this.listFilterService.filter
          && this.listFilterService.filter[props[1]]
          && this.listFilterService.filter[props[1]][props[2]]
          && this.listFilterService.filter[props[1]][props[2]][props[3]]
          && this.listFilterService.filter[props[1]][props[2]][props[3]][props[4]]) {

          initialValue = this.listFilterService.filter[props[1]][props[2]][props[3]][props[4]];
          // console.log('initialValue/5: ', initialValue);
        }

        if (field.prop === 'pet.measurement-units.id') {
          initialValue = this.listFilterService.filter[props[1]];
        }
      } else if (this.listFilterService.filter) {
        if (props.length === 1 && this.listFilterService.filter
          && this.listFilterService.filter[props[0]]) {
          initialValue = this.listFilterService.filter[props[0]];
        }

        if (props.length === 2 && this.listFilterService.filter
          && this.listFilterService.filter[props[0]]
          && this.listFilterService.filter[props[0]][props[1]]) {
          initialValue = this.listFilterService.filter[props[0]][props[1]];
        }

        if (props.length === 3 && this.listFilterService.filter
          && this.listFilterService.filter[props[0]]
          && this.listFilterService.filter[props[0]][props[1]]
          && this.listFilterService.filter[props[0]][props[1]][props[2]]) {
          initialValue = this.listFilterService.filter[props[0]][props[1]][props[2]];
        }

        if (props.length === 4 && this.listFilterService.filter
          && this.listFilterService.filter[props[0]]
          && this.listFilterService.filter[props[0]][props[1]]
          && this.listFilterService.filter[props[0]][props[1]][props[2]]
          && this.listFilterService.filter[props[0]][props[1]][props[2]][props[3]]) {
          initialValue = this.listFilterService.filter[props[0]][props[1]][props[2]][props[3]];
        }
      }

      props.reduce((acc, prop, i) => {
        if (!acc.get(prop)) {
          acc.addControl(prop, (i !== (props.length - 1)) ?
            this.fb.group({}) :
            new FormControl(initialValue));
        }
        return acc.get(prop);
      }, this.formGroup.get('filter') as FormGroup);
    }));

    this.listFilterService.filter = [];
  }

  ngAfterContentInit(): void {
    if (this.listFilterService.search) {
      this.listFilterService.search = '';
    }
  }

  toggle(): void {
    $(this.extendBtn.nativeElement).fadeToggle(400);
    $(this.searchDetails.nativeElement).fadeToggle(400, 'swing', () => this.extended = !this.extended);
  }

  submit(): void {
    if (this.formGroup.valid) {

      const model = {...this.formGroup.value};
      if (this.mutableSearch) {
        const currentFilter = model.filter[this.formGroup.get('mutableSearch.type').value];
        delete model.filter;
        model.filter = currentFilter;
      }

      if (model.filter.contractorId && model.filter.contractorId.name) {
        model.filter.contractorId = model.filter.contractorId.id;
      }

      if (model.filter.breed && model.filter.breed.id) {

        if (typeof model.filter.breed.id === 'object') {
          model.filter.breed.id = model.filter.breed.id.id;
        }
      }

      if (this.mutableSearch && this.type !== model.mutableSearch.type) {

        this.listFilterService.search = model.search;
        this.listFilterService.filter = {...model.filter};

        if (this.extended) {
          this.listFilterService.extended = this.extended;
        }

        clearNullsDeep(this.listFilterService.filter);
        const currentOption: Array<SearchOption> = this.mutableSearchOption.filter(item => item.type === model.mutableSearch.type);
        this.router.navigate([currentOption[0].url]).then();

      } else {
        let fullName = false;
        if (model.filter.user && model.filter.user.fullName) {
          fullName = model.filter.user.fullName;
          delete model.filter.user.fullName;
        }
        clearNullsDeep(model.filter);
        this.outFilter.emit(model);
        if (fullName) {
          model.filter.user.fullName = fullName;
        }
        this.listFilterService.SetFilterToHash(model.filter);
      }
    }
  }

  convertAppointmentsDate(filterData: any): string {
    const date = (filterData as string).split('.');
    if (date.length === 3) {
      return '\'' + date[1] + '.' + date[0] + '.' + date[2] + '\'';
    }
  }
}
