import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Store} from '@ngrx/store';
import {AbstractControl, FormArray, FormControl} from '@angular/forms';
import {MatOption} from '@angular/material/core';
import {ApiParamsInterface} from 'src/app/api/api-connector/api-connector.models';
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudState, CrudStoreService} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-ui-select-reference',
  templateUrl: './ui-select-reference.component.html',
  styleUrls: ['./ui-select-reference.component.css']
})
export class UiSelectReferenceComponent implements OnInit {

  @Input() crudType: string;
  @Input() viewType = 'select';
  @Input() multiSelect: boolean;
  @Input() index?: number;
  @Input() control: FormControl | AbstractControl | null;
  @Input() value: any;
  @Input() filter: object;
  @Input() required: boolean;
  @Input() title: string;
  @Input() showDeleted = false;

  @Output() selected: EventEmitter<any> = new EventEmitter();

  checkboxFormArray: FormArray;
  options: MatOption[];
  reference = [];

  constructor(
    protected store: Store<CrudState>,
    protected connector: ApiConnectorService,
    protected crud: CrudStoreService,
  ) {
  }

  ngOnInit() {
    if (this.value && !Array.isArray(this.value)) {
      this.value = Array.isArray(JSON.parse(this.value))
        ? JSON.parse(this.value)
        : this.value.toString();
    }
    if (this.crudType) {
      // this.loading$ = this.store.pipe(select(getCrudModelGetListLoading , {type: this.crudType}));
      // this.store.dispatch(new LoadGetListAction({
      //   type: this.crudType,
      //   params: {order: {name: 'ASC'}, filter: this.filter ? this.filter : {}},
      //   onSuccess: response => {
      //     if (response) {
      //       this.reference = response.response.items;
      //     }
      //   }
      // }));

      if (this.showDeleted) {
        this.filter['deleted'] = '*';
      }

      const params: ApiParamsInterface = {order: {name: 'ASC'}, filter: this.filter ? this.filter : {}};
      this.connector.getList(
        this.crud.config[this.crudType].setData(params).url,
        params
      ).subscribe(response => {
        if (response) {
          this.reference = response.response.items;
          if (this.control && this.viewType === 'checkbox') {
            let values = {};
            if (this.control.value instanceof Array) {
              values = this.control.value.reduce((acc, v) => ({...acc, [v]: true}), {});
            }

            if (this.showDeleted) {
              this.reference = this.reference.filter(item => {
                if ((item.isDeleted && values[item.id]) || item.isDeleted === false) {
                  return true;
                }
              });
            }

            this.checkboxFormArray = new FormArray(this.reference.map(item => {
              return new FormControl(values[item.id]);
            }));
            this.checkboxFormArray.valueChanges
              .subscribe(vals => this.control.setValue(vals.reduce((acc, v, i) => {
                if (v) {
                  acc.push(this.reference[i].id);
                }
                return acc;
              }, [])));
          } else {
            if (this.showDeleted) {
              if (this.value instanceof Array) {
                const values = this.control.value.reduce((acc, v) => ({...acc, [v]: true}), {});
                this.reference = this.reference.filter(item => {
                  if ((item.isDeleted && values[item.id]) || item.isDeleted === false) {
                    return true;
                  }
                });
              } else {
                this.reference = this.reference.filter(item => {
                  if ((item.isDeleted && item.id === +this.value) || item.isDeleted === false) {
                    return true;
                  }
                });
              }
            }
          }
        }
      });
    }
  }

  onSelected($event) {
    this.selected.emit($event);
  }

  compareFn(o1: any, o2: any): boolean {
    let result = false;
    this.options.map(option => {
      if (+option.value === +o2) {
        result = true;
      }
    });
    return result && +o1 === +o2;
  }
}
