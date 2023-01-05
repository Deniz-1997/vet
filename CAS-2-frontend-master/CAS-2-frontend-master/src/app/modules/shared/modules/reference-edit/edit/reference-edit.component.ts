import {Component, Inject, Input, OnInit, Optional} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {getAllProperties, getPropertyesType, PropertyViewType} from '../../../decorators/property-type.decorator';
import {CrudType, CrudTypes} from '../../../../../common/crud-types';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {combineLatest, Observable} from 'rxjs';
import {SnackBarService} from '../../../../../services/snack-bar.service';
import {map} from 'rxjs/operators';
import {
  getCrudModelCreateLoading,
  getCrudModelCreatePatchLoading,
  getCrudModelGetLoading
} from '../../../../../api/api-connector/crud/crud.selectors';
import {
  LoadCreateAction,
  LoadGetAction,
  LoadGetListAction,
  LoadPatchAction
} from '../../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-reference',
  templateUrl: './reference-edit.component.html',
  styleUrls: ['./reference-edit.component.css'],
})

export class ReferenceEditComponent implements OnInit {
  private _type: CrudType;
  @Input()
  get type(): CrudType {
    return this._type;
  }
  set type(value: CrudType) {
    this._type = value;
    this._model = CrudTypes[value].model;
  }
  private _model: any;
  @Input() listNavigate: Array<string> = [];
  @Input() titleName = '';
  @Input() title = 'Создать';
  @Input() params: Params = {};
  properties = [];
  id: string;
  openDialog = false;
  formGroup: FormGroup;
  showError = false;
  loading$: Observable<boolean>;
  propertyViewType = PropertyViewType;
  fullName = 'Иванов Иван Иванович';
  model: any;
  fields: object;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    private snackBar: SnackBarService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<any>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean, id: string}
  ) {
  }

  ngOnInit(): void {
    this.getClassMetadata();
    if (this.data) {
      this.openDialog = this.data.openDialog;
      this.id = this.data.id;
    }
    if (this.id != null && this.openDialog === true) {
      this.params.id = this.id;
    } else {
      this.id = this.route.snapshot.paramMap.get('id');
      this.params.id = this.id;
    }
    if (this.type === CrudType.Group) {
      this.params.fields = {0: 'id', 1: 'name', 2: 'code', 3: 'roles'};
    }
    this.loading$ = combineLatest([
      this.store.pipe(select(getCrudModelGetLoading, { type: this.type })),
      this.store.pipe(select(getCrudModelCreatePatchLoading, { type: this.type })),
      this.store.pipe(select(getCrudModelCreateLoading, { type: this.type })),
    ]).pipe(map(loading => loading.some(l => l)));

    if (this.isEdit()) {
      if (this.fields !== undefined) {
        this.params.fields = this.fields;
      }
      this.store.dispatch(new LoadGetAction({
        type: this.type,
        params: this.params,
        onSuccess: (res) => {
          this._model = res.response;
          this.setModel();
          this.title = 'Редактировать' + ' ' + this.getTitle(this.id).toLowerCase() + ' ';
        },
        onComplete: () => {
          this.title = 'Редактировать' + ' ' + this.getTitle(this.id).toLowerCase() + ' ';
        }
      }));
    } else {
      this.title = this.getTitle(this.id);
      this.setModel();
    }
  }

  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }

  submit($event?: any, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      this.model = value ? value : {...this.model, ...this.formGroup.value};
      const action = this._model.id ? LoadPatchAction : LoadCreateAction;
      delete this.model.field;
      if (this.model.parent && !this.model.parent.id) {
        this.model.parent = null;
      }

      if (this._model.id) {
        this.model.id = this._model.id;
      }
      if (!this.model.id) {
        this.model.id = 0;
      }
      Object.keys(this.properties).forEach(val => {
        if (this.properties[val].crudType === 'User') {
          switch (this.properties[val].type) {
            case 3:
              delete this.model['user'].name;
              break;
            default:
              if (this.model['users'] !== null) {
                this.model['users'].map(user => delete user.name);
              } else {
                this.model['users'] = [];
              }
              break;
          }
        }
      });

      this.store.dispatch(new action({
        type: this.type,
        params: this.model as any,
        fields: {fields: this.fields},
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            if (this.openDialog === true) {
              this.dialogRef.close(res.response);
            } else {
              const n = JSON.parse(JSON.stringify(this.listNavigate));
              this.router.navigate(n).then();
            }
          }
        }
      }));
    } else {
      this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
      this.showError = true;
    }
  }

  isEdit(): boolean {
    return ['create', null, undefined].indexOf(this.id) < 0;
  }

  changeInput(val: string, property: string): void {
    this.formGroup.controls[property].setValue(val);
  }

  setModel(): void {
    if (this._model.headFullName !== undefined && this._model.headFullName !== null) {
      this.fullName = this._model.headFullName;
    }
    if (this._model.owner !== undefined && this._model.owner !== null) {
      this.fullName = this._model.owner;
    }
    this.formGroup = new FormGroup({});
    this.properties.forEach(val => {
      const validatorOpts = [];
      if (val.required === true) {
        validatorOpts.push(Validators.required);
      }
      if (val.type === PropertyViewType.SELECT) {
        const group = new FormGroup({
          code: new FormControl(typeof this._model === 'object' ? this._model[val.name].code : null, validatorOpts),
        });
        this.formGroup.addControl(val.name, group);
      } else if (val.type === PropertyViewType.INPUT_INT) {
        this.formGroup.addControl(val.name, new FormControl(typeof this._model === 'object' ? this._model[val.name] : 0, validatorOpts));
      } else if (val.type === PropertyViewType.CHECK_BOX) {
        this.formGroup.addControl(val.name, new FormControl(typeof this._model === 'object' ? this._model[val.name] : false, validatorOpts));
      } else if (val.inputType !== 0) {
        this.formGroup.addControl(val.name, new FormControl(typeof this._model === 'object' ? this._model[val.name] : null, validatorOpts));
      }
    });
  }

  protected getSelectItems(selectType: CrudType, property: any): void {
    if (!selectType) { return; }
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {id: [selectType]}
      },
      onSuccess: (res => {
        property.selectValues = res.response[0].items;
      })
    }));
  }

  getTitle(id?: any): string {
    let name = '#' + id;
    if (this._model.name) {
      name = this._model.name;
    }
    return !this.isEdit() ? 'Создать ' + (this.titleName as string).toLowerCase() : this.titleName + ' ' + name;
  }

  getClassMetadata(): void {
    const propertiesName = getAllProperties(new this._model());
    for (const i in propertiesName) {
      if (propertiesName.hasOwnProperty(i)) {
        this.properties.push(getPropertyesType(new this._model(), propertiesName[i]));
        if (this.properties[i].type === PropertyViewType.AUTOCOMPLETE || this.properties[i].type === PropertyViewType.MULTISELECT) {
          this.properties[i].crudTypeReference = CrudType[this.properties[i].crudType];
        }
        if (this.properties[i].type === PropertyViewType.SELECT) {
          this.getSelectItems(this.properties[i].crudType, this.properties[i]);
        }
        if (this.properties[i].type === PropertyViewType.FIELDS) {
          this.fields = this.properties[i].fields;
        }
      }
    }
  }
}
