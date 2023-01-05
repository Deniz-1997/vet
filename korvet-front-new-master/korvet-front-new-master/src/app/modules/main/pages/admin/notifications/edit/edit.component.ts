import {Component, Inject, Input, Optional} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../common/crud-types';
import {ReferenceNotificationsTypeModel} from '../../../../../../models/reference/reference.notifications.type.models';
import {Observable} from 'rxjs';
import {UserModels} from '../../../../../../models/user/user.models';
import {MainNotificationComponent} from '../../../../components/main-notification/main-notification.component';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})
export class EditComponent extends ReferenceItemModels {
  // data: '';
  crudType = CrudType;
  @Input() referenceNotificationsType: Observable<ReferenceNotificationsTypeModel[]>;
  @Input() user: Observable<UserModels[]>;
  users: Observable<{ id: number, fullName: string }[]>;

  protected listNavigate = ['admin', 'notifications'];
  protected titleName = 'Оповещения';
  private toSend;
  private template: string;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.Notifications, ReferenceNotificationsTypeModel, data.id, data.openDialog);
  }

  ngOnInit() {
    super.ngOnInit();
    this.store.dispatch(new LoadGetListAction(
      {
        type: CrudType.User,
        params: {
          fields: {0: 'id'},
          offset: 0,
          limit: 500
        },
        onSuccess: r => {
          if (r.status && r.response.items.length > 0) {
            this.toSend = r.response.items.map(v => {
              return {type: 'USER', value: v.id, channel: MainNotificationComponent.getChannelKorvet()};
            });
          } else {
            // TODO Вывести сообщение об ошибке
          }
        },
        onError: e => {
          console.log(e);
        }
      }));
  }

  onChangeType($event, formField, index): void {
    if ($event !== undefined) {
      this.store.dispatch(new LoadGetListAction(
        {
          type: CrudType.ReferenceNotificationsType,
          params: {
            filter: {id: $event.id},
            fields: {0: 'template'}
          },
          onSuccess: res => {
            if (res.status && res.response.items.length > 0) {
              const items = res.response.items[0];
              this.formGroup.get('template').setValue(items.template);
            } else {
              // TODO выводить ошибку
            }
          }
        }));
    }
  }

  submit($event?, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      const model = value ? value : {...this.formGroup.value};
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;

      if (this.item.id) {
        model.id = this.item.id;
      }
      console.log(model);
      if (model.user !== 0) {
        this.toSend = [{
          type: 'USER',
          value: model.user.id,
          channel: MainNotificationComponent.getChannelKorvet(),
          notificationsList: this.item.toSend && this.item.toSend.length ? this.item.toSend[0].notificationsList : null
        }];
      } else {
        if (!confirm('После сохранения, сообщение будет отправлено всем пользователям, продолжить ?')) {
          throw new Error('stop');
        }
      }
      model.data = {};
      model.toSend = this.toSend;
      model.data.template = this.template;
      if (model.header) {
        model.data.header = model.header;
      }
      model.data.date = new Date().toLocaleDateString();

      this.store.dispatch(new action({
        type: this.type,
        params: <any>model, onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.notify.handleMessage('Оповещение успешно отправлено', 'success');
            this.dialogRef.close();
            this.router.navigate([this.goListUrl()]).then();
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  changeTemplate($event: any) {
    this.template = $event.getData();
  }

  protected setModel() {
    if (this.item.data && this.item.data.template) {
      this.template = this.item.data.template;
    }
    this.formGroup = new FormGroup({
      type: new FormControl(this.item.type, [Validators.required]),
      template: new FormControl(this.template, [Validators.required]),
      user: new FormControl(0, []),
      header: new FormControl(this.item.data && this.item.data.header ? this.item.data.header : null, []),
    });

    if (this.item.toSend && this.item.toSend.length) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.User,
        params: {
          filter: {
            id: this.item.toSend[0]['value'],
          },
          fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        },
        onSuccess: (res) => {
          if (res.status && res.response.items.length) {
            this.formGroup.controls.user.setValue({
              id: res.response.items[0].id,
              fullName: res.response.items[0].surname + ' ' + res.response.items[0].name + ' ' + res.response.items[0].patronymic
            });
          }
        }
      }));
    }
  }
}
