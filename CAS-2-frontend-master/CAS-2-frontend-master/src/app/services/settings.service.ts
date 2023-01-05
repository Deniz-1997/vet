import {Injectable} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject, Observable, of} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {catchError, filter, map, switchMap} from 'rxjs/operators';
import {SettingModel} from '../models/setting.models';
import {getCrudModelData, getCrudModelLoaded} from '../api/api-connector/crud/crud.selectors';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';

@Injectable({
  providedIn: 'root'
})
export class SettingsService {

  contactValue = new BehaviorSubject({
    tell: '',
    tellText: '',
    globalPhone: '',
    globalPhoneText: '',
    email: '',
    signature: '',
    enable1c: '',
    date: new Date()
  } as {
    tell: string;
    tellText: string;
    globalPhone: string;
    globalPhoneText: string;
    email: string;
    signature: string;
    enable1c: string;
    date: Date;
  });
  private key = 'settings';
  private loaded = false;
  private settings$ = new BehaviorSubject(new Map());

  constructor(
    protected store: Store<CrudState>,
  ) {
    this.getSupport();
  }

  static checkSupportDate(date: Date | string): boolean {
    const diff = Math.abs((new Date()).getTime() - new Date(date).getTime());
    return Math.ceil(diff / (1000 * 3600 * 24)) < 5; // 5 дней храним
  }

  getSupport(): any {
    of(localStorage.getItem(this.key))
      .pipe(
        map(data => {
          const localSettings = JSON.parse(data);
          if (!localSettings || !SettingsService.checkSupportDate(localSettings.date)) {
            throw new Error('no settings');
          }
          return localSettings;
        }),
        catchError(() => {
          this.store.dispatch(new LoadGetListAction({
            type: CrudType.Settings,
            params: {order: {id: 'ASC'}},
          }));
          return this.store.pipe(
            select(getCrudModelLoaded, {type: CrudType.Settings}),
            filter(loaded => loaded),
            switchMap(() => this.store.pipe(select(getCrudModelData, {type: CrudType.Settings}))),
          );
        }),
      )
      .subscribe((settings: Array<SettingModel>) => {
        localStorage.setItem(this.key, JSON.stringify(settings));
        this.loaded = true;
        this.settings$.next(settings.reduce((acc, setting) => {
          acc.set(setting.key, setting);
          return acc;
        }, new Map()));
        const contact = {
          tell: '',
          tellText: '',
          globalPhone: '',
          globalPhoneText: '',
          email: '',
          signature: '',
          enable1c: '',
          date: new Date()
        };
        settings.forEach(setting => {
          switch (setting.key) {
            case 'contact.phone_number':
              contact.tellText = setting.value;
              contact.tell = (contact.tellText || '').replace(/\s/g, '');
              break;
            case 'contact.global_phone_number':
              contact.globalPhoneText = setting.value;
              contact.globalPhone = (contact.globalPhoneText || '').replace(/\s/g, '');
              break;
            case 'contact.signature':
              contact.signature = setting.value;
              break;
            case 'contact.email':
              contact.email = setting.value;
              break;
            case 'settings.enable1c':
              contact.enable1c = setting.value;
              break;
          }
        });
        this.contactValue.next(contact);
      });
    return this.contactValue;
  }

  getSetting(key: string): Observable<SettingModel> {
    return this.settings$
      .pipe(
        filter(() => this.loaded),
        map(settings => settings.get(key))
      );
  }

  getSettings(): Observable<Array<SettingModel>> {
    return new Observable<Array<SettingModel>>((subscriber) => {
      const settingsList = localStorage.getItem(this.key);
      if (settingsList) {
        subscriber.next(JSON.parse(settingsList) as Array<SettingModel>);
        subscriber.complete();
      } else {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Settings,
          params: {order: {'id': 'ASC'}},
          onSuccess: (res) => {
            if (res && res.status && res.response) {
              subscriber.next(res.response.items);
              subscriber.complete();
            }
          }
        }));
      }
    });
  }
}
