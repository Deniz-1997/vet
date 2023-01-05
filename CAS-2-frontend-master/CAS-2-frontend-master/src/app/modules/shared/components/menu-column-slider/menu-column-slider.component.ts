import {Component, Input, OnInit} from '@angular/core';
import {slideInOut} from '../../../../animations/slideInOut.animation';
import {NavigationEnd, Router} from '@angular/router';
import {MainSidenavModel} from '../../../../models/main/sidenav.model';
import {filter} from 'rxjs/operators';

@Component({
  selector: 'app-menu-column-slider',
  templateUrl: './menu-column-slider.component.html',
  styleUrls: ['./menu-column-slider.component.css'],
  animations: [slideInOut]
})
export class MenuColumnSliderComponent implements OnInit {

  @Input() menu: Array<MainSidenavModel> = [];

  activeRoute;

  constructor(router: Router) {
    this.activeRoute = router.url.split('/')[1];

    router.events.pipe(filter(e => e instanceof NavigationEnd))
      .subscribe((val) => this.activeRoute = val['url'].split('/')[1]);
  }

  ngOnInit(): void {
  }

  toggle(i: number, e: Event): void {
  }

  isActive(item: any): boolean {
    if (item.url && item.url === '/' + this.activeRoute) {
      return true;
    } else if (item.url && item.url === '/') {
      if (this.activeRoute === '' || this.activeRoute === 'appointments') {
        return true;
      } else {
        return item.items.some(link => link.url === '/' + this.activeRoute);
      }
    } else {
      return false;
    }
  }

}
