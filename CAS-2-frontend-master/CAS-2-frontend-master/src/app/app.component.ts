import {Component, HostBinding, OnInit} from '@angular/core';
import {RouterOutletService} from './services/router-outlet.service';
import {SettingsService} from './services/settings.service';
import {NavigationEnd, Router} from '@angular/router';
import {LoadingService} from './services/loading.service';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: []
})
export class AppComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-application--wrap'
  ];
  title = 'app';

  constructor(
    public loading: LoadingService,
    private routingState: RouterOutletService,
    private setting: SettingsService,
    private router: Router
  ) {
    routingState.loadRouting();
    router.events.subscribe(e => {
      if (!(e instanceof NavigationEnd)) {
        return;
      }

      if (window.innerWidth <= 1024) {
        const sideBarMenu = document.getElementById('main-sidenav');
        if (sideBarMenu) {
          sideBarMenu.style.visibility = 'hidden';
        }
      }
    });
  }

  ngOnInit(): void {
  }

}
