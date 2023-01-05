import {Component, ElementRef, Inject, Optional, ViewChild} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {NotifyService} from '../../../../../../services/notify.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {UserModels} from '../../../../../../models/user/user.models';
import {Observable} from 'rxjs';
import {AuthService} from '../../../../../../services/auth.service';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {CrudType} from 'src/app/common/crud-types';
import {SnackBarService} from '../../../../../../services/snack-bar.service';
import {getCrudModelData} from '../../../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../../api/api-connector/crud/crud.actions';
import {CrudDataType} from '../../../../../../api/api-connector/crud/crud.config';

@Component({templateUrl: './edit.component.html',
              styleUrls: ['./edit.component.scss']})

export class EditComponent extends ReferenceItemModels {
  title = 'Редактировать пользователя';
  @ViewChild('file', {static: true}) fileElement: ElementRef;
  groups$: Observable<Array<CrudDataType>>;
  crudType = CrudType;
  unitItems: Observable<Array<ReferenceUnitModel>>;
  protected listNavigate = ['admin', 'user'];
  protected titleName = 'Пользователя';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected snackBar: SnackBarService,
    protected brdSrv: BreadcrumbsService,
    public authService: AuthService,
    private dialog: MatDialog,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string, fields: object}
  ) {
    super(CrudType.User, UserModels, data?.id, data?.openDialog, data.fields);

    this.groups$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Group}));
    this.store.dispatch(
      new LoadGetListAction({
        type: CrudType.Group,
        params: {
          fields: {0: 'id', 1: 'name'},
          order: {name: 'ASC'}
        }
      })
    );
  }

  getMatches(): void {
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
      params: {
        filter: params,
      } as any,
      onSuccess: (matches) => {
        if (matches.response.totalCount > 0) {
          this.onAdd();
        } else {
          this.submit();
        }
      }
    }));

  }

  onAdd(): void {
  }
  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }

  onSubmit($event: any): void {
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
      this.snackBar.handleMessage('Поля пароль и повторите пароль не совпадают', 'danger-snackBar', 2000);
      this.showError = true;
    } else {
      this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 3000);
      this.showError = true;
    }
  }
  isEdit(): boolean {
    return ['create', null, undefined].indexOf(this.id) < 0;
  }

  protected setModel(): void {
    this.formGroup = new FormGroup({
      username: new FormControl(this.item.username, [Validators.required]),
      surname: new FormControl(this.item.surname, [Validators.required]),
      name: new FormControl(this.item.name, [Validators.required]),
      patronymic: new FormControl(this.item.patronymic, [Validators.required]),
      phoneNumber: new FormControl(this.item.phoneNumber),
      email: new FormControl(this.item.email, [Validators.required, Validators.email]),
      additionalFields: new FormGroup({
        inn: new FormControl(this.item.additionalFields && this.item.additionalFields.inn ? this.item.additionalFields.inn : '', [])
      }),
      plainPassword: new FormControl('', ),
      repeat_password: new FormControl('', ),
      status: new FormControl(this.item.status, [Validators.required]),
      groups: new FormControl(this.item.groups ? this.item.groups : [], [Validators.required]),
      stations: new FormControl(this.item.stations ? this.item.stations : []),
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

  passwordValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['plainPassword'].value !== '' && group.controls['repeat_password'].value !== '' &&
      group.controls['plainPassword'].value !== group.controls['repeat_password'].value) {
      group.controls['plainPassword'].setErrors({ required: true });
      group.controls['repeat_password'].setErrors({ required: true });
      return { password: true };
    } else {
      group.controls['plainPassword'].setErrors(null);
      group.controls['repeat_password'].setErrors(null);
    }
    return null;
  }
}
