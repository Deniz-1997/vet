import {Component, EventEmitter, Input, OnInit, Output, ViewChild} from '@angular/core';
import {FormControl} from '@angular/forms';
import {CrudType} from '../../../../common/crud-types';
import {concat, Observable, of, timer} from 'rxjs';
import {debounce, distinctUntilChanged, finalize, map, switchMap, tap} from 'rxjs/operators';
import {MatAutocomplete, MatAutocompleteSelectedEvent, MatAutocompleteTrigger} from '@angular/material/autocomplete';
import {ApiParamsInterface} from 'src/app/api/api-connector/api-connector.models';
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudStoreService} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-ui-autocomplete-field',
  templateUrl: './ui-autocomplete-field.component.html',
  styleUrls: ['./ui-autocomplete-field.component.css']
})
export class UiAutocompleteFieldComponent implements OnInit {

  @Input() control: FormControl;
  @Input() inputControl: FormControl = new FormControl('');
  @Input() baseParams: ApiParamsInterface = {};
  @Input() type: CrudType;
  @Input() styleClass: string;
  @Input() field: string | string[] = 'name';
  @Input() initial: any = null;
  @Output() selectedOn = new EventEmitter();

  loading = false;
  filteredOptions$: Observable<any[]>;
  @Input() displayWithFn = ((value: any) => {
    if (value instanceof Object) {
      if (this.field) {
        const field = this.field instanceof Array ? this.field : this.field.split('.');
        return field.reduce((acc, key) => acc instanceof Object ? (acc[key] || '') : (acc || ''), value);
      } else {
        return value['name'] || '';
      }
    } else {
      return value;
    }
  });
  @Input() valueFn = ((value: any) => value['id'] || NaN);
  @ViewChild('auto', {static: true}) autocomplete: MatAutocomplete;
  @ViewChild(MatAutocompleteTrigger, {static: true}) autocompleteTrigger: MatAutocompleteTrigger;
  private options: any[] = [];

  constructor(
    private crudConfig: CrudStoreService,
    private crud: ApiConnectorService,
  ) {
  }

  ngOnInit() {
    if (this.initial) {
      this.inputControl.setValue(this.displayWithFn(this.initial));
    }
    this.filteredOptions$ = this.inputControl.valueChanges.pipe(
      // startWith(this.inputControl.value || ''),
      debounce(value => value ? timer(1000) : timer(0)),
      distinctUntilChanged(),
      switchMap(value => {
        if (value instanceof Object) {
          return of([]);
        } else {
          this.loading = true;
          this.control.setValue(null);
          this.selectedOn.emit(null);
          const url = this.crudConfig.config[this.type].setData({}).url;
          const field = this.field instanceof Array ? this.field : this.field.split('.');
          const filter = field.reduceRight((acc, k, i) => ({[i === 0 ? '~' + k : k]: acc}), value);
          const req = this.crud.getList(url, {
            limit: 10,
            offset: 0,
            ...this.baseParams,
            filter: {
              ...(this.baseParams.filter || {}),
              ...filter,
            }
          }).pipe(
            map(list => list.response.items),
            map(items => items.length ? items : [{id: null, name: 'Ничего не найдено', disabled: true}]),
            tap(options => {
              this.options = options;
              if (options.length === 1 && this.displayWithFn(options[0]) === this.inputControl.value) {
                this.control.setValue(this.valueFn(options[0]));
                this.selectedOn.emit(options[0]);
                this.autocompleteTrigger.closePanel();
              }
            }),
            finalize(() => this.loading = false)
          );
          if (!this.options.length || this.control.value) {
            return concat(of([{id: null, name: 'Загрузка...', disabled: true}]), req);
          } else {
            return req;
          }
        }
      }),
    );
  }

  optionSelected(selected: MatAutocompleteSelectedEvent): void {
    this.control.setValue(this.valueFn(selected.option.value));
    this.selectedOn.emit(selected.option.value);
  }

  onBlur(): void {
    if (!this.control.value) {
      this.inputControl.setValue('');
    }
  }
}
