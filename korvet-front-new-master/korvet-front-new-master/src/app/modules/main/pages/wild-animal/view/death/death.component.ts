import {Component, ElementRef, ViewChild} from '@angular/core';
import {Observable} from 'rxjs';
import {WildAnimalModel} from '../../../../../../models/wild/wild-animal.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {DataType} from '../../../../../../common/data-type';
import {EnumModel} from '../../../../../../models/enum .models';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {WildAnimalFilesModel} from '../../../../../../models/wild/wild-animal-files.models';
import {previousDate} from '../../../../../shared/validators/previous_date';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction, LoadCreateAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-wild-animal-death',
  templateUrl: './death.component.html'
})
export class DeathComponent extends ReferenceItemModels {
  crudType = CrudType;
  type = CrudType.WildAnimal;
  loading$: Observable<boolean>;
  WildAnimalFileTypeEnum: EnumModel;
  wildAnimalFile$: Observable<WildAnimalFilesModel[]>;
  protected listNavigate = ['culling'];
  protected titleName = 'Безнадзорное животные';
  @ViewChild('photoInput')
  private photoInput: ElementRef;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog
  ) {
    super(CrudType.WildAnimal, WildAnimalModel);
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
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

  submit(): void {

    if (this.formGroup.valid) {
      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: 'Вы точно хотите зарегистрировать или изменить регистрацию смерти животного?',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--gray',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right btn-st--red',
              action: true,
              title: 'Да'
            },
          ],
        }
      });
      dialogRef.afterClosed().subscribe((result: boolean) => {
        if (result) {
          const model = {...this.formGroup.value};
          this.store.dispatch(new LoadPatchAction({
            type: CrudType.WildAnimal,
            params: model,
            onSuccess: () => {
              this.router.navigate(['/culling', this.formGroup.controls.id.value, 'profile']).then();
            }
          }));
        }
      });
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  onCancel() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите отменить регистрацию смерти животного?',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Да'
          },
        ],
      }
    });
    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        const model = {...this.formGroup.value};
        model.dateOfDeath = null;
        this.store.dispatch(new LoadPatchAction({
          type: CrudType.WildAnimal,
          params: model,
          onSuccess: () => {
            this.router.navigate(['/culling', this.formGroup.controls.id.value, 'profile']).then();
          }
        }));
      }
    });
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
      params: {file: file},
      dataType: DataType.formData,

      onSuccess: (res) => {
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
              code: this.WildAnimalFileTypeEnum[2].id,
              title: this.WildAnimalFileTypeEnum[2].name,
            },
            name: res.response.name
          },
        }));
      }
    }));
  }

  onDelete(item) {
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
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.WildAnimalFile,
          params: {id: item.id},
        }));
      }
    });
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      id: new FormControl(this.item.id ? this.item.id : null, []),
      dateOfDeath: new FormControl(this.item.dateOfDeath ? this.item.dateOfDeath : null, [Validators.required, previousDate]),
      causeOfDeath: new FormControl(this.item.causeOfDeath ? this.item.causeOfDeath : '', [Validators.required]),
    });
    this.brdSrv.replaceLabelByIndex(this.item.type.name + ' ' + this.item.breed.name, 2);

    if (this.item.id) {
      this.wildAnimalFile$ = this.store.pipe(select(getCrudModelData, {type: CrudType.WildAnimalFile}));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.WildAnimalFile,
        params: <any>{
          filter: {
            wildAnimal: {
              id: this.item.id
            },
            photoType: 'DEATH_PHOTO'
          }
        }
      }));
    }
  }
}

