
import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {Router} from '@angular/router';
import {SettingsService} from '../../../../services/settings.service';
import {HttpClient} from '@angular/common/http';
import {NotifyService} from '../../../../services/notify.service';
import { MatDialog } from '@angular/material/dialog';
import { ModalSupportFormComponent } from 'src/app/modules/shared/components/modal-support-form/modal.component';
import {SnackBarService} from '../../../../services/snack-bar.service';
import {UserAuthModel} from '../../../../api/auth/auth.models';
import {AuthState} from '../../../../api/auth/auth.reducer';
import {getUser} from '../../../../api/auth/auth.selectors';
import {ApiMenuService} from '../../../../api/api-menu/api-menu.service';

@Component({
  selector: 'app-main-sidenav',
  templateUrl: './main-sidenav.component.html',
  styleUrls: ['./main-sidenav.component.css']
})
export class MainSidenavComponent implements OnInit {
  config: any;
  configHref: string;
  configGlobalPhone: string;
  configMailto: string;
  user$: Observable<UserAuthModel>;
  user: UserAuthModel;
  isChecked =  false;
  color: '#000000';
  date: any;
  path = '/uploaded/guide-korvet.pdf';

  constructor(private store: Store<AuthState>,
              private http: HttpClient,
              private router: Router,
              public menuService: ApiMenuService,
              private setting: SettingsService,
              private snackBar: SnackBarService,
              private notify: NotifyService,
              private dialog: MatDialog,
  ) {
    this.user$ = store.pipe(select(getUser));
    this.menuService.getMenu();
    this.date = new Date().getFullYear();
  }

  ngOnInit(): void {
    this.user$.subscribe(user => {
      if (user) {
        this.user = user;
        // @ts-ignore
        this.isChecked = user.user.modeCashboxMobile;
      }
    });
    this.setting.contactValue.subscribe(() => {
        this.config = this.setting.contactValue.value;
        this.configHref = `tel:${this.config.tell}`;
        this.configGlobalPhone = `tel:${this.config.globalPhone}`;
        this.configMailto = `mailto:${this.config.email}`;
      }
    );
  }

  HideSideNav() {
    const sideBarMenu = document.getElementById('main-sidenav');
    if (sideBarMenu) {
      sideBarMenu.style.visibility = 'hidden';
    }
  }

  modeCashbox() {
    let head: string;
    this.isChecked ? head = 'Вы уверены, что хотите активировать режим мобильной кассы?' : head = 'Вы уверены, что хотите деактивировать режим мобильной кассы?';
    // const dialogRef = this.dialog.open(ModalConfirmComponent, {
    //   data: {
    //     head: head,
    //     actions: [
    //       {
    //         class: 'btn-st btn-st--left btn-st--gray',
    //         action: false,
    //         title: 'Отмена'
    //       },
    //       {
    //         class: 'btn-st btn-st--right btn-st--red',
    //         action: true,
    //         title: 'Да'
    //       },
    //     ],
    //   }
    // });
    // dialogRef.afterClosed().subscribe((result: boolean) => {
    //   if (result) {
    //     this.store.dispatch(new LoadPatchAction({
    //       type: CrudType.User,
    //       params: <any>{
    //         id: this.user.user.id,
    //         modeCashboxMobile: this.isChecked,
    //       },
    //       onSuccess: (res) => {
    //         this.notify.handleMessage(this.isChecked ? 'Включен режим мобильной кассы' : 'Выключен режим мобильной кассы', 'success', 3000);
    //       },
    //       onError: e => this.notify.handleMessage('Ошибка при включении режима', 'danger', 3000)
    //     }));
    //     // window.location.reload();
    //   } else  {
    //     this.isChecked = this.isChecked === false;
    //   }
    // });
  }

  openSupportModal() {

    const dialogRef = this.dialog.open(ModalSupportFormComponent, {
      width: '1000px',
      height: '100% - 50px',
      data: {
      },
    });

  }

  showSwitchUserExit() {
    return localStorage.getItem('switchUser') ? true : false;
  }

  SwitchUserExit() {
    localStorage.removeItem('switchUser');
    localStorage.removeItem('sidenav');
    window.location.reload();
  }
}
