import { Component, Input, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { select, Store } from '@ngrx/store';
import { AuthState } from '../auth.reducer';
import { AuthActionTypes, GetTokenAction, RemoveErrorAction, SetErrorAction } from '../auth.actions';
import { StorageType } from '../auth.utils';
import { Observable } from 'rxjs';
import { TokenAuthModel } from '../auth.models';
import { getToken, getTokenLoading } from '../auth.selectors';
import { filter } from 'rxjs/operators';
import { Router } from '@angular/router';

export interface LoginFormInterface {
  username: string;
  password: string;
  remember: boolean;
}

@Component({
  selector: 'app-ws-auth-login',
  templateUrl: 'auth-login.component.html',
  styleUrls: ['auth-login.component.css']
})
export class AuthLoginComponent implements OnInit {

  @Input() formTemplate: TemplateRef<any>;

  formGroup: FormGroup;
  loginForm: LoginFormInterface = {
    username: '',
    password: '',
    remember: true,
  };
  private token$: Observable<TokenAuthModel>;
  tokenLoading$: Observable<boolean>;

  constructor(
    private fb: FormBuilder,
    private store: Store<AuthState>,
    private router: Router,
  ) { }

  ngOnInit(): void {
    this.store.dispatch(new RemoveErrorAction({type: AuthActionTypes.SetErrorActon}));
    this.formGroup = this.fb.group({
      username: [this.loginForm.username, [Validators.required]],
      password: [this.loginForm.password, Validators.required],
      remember: [this.loginForm.remember],
    });
    this.token$ = this.store.pipe(select(getToken), filter(token => !!token));
    this.token$.subscribe(token => this.router.navigate(['/']));
    this.tokenLoading$ = this.store.pipe(select(getTokenLoading));
    this.tokenLoading$.subscribe(loading => loading ? this.formGroup.disable() : this.formGroup.enable());
  }

  onSubmit(): void {
    if (!this.formGroup.valid) {
      this.store.dispatch(new SetErrorAction({
        type: AuthActionTypes.SetErrorActon,
        error: [{message: 'Проверьте правильность заполнения формы'}]
      }));
    } else {
      const value: LoginFormInterface = this.formGroup.value;
      this.store.dispatch(new GetTokenAction({
        params: {username: value.username, password: value.password},
        storage: value.remember ? StorageType.localStorage : StorageType.sessionStorage,
      }));
    }
  }
}
