import {Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable, Subscription} from 'rxjs';
import {NgxSpinnerService} from 'ngx-spinner';
import {NoAccessService} from '../../../../services/no-access.service';
import {filter} from 'rxjs/operators';
import {GetUserAction} from 'src/app/api/auth/auth.actions';
import {TokenAuthModel} from 'src/app/api/auth/auth.models';
import {AuthState} from 'src/app/api/auth/auth.reducer';
import {getUserLoading, getToken} from 'src/app/api/auth/auth.selectors';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit, OnDestroy {

  userLoading$: Observable<boolean>;
  token$: Observable<TokenAuthModel>;
  private subscription: Subscription;

  constructor(
    private store: Store<AuthState>,
    private spinner: NgxSpinnerService,
    public noAccessService: NoAccessService,
  ) {
    this.userLoading$ = store.pipe(select(getUserLoading));
    this.token$ = store.pipe(select(getToken), filter(token => !token));
  }

  ngOnInit() {
    this.subscription = new Subscription();
    this.store.dispatch(new GetUserAction());
    this.subscription.add(this.userLoading$.subscribe(loading => loading ? this.spinner.show() : this.spinner.hide()));
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }
}
