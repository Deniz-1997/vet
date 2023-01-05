import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';
import {SettingsService} from '../../../../../services/settings.service';
import {animate, state, style, transition, trigger} from '@angular/animations';
import {NavigationEnd, Router} from '@angular/router';
import {filter} from 'rxjs/operators';
import {ReferenceBusinessEntityModel} from '../../../../../models/reference/reference.businessEntity.models';
import {ModalSupportFormComponent} from '../../../../shared/components/modal-support-form/modal.component';
import {MatDialog} from '@angular/material/dialog';
import {UserObjectListService} from '../../../../../services/user-object-list.service';
import {ReferenceStationModel} from '../../../../../models/reference/reference.station.models';
import {UserAuthModel} from '../../../../../api/auth/auth.models';
import {ApiMenuModel} from '../../../../../api/api-menu/api-menu.model';
import {ModalStationListComponent} from '../../../../shared/components/modal-station-list/modal-station-list.component';
import {SessionStorageService} from '../../../../../services/sessionStorage.service';

@Component({
  selector: 'app-navigator',
  templateUrl: './navigator.component.html',
  styleUrls: ['./navigator.component.css'],
  animations: [
    trigger('openClose', [
      state('true', style({overflow: 'hidden', height: '*'})),
      state('false', style({overflow: 'hidden', height: '0px'})),
      transition('1 => 0', animate('150ms ease-in-out')),
      transition('0 => 1', animate('300ms ease-in-out')),
    ]),
    trigger('triggerBackgroundMenu',
      [
        transition(':enter', [
            style({opacity: 0}),
            animate('150ms ease-in-out', style({'opacity': 1}))
          ]
        ),
        transition(':leave', [
            style({'opacity': 1}),
            animate('300ms ease-in-out', style({'opacity': 0}))
          ]
        )
      ]),
  ],
})
export class NavigatorComponent implements OnInit {

  @Input() user: UserAuthModel;
  @Input() items: Array<ApiMenuModel>;

  @Input() isMobile = false;
  @Input() isShowMenu = false;

  @Output() changeIsShowMenu: EventEmitter<any> = new EventEmitter();
  @Input() widthNavigation = 300;

  openedMenu = [];
  indexActiveMenu: number;
  date: number;

  config: any;
  configHref: string;
  configGlobalPhone: string;
  configMailto: string;
  activeRoute: string;
  currentObject: ReferenceStationModel | ReferenceBusinessEntityModel;
  userObjectList: Array<ReferenceStationModel | ReferenceBusinessEntityModel>;
  typeObject: string;
  path = '/docs/guide-cas.pdf';

  constructor(private router: Router,
              private setting: SettingsService,
              private userObjectService: UserObjectListService,
              private sessionStorageService: SessionStorageService,
              private dialog: MatDialog) {

    this.activeRoute = this._getActiveRoute(router.url) as string;

    router.events.pipe(filter(e => e instanceof NavigationEnd))
      .subscribe((val) => {
        this.activeRoute = this._getActiveRoute(val['url']) as string;
      });
  }

  /**
   * Проверяем изменилась ли переменная isMobile
   * Если текущее разрешение экрана не является мобильным (планшетным) и меню открыто через кнопку
   * То отключаем открытие меню по isShowMenu и отправляет changeIsShowMenu родителю
   */
  ngOnChanges(changes: SimpleChanges): void {
    if (typeof changes.isMobile !== 'undefined') {
      if (!this.isMobile && this.isShowMenu) {
        this.isShowMenu = false;
        this.changeIsShowMenu.emit(false);
      }
    }
  }

  ngOnInit(): void {
    this.setting.contactValue.subscribe(() => {
      this.config = this.setting.contactValue.value;
      this.configHref = `tel:${this.config.tell}`;
      this.configGlobalPhone = `tel:${this.config.globalPhone}`;
      this.configMailto = `mailto:${this.config.email}`;
    });
    if (!(!!this.sessionStorageService.getSessionStorageValue('objectType'))) {
      this.typeObject = this.sessionStorageService.getSessionStorageValue('objectType');
    }
    this.userObjectService.getCurrentObjectList().subscribe((res: [ReferenceStationModel
      | ReferenceBusinessEntityModel, Array<ReferenceStationModel | ReferenceBusinessEntityModel>, string]) => {
      this.currentObject = res[0];
      this.userObjectList = res[1];
      this.typeObject = res[2];
    });

    this.date = new Date().getFullYear();
    this._setActiveMenu();
  }

  /**
   * Проверяем по массиву, находится ли там нужный индекс меню
   */
  isActiveMenu(index: any, subMenu?: any): Array<string> {
    const indexOpenedMenu = this.openedMenu.findIndex(v => subMenu === undefined ? v === index : v === subMenu);
    if (indexOpenedMenu !== -1) {
      if (subMenu === undefined && indexOpenedMenu === 0) {
        return ['krv-list-item--active']; // если это 1 уровень меню
      } else if (subMenu !== undefined && indexOpenedMenu > 0 && this.openedMenu[0] === this.indexActiveMenu) {
        return ['krv-list-item--active']; // если это 2 уровень меню
      }
    }
    return [];
  }

  /**
   * Подставляем индекс октрытого меню в openedMenu
   */
  openMenu(index: any): void {
    this.openedMenu[0] = this.openedMenu[0] === index ? null : index;
  }

  /**
   * Проверяем открыто-ли меню находя индекс меню в openedMenu
   */
  isOpenMenu(index: any): boolean {
    const indexOpenedMenu = this.openedMenu.findIndex(v => v === index);
    return indexOpenedMenu !== -1 && indexOpenedMenu === 0;
  }

  /**
   * TODO изменить наименование иконо в БД
   * Временно подставляем нужную иконку в меню
   */
  getIconForMenu(name: string): string {
    switch (name) {
      case 'icon-administration':
        return 'assignment';
      case 'setting':
        return 'settings';
      case 'icon-laboratory':
        return 'science';
      case 'notifications':
        return 'notifications';
      case 'icon-description':
        return 'description';
      default:
        return name;
    }
  }

  /**
   * TODO вернуть модальное окно
   */
  openSupportModal(): void {
    const dialogRef = this.dialog.open(ModalSupportFormComponent, {
      width: '1000px',
      autoFocus: false,
      height: '100% - 50px',
      data: {
      },
    });
  }

  openModelStationList($event: any): void {
    if ($event) {
      $event.preventDefault();
    }
    this.dialog.open(ModalStationListComponent, {
      width:  window.innerWidth > 960 ? '60%' : '90%',
      autoFocus: false,
      height: '100% - 50px',
      data: !!this.userObjectList ? this.userObjectList : this.sessionStorageService.getSessionStorageObject(this.sessionStorageService.getSessionStorageValue('objectType')),
    });
  }

  showSwitchUserExit(): boolean {
    return localStorage.getItem('switchUser') ? true : false;
  }

  switchUserExit(): void {
    localStorage.removeItem('switchUser');
    localStorage.removeItem('sidenav');
    window.location.href = '/';
  }

  getLink(item: any, subMenu?: string | undefined): string | undefined {
    if (subMenu !== undefined) {
      return item.url;
    }

    return item.type.code === 'URL' ? item.url : undefined;
  }

  /**
   * openedMenu - хранит в себе 2 уровня меню
   * 0 - индекс, это раскрытие меню
   * 1 - индекс, этот индекс делает кнопку активной в подменю
   */
  _setActiveMenu(): void {
    if (this.items !== undefined) {
      this.items.forEach(({items, url}, index) => {
        if (this._getActiveRoute(url) === this.activeRoute) {
          this.openedMenu.push(index);

          items.forEach((item, subIndex) => {
            if (this.router.url === item.url) {
              this.openedMenu.push(subIndex);
              this.indexActiveMenu = index;
            }
          });
        }
      });
    }
  }

  /**
   * Возвращаем активный роутинг (admin|lk|dictionary and etc)
   */
  _getActiveRoute(url: string, returnArray?: boolean): string | Array<string> {
    return returnArray !== undefined ? url.split('/') : url.split('/')[1];
  }
  selectObject(event: any): void {
    this.userObjectService.setCurrentObjectList(event.value);
  }
}
