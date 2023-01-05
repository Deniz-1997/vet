import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {Observable, Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {NotifyService} from '../../../../services/notify.service';
import {PricePipe} from '../../pipes/price.pipe';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

export class Params {
  order?: { sort?: 'ASC' | 'DESC', surname?: 'ASC' | 'DESC', name?: 'ASC' | 'DESC' };
  offset?: number;
  limit?: number;
  fields?: any;
  filter?: {
    name?: string;
    type?: { id?: number | number[] };
    owners?: { owner?: { id?: number | number[] } }
    pets?: { pet?: { id?: number | number[] } }
  };
}

export class AutocompleteModel {
  id: number;
  name: string;
}

@Component({
  selector: 'app-ui-autocomplete',
  templateUrl: './ui-autocomplete.component.html',
  styleUrls: ['./ui-autocomplete.component.css']
})

export class UiAutocompleteComponent implements OnInit, OnDestroy {
  @Input() type: CrudType;
  @Input() petId?: number | number[];
  @Input() ownerId?: number;
  @Input() control: FormControl | AbstractControl | null;
  @Input() getterName: string | null = null;
  @Input() options?: Observable<AutocompleteModel[]>;
  @Input() addFilter: any;
  @Input() convertReturnResult: any;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  @Output() receivedCollectionFilter: EventEmitter<any> = new EventEmitter();
  @Input() fields: any = null;
  @Input() field = 'name';
  @Input() placeholder: string;
  @Input() styleClass?;
  @Input() required?: false;
  @Input() disabled?: false;
  @Input() limit?: number;
  @Input() filter = {};
  @Input() onChange?: EventEmitter<any> = new EventEmitter();
  loading = false;
  param: Params;
  crudType: CrudType;
  filteredOptions: AutocompleteModel[];
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();
  private isInit = false;

  constructor(
    private pricePipe: PricePipe,
    private notify: NotifyService,
    private store: Store<CrudState>) {
  }

  ngOnInit() {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
  }

  onSelected() {
    this.selected.emit(this.control.value);
  }

  getLoad() {
    return this.loading$ ? '' : '';
  }

  onBlur() {
    if (typeof this.control.value === 'string') {
      this.control.setValue(null);
      this.selected.emit();
    }
  }

  onFocus() {
    if (!this.isInit) {
      this.control.valueChanges
        .pipe(
          debounceTime(1000),
          distinctUntilChanged(),
          takeUntil(this.destroy$)
        )
        .subscribe(value => {
          if (!value || value.length < 2) {
            this.selected.emit();
            return this.filterAsync('');
          } else if (value && value.length > 1 && typeof value === 'string') {
            return this.filterAsync(value);
          }
        });
      this.isInit = true;
    }
    if (!this.petId && this.type === CrudType.ReferenceBreed) {
      this.options = null;
    } else {
      if (this.control.value) {
        this.filterAsync(this.control.value);
      } else {
        this.filterAsync('');
      }
    }
  }

  displayPetType(value: any): string {
    if (value && typeof value === 'object') {
      return value[this.field];
    } else if (value && typeof value === 'string') {
      return value;
    } else {
      return '';
    }
  }

  getFullName(item) {
    return ((item.surname || '') + ' ' + (item.name || '') + ' ' + (item.patronymic || '')).trim();
  }

  getAggressiveName(item) {
    let name = item.name;
    {
      for (let i = 0; i < item.level; i++) {
        if (i === 0) {
          name += ' (';
        }
        name += '*';
        if (i === item.level - 1) {
          name += ')';
        }
      }
    }
    return name;
  }

  getCategoryName(item) {
    return item['parent'] ? this.getCategoryName(item['parent']) + item['parent']['name'] + '->' : '';
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  private filterAsync(value: any): any {
    this.param = {};
    this.param.filter = this.filter;
    if (this.addFilter) {
      this.param.filter = Object.assign({}, this.addFilter);
    }

    this.param.offset = 0;

    if (typeof value === 'string' && value.length > 1 && this.type === CrudType.Pet) {
      this.param.filter['~name'] = value;
      this.param.filter['owners'] = {owner: {id: this.ownerId}};
    } else if (this.type === CrudType.Pet) {
      this.param.filter.owners = {};
      this.param.filter.owners = {owner: {id: this.ownerId}};
      this.param.order = {name: 'ASC'};
    }

    if (this.type === CrudType.ReferenceBreed && this.petId) {
      this.param.filter.type = {};
      this.param.filter.type.id = this.petId;
    }

    if (this.type === CrudType.Owner && this.petId) {
      this.param.filter.pets = {};
      this.param.filter.pets.pet = {id: this.petId};
    }

    if (typeof value === 'string' && value.length > 1) {
      this.param.order = {name: 'ASC'};
      this.param.limit = 500;

      if (this.type === CrudType.Owner) {
        this.param.filter['fullName'] = value;
      } else if (this.type === CrudType.User) {
        this.param.filter['fullName'] = value;
      } else {
        this.param.filter['~name'] = value;
      }
    } else {
      this.param.order = {sort: 'ASC', name: 'ASC'};
      if (this.limit) {
        this.param.limit = this.limit;
      } else {
        this.param.limit = 30;
      }
    }

    if (this.type === CrudType.ReferenceCashRegister) {
      this.param.limit = 20;
    }

    if (this.fields) {
      this.param.fields = this.fields;
    } else if (this.type === CrudType.User) {
      this.param.fields = {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'};
      this.param.order = {sort: 'ASC', surname: 'ASC', name: 'ASC'};
    } else if (this.type === CrudType.ReferencePetAggressiveType) {
      this.param.fields = {0: 'id', 1: 'name', 2: 'level'};
    } else {
      this.param.fields = {0: 'id', 1: 'name'};
    }

    this.loading = true;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: this.param,
      onSuccess: response => {
        this.loading = false;

        if (response.response.items.length === 0) {
          this.notify.handleMessage('Не найдено искомое значение', 'warning');
        }

        if (typeof this.convertReturnResult !== 'undefined') {
          this.filteredOptions = this.convertReturnResult(response.response.items, this.type);
        } else {
          this.filteredOptions = this.field === 'fullName' ? response.response.items.map(user => {
              return {id: user['id'], fullName: this.getFullName(user)};

            })
            : response.response.items;
        }

        if (this.receivedCollectionFilter.observers.length) {
          this.receivedCollectionFilter.emit(this.filteredOptions);
        }

        if (this.type === CrudType.ReferencePetAggressiveType) {
          this.field = 'subName';
          this.filteredOptions = response.response.items.sort((item1, item2) => {
            if (item1['level'] > item2['level']) {
              return 1;
            } else if (item1['level'] < item2['level']) {
              return -1;
            } else {
              return 0;
            }
          }).map(item => {
            return {id: item['id'], subName: this.getAggressiveName(item)};
          });
        }

        if (this.type === CrudType.ReferenceProduct) {
          this.field = 'subName';
          this.filteredOptions = response.response.items.map(item => {
            const value = {};
            for (const i in this.param.fields) {
              value[this.param.fields[i]] = item[this.param.fields[i]];
            }
            value['subName'] = item['price'] ? item.name + ' - ' + this.pricePipe.transform(item.price) : item.name;
            return value;
          });
        }

        if (this.type === CrudType.ReferenceCategoryNomenclature) {
          this.field = 'subName';
          this.filteredOptions = response.response.items.map(item => {
            return {id: item['id'], subName: this.getCategoryName(item) + item.name};
          });
        }
      }
    }));
  }
}
