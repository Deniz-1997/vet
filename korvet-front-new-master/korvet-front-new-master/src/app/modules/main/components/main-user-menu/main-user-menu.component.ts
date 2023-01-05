import {Component, OnInit} from '@angular/core';
import {Store} from '@ngrx/store';
import {NavigationEnd, Router} from '@angular/router';
import {filter} from 'rxjs/operators';
import {AppointmentsPermissionService} from '../../../../services/appointments-permission.service';
import {ApiMenuService} from 'src/app/api/api-menu/api-menu.service';
import {GetLogoutAction} from 'src/app/api/auth/auth.actions';
import {AuthState} from 'src/app/api/auth/auth.reducer';

declare var $: any;

@Component({
  selector: 'app-main-user-menu',
  templateUrl: './main-user-menu.component.html',
  styleUrls: ['./main-user-menu.component.css']
})
export class MainUserMenuComponent implements OnInit {

  constructor(
    private store: Store<AuthState>,
    private router: Router,
    private menuService: ApiMenuService,
    private appointmentsPermission: AppointmentsPermissionService,
  ) {
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(event => {
        if ($('.menu-column__user-menu-dr:visible').length || $('.menu-column__ms-popup:visible').length) {
          $('.menu-column__user-menu-dr').hide();
          $('.menu-column__user-menu-ico a').removeClass('active');
          $('.menu-column__ms-popup').hide();
          $('.menu-column__ms-ico a').removeClass('active');
        }
      });
  }

  ngOnInit() {
    if ($('.menu-column__user-menu-ico a').length) {
      $(window).scroll(function () {
        if ($('.menu-column__user-menu-dr:visible').length || $('.menu-column__ms-popup:visible').length) {
          $('.menu-column__user-menu-dr').hide();
          $('.menu-column__user-menu-ico a').removeClass('active');
          $('.menu-column__ms-popup').hide();
          $('.menu-column__ms-ico a').removeClass('active');
        }
      });
    }
  }

  close(e?: Event): void {
    if (e) {
      e.preventDefault();
    }
    $('.menu-column__user-menu-ico a').removeClass('active');
    $('#user-menu').fadeOut();
  }

  logout(e?: Event): void {
    this.menuService.removeMenu();

    this.appointmentsPermission.removeAppointments();

    this.store.dispatch(new GetLogoutAction());
  }

}
