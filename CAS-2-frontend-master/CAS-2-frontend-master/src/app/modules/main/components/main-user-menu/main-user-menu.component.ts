import {Component, OnInit} from '@angular/core';
import {Store} from '@ngrx/store';
import {NavigationEnd, Router} from '@angular/router';
import {filter} from 'rxjs/operators';
import {AuthState} from '../../../../api/auth/auth.reducer';
import {ApiMenuService} from '../../../../api/api-menu/api-menu.service';
import {GetLogoutAction} from '../../../../api/auth/auth.actions';


@Component({
  selector: 'app-main-user-menu',
  templateUrl: './main-user-menu.component.html'
})
export class MainUserMenuComponent implements OnInit {

  constructor(
    private store: Store<AuthState>,
    private router: Router,
    private menuService: ApiMenuService,
  ) {
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(event => {
        // if ($('.menu-column__user-menu-dr:visible').length || $('.menu-column__ms-popup:visible').length) {
        //   $('.menu-column__user-menu-dr').hide();
        //   $('.menu-column__user-menu-ico a').removeClass('active');
        //   $('.menu-column__ms-popup').hide();
        //   $('.menu-column__ms-ico a').removeClass('active');
        // }
      });
  }

  ngOnInit(): void {
    // if ($('.menu-column__user-menu-ico a').length) {
    //   $(window).scroll(function () {
    //     if ($('.menu-column__user-menu-dr:visible').length || $('.menu-column__ms-popup:visible').length) {
    //       $('.menu-column__user-menu-dr').hide();
    //       $('.menu-column__user-menu-ico a').removeClass('active');
    //       $('.menu-column__ms-popup').hide();
    //       $('.menu-column__ms-ico a').removeClass('active');
    //     }
    //   });
    // }
  }

  close(e?: Event): void {
    // if (e) {
    //   e.preventDefault();
    // }
    // $('.menu-column__user-menu-ico a').removeClass('active');
    // $('.menu-column__user-menu-dr').fadeOut();
  }

  logout(e?: Event): void {
    localStorage.removeItem('switchUser');
    localStorage.removeItem('sidenav');
    this.menuService.removeMenu();
    this.store.dispatch(new GetLogoutAction());
    window.location.href = '/auth';
  }

}
