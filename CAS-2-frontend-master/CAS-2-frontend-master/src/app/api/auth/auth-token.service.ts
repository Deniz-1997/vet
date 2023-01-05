import { Injectable, Optional } from '@angular/core';
import { TokenAuthInterface } from './auth.models';
import { AuthConfig } from './auth.config';
import { StorageType } from './auth.utils';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthTokenService {

  readonly tokenName: string = 'token';
  private lastRoute: string;

  constructor(@Optional() private authConfig: AuthConfig, private router: Router) {
    if (authConfig) {
      this.tokenName = authConfig.tokenName;
    }
  }

  setLastRoute(): void {
    this.lastRoute = this.router.url;
  }

  getLastRoute(): string {
    return this.lastRoute;
  }

  get(): any {
    try {
      const token = localStorage.getItem(this.tokenName) || sessionStorage.getItem(this.tokenName);
      return JSON.parse(token);
    } catch (e) {
      return null;
    }
  }

  set(token: TokenAuthInterface, storageType?: StorageType): void {
    const storage = storageType ? this.getStorage(storageType) : this.getCurrentStorage();
    storage.setItem(this.tokenName, JSON.stringify(token));
  }

  remove(): void {
    localStorage.removeItem(this.tokenName);
    sessionStorage.removeItem(this.tokenName);
  }

  check(): any {
    return !!localStorage.getItem(this.tokenName) || !!sessionStorage.getItem(this.tokenName);
  }

  getStorage(storageType: StorageType): Storage {
    switch (storageType) {
      case StorageType.sessionStorage:
        return sessionStorage;
      case StorageType.localStorage:
      default:
        return localStorage;
    }
  }

  getCurrentStorage(): Storage {
    return (sessionStorage.getItem(this.tokenName) && sessionStorage) || localStorage;
  }

}
