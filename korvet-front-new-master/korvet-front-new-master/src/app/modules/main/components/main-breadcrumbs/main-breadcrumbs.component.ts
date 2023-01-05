import {Component, OnInit} from '@angular/core';
import {BreadcrumbsService} from '../../../../services/breadcrumbs.service';
import {Observable, Subscription} from 'rxjs';

@Component({
  selector: 'app-main-breadcrumbs',
  templateUrl: './main-breadcrumbs.component.html',
  styleUrls: ['./main-breadcrumbs.component.css']
})
export class MainBreadcrumbsComponent implements OnInit {

  breadcrumbs$: Observable<any[]>;
  private subscription: Subscription;

  constructor(
    private brdSrv: BreadcrumbsService,
  ) {
  }

  ngOnInit() {
    this.subscription = new Subscription;
    this.breadcrumbs$ = this.brdSrv.breadcrumbs;
  }

  ShowSideBarMenu() {
    const sideBarMenu = document.getElementById('main-sidenav');
    if (sideBarMenu) {
      sideBarMenu.style.visibility = 'visible';
    }
  }
}
