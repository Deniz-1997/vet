import { Component, ElementRef, ViewChild } from '@angular/core';
import { ReferenceItemModels } from '../../../../../models/reference/reference.item.models';
import { combineLatest, Observable } from 'rxjs';
import { select, Store } from '@ngrx/store';
import { ActivatedRoute, Router } from '@angular/router';
import { NotifyService } from '../../../../../services/notify.service';
import { BreadcrumbsService } from '../../../../../services/breadcrumbs.service';
import { CrudType } from 'src/app/common/crud-types';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { WildAnimalModel } from '../../../../../models/wild/wild-animal.models';
import { ReferencePetTypeModel } from '../../../../../models/reference/reference.pet.type.models';
import { ReferenceBreedModel } from '../../../../../models/reference/reference.breed.models';
import { PetsService } from '../../../../../services/pets.service';
import { EnumModel } from '../../../../../models/enum .models';
import { DataType } from '../../../../../common/data-type';
import { ModalConfirmComponent } from '../../../../shared/components/modal-confirm/modal-confirm.component';
import { MatDialog } from '@angular/material/dialog';
import { WildAnimalFilesModel } from '../../../../../models/wild/wild-animal-files.models';
import { map } from 'rxjs/operators';
import { BirthdayService } from 'src/app/services/birthday.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadCreateAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelPatchLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';


@Component({
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  type = CrudType.WildAnimal;
  public petTypesItems: Observable<ReferencePetTypeModel[]>;
  public breedItems: Observable<ReferenceBreedModel[]>;
  loading$: Observable<boolean>;
  WildAnimalAgeEnum: EnumModel;
  WildAnimalFileTypeEnum: EnumModel;
  arrayImages = [];
  currentPhoto = '';
  defaultPhoto = '';
  currentPath = '';
  defaultPath = '';
  wildAnimalFile$: Observable<WildAnimalFilesModel[]>;
  protected listNavigate = ['culling'];
  protected titleName = 'Безнадзорное животные';
  @ViewChild('photoInput')
  private photoInput: ElementRef;

  constructor(
    private petsService: PetsService,
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog,
    private birthdayService: BirthdayService
  ) {
    super(CrudType.WildAnimal, WildAnimalModel);
    this.petTypesItems = this.petsService.getPetTypes();
    this.loading$ = combineLatest(
      store.pipe(select(getCrudModelGetListLoading, { type: this.type })),
      store.pipe(select(getCrudModelPatchLoading, { type: this.type })),
    ).pipe(map(([getListLoading, patchLoading]) => getListLoading || patchLoading));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'WildAnimalAgeEnum',
            'WildAnimalFileTypeEnum',
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

  }

  onChangeType() {
    this.formGroup.controls.breed.setValue(null);
    const typeId = this.formGroup.value.type;
    if (typeId && typeId.id > 0) {
      this.breedItems = this.petsService.getBred10(typeId);
    } else {
      this.formGroup.controls.breed.setValue(null);
      this.breedItems = null;
    }
  }

  hasType() {
    return this.formGroup.controls.type.value && typeof this.formGroup.controls.type.value === 'object';
  }
  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }

  submit(): void {
    const model = { ...this.formGroup.value };

    if (!this.item.id) {
      model.wildAnimalFiles = { ...this.arrayImages };
    }

    if (model.isSterilized === 'true') {
      model.isSterilized = true;
    } else if (model.isSterilized === 'false') {
      model.isSterilized = false;
    }
    if (this.item.birthday) {
      model.birthday = this.item.birthday;
    }
    delete model.type;
    super.submit(null, model);
  }

  isEdit() {
    return ['create', null, undefined].indexOf(this.id) < 0;
  }

  photoClick(e: Event): void {
    this.photoInput.nativeElement.click(e);
  }

  photoUpload(e: Event): void {

    const fileList: FileList = e.target['files'];
    const file: File = fileList.item(0);
    const reader = new FileReader();
    reader.readAsDataURL(file);

    this.store.dispatch(new LoadCreateAction({
      type: CrudType.UploadedFile,
      params: { file: file },
      dataType: DataType.formData,

      onSuccess: (res) => {
        if (!this.item.id) {
          this.arrayImages.push({
            uploadedFile: {
              id: res.response.id
            },
            photoType: {
              code: this.WildAnimalFileTypeEnum[1].id,
              title: this.WildAnimalFileTypeEnum[1].name,
            },
            name: res.response.name
          });
          this.mouseLeave();
        } else {
          this.store.dispatch(new LoadCreateAction({
            type: CrudType.WildAnimalFile,
            params: <any>{
              uploadedFile: {
                id: res.response.id,
              },
              wildAnimal: {
                id: this.item.id,
              },
              photoType: {
                code: this.WildAnimalFileTypeEnum[1].id,
                title: this.WildAnimalFileTypeEnum[1].name,
              },
              name: res.response.name
            },
          }));
        }
      }
    }));
  }

  onDelete($event, item) {
    $event.stopPropagation();

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
      if (result && item.id) {
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.WildAnimalFile,
          params: { id: item.id },
        }));
      }

      if (result && !item.id) {
        this.arrayImages = this.arrayImages.filter(el => el.uploadedFile.id !== item.uploadedFile.id);
      }
      this.mouseLeave();
    });
  }

  mouseEnter(name, path) {
    this.currentPhoto = name;
    this.currentPath = path;
  }

  mouseLeave() {
    if (this.arrayImages.length > 0) {
      this.currentPhoto = this.arrayImages[0].name;
    } else {
      this.currentPhoto = this.defaultPhoto;
      this.currentPath = this.defaultPath;
    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      type: new FormControl((this.item.type && this.item.type.id) ? {
        id: this.item.type.id,
        name: this.item.type.name
      } : null, [Validators.required]),
      breed: new FormControl((this.item.breed && this.item.breed.id) ? {
        id: this.item.breed.id,
        name: this.item.breed.name
      } : null, [Validators.required]),
      gender: new FormControl(this.item.gender ? this.item.gender : 'MALE', [Validators.required]),
      isSterilized: new FormControl(this.item.isSterilized ? true : (this.item.isSterilized === false ? false : null)),
      chipNumber: new FormControl(this.item.chipNumber ? this.item.chipNumber : '', []),
      description: new FormControl(this.item.description ? this.item.description : ''),

      dateOfDeath: new FormControl(this.item.dateOfDeath ? this.item.dateOfDeath : null),
      causeOfDeath: new FormControl(this.item.causeOfDeath ? this.item.causeOfDeath : ''),
      animalNumber: new FormControl(this.item.animalNumber ? this.item.animalNumber : ''),
      numberOfYears: new FormControl(''),
      numberOfMonths: new FormControl(''),
      aggressive: new FormControl(this.item.aggressive ? this.item.aggressive : false),
    }, [this.ageValidator]);

    if (this.item.id) {
      this.wildAnimalFile$ = this.store.pipe(select(getCrudModelData, { type: CrudType.WildAnimalFile }));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.WildAnimalFile,
        params: <any>{
          filter: {
            wildAnimal: {
              id: this.item.id
            },
            photoType: 'PHOTO'
          }
        }
      }));
      this.wildAnimalFile$.subscribe(
        item => {
          if (item.length > 0) {
            this.defaultPhoto = item[0].uploadedFile.name;
            this.currentPhoto = item[0].uploadedFile.name;
            this.currentPath = item[0]['uploadedFile']['path'];
            this.defaultPath = item[0]['uploadedFile']['path'];
          } else {
            this.currentPhoto = '';
          }
        }
      );
    }

    if (this.id) {
      this.listNavigate.push(this.id);
      this.brdSrv.replaceLabelByIndex(this.item.type.name + ' ' + this.item.breed.name, 2);
    }

    if (this.item.birthday) {
      this.setYearsAndMonthsByBirthday();
    }
  }

  public setYearsAndMonthsByBirthday() {
    const birthday = this.item.birthday;
    const years = this.formGroup.get('numberOfYears');
    const months = this.formGroup.get('numberOfMonths');
    const dateEnd = this.formGroup.get('dateOfDeath');
    const yearsAndMonths = this.birthdayService.getYearsAndMonthsFromBirthday(birthday, dateEnd.value);
    if (years.value !== yearsAndMonths['years']) {
      years.setValue(yearsAndMonths['years']);
    }
    if (months.value !== yearsAndMonths['months']) {
      months.setValue(yearsAndMonths['months']);
    }
  }

  public setBirthdayByYearsAndMonth() {
    const limitYears = 99;
    const birthday = this.item.birthday;
    const years = this.formGroup.get('numberOfYears');
    const months = this.formGroup.get('numberOfMonths');
    const dateEnd = this.formGroup.get('dateOfDeath');
    if (years.value > limitYears) {
      years.setValue(limitYears);
      months.setValue(0);
      return false;
    }
    const yearsAndMonthSum = this.birthdayService.getYearsAndMonthsSum(years.value, months.value);
    if (yearsAndMonthSum > limitYears) {
      years.setValue(limitYears);
      months.setValue(0);
      return false;
    }
    if (years || months) {
      const date = this.birthdayService.getBirthdayFromYearsAndMonth(years.value, months.value);
      const formatDate = this.birthdayService.getValueByDate(date);
      if (birthday !== formatDate && yearsAndMonthSum <= limitYears) {
        this.item.birthday = formatDate;
      }
    }
  }

  ageValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['numberOfYears'] && group.controls['numberOfMonths']) {
      if (!group.controls['numberOfYears'].value && !group.controls['numberOfMonths'].value) {
        group.controls['numberOfYears'].setErrors({ required: true });
        group.controls['numberOfMonths'].setErrors({ required: true });
        return { 'age': true };
      } else {
        group.controls['numberOfYears'].setErrors(null);
        group.controls['numberOfMonths'].setErrors(null);
      }
    }
    return null;
  }
}
