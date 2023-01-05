import {ChangeDetectorRef, Component, ElementRef, HostListener, Input, OnInit, ViewChild} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {MatSelectionListChange} from '@angular/material/list';
import {Params} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {debounceTime, distinctUntilChanged} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {LoadAppendListAction, LoadGetListAction} from '../../../../api/api-connector/crud/crud.actions';
import {StationListConvertService} from '../../../../services/stationListConvert.service';
import {Observable} from 'rxjs';
import {getCrudModelData} from '../../../../api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-ui-multi-select-field',
  templateUrl: './ui-multi-select-field.component.html',
  styleUrls: ['./ui-multi-select-field.component.scss'],
})
export class UiMultiSelectFieldComponent implements OnInit {
  @ViewChild('search') searchTextBox: ElementRef;
  @ViewChild('resultInput', {read: ElementRef}) resultInput: ElementRef;
  @ViewChild('panel', {read: ElementRef}) panel: ElementRef;

  @Input() addFilter: any;
  @Input() selectFormControl: FormControl | AbstractControl | null;
  @Input() label = '-';
  @Input() offClearData = false;
  @Input() placeholder = 'Выберите значения';
  @Input() disabled = false;
  @Input() message: string;
  offset = 0;
  items$: Observable<Array<any>>;

  _data;

  @Input() set data(val: any) {
    this._data = val;
  }

  get data(): any {
    if (this.filterByName) {
      return this._data.filter(option => option.name.toLowerCase().includes(
        this.searchTextboxControl.value.toLowerCase()
      ));
    }

    return this._data;
  }

  @Input() type: CrudType;
  searchTextboxControl = new FormControl();
  dataLoading = false;
  filterByName = false;
  selectedValues = new Array<any>();
  panelOpened = false;
  resultInputValue = '';

  @HostListener('document:click', ['$event.target'])
  onGlobalClick(target?: any): void {
    if (this.panelOpened) {
      this.changeDetector.detectChanges();
      if (this.resultInput.nativeElement.contains(target)
        || target.innerText === 'close'
        || (target as HTMLElement).className === 'mat-list-text'
        || (target as HTMLElement).className === 'mat-list-item-content'
        || (target as HTMLElement).className === 'mat-pseudo-checkbox ng-star-inserted'
        || (target as HTMLElement).className === 'mat-pseudo-checkbox mat-pseudo-checkbox-checked ng-star-inserted') {
        return;
      }
      if (!this.panel.nativeElement.contains(target)) {
        this.panelOpened = false;
      }
    }
  }

  constructor(
    protected store: Store<CrudState>,
    private changeDetector: ChangeDetectorRef,
    private stationListConvertService: StationListConvertService
  ) {
    this.searchTextboxControl.valueChanges.pipe(
      debounceTime(1000),
      distinctUntilChanged()
    ).subscribe(value => {
      this.loadProducts();
      this.filterByName = value !== '' && value !== null && value !== undefined;
    });
  }

  ngOnInit(): void {
    if ((!this.data || this.data.length === 0) && this.type) {
      this.loadProducts();
    }
    if (this.selectFormControl.value && this.selectFormControl.value.length > 0) {
      this.selectedValues = this.selectFormControl.value;
      this.setInputString();
    }
  }

  selectionChange(event: MatSelectionListChange): void {
    if (event.options[0].selected && !this.selectedValues.find(n => n.id === event.options[0].value['id'])) {
      this.selectedValues.push(event.options[0].value);
      if (this.type === CrudType.ReferenceStation &&  event.options[0].value.children.length) {
        this.selectedValues.push(...this.stationListConvertService.selectedValues(event.options[0].value.children));
      }
    }
    if (!event.options[0].selected) {
      const item = this.selectedValues.find(n => n.id === event.options[0].value['id']);
      if (item) {
        const index = this.selectedValues.indexOf(item);
        this.selectedValues.splice(index, 1);
      }
    }
    this.setInputString();
  }

  private setInputString(): void {
    this.resultInputValue = '';
    for (const item of this.selectedValues) {
      this.resultInputValue += `${this.getFullName(item)} ${item['username'] ? `(${item['username']})` : ''}` + '; ';
    }
    this.setFormControlValue();
  }

  isOptionSelected(option: any): boolean {
    return !!this.selectedValues.find(n => n.id === option['id']);
  }

  onDeselectAll(): void {
    this.selectedValues = new Array<any>();
    this.setInputString();

    if (!this.offClearData){
      // this.data = new Array<any>();
      this.loadProducts();
    }
  }

  private setFormControlValue(): void {
    this.selectFormControl.setValue(this.selectedValues);
  }

  openPanel(): void {
    this.panelOpened = !this.panelOpened;
    if (this.panelOpened) {
      this.changeDetector.detectChanges();
      this.searchTextBox.nativeElement.focus();
    }
  }

  clearSearch($event: any): void {
    this.filterByName = false;
    this.searchTextboxControl.setValue(null);
  }
  getFullName(item: any): string {
    return ((item.surname || '') + ' ' + (item.name || '') + ' ' + (item.patronymic || '')).trim();
  }

  private loadProducts(offset: number = 0): void {
    if (this.type !== undefined){
      let filter: Params = {};
      let search: string;
      let field: Params = {};
      let limit = 30;
      let count;
      let order: Params = {name: 'ASC'};
      if (this.addFilter) {
        filter = Object.assign({}, this.addFilter);
      }
      if (this.searchTextboxControl.value) {
        if (this.type === 'user') {
          search = this.searchTextboxControl.value;
        } else {
          filter['~name'] = this.searchTextboxControl.value;
        }
      }
      switch (this.type) {
        case CrudType.User:
          field = {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic', 4: 'username'};
          break;
        case CrudType.ReferenceStation:
          this.items$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStation}));
          field = {0: 'id', 1: 'name', parent: ['id', 'name']};
          order = {id: 'ASC'};
          limit = 50;
          break;
        default:
          field = {0: 'id', 1: 'name'};
      }
      this.dataLoading = true;
      this.store.dispatch(new LoadGetListAction({
        type: this.type,
        params: {
          fields: field,
          search: search,
          order: order,
          filter: filter,
          offset: offset,
          limit: limit
        },

        onSuccess: ({status, response}) => {
          if (status && response && response.items) {
            switch (this.type) {
              case CrudType.User:
                this.data = response.items.map(user => {
                  return {id: user['id'], name: `${this.getFullName(user)} ${user['username'] ? `(${user['username']})` : ''}`};
                });
                break;
              case CrudType.ReferenceStation:
                this.offset = response.countItems;
                count = response.totalCount;
                if (this.offset < response.totalCount) {
                  this.appendList(limit, this.offset, field, order);
                }
                break;
              default:
                this.data = response.items;
            }
          }
          this.dataLoading = false;
        },
        onError: _ => {
          this.dataLoading = false;
        }
      }));
      if (this.type === CrudType.ReferenceStation) {
        this.items$.subscribe(states => {
          this.stationListConvertService.stationList = states;
          if (this.stationListConvertService.stationList.length === count) {
            this.stationListConvertService.stationListConvert =
              this.stationListConvertService.formatListStation(this.stationListConvertService.stationList[0]?.parent);
            this.data = this.stationListConvertService.stationListConvert;
          }

        });
      }
    }
  }
  appendList(limit: number, offset: number, fields: Params, order: Params): void {
    this.store.dispatch(new LoadAppendListAction({
      type: this.type,
      params: {
        order: order,
        offset: offset,
        limit: limit,
        fields: fields,
      },
      onSuccess: ({response}) => {
        this.offset += response.countItems;
        if (this.offset < response.totalCount) {
          this.appendList(limit, this.offset, fields,  order);
        }
      },
    }));
  }

}
