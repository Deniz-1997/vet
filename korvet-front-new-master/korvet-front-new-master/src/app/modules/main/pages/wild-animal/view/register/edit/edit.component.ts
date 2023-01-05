import {debounceTime, distinctUntilChanged, map, take, takeUntil} from 'rxjs/operators';
import {Component, ElementRef, Input, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {combineLatest, Observable, Subject} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceContractorModel} from '../../../../../../../models/reference/contractor.model';
import {DataType} from '../../../../../../../common/data-type';
import {ReferencePetTypeModel} from '../../../../../../../models/reference/reference.pet.type.models';
import {CullingRegistrationModel} from '../../../../../../../models/wild/culling.registration.models';
import {EnumModel} from '../../../../../../../models/enum .models';
import {WildAnimalModel} from '../../../../../../../models/wild/wild-animal.models';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {previousDate} from '../../../../../../shared/validators/previous_date';
import {CullingRegistrationFileModel} from '../../../../../../../models/wild/culling-registration-file.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadPatchAction, LoadCreateAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelPatchLoading, getCrudModelStoreId, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-wild-animal-register-edit',
  templateUrl: './edit.component.html',
})
export class EditComponent implements OnInit, OnDestroy {
  @Input() wildAnimalId: number;
  @Input() wildAnimal: WildAnimalModel;

  crudType = CrudType;
  type = CrudType.CullingRegistration;

  public ReferenceContractorItems$: Observable<ReferenceContractorModel[]>;
  public WildAnimalReleaseTypeEnum$: Observable<string[]>;
  contactPersons = [];
  contractorFields = {0: 'id', 1: 'name', 'contactPersons': ['id', 'person']};

  users$: Observable<{ id: number, fullName: string }[]>;
  ReferenceSterilizationType$: Observable<{ id: number, name: string }[]>;
  ReferenceVaccinationType$: Observable<{ id: number, name: string }[]>;
  ReferenceTagForm$: Observable<{ id: number, name: string }[]>;
  ReferenceTagColor$: Observable<{ id: number, name: string }[]>;
  ReferenceShelter$: Observable<{ id: number, name: string }[]>;

  loading$: Observable<boolean>;
  getLoading$: Observable<boolean>;
  wildAnimal$: Observable<WildAnimalModel>;

  public formGroup: FormGroup;
  model;
  registerId;
  WildAnimalReleaseTypeEnum: EnumModel;
  CullingRegistrationFileTypeEnum: EnumModel;
  cullingRegistrationFile$: Observable<CullingRegistrationFileModel[]>;
  arrayImages = [];
  currentPhotoType = {
    id: 'PHOTO',
    name: 'Фото отлова'
  };
  showError = false;
  @ViewChild('photoInput')
  private photoInput: ElementRef;
  private destroy$ = new Subject<any>();

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog
  ) {

    this.WildAnimalReleaseTypeEnum$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Enum}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'WildAnimalReleaseTypeEnum',
            'CullingRegistrationFileTypeEnum',
          ]
        }
      },
      onSuccess: r => {
        r.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));

    this.model = new CullingRegistrationModel();

    this.getLoading$ = combineLatest(
      store.pipe(select(getCrudModelGetListLoading, {type: this.type})),
      store.pipe(select(getCrudModelPatchLoading, {type: this.type})),
    ).pipe(map(([getListLoading, patchLoading]) => getListLoading || patchLoading));

    this.registerId = this.route.snapshot.paramMap.get('registerId');

    if (this.route.snapshot.paramMap.get('id') !== null) {
      this.wildAnimalId = parseInt(this.route.snapshot.paramMap.get('id'));

      this.wildAnimal$ = this.store.pipe(select(getCrudModelStoreId, {
        type: CrudType.WildAnimal,
        params: this.wildAnimalId
      }));
      this.store.dispatch(new LoadGetAction({
        type: CrudType.WildAnimal,
        params: this.wildAnimalId,
        onSuccess: (res) => {
          this.wildAnimal = res.response;
          if (!this.registerId) {
            this.setModel();
          }
        }
      }));
    }

    if (this.registerId) {
      this.store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.registerId}));
      this.store.dispatch(new LoadGetAction({
        type: this.type,
        params: this.registerId,
        onSuccess: (res) => {
          /*fix не подгружается модель при обновлении страницы*/
          if (!this.model.id) {
            this.model = new ReferencePetTypeModel(res.response);
            } else {
              this.model = res.response;
            }
            this.setModel();
          }
        }
      ));
    }

    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));

    this.ReferenceContractorItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceContractor}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceContractor,
      params: {
        fields: this.contractorFields,
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.ReferenceShelter$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceShelter}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceShelter,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.ReferenceTagForm$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceTagForm}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceTagForm,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.ReferenceTagColor$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceTagColor}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceTagColor,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.users$ = this.store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: <any>{
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  getFullName(item): string {
    return (((item.surname).trim() + ' ' + (item.name + ' ' + item.patronymic).trim()).trim());
  }

  ngOnInit(): void {

  }

  hasType() {
    return this.formGroup.controls.contractor.value && typeof this.formGroup.controls.contractor.value === 'object';
  }

  submit(): void {
    this.formGroup.markAsTouched();
    this.showError = true;

    if (this.formGroup.valid) {
      const action = this.registerId ? LoadPatchAction : LoadCreateAction;
      const model = {...this.formGroup.value};

      if (!this.registerId) {
        model.cullingRegistrationFiles = [];
        this.arrayImages.map(
          array => model.cullingRegistrationFiles.push({
            uploadedFile: {
              id: array.uploadedFile.id,
            },
            name: array.name,
            photoType: {
              code: array.photoType.code
            }
          })
        );
      }

      if (this.registerId) {
        model.id = +this.registerId;
      }

      model.wildAnimal.id = +this.wildAnimalId;

      if (model.sterilizationType || model.sterilizationDate || model.sterilizationUser) {
        model.wildAnimal.isSterilized = true;
      }
      this.store.dispatch(new action({
        type: this.type,
        params: <any>model, onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.router.navigate(['/culling', this.wildAnimalId, 'profile']).then();
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  photoClick(e: Event, photoType): void {

    if (photoType) {
      this.currentPhotoType = {...photoType};
    }

    this.photoInput.nativeElement.click(e);
  }

  photoUpload(e: Event): void {

    const fileList: FileList = e.target['files'];
    const file: File = fileList.item(0);
    const reader = new FileReader();
    reader.readAsDataURL(file);

    this.store.dispatch(new LoadCreateAction({
      type: CrudType.UploadedFile,
      params: {file: file},
      dataType: DataType.formData,

      onSuccess: (res) => {

        if (!this.model.id) {
          this.arrayImages.push({
            uploadedFile: {
              id: res.response.id,
              name: res.response.name,
              size: res.response.size,
              mimeType: res.response.mimeType,
            },
            photoType: {
              code: this.currentPhotoType.id,
              title: this.currentPhotoType.name
            },
            name: res.response.name
          });
        } else {
          this.store.dispatch(new LoadCreateAction({
            type: CrudType.CullingRegistrationFile,
            params: <any>{
              uploadedFile: {
                id: res.response.id,
              },
              cullingRegistration: {
                id: this.model.id,
              },
              photoType: {
                code: this.currentPhotoType.id,
                title: this.currentPhotoType.name
              },
              name: res.response.name
            },
          }));
        }
      }
    }));
  }

  arrayImagesType(code) {
    return this.arrayImages.filter(img => img.photoType.code === code?.id);
  }

  onDelete(image) {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить фото?',
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
        if (result && image.id) {
          this.store.dispatch(new LoadDeleteAction({
            type: CrudType.CullingRegistrationFile,
            params: {id: image.id},
          }));
        }

        if (result && !image.id || image.id) {
          this.arrayImages = this.arrayImages.filter(el => el.uploadedFile.id !== image.uploadedFile.id);
        }
      }
    });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  protected setModel() {
    let validators = [Validators.required];
    let validatorsForTagDate = [Validators.required, previousDate];

    if (this.wildAnimal !== undefined) {
      if (this.wildAnimal.chipNumber !== '' && this.wildAnimal.chipNumber !== null) {
        validators = [];
        validatorsForTagDate = [];
      }
    }

    this.formGroup = new FormGroup({
      date: new FormControl(this.model.date ? this.model.date : '', [Validators.required, previousDate]),

      address: new FormGroup({
        full: new FormControl(this.model.address ? this.model.address.full : null, [Validators.required]),
        coordinates: new FormControl(this.model.address ? this.model.address.coordinates : null)
      }),

      appealDate: new FormControl(this.model.appealDate ? this.model.appealDate : null, [previousDate]),
      appealNumber: new FormControl(this.model.appealNumber ? this.model.appealNumber : '', ),
      contractor: new FormControl(this.model.contractor ?
        {id: this.model.contractor.id, name: this.model.contractor.name} : null, [Validators.required]),

      contactPerson: new FormControl(null, [Validators.required]),

      wildAnimal: new FormControl({}),

      sterilizationDate: new FormControl(this.model.sterilizationDate ? this.model.sterilizationDate : null, [previousDate]),
      sterilizationUser: new FormControl(this.model.sterilizationUser ?
        {id: this.model.sterilizationUser.id, fullName: this.getFullName(this.model.sterilizationUser)} : null, []),
      sterilizationType: new FormControl(this.model.sterilizationType ?
        {id: this.model.sterilizationType.id, name: this.model.sterilizationType.name} : null, []),

      vaccinationDate: new FormControl(this.model.vaccinationDate ? this.model.vaccinationDate : null, [previousDate]),
      vaccinationUser: new FormControl(this.model.vaccinationUser ?
        {id: this.model.vaccinationUser.id, fullName: this.getFullName(this.model.vaccinationUser)} : null),

      vaccinationType: new FormControl(this.model.vaccinationType ?
        {id: this.model.vaccinationType.id, name: this.model.vaccinationType.name} : null, []),
      // vaccinationType: new FormControl(this.model.vaccinationType ? this.model.vaccinationType : ''),

      tagDate: new FormControl(this.model.tagDate ? this.model.tagDate : null, [previousDate]),
      tagNumber: new FormControl(this.model.tagNumber ? this.model.tagNumber : ''),

      tagForm: new FormControl(this.model.tagForm ?
        {id: this.model.tagForm.id, name: this.model.tagForm.name} : null),

      tagColor: new FormControl(this.model.tagColor ?
        {id: this.model.tagColor.id, name: this.model.tagColor.name} : null),


      tagText: new FormControl(this.model.tagText ? this.model.tagText : ''),

      releaseDate: new FormControl(this.model.releaseDate ? this.model.releaseDate : null, [previousDate]),
      quarantinePeriodStartTime: new FormControl(this.model.quarantinePeriodStartTime ? this.model.quarantinePeriodStartTime : null, [previousDate]),
      quarantinePeriodEndTime: new FormControl(this.model.quarantinePeriodEndTime ? this.model.quarantinePeriodEndTime : null, [previousDate]),

      releaseAddress: new FormGroup({
        full: new FormControl(this.model.releaseAddress ? this.model.releaseAddress.full : null),
        coordinates: new FormControl(this.model.releaseAddress ? this.model.releaseAddress.coordinates : null)
      }),

      releaseShelter: new FormControl(this.model.releaseShelter ?
        {id: this.model.releaseShelter.id, name: this.model.releaseShelter.name} : null),
      releaseNewOwners: new FormControl(this.model.releaseNewOwners ? this.model.releaseNewOwners : ''),
      releaseInn: new FormControl(this.model.releaseInn ? this.model.releaseInn : ''),
      releaseType: new FormGroup({
        code: new FormControl(this.model.releaseType ? this.model.releaseType.code : null, [])
      }),

    });

    if (this.model.id) {
      this.brdSrv.deleteIndex(3);

      this.cullingRegistrationFile$ = this.store.pipe(select(getCrudModelData, {type: CrudType.CullingRegistrationFile}));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.CullingRegistrationFile,
        params: <any>{
          filter: {
            cullingRegistration: {
              id: this.model.id
            }
          }
        }
      }));

      this.cullingRegistrationFile$
        .pipe(
          takeUntil(this.destroy$)
        )
        .subscribe(item => {
          if (item.length) {
            this.arrayImages = item;
          }
        });
    }

    if (this.formGroup.get('contractor').value) {
      this.ReferenceContractorItems$
        .pipe(
          take(1)
        )
        .subscribe(
          item => {
            item.map(el => {
              if (el.id === this.formGroup.get('contractor').value.id) {
                this.contactPersons = el.contactPersons;
                el.contactPersons.map(i => {
                  if (i.id === this.model.contactPerson.id) {
                    this.formGroup.get('contactPerson').setValue(i);
                  }
                });

              }
            });
          }
        );
    }

    this.formGroup.get('address.full').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(item => {
          if ((!this.formGroup.get('releaseAddress.full').dirty
            || (this.formGroup.get('releaseAddress.full') && !this.formGroup.get('releaseAddress.full').value))) {
            this.formGroup.get('releaseAddress.full').setValue(item);
          }
        }
      );

    this.formGroup.get('address.coordinates').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(item => {
          if ((!this.formGroup.get('releaseAddress.coordinates').dirty
            || (this.formGroup.get('releaseAddress.coordinates') && !this.formGroup.get('releaseAddress.coordinates').value))) {
            this.formGroup.get('releaseAddress.coordinates').setValue(item);
          }
        }
      );

    this.formGroup.get('contractor').valueChanges.subscribe(
      result => {
        if (result instanceof Object) {
          if (result.contactPersons) {
            this.contactPersons = result.contactPersons;
          }
        }
      }
    );
  }
}

