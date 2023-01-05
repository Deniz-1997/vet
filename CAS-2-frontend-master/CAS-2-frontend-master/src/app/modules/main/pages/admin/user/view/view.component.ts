import {Component, OnDestroy, OnInit} from '@angular/core';
import {Observable, Subscription} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {ViewService} from './view.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {UserModels} from '../../../../../../models/user/user.models';

@Component({templateUrl: './view.component.html'})

export class ViewComponent implements OnInit, OnDestroy {
  tabs: Array<{ link: string | Array<string>, title: string }> = [
    {link: ['profile'], title: 'Профиль'},
    {link: '', title: 'График работы'},
  ];

  user: UserModels;
  loading$: Observable<boolean>;
  private subscriptions: Array<Subscription> = [];

  constructor(
    public petsViewService: ViewService,
    private route: ActivatedRoute,
    public router: Router,
    private brdSrv: BreadcrumbsService,
  ) {
    let s = route.paramMap.subscribe(routeParam => {
      this.petsViewService.id = routeParam.get('id');
    });
    this.subscriptions.push(s);
    this.loading$ = petsViewService.loading$;

    s = petsViewService.user.subscribe(user => {
      if (user && user.id) {
        this.user = user;
        this.brdSrv.replaceLabelByIndex(this.user.getFullName(), 3);
      }
    });
    this.subscriptions.push(s);
  }

  ngOnInit(): void {

  }


  ngOnDestroy(): void {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

}
