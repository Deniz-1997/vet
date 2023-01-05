import {Component, HostBinding, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable, Subscription} from 'rxjs';
import {NgxSpinnerService} from 'ngx-spinner';
import {NoAccessService} from '../../../../services/no-access.service';
import {filter} from 'rxjs/operators';
import {BreadcrumbsService} from '../../../../services/breadcrumbs.service';
import {HttpClient} from '@angular/common/http';
import {Router} from '@angular/router';
import {NotifyService} from '../../../../services/notify.service';
import {MatDialog} from '@angular/material/dialog';
import {getToken, getUser, getUserLoading} from '../../../../api/auth/auth.selectors';
import {TokenAuthModel, UserAuthModel} from '../../../../api/auth/auth.models';
import {AuthState} from '../../../../api/auth/auth.reducer';
import {GetUserAction} from '../../../../api/auth/auth.actions';
import {ApiMenuService} from '../../../../api/api-menu/api-menu.service';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit, OnDestroy {
  @HostBinding('class')
  elementClass: Array<string> = ['krv-application--wrap'];

  widthNavigation = 300;

  isMobile: boolean;
  isShowMenu = false;

  styles = {};

  breadcrumbs$: Observable<Array<any>>;
  userLoading$: Observable<boolean>;
  token$: Observable<TokenAuthModel>;
  private subscription: Subscription;
  user$: Observable<UserAuthModel>;
  user: UserAuthModel;
  isChecked = false;
  color: '#000000';
  path = '/uploaded/guide-korvet.pdf';
  constructor(
    private store: Store<AuthState>,
    private spinner: NgxSpinnerService,
    public noAccessService: NoAccessService,
    private brdSrv: BreadcrumbsService,
    private http: HttpClient,
    private router: Router,
    public menuService: ApiMenuService,
    private notify: NotifyService,
    private dialog: MatDialog,
  ) {
    this.userLoading$ = store.pipe(select(getUserLoading));
    this.token$ = store.pipe(select(getToken), filter(token => !token));

    this.user$ = store.pipe(select(getUser));
    this.menuService.getMenu();
  }

  ngOnInit(): void {
    this.user$.subscribe(user => {
      if (user) {
        this.user = user;
        // @ts-ignore
        this.isChecked = user.user.modeCashboxMobile;
      }
    });
    this.subscription = new Subscription();
    this.breadcrumbs$ = this.brdSrv.breadcrumbs;
    this.store.dispatch(new GetUserAction());
    this.subscription.add(this.userLoading$.subscribe(loading => loading ? this.spinner.show() : this.spinner.hide()));
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

  onResized(event: any): void {
    const {isMobile} = event;

    this.isMobile = isMobile;

    if (isMobile) {
      this.styles = {};
    } else {
      this.styles = {
        'padding-left:': `400px`,
        'padding-top:': '50px',
      };
    }
  }
}
