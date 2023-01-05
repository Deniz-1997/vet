import {Component, Input, OnInit, TemplateRef} from '@angular/core';
import {ApiMenuService} from './api-menu.service';
import {filter} from 'rxjs/operators';
import {NavigationEnd, Router} from '@angular/router';
import {AuthTokenService} from '../auth/auth-token.service';
import {ApiMenuModel} from './api-menu.model';

@Component({
  selector: 'ws-api-menu',
  templateUrl: 'api-menu.component.html',
  styles: []
})
export class ApiMenuComponent implements OnInit {
  get menu(): ApiMenuModel[] {
    if (this.authTokenService.check()) {
      this.menuService.getMenu();
    }
    this._menu = this.menuService.sidenav;
    return this._menu;
  }

  @Input() formTemplate: TemplateRef<any>;

  private _menu: ApiMenuModel[] = [];

  activeRoute;

  constructor(router: Router,
              private menuService: ApiMenuService,
              private authTokenService: AuthTokenService) {
    this.activeRoute = router.url.split('/')[1];

    router.events
      .pipe(
        filter(e => e instanceof NavigationEnd)
      )
      .subscribe((val) => {
        this.activeRoute = val['url'].split('/')[1];
      });

  }

  ngOnInit() {
  }

  toggle(i: number, e: Event): void {
  }

  isActive(item): boolean {
    if (item.url && item.url === '/' + this.activeRoute) {
      return true;
    } else if (item.url && item.url === '/') {
      if (this.activeRoute === '' || this.activeRoute === 'appointments') {
        return true;
      } else {
        return item && item.items && item.items.some(link => link.url === '/' + this.activeRoute);
      }
    } else {
      return false;
    }
  }

}
