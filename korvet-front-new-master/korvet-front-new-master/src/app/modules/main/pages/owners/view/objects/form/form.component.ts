import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {MonitoredObjectModel} from '../../../../../../../models/owner/monitored-object.models';
import {Observable, Subject} from 'rxjs';
import {ReferenceOwnerActivityModel} from '../../../../../../../models/reference/reference.owner.activity.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ViewService} from '../../view.service';
import {filter, takeUntil} from 'rxjs/operators';
import {PersonModel} from '../../../../../../../models/owner/owner.models';
import {PersonType} from '../../../../../../../utils/person-type';
import {MatDialog} from '@angular/material/dialog';
import {MonitoredObjectService} from '../../../../../../../services/monitored-object.service';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {ModalFileAddFormComponent} from '../../../../../../shared/components/modal-file-add-form/modal-file-add-form.component';
import {FileMonitoredObjectModel} from '../../../../../../../models/file/file.monitored.object.models';
import {CrudType} from 'src/app/common/crud-types';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading, getCrudModelStoreId, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './form.component.html'})

export class FormComponent implements OnInit, OnDestroy {

  formGroup: FormGroup;
  type = CrudType.MonitoredObject;
  typeFile = CrudType.FileMonitoredObject;
  monitoredObject = new MonitoredObjectModel();
  activities$: Observable<ReferenceOwnerActivityModel[]>;
  choicesActivities: { value: any, name: string, chosen: boolean }[] = [];
  showError = false;
  getLoading$: Observable<boolean>;
  loadingRemove$: Observable<boolean>;
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  files$: Observable<FileMonitoredObjectModel[]>;
  id: number;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
    private router: Router,
    private route: ActivatedRoute,
    private service: ViewService,
    private monitoredObjectService: MonitoredObjectService,
    private dialog: MatDialog
  ) {

    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
    this.route.params
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(params => {
        this.id = params['objectId'];
      });

    this.route.parent.paramMap.pipe(filter(params => !!params.get('objectId'))).subscribe(params => {
      store.dispatch(new LoadGetAction({type: CrudType.MonitoredObject, params: params.get('objectId')}));
      store.pipe(
        select(getCrudModelStoreId, {type: CrudType.MonitoredObject, params: params.get('objectId')}),
        filter(object => !!object)
      ).subscribe(object => {
        this.monitoredObject = object;
        if (this.formGroup) {
          this.formGroup.reset(this.monitoredObject);
        }
      });
    });
    this.activities$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceOwnerActivity}));
    this.fileTypes$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {'sort': 'ASC', 'name': 'ASC'}, limit: 10}
    }));

    this.files$ = store.pipe(select(getCrudModelData, {type: this.typeFile}));
    this.store.dispatch(
      new LoadGetListAction({
        type: this.typeFile,
        params: {filter: {monitoredObject: {id: this.id}}}
      }));
  }

  get owner() {
    return this.service.owner;
  }

  addFile(): void {
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      data: {
        subject: this.monitoredObject,
        fileTypes$: this.fileTypes$,
      }
    });
  }

  ngOnInit() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.monitoredObject.name, [Validators.required]),
      phone: new FormControl(this.monitoredObject.phone),
      email: new FormControl(this.monitoredObject.email, [Validators.email]),
      address: new FormGroup({
        full: new FormControl(this.monitoredObject.address && this.monitoredObject.address.full),
        coordinates: new FormControl(this.monitoredObject.address && this.monitoredObject.address.coordinates),
      }),
      responsibleIsOwnerHead: new FormControl(this.monitoredObject.responsibleIsOwnerHead),
      responsible: new FormGroup({
        fullName: new FormGroup({
          lastName: new FormControl(this.monitoredObject.responsible &&
            this.monitoredObject.responsible.fullName &&
            this.monitoredObject.responsible.fullName.lastName),
          name: new FormControl(this.monitoredObject.responsible &&
            this.monitoredObject.responsible.fullName &&
            this.monitoredObject.responsible.fullName.name),
          middleName: new FormControl(this.monitoredObject.responsible &&
            this.monitoredObject.responsible.fullName &&
            this.monitoredObject.responsible.fullName.middleName),
        }),
        phone: new FormControl(this.monitoredObject.responsible && this.monitoredObject.responsible.phone),
        email: new FormControl(this.monitoredObject.responsible && this.monitoredObject.responsible.email)
      }),
      customActivities: new FormControl(this.monitoredObject.customActivities || ''),
    });
    this.activities$.subscribe(activities => this.choicesActivities = activities.map(item => {
      return {
        value: {id: item.id},
        name: item.name,
        chosen: this.monitoredObject.activities && this.monitoredObject.activities.some(activity => activity.id === item.id)
      };
    }));

    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceOwnerActivity}));
    this.formGroup.get('responsibleIsOwnerHead').valueChanges.subscribe(value => {
      if (value && this.owner) {
        let head: PersonModel = null;
        switch (this.owner.type) {
          case PersonType.FARM:
            head = this.owner.farm.head.person;
            break;
          case PersonType.LEGAL_ENTITY:
            head = this.owner.legalEntity.head;
            break;
        }
        if (head) {
          this.formGroup.get('responsible').reset(head);
        }
      }
    });
  }

  submit(): void {
    this.showError = true;
    if (this.formGroup.valid) {
      const id = this.service.id;
      const action = this.monitoredObject.id ? LoadPatchAction : LoadCreateAction;
      this.store.dispatch(new action({
        type: this.type,
        params: {
          ...this.monitoredObject,
          ...this.formGroup.value,
          owner: {id: +id},
          activities: this.choicesActivities.filter(choice => choice.chosen).map(choice => choice.value)
        },
        onSuccess: () => this.router.navigate(['/owners', id, 'objects'])
      }));
    }
  }

  onDelete() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить подконтрольный объект?',
        headComment: 'Действие необратимо <br> (' + this.monitoredObject.name + ')',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.monitoredObjectService.remove(this.monitoredObject.id).subscribe((res: ApiResponse) => {
          if (res && res.status === true) {
            this.router.navigate(['/owners/' + this.service.id + '/objects']);
          }
        });
      }
    });
  }

  goBack(): void {
    const id = this.service.id;
    this.router.navigate(['/owners/' + id + '/objects']).then();
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
