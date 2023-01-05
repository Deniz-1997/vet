import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { AuthService } from './auth.service';
import { Store } from '@ngrx/store';
import { AuthState } from './auth.reducer';
import { finalize, map, tap } from 'rxjs/operators';
import { LogoutAction } from './auth.actions';
import { AuthUserService } from './auth-user.service';

@Injectable({
  providedIn: 'root'
})
export class AuthUserGuard implements CanActivate {

  loading$ = new BehaviorSubject(false);

  constructor(
    private service: AuthService,
    private store: Store<AuthState>,
    private userService: AuthUserService,
  ) {}

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
    this.loading$.next(true);
    return this.service.getAccountUser()
      .pipe(
        finalize(() => this.loading$.next(false)),
        tap(
          (res) => this.userService.setUser(res['response']),
          () => this.store.dispatch(new LogoutAction())
        ),
        map(response => true)
  );
  }
}
