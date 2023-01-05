import {Injectable} from '@angular/core';
import {NavigationEnd, Router} from '@angular/router';
import {filter} from 'rxjs/operators';
import {NoAccessService} from './no-access.service';

@Injectable({
  providedIn: 'root'
})
export class RouterOutletService {
  private history = [];

  constructor(
    private router: Router,
    public noAccessService: NoAccessService,
  ) {
  }

  public loadRouting(): void {

    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(({urlAfterRedirects}: NavigationEnd) => {
        this.history = [...this.history, urlAfterRedirects];
        this.noAccessService.noAccess.next(false);
      });
  }

  public getHistory(): Array<string> {
    return this.history;
  }

  public getPreviousUrl(): string {
    return this.history[this.history.length - 2] || '';
  }

  public getThisUrl(): string {
    return this.history[this.history.length - 1] || '';
  }
}
