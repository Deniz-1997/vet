import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthUserService {

  constructor(private service: AuthService) { }

  setUser(user): void {
    return localStorage.setItem(this.service.getUserKey(), JSON.stringify(user));
  }

  getUser(): any {
    try {
      return JSON.parse(localStorage.getItem(this.service.getUserKey()));
    } catch (e) {
      return null;
    }
  }

  removeUser(): void {
    return localStorage.removeItem(this.service.getUserKey());
  }

}
