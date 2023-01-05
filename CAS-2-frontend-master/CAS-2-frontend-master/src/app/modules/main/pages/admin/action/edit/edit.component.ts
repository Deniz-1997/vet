import {Component, Inject, OnDestroy, Optional} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable, Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {ReferenceItemModels} from 'src/app/models/reference/reference.item.models';
import {CrudType} from 'src/app/common/crud-types';
import {EnumModel} from 'src/app/models/enum.models';
import {ActionGroupModel} from 'src/app/models/action/action.group.models';
import {ActionModel} from 'src/app/models/action/action.models';
import {EntityModel} from 'src/app/models/entity.models';
import {ReferenceIconModel} from 'src/app/models/reference/icon.model';
import {NotifyService} from 'src/app/services/notify.service';
import {BreadcrumbsService} from 'src/app/services/breadcrumbs.service';
import {SnackBarService} from '../../../../../../services/snack-bar.service';
import {getCrudModelData, getCrudModelGetListLoading} from '../../../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../../api/api-connector/crud/crud.actions';
import {CrudDataType} from '../../../../../../api/api-connector/crud/crud.config';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnDestroy {
  type = CrudType.Action;
  ActionTypeEnum: Array<EnumModel>;
  crudType = CrudType;
  actionGroupItems$: Observable<Array<ActionGroupModel>>;
  actionItems$: Observable<Array<ActionModel>>;
  entityItems: Array<EntityModel>;
  filteredEntity: Array<EntityModel>;
  roles$: Observable<Array<CrudDataType>>;
  icons$: Observable<Array<ReferenceIconModel>>;
  iconFields = {0: 'id', 1: 'code', 2: 'name'};
  loading$: Observable<boolean>;
  searchDefActions;
  searchActions;
  searchDeliveryState;
  searchState;
  protected listNavigate = ['admin', 'action'];
  protected titleName = 'Действие';
  private destroy$ = new Subject<any>();

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected snackBar: SnackBarService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean, id: string}
  ) {
    super(CrudType.Action, ActionModel, data?.id, data?.openDialog);

    this.roles$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Role}));
    this.store.dispatch(new LoadGetListAction({type: CrudType.Role, params: {fields: {0: 'id', 1: 'name', 2: 'code'}}}));
    this.roles$.subscribe(states => {
      this.searchDeliveryState = states;
      this.searchState = states;
    });

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

    this.actionGroupItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ActionGroup}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ActionGroup,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
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
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceIcon, params: {fields: {0: 'id', 1: 'name', 2: 'code'}}}));

    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
  }

  displayFn(user?: EntityModel): string | undefined {
    return user ? user.name : undefined;
  }

  isTypeEntityListUrl(): boolean {
    return this.formGroup && this.formGroup.get('type').value.code === 'ENTITY_LIST_URL';
  }

  isTypeEntity(): boolean {
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
    if (model.entityClass == null) {
      model.entityClass = {name: null, className: null};
    }
    super.submit(null, model);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  protected setModel(): void {

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      code: new FormControl(this.item.code, [Validators.required]),
      description: new FormControl(this.item.description),

      additionalActions: new FormControl(this.item.additionalActions ? this.item.additionalActions : []),
      actionsSearch: new FormControl(null),

      parent: new FormControl(this.item.parent ? this.item.parent : null),
      groups: new FormControl(this.item.groups ? this.item.groups : []),
      sort: new FormControl(this.item.sort),

      type: new FormGroup({
        code: new FormControl(this.item.type && this.item.type.code || null, [Validators.required]),
      }),
      url: new FormControl(this.item.url),
      entityClass: new FormControl(this.item.entityClass),

      roles: new FormControl(this.item.roles ? this.item.roles : []),
      rolesSearch: new FormControl(null),

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

    this.formGroup.controls.rolesSearch.valueChanges
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((value) => {
        const filterValue = value ? value.toLowerCase() : '';
        this.searchState = this.searchDeliveryState.filter(option => option.name.toLowerCase().includes(filterValue));
      });

    this.formGroup.controls.actionsSearch.valueChanges
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((value) => {
        const filterValue = value ? value.toLowerCase() : '';
        this.searchActions = this.searchDefActions.filter(option => option.name.toLowerCase().includes(filterValue));
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

    this.actionItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Action}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Action,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        limit: 500,
      }
    }));

    this.actionItems$.subscribe(action => {
      this.searchDefActions = action;
      this.searchActions = action;
    }
    );
  }

  private filter(name: string): Array<EntityModel> {
    const filterValue = name.toLowerCase();
    return this.entityItems.filter(option => option.name.toLowerCase().indexOf(filterValue) === 0);
  }
}
