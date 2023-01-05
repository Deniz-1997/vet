import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { Store } from '@ngrx/store';
import { AuthState } from './auth.reducer';
import { AuthTokenService } from './auth-token.service';
import { LogoutAction } from './auth.actions';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(
    private token: AuthTokenService,
    private store: Store<AuthState>
  ) {}

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
    if (!this.token.check()) {
      this.store.dispatch(new LogoutAction());
      return false;
    }
    return true;
  }
}
