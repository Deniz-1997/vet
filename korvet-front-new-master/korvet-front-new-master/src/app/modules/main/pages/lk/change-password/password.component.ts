import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {filter, take} from 'rxjs/operators';
import {NotifyService} from '../../../../../services/notify.service';
import {PasswordMessages} from './password.messages';
import {Router} from '@angular/router';
import {AuthState} from 'src/app/api/auth/auth.reducer';
import {getPasswordChangeLoading, getPasswordChangeStatus} from 'src/app/api/auth/auth.selectors';
import {RequestPasswordChange, ResponsePasswordChange} from 'src/app/api/auth/auth.actions';

@Component({templateUrl: './password.component.html'})

export class PasswordComponent implements OnInit, OnDestroy {

  formGroup = new FormGroup({
    oldPassword: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),
    passwordConfirm: new FormControl('', [Validators.required]),
  });
  passwordChangeLoading$: Observable<boolean>;
  private passwordChangeStatus$: Observable<boolean>;

  constructor(
    private store: Store<AuthState>,
    private notifyService: NotifyService,
    private router: Router,
  ) {
    this.passwordChangeStatus$ = store.pipe(select(getPasswordChangeStatus));
    this.passwordChangeLoading$ = store.pipe(select(getPasswordChangeLoading));
  }

  ngOnInit() {
    this.passwordChangeStatus$
      .pipe(filter(status => status), take(1))
      .subscribe(status => {
        this.notifyService.handleMessage(
          PasswordMessages.successPasswordChange,
          'success',
        );
        this.router.navigate(['/lk']).then();
      });
  }

  submit(): void {
    if (this.formGroup.valid) {
      this.store.dispatch(new RequestPasswordChange(this.formGroup.value));
    }
  }

  ngOnDestroy(): void {
    this.store.dispatch(new ResponsePasswordChange(false));
  }
}
