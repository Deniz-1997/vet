import {Component, Inject, OnDestroy, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ActionModel} from '../../../../../../../models/action/action.models';
import {EnumModel} from '../../../../../../../models/enum .models';
import {Observable, Subject} from 'rxjs';
import {EntityModel} from '../../../../../../../models/entity.models';
import {ReferenceIconModel} from '../../../../../../../models/reference/icon.model';
import {CrudType} from '../../../../../../../common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnDestroy {
  type = CrudType.Action;
  ActionTypeEnum: EnumModel[];
  crudType = CrudType;
  actionItems$: Observable<ActionModel[]>;
  entityItems: EntityModel[];
  filteredEntity: EntityModel[];
  icons$: Observable<ReferenceIconModel[]>;
  iconFields = {0: 'id', 1: 'code', 2: 'name'};
  loading$: Observable<boolean>;
  searchDefActions;
  protected listNavigate = ['admin', 'references', 'action'];
  protected titleName = 'Действие';
  private destroy$ = new Subject<any>();

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.Action, ActionModel, data.id, data.openDialog);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'ActionTypeEnum',
          ]
        }
      },
      onSuccess: (res) => {
        res.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));

    this.store.pipe(select(getCrudModelData, {type: CrudType.Entity}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Entity,
      onSuccess: item => {
        this.entityItems = item.response;
        this.filteredEntity = item.response;
      }
    }));
    /*Иконки*/
    this.icons$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceIcon}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceIcon,
      params: {fields: {0: 'id', 1: 'name', 2: 'code'},
      filter: {subclass: 'NOANIMAL'}}}));

    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
  }

  displayFn(user?: EntityModel): string | undefined {
    return user ? user.name : undefined;
  }

  isTypeEntityListUrl() {
    return this.formGroup && this.formGroup.get('type').value.code === 'ENTITY_LIST_URL';
  }

  isTypeEntity() {
    return this.formGroup && this.formGroup.get('type').value.code === 'ENTITY';
  }

  submit(): void {
    const model = {...this.formGroup.value};
    if (model.rolesSearch) {
      delete model.rolesSearch;
    }

    if (model.buttonSettings.icon && model.buttonSettings.icon.id === null) {
      delete model.buttonSettings.icon;
    }
    if (model.confirmation.confirmButtonSettings.icon && model.confirmation.confirmButtonSettings.icon.id === null) {
      delete model.confirmation.confirmButtonSettings.icon;
    }
    if (model.confirmation.cancelButtonSettings.icon && model.confirmation.cancelButtonSettings.icon.id === null) {
      delete model.confirmation.cancelButtonSettings.icon;
    }

    super.submit(null, model);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  protected setModel() {

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      code: new FormControl(this.item.code, [Validators.required]),
      description: new FormControl(this.item.description),

      additionalActions: new FormControl(this.item.additionalActions ? this.item.additionalActions : []),

      parent: new FormControl(this.item.parent ? this.item.parent : null),
      groups: new FormControl(this.item.groups ? this.item.groups : []),
      sort: new FormControl(this.item.sort),

      type: new FormGroup({
        code: new FormControl(this.item.type && this.item.type.code || null, [Validators.required]),
      }),
      url: new FormControl(this.item.url),
      entityClass: new FormControl(this.item.entityClass),

      roles: new FormControl(this.item.roles ? this.item.roles : []),

      buttonSettings: new FormGroup({
        color: new FormControl((this.item.buttonSettings && this.item.buttonSettings.color)
          ? this.item.buttonSettings.color : null),
        backgroundColor: new FormControl((this.item.buttonSettings && this.item.buttonSettings.backgroundColor)
          ? this.item.buttonSettings.backgroundColor : null),

        icon: new FormControl(this.item.buttonSettings && this.item.buttonSettings.icon ? this.item.buttonSettings.icon : null),
      }),

      confirmation: new FormGroup({
        title: new FormControl((this.item.confirmation && this.item.confirmation.title)
          ? this.item.confirmation.title : ''),
        description: new FormControl(
          (this.item.confirmation && this.item.confirmation.description)
            ? this.item.confirmation.description : ''),
        confirmButtonSettings: new FormGroup({
          color: new FormControl(
            (this.item.confirmation && this.item.confirmation.confirmButtonSettings)
              ? this.item.confirmation.confirmButtonSettings.color : ''),
          backgroundColor: new FormControl(
            (this.item.confirmation && this.item.confirmation.confirmButtonSettings)
              ? this.item.confirmation.confirmButtonSettings.backgroundColor : ''),
          icon: new FormControl((this.item.confirmation &&
            this.item.confirmation.confirmButtonSettings &&
            this.item.confirmation.confirmButtonSettings.icon) ?
            this.item.confirmation.confirmButtonSettings.icon : null)
        }),
        cancelButtonSettings: new FormGroup({
          color: new FormControl(
            (this.item.confirmation && this.item.confirmation.cancelButtonSettings)
              ? this.item.confirmation.cancelButtonSettings.color : ''),
          backgroundColor: new FormControl(
            (this.item.confirmation && this.item.confirmation.cancelButtonSettings)
              ? this.item.confirmation.cancelButtonSettings.backgroundColor : ''),
          icon: new FormControl((this.item.confirmation &&
            this.item.confirmation.cancelButtonSettings &&
            this.item.confirmation.cancelButtonSettings.icon) ?
            this.item.confirmation.cancelButtonSettings.icon : null)
        }),
      }),

      // type = ENTITY_LIST_URL
      itemsCountEnabled: new FormControl({
        value: (this.item.itemsCountEnabled ? this.item.itemsCountEnabled : false),
        disabled: this.isTypeEntityListUrl()
      }),
      itemsCount: new FormControl({
        value: (this.item.itemsCount ? this.item.itemsCount : 0),
        disabled: this.isTypeEntityListUrl()
      }),

      // type = ENTITY
      getListEnabled: new FormControl({
        value: (this.item.getListEnabled ? this.item.getListEnabled : false),
        disabled: this.isTypeEntity()
      }),
      viewItemEnabled: new FormControl({
        value: (this.item.viewItemEnabled ? this.item.viewItemEnabled : false),
        disabled: this.isTypeEntity()
      }),
      getItemEnabled: new FormControl({
        value: (this.item.getItemEnabled ? this.item.getItemEnabled : false),
        disabled: this.isTypeEntity()
      }),
    });

    this.formGroup.controls.entityClass.valueChanges
      .subscribe(item => {
        if (typeof item === 'string') {
          this.filteredEntity = this.filter(item);
        } else {
          this.filteredEntity = this.entityItems;
        }
      });

    this.formGroup.controls.type.valueChanges
      .subscribe(item => {
          if (item && item.code !== 'ENTITY_LIST_URL') {
            this.formGroup.controls.itemsCountEnabled.setValue(false);
            this.formGroup.controls.itemsCount.setValue(0);
          }

          if (item && item.code !== 'ENTITY') {
            this.formGroup.controls.getListEnabled.setValue(false);
            this.formGroup.controls.viewItemEnabled.setValue(false);
            this.formGroup.controls.getItemEnabled.setValue(false);
          }
        }
      );
  }
  apendList(offset, type) {
    this.store.dispatch(new LoadAppendListAction({
      type: type,
      params: {
        order: {name: 'ASC'},
        fields: {0: 'id', 1: 'name'},
        offset: offset,
        limit: 20
      },
      onSuccess: res => {

        if (res.response.items.length !== 0) {

          offset += res.response.countItems;
          if (offset <= res.response.totalCount) {
            this.apendList(offset, type);
          }
        }

      }
    }));
  }

  private filter(name: string): EntityModel[] {
    const filterValue = name.toLowerCase();
    return this.entityItems.filter(option => option.name.toLowerCase().indexOf(filterValue) === 0);
  }
}
