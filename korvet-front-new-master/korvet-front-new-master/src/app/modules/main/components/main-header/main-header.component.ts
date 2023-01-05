import {ChangeDetectorRef, Component, OnDestroy, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {filter, takeUntil} from 'rxjs/operators';
import {Subject} from 'rxjs';

@Component({
  selector: 'app-main-header',
  templateUrl: './main-header.component.html',
  styleUrls: ['./main-header.component.css']
})
export class MainHeaderComponent implements OnInit, OnDestroy {

  header: string;

  private destroy$ = new Subject<any>();

  constructor(
    private title: Title,
    private router: Router,
    private route: ActivatedRoute,
    private changeDetectorRef: ChangeDetectorRef,
  ) {
  }

  ngOnInit() {
    this.setTitle();
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(event => this.setTitle());
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  private setTitle(): void {
    const route = this.getLastChild(this.route);
    let title = (route.routeConfig.data && route.routeConfig.data['title']) ?
      route.routeConfig.data['title'] : '';
    this.header = title;
    title = title ? title : 'КОРВЕТ';
    this.title.setTitle(title);
    this.changeDetectorRef.detectChanges();
  }

  private getLastChild(route: ActivatedRoute): ActivatedRoute {
    return route.children.length ? this.getLastChild(route.children[0]) : route;
  }
}
