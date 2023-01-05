import {Component, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../services/notify.service';
import {filter} from 'rxjs/operators';
import {SnackBarService} from '../../../../services/snack-bar.service';
import {
  GetRestoreAction,
  RequestAccountPasswordRecovery,
  RequestRestoreCheckCodeAction
} from '../../../../api/auth/auth.actions';
import {AuthState} from '../../../../api/auth/auth.reducer';
import {
  getAccountPasswordRecoveryStatus, getRestoreCheckCodeStatus,
  getRestoreLoading,
  getRestoreRecipient
} from '../../../../api/auth/auth.selectors';

@Component({templateUrl: './restore.component.html', styleUrls: ['../auth.scss']})
export class RestoreComponent implements OnInit {
  page = 'restore';
  recipient = '';
  recipientCode = '';
  status = '';
  isError: BehaviorSubject<boolean> = new BehaviorSubject(false);

  loading$: Observable<boolean>;

  loginFormGroup = new FormGroup({
    login: new FormControl('', [Validators.required]),
  });
  recipient$: Observable<string>;

  codeFormGroup = new FormGroup({
    code: new FormControl('', [Validators.required])
  });
  code$: Observable<boolean>;

  newPasswordFormGroup = new FormGroup({
    newPassword: new FormControl('', [Validators.required]),
    newPasswordConfirm: new FormControl('', [Validators.required])
  });
  psw$: Observable<boolean>;

  private queryRouteSubscription: Subscription;

  constructor(
    private store: Store<AuthState>,
    private router: Router,
    private notify: NotifyService,
    private snackBar: SnackBarService,
    private route: ActivatedRoute,
  ) {

    this.recipient$ = store.pipe(select(getRestoreRecipient), filter(state => !!state));
    this.loading$ = store.pipe(select(getRestoreLoading));
    this.code$ = store.pipe(select(getRestoreCheckCodeStatus), filter(state => !!state));
    this.psw$ = store.pipe(select(getAccountPasswordRecoveryStatus), filter(state => !!state));
  }

  ngOnInit(): void {
    this.queryRouteSubscription = this.route.queryParams.subscribe(
      (queryParam: any) => {
        this.recipient = queryParam['email'];
        this.recipientCode = queryParam['code'];
        this.status = queryParam['status'];
        this.isError.next(false);
        if (!!this.status) {
          this.page = 'restore-complete';
        } else if (!!this.recipient && !!this.recipientCode) {
          this.page = 'restore-password';
        } else if (!!this.recipient) {
          this.page = 'restore-code';
        }
      }
    );

    this.recipient$.subscribe((res) => {
      if (this.loginFormGroup.valid) {
        this.recipient = this.loginFormGroup.controls['login'].value;
        this.router.navigate([], {queryParams: {email: this.recipient}});
      }
    });

    this.code$.subscribe((res) => {
      if (this.codeFormGroup.valid) {
        this.recipientCode = this.codeFormGroup.controls['code'].value;
        this.router.navigate([], {queryParams: {email: this.recipient, code: this.recipientCode}});
      }
    });

    this.psw$.subscribe(() => {
      if (this.newPasswordFormGroup.valid) {
        this.router.navigate([], {queryParams: {status: 'complete'}});
      }
    });
  }

  submitLogin(): void {
    this.setError();
    if (this.loginFormGroup.valid) {
      this.store.dispatch(new GetRestoreAction(this.loginFormGroup.controls['login'].value));
    } else {
      this.snackBar.handleMessage('Заполните форму', 'warning-snackBar', 2000);
    }
  }

  submitCode(): boolean {
    this.setError();
    if (this.codeFormGroup.valid) {
      this.store.dispatch(new RequestRestoreCheckCodeAction(
        {
          code: this.codeFormGroup.controls['code'].value,
          recipient: this.recipient
        }));
    } else {
      this.snackBar.handleMessage('Заполните форму', 'warning-snackBar', 2000);
    }
    return false;
  }

  submitNewPassword(): boolean {
    this.store.dispatch(new RequestAccountPasswordRecovery(
      {
        code: this.recipientCode,
        recipient: this.recipient,
        newPassword: this.newPasswordFormGroup.controls['newPassword'].value,
        newPasswordConfirm: this.newPasswordFormGroup.controls['newPasswordConfirm'].value
      }));
    return false;
  }

  toAuth(): any {
    return this.router.navigate(['auth']);
  }

  setError(): void {
    this.isError.next(true);
    setTimeout(() => this.isError.next(false), 8000);
  }
}
