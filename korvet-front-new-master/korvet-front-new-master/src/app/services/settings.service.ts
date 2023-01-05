import {Injectable} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject, Observable, Observer, of} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {catchError, filter, map, switchMap} from 'rxjs/operators';
import {SettingModel} from '../models/setting.models';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelLoaded} from '../api/api-connector/crud/crud.selectors';

@Injectable({
  providedIn: 'root'
})
export class SettingsService {

  contactValue = new BehaviorSubject(<{
    tell: string;
    tellText: string;
    globalPhone: string;
    globalPhoneText: string;
    email: string;
    signature: string;
    enable1c: string;
    date: Date;
  }>{
      tell: '',
      tellText: '',
      globalPhone: '',
      globalPhoneText: '',
      email: '',
      signature: '',
      enable1c: '',
      date: new Date()
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

  getSupport() {
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
            params: {order: {'id': 'ASC'}},
          }));
          return this.store.pipe(
            select(getCrudModelLoaded, {type: CrudType.Settings}),
            filter(loaded => loaded),
            switchMap(() => this.store.pipe(select(getCrudModelData, {type: CrudType.Settings}))),
          );
        }),
      )
      .subscribe((settings: SettingModel[]) => {
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
    /*const supportString = localStorage.getItem('contact'),
      def = {
        tell: '',
        tellText: '',
        email: '',
        signature: '',
        enable1c: '',
        date: new Date()
      };
    const contact = supportString ? JSON.parse(supportString) : null;
    if (supportString && contact && SettingsService.isSupportValid(contact)) {
      this.contactValue.next(contact);
    } else {
      this.contactValue.next(def);
      this.getMainSettings().subscribe(res => {
        if (res && res.status === true && res.response && res.response.items) {
          for (const i in res.response.items) {
            if (res.response.items.hasOwnProperty(i)) {
              switch (res.response.items[i].key) {
                case 'contact.phone_number':
                  def.tellText = res.response.items[i].value;
                  def.tell = (def.tellText || '').replace(/\s/g, '');
                  break;
                case 'contact.signature':
                  def.signature = res.response.items[i].value;
                  break;
                case 'contact.email':
                  def.email = res.response.items[i].value;
                  break;
                case 'settings.enable1c':
                  def.enable1c = res.response.items[i].value;
                  break;
              }
            }
          }
          if (def.tell) {
            localStorage.setItem('contact', JSON.stringify(def));
          }
          this.contactValue.next(def);
        }
      });
    }*/
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

  // getMainSettings(): BehaviorSubject<ApiResponse> {
  //   const then = new BehaviorSubject(<ApiResponse>{}),
  //     filter = {'key': ['contact.signature', 'contact.phone_number', 'contact.email', 'settings.enable1c']};
  //   this.store.dispatch(new LoadGetListAction({
  //     type: CrudType.Settings,
  //     params: {filter: filter, order: {'id': 'ASC'}},
  //     onSuccess(res: ApiResponse) {
  //       then.next(res);
  //     }
  //   }));
  //   return then;
  // }
}
