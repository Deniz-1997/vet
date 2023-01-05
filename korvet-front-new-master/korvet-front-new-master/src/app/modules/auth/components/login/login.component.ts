import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {ClearErrorAction, ErrorState, getError} from '../../../../store/error';
import {Observable, Subscription} from 'rxjs';
import {filter, map} from 'rxjs/operators';
import {ErrorType} from '../../../../common/error-type';
import {Router} from '@angular/router';
import {StorageType} from '../../../../common/storage-type.enum';
import {AuthState} from 'src/app/api/auth/auth.reducer';
import {AuthTokenService} from 'src/app/api/auth/auth-token.service';
import {GetTokenAction} from 'src/app/api/auth/auth.actions';
import {TokenAuthModel} from 'src/app/api/auth/auth.models';
import {getToken, getTokenLoading} from 'src/app/api/auth/auth.selectors';

@Component({templateUrl: './login.component.html', styleUrls: ['./login.component.css']})
export class LoginComponent implements OnInit, OnDestroy {

  formGroup = new FormGroup({
    login: new FormControl(''),
    password: new FormControl(''),
    save: new FormControl(true),
  });
  error$: Observable<boolean>;
  token$: Observable<TokenAuthModel>;
  tokenLoading$: Observable<boolean>;
  private subscription: Subscription;

  constructor(
    private store: Store<AuthState | ErrorState>,
    private router: Router,
    private token: AuthTokenService,
  ) {
  }

  ngOnInit() {
    this.error$ = this.store.pipe(
      select(getError),
      map(error => !!error[ErrorType.GetToken]),
    );
    this.token$ = this.store.pipe(
      select(getToken),
      filter(token => !!token),
    );
    this.tokenLoading$ = this.store.pipe(select(getTokenLoading));
    this.subscription = new Subscription();
    this.subscription.add(
      this.token$.subscribe(token =>
        this.router.navigateByUrl(this.token.getLastRoute() || '/').then()
      )
    );
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

  submit(): void {
    if (this.formGroup.valid) {
      this.store.dispatch(new GetTokenAction({
        params: {
          username: this.formGroup.value['login'],
          password: this.formGroup.value['password'],
        },
        storage: this.formGroup.value['save'] ?
          StorageType.localStorage :
          StorageType.sessionStorage,
      }));
    }
  }

  toRestore(): void {
    this.store.dispatch(new ClearErrorAction());
    this.router.navigate(['auth', 'restore']);
  }
}
