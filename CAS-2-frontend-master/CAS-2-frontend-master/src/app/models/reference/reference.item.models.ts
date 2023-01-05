import {FormGroup} from '@angular/forms';
import {Injectable, OnInit, Optional} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {NotifyService} from '../../services/notify.service';
import {BreadcrumbsService} from '../../services/breadcrumbs.service';
import {GroupModel} from '../group.models';
import {CrudType} from 'src/app/common/crud-types';
import {Observable, of} from 'rxjs';
import {filter} from 'rxjs/operators';
import {MatDialogRef} from '@angular/material/dialog';
import {SnackBarService} from '../../services/snack-bar.service';
import {CrudState} from '../../api/api-connector/crud/crud-store.service';
import {
  LoadCreateAction,
  LoadGetAction,
  LoadGetListAction,
  LoadPatchAction
} from '../../api/api-connector/crud/crud.actions';
import {
  getCrudModelCreateLoading,
  getCrudModelCreatePatchLoading,
  getCrudModelGetLoading,
  getCrudModelStoreId
} from '../../api/api-connector/crud/crud.selectors';

@Injectable()
export class ReferenceItemModels {
  id: string;
  type: CrudType;
  model: any;
  item$: Observable<any>;
  item: any;
  title = '';
  openDialog = false;
  public formGroup: FormGroup;
  showError = false;
  colSizeParent: string;
  colSizeChild: string;
  fields: object;
  bussinesEntityNameList: Array<any> = [];
  public loading$: Observable<boolean>;
  protected store: Store<CrudState>;
  protected router: Router;
  protected route: ActivatedRoute;
  protected listNavigate: Array<string> = [];
  protected titleName = '';
  protected notify: NotifyService;
  protected snackBar: SnackBarService;
  protected brdSrv: BreadcrumbsService;
  private params: Params = {};
  @Optional() public dialogRef: MatDialogRef<any>;

  constructor(type: CrudType, model: any, id?: string, openDialog?: boolean, fields?: object) {
    this.openDialog = openDialog;
    this.id = id;
    this.type = type;
    this.model = model;
    this.item = new model();
    this.fields = fields;
  }

  ngOnInit(): void {
    if (this.id != null && this.openDialog === true) {
      this.params.id = this.id;
    } else {
      this.id = this.route.snapshot.paramMap.get('id');
      this.params.id = this.id;
    }
    if (this.type === CrudType.Group) {
      this.params.fields = {0: 'id', 1: 'name', 2: 'code', 3: 'roles'};
    }
    if (this.type === CrudType.User) {
      this.params.fields = this.fields;
    }
    if (this.isEdit()) {
      this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));
      this.store.dispatch(new LoadGetAction({
        type: this.type,
        params: this.params,
        onSuccess: (res) => {
          this.item = new this.model(res.response);
          if (this.type === CrudType.User) {
            this.getUserBusinessEntityList(this.item.id);
          }
          this.setModel();
          this.title = 'Редактировать' + ' ' + this.getTitle(this.id).toLowerCase() + ' ';
        },
        onComplete: () => {
          this.title = 'Редактировать' + ' ' + this.getTitle(this.id).toLowerCase() + ' ';
        }
      }));
      this.item$ = this.store.pipe(
        select(getCrudModelStoreId, {
          type: this.type,
          params: this.id,
        }),
        filter(item => !!item)
      );
    } else {
      /*заменяем хлебные крошки*/
      if (this.openDialog === true) {
        this.title = 'Создать ' + ' ' + this.titleName.toLowerCase() + ' ';
      } else if (this.brdSrv) {
        const b = this.brdSrv.getLast();
        b.label = 'Создать ' + ' ' + this.titleName.toLowerCase() + ' ';
        this.brdSrv.replaceLast(b);
      }
      /*заменяем хлебные крошки*/
      this.title = this.getTitle(this.id);
      if (!this.item.sort) {
        this.item.sort = 0;
      }
      this.setModel();
      this.item$ = of(null);
    }
  }

  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }
  getUserBusinessEntityList(id: number): void {
    this.store.dispatch(new LoadGetListAction(
      {
        type: CrudType.ReferenceBusinessEntity,
        params: {
          fields: {0: 'id', 1: 'name', users: ['username', 'id']},
          filter: {
            users: {id: id}
          }
        },
        onSuccess: (res: any) => {
          res.response.items.forEach(val => {
            this.bussinesEntityNameList.push({
              id : val.id,
              name: val.name,
              users: val.users
            });
          });
          if (this.bussinesEntityNameList.length) {
            this.colSizeParent = '9';
            this.colSizeChild = '3';
          } else {
            this.colSizeParent = '12';
            this.colSizeChild = '0';
          }
        }
      }
    ));
  }
  goBussinesEntity(id: number): void {
    const url: string =  location.origin + String(this.router.createUrlTree([`/reference/organization/business-entity/${id}`]));
    window.open(url, '_blank');
  }

  submit($event?: any, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      const model = value ? value : {...this.formGroup.value};
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;


      if (this.item.id) {
        model.id = this.item.id;
      }

      if (model.cashier && model.cashier.name) {
        delete model.cashier.name;
      }

      if (model['categoryNomenclature'] === '') {
        delete model.categoryNomenclature;
      }
      if (model['manufacturer'] === '') {
        delete model.manufacturer;
      }
      if (model['countries'] === '') {
        delete model.countries;
      }
      if (model['releaseForm'] === '') {
        delete model.releaseForm;
      }
      if (model['phoneNumber'] === '') {
        model.phoneNumber = null;
      }
      this.loading$ = this.store.pipe(select(this.item.id ? getCrudModelCreatePatchLoading : getCrudModelCreateLoading, {type: this.type}));

      this.store.dispatch(new action({
        type: this.type,
        params: model as any,
        fields: {fields: this.fields},
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            if (this.openDialog === true) {
              if (this.type === CrudType.User) {
                sessionStorage.setItem('station' , JSON.stringify(model.stations));
              }
              this.dialogRef.close(res.response);
            } else {
              const n = JSON.parse(JSON.stringify(this.listNavigate));
              /*n.push(res.response.id);*/
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

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }

  protected setModel(): void {
  }

  protected getTitle(id?: any): string {
    let name = '#' + id;
    if (this.item.name) {
      name = this.item.name;
    }
    return !this.isEdit() ? 'Создать ' + (String(this.titleName)).toLowerCase() : this.titleName + ' ' + name;
  }
}
