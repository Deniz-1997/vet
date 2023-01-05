import {Injectable, Optional} from '@angular/core';
import {Router} from '@angular/router';
import {BehaviorSubject, Observable} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {ApiMenuConfig} from './api-menu.config';
import {ApiParamsModel} from '../api-connector/api-connector.models';
import {prepareHttpParams} from '../api-connector/api-connector.utils';
import {ApiMenuResponse} from './api-menu-response.model';
import {ApiMenuModel} from './api-menu.model';

@Injectable({
  providedIn: 'root'
})
export class ApiMenuService {

  sidenav: Array<ApiMenuModel>;
  routsAccess: Array<string> = [];

  readonly urls = {
    api: '/api/',
    apiActionMenu: '/api/action/'
  };
  readonly localStorageKey: string = 'sidenav';
  readonly errorNavigate = ['/errors'];

  constructor(
    private router: Router,
    private http: HttpClient,
    @Optional() private apiMenuConfig: ApiMenuConfig,
  ) {
    if (apiMenuConfig) {
      Object.assign(this.urls, apiMenuConfig.urls);
      if (apiMenuConfig.errorNavigate) {
        this.errorNavigate = apiMenuConfig.errorNavigate;
      }
    }
  }

  getMenu(callback?: any): void {

    const localItem = localStorage.getItem(this.localStorageKey);

    if (localItem) {
      const currentSidenav = JSON.parse(localItem);
      const today = new Date();
      const currentDate = new Date(currentSidenav.date);

      if (currentDate.toDateString() === today.toDateString()) {
        this.sidenav = currentSidenav['sidenav'];

        this.setRoutsAccess(this.sidenav);

        if (callback) {
          callback(this.sidenav);
        }

      } else {
        this.setMenu(callback);
      }

    } else {
      this.setMenu(callback);
    }
  }

  setMenu(callback?: any): any {
    const params = {
      filter: {
        groups: {code: 'LEFT_MENU'},
        parent: 'isNull'
      },
      order: {sort: 'ASC', items: {sort: 'ASC'}},
      fields: {
        0: 'id',
        1: 'name',
        3: 'url',
        4: 'description',
        5: 'buttonSettings',
        6: 'sort',
        7: 'additionalActions',
        8: 'type',
        'items': ['id',
          'name',
          'url',
          'description',
          'buttonSettings',
          'sort',
          'additionalActions',
          'type']
      }
    };
    return this.http.get<ApiMenuResponse<ApiMenuModel>>(this.urls.apiActionMenu, {
      params: prepareHttpParams(new ApiParamsModel(params).forApi)
    }).subscribe(response => {
      if (response.status && response.response && response.response.items) {

        this.sidenav = response.response.items;
        this.setRoutsAccess(this.sidenav);
        localStorage.setItem(this.localStorageKey, JSON.stringify({
            date: new Date(),
            sidenav: response.response.items
          }
        ));

        if (callback) {
          callback(response);
        }
      }
      return response;
    });
  }

  removeMenu(): void {
    localStorage.removeItem(this.localStorageKey);
  }

  setRoutsAccess(sidenav: Array<ApiMenuModel>): void {
    this.routsAccess = [];
    sidenav.map(
      (menu) => {

        if (menu.url) {
          this.routsAccess.push(menu.url);

          if (menu.items) {
            menu.items.map(
              (item: ApiMenuModel) => item.url ? this.routsAccess.push(item.url) : ''
            );
          }
        }
      }
    );
  }

  hasAccess(rout: string): boolean | Observable<boolean> {

    if (this.sidenav) {

      return this.hasRout(rout);
    } else {

      const returnState: BehaviorSubject<boolean> = new BehaviorSubject(false);
      this.getMenu((item) => {
        return item ? returnState.next(this.hasRout(rout)) : returnState.next(false);
      });
      return returnState;
    }
  }


  hasRout(rout: string): boolean {
    if (rout.indexOf('/') > -1) {

      const activeRoute = rout.split('/');
      const lengthActiveRoute = activeRoute.length;

      if (this.routsAccess.some((item: string) => {

          const url = item.split('/');

          return lengthActiveRoute === 2 && url[1] === activeRoute[1]
            || lengthActiveRoute > 2 && url[1] === activeRoute[1] && url[2] === activeRoute[2];
        }
      )) {
        return true;
      } else {

        this.router.navigate(this.errorNavigate).then();
        return false;
      }

    } else {

      return true;
    }
  }
}
