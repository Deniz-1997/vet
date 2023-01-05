import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {FormControl} from '@angular/forms';
import {Observable, Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {NotifyService} from '../../../../services/notify.service';
import {SnackBarService} from '../../../../services/snack-bar.service';
import {getCrudModelGetListLoading} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../api/api-connector/crud/crud.actions';
import {ErrorStateMatcher} from '@angular/material/core';

export class Params {
  order?: {sort?: 'ASC' | 'DESC', surname?: 'ASC' | 'DESC', name?: 'ASC' | 'DESC', address?: 'ASC' | 'DESC'};
  offset?: number;
  limit?: number;
  fields?: any;
  filter?: {
    name?: string;
    type?: {id?: number | Array<number>};
  };
}

export class AutocompleteModel {
  id: number;
  name: string;
  address: string;
}

@Component({
  selector: 'app-ui-autocomplete',
  templateUrl: './ui-autocomplete.component.html',
  styleUrls: ['./ui-autocomplete.component.scss']
})

export class UiAutocompleteComponent implements OnInit, OnDestroy {
  @Input() type: CrudType;
  @Input() label: string;
  @Input() petId?: number | Array<number>;
  @Input() control: FormControl;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  @Input() fields: any = null;
  @Input() field = 'name';
  @Input() placeholder: string;
  @Input() required?: false;
  @Input() disabled =  false;
  @Input() limit?: number;
  @Input() matcher!: ErrorStateMatcher;
  @Input() textError = 'Обязательное поле';
  loading = false;
  param: Params;
  filteredOptions: Array<AutocompleteModel>;
  loading$: Observable<boolean>;
  private _addFilter: any;
  private destroy$ = new Subject<any>();
  @Input('addFilter')
  get addFilter(): any {
    return this._addFilter;
  }

  set addFilter(value: any) {
    this._addFilter = value;
    setTimeout(() => this.filterAsync(''), 500);
  }

  constructor(
    private notify: NotifyService,
    private snackBar: SnackBarService,
    private store: Store<CrudState>) {
  }

  ngOnInit(): void {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    setTimeout(() => this.filterAsync(''), 500);
    this.control.valueChanges.pipe(
      debounceTime(500),
      distinctUntilChanged(),
      takeUntil(this.destroy$)
    ).subscribe(value => {
      if (typeof value === 'string') {
        this.filterAsync(value);
      }
    });
  }

  onSelected(): void {
    this.selected.emit(this.control.value);
  }

  displayFn(value: any): string {
    return value && value[this.field] ? value[this.field] : '';
  }

  getFullName(item: any): string {
    return ((item.surname || '') + ' ' + (item.name || '') + ' ' + (item.patronymic || '')).trim();
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  private filterAsync(value: any): any {
    this.loading = true;
    this.param = {};
    this.param.filter = {};
    this.param.offset = 0;
    this.param.limit = 30;
    this.param.order = {name: 'ASC'};
    if (this.addFilter) {
      this.param.filter = Object.assign({}, this.addFilter);
    }
    if (this.type === CrudType.User) {
      this.param.filter['fullName'] = value;
      this.param.fields = {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic', 4: 'username'};
      this.param.order = {sort: 'ASC', surname: 'ASC', name: 'ASC'};
    } else if (this.type === CrudType.ReferenceBreed && this.petId) {
      this.param.filter.type = {};
      this.param.filter.type.id = this.petId;
    } else if (this.type === CrudType.ReferenceLocation || this.type === CrudType.ReferenceSupervisedObject) {
      this.param.order = {address: 'ASC'};
      this.param.fields = {0: 'id', 1: 'address'};
      this.param.filter['~address'] = value;
    } else {
      this.param.filter['~name'] = value;
      this.param.fields = {0: 'id', 1: 'name'};
    }
    if (this.limit) {
      this.param.limit = this.limit;
    }
    if (this.fields) {
      this.param.fields = this.fields;
    }
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: this.param,
      onSuccess: ({status, response}) => {
        if (status) {
          this.loading = false;
          if (!response.items.length) {
            this.snackBar.handleMessage('Не найдено искомое значение', 'warning-snackBar', 2000);
          }
          if (this.type === CrudType.User) {
            this.filteredOptions = response.items.map(user => {
              return {id: user['id'], name: `${this.getFullName(user)} ${user['username'] ? `(${user['username']})` : ''}`};
            });
          } else if (this.type === CrudType.ReferenceLocation || this.type === CrudType.ReferenceSupervisedObject) {
            this.filteredOptions = response.items.map(location => {
              return {id: location['id'], address: location['address']};
            });
          } else {
            this.filteredOptions = this.field === 'fullName' ? response.items.map(user => {
              return {id: user['id'], fullName: this.getFullName(user)};
            }) : response.items;
          }
        }
      },
      onError: _ => {
        this.snackBar.handleMessage('Ошибка запроса на сервер', 'warning-snackBar', 2000);
        this.loading = false;
      }
    }));
  }
}
