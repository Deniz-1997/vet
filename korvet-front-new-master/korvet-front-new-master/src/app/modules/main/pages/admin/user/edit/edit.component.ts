import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {NotifyService} from '../../../../../../services/notify.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {UserModels} from '../../../../../../models/user/user.models';
import {Observable} from 'rxjs';
import {AuthService} from '../../../../../../services/auth.service';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction, LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})

export class EditComponent extends ReferenceItemModels  {
  title = 'Редактировать пользователя';
  @ViewChild('file', {static: true}) fileElement: ElementRef;
  crudType = CrudType;
  unitItems: Observable<ReferenceUnitModel[]>;
  protected listNavigate = ['admin', 'user'];
  protected titleName = 'Пользователя';
  countGroup: number;
  countProfession: number;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    public authService: AuthService,
    private dialog: MatDialog
  ) {
    super(CrudType.User, UserModels);

    this.unitItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceUnit}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }


  getMatches() {
    const params = {};
    if (this.formGroup.value.surname) {
      params['=surname'] = this.formGroup.value['surname'].trim();
    }
    if (this.formGroup.value.name) {
      params['=name'] = this.formGroup.value['name'].trim();
    }
    if (this.formGroup.value.patronymic) {
      params['=patronymic'] = this.formGroup.value['patronymic'].trim();
    }
    if (this.id) {
      params['!id'] = this.id;
    }

    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: <any>{
        filter: params,
      },
      onSuccess: (matches) => {
        if (matches.response.totalCount > 0) {
          this.onAdd();
        } else {
          this.submit();
        }
      }
    }));

  }

  onAdd() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите добавить/изменить пользователя?',
        headComment: 'Подобный пользователь уже есть <br> (' +
          this.formGroup.value.surname + ' ' + this.formGroup.value.name + ' ' + this.formGroup.value.patronymic + ')',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right',
            action: true,
            title: 'Сохранить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.submit();
      }
    });
  }
  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }

  onSubmit($event) {
    if (this.formGroup.valid &&
      this.formGroup.controls.plainPassword.value &&
      this.formGroup.controls.repeat_password.value === this.formGroup.controls.plainPassword.value) {
      this.formGroup.controls['repeat_password'].disable();
      this.getMatches();
    } else if (this.id && this.authService.userGroupAdmin$ &&
      !this.formGroup.controls.repeat_password.value && !this.formGroup.controls.plainPassword.value) {
      this.formGroup.controls['plainPassword'].disable();
      this.formGroup.controls['repeat_password'].disable();
      this.getMatches();
    } else if (this.formGroup.valid) {
      this.notify.handleMessage('Поля пароль и повторите пароль не совпадают', 'danger');
      this.showError = true;
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }
  isEdit() {
    return ['create', null, undefined].indexOf(this.id) < 0;
  }

  onDelete() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить пользователя?',
        headComment: 'Действие необратимо <br> (' + this.id + ')',
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
          type: this.type,
          params: {id: +this.id},
          onSuccess: () => {
            this.router.navigate(['/admin/user']).then();
          }
        }));
      }
    });
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      username: new FormControl(this.item.username, [Validators.required]),
      surname: new FormControl(this.item.surname, [Validators.required]),
      name: new FormControl(this.item.name, [Validators.required]),
      patronymic: new FormControl(this.item.patronymic, [Validators.required]),
      phoneNumber: new FormControl(this.item.phoneNumber, [Validators.required]),
      email: new FormControl(this.item.email, [Validators.required, Validators.email]),
      additionalFields: new FormGroup({
        inn: new FormControl(this.item.additionalFields && this.item.additionalFields.inn ? this.item.additionalFields.inn : '', [])
      }),
      plainPassword: new FormControl('', ),
      repeat_password: new FormControl('', ),
      status: new FormControl(this.item.status, [Validators.required]),
      groups: new FormControl(this.item.groups ? this.item.groups : [], [Validators.required]),
      unit: new FormControl((this.item.unit && this.item.unit.id)
        ? {id: this.item.unit.id, name: this.item.unit.name} : null, [Validators.required]),
      professions: new FormControl(this.item.professions ? this.item.professions : [], [Validators.required])
    }, [this.passwordValidator]);
    if (this.formGroup.controls.status.value === null) {
      this.formGroup.controls.status.setValue(true);
    }

    if (this.id) {
      this.brdSrv.replaceLabelByIndex(this.item.surname + ' ' + this.item.name + ' ' + this.item.patronymic, 3);
    }

    if (this.id && this.formGroup.controls['username'].value) {
      this.formGroup.controls['username'].disable();
    }

    if (this.id && !this.authService.userGroupAdmin$) {
      this.formGroup.controls['plainPassword'].disable();
      this.formGroup.controls['repeat_password'].disable();
    }
  }

  apendList(offset, type) {
    this.store.dispatch(new LoadAppendListAction({
      type: type,
      params: {
        order: {surname: 'ASC'},
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

  passwordValidator(group: FormGroup): { [s: string]: boolean } {
     if (group.controls['plainPassword'].value !== '' && group.controls['repeat_password'].value !== '' &&
      group.controls['plainPassword'].value !== group.controls['repeat_password'].value) {
      group.controls['plainPassword'].setErrors({ required: true });
      group.controls['repeat_password'].setErrors({ required: true });
      return { 'password': true };
    } else {
      group.controls['plainPassword'].setErrors(null);
      group.controls['repeat_password'].setErrors(null);
    }
    return null;
  }

}
