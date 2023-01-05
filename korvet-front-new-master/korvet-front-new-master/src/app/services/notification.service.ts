import {EventEmitter, Injectable} from '@angular/core';
import {MainNotificationModel} from '../models/notifications/notifications.models';
import {Store} from '@ngrx/store';
import {CrudType} from '../common/crud-types';
import {AuthService} from './auth.service';
import {SocketDataType} from '../common/socket-type';
import {SocketDataModel} from '../models/notifications/socket-data.model';
import {CrudState, CrudStoreService} from '../api/api-connector/crud/crud-store.service';
import {ApiConnectorService} from '../api/api-connector/api-connector.service';
import {AuthTokenService} from '../api/auth/auth-token.service';
import {LoadPatchAction} from '../api/api-connector/crud/crud.actions';
import * as signalR from '@microsoft/signalr';
import {SettingsService} from './settings.service';
import {SettingModel} from '../models/setting.models';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

  public userId: number;
  notifications: MainNotificationModel[] = [];
  private readonly KORVET_CHANNEL_ID: number = 1;
  private USER_TYPE = 'USER';
  webSocket: WebSocket;
  notificationChanged: EventEmitter<any> = new EventEmitter();

  constructor(private store: Store<CrudState>,
    protected authService: AuthService,
    private crud: ApiConnectorService,
    private settingsService: SettingsService,
    protected crudService: CrudStoreService,
    private token: AuthTokenService,) {
    this.authService.userId$.subscribe(
      (userId: number) => {
        this.userId = userId;
        this.webSocketConnect();
      });
  }

  private webSocketConnect() {

    const connection = new signalR.HubConnectionBuilder()
      .withUrl('https://web-socket.mart-info.ru/consumerhub')
      .withAutomaticReconnect()
      .build();

    connection.on("NotificationEvent", (header: string, text: any) => {
      if (text) {
        const data = JSON.parse(text);
        switch (data.type) {
          case 'updateClientNotificationsList': this.notificationChanged.emit(); break;
        }
      }
    });

    connection.start()
      .then(() => {
        this.settingsService.getSettings().subscribe((res: Array<SettingModel>) => {
          const serverCode = res.find(n => n.key === 'SOCKET_SERVER');
          if (serverCode) {
            connection.invoke('auth', this.token?.get()?.access_token, serverCode.value);
          } else {
            console.warn('В настройках не установлен код сервера (SOCKET_SERVER)');
          }
        });

      })
      .catch((e) => {
        console.warn('Соединение с web socket сервером не установлено');
      });
  }

  public getNotificationsList(from: number = 0, to: number = 5, callback: (any, number) => void) {
    let url = this.crudService.config[CrudType.Notifications].url;
    if (this.crudService.config[CrudType.Notifications]["data"]["id"]) {
      url = url.replace(this.crudService.config[CrudType.Notifications]["data"]["id"] + '/', '');
    }
    const req = this.crud.getList(url, {
      filter: {
        toSend: {
          type: this.USER_TYPE,
          channel: {id: this.KORVET_CHANNEL_ID}
        }
      },
      limit: to,
      offset: from,
    }).subscribe(result => {
      if (result && result.status === true) {
        callback(result.response.items, result.response.totalCount);
      } else {
        callback(null, 0);
      }
    });
  }

  public setNotificationViewed(notification: MainNotificationModel, callback: (any) => void) {
    let needUpdate = false;
    notification.toSend.forEach((toSendItem) => {
      if (!toSendItem.viewed && toSendItem.value === this.userId) {
        needUpdate = true;
        toSendItem.viewed = true;
        toSendItem.opened = true;
      }
    });
    if (needUpdate) {
      this.store.dispatch(new LoadPatchAction({
        type: CrudType.Notifications,
        params: notification,
        onSuccess: (res) => {
          callback(res && res.status === true ? res.response : null);
        }
      }));
    }
    else {
      callback(null);
    }
  }

  public webSocketSend() {
    if (this.webSocket.readyState === this.webSocket.OPEN) {
      const data: SocketDataModel = new SocketDataModel();
      data.type = SocketDataType.message;
      data.data = 'Сообщение с клиента korvet';
      this.webSocket.send(JSON.stringify(data));
      console.log('Отпревлено текстовое сообщение на сервер');
    } else {
      console.log('Соединение закрыто, отправка не возможна!');
    }
  }
}

