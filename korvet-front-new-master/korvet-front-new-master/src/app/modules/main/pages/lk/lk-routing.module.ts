import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LkComponent} from './lk.component';
import {AppModule} from '../../../../app.module';
import {AuthService} from '../../../../services/auth.service';

const injector = AppModule.injector.get(AuthService);
const routes: Routes = [{
  path: '',
  component: LkComponent,
  data: {breadcrumb: injector.user$.getValue() && injector.user$.getValue().user.username},
  children: [
    {
      path: '',
      loadChildren: () => import('./main/main.module').then(m => m.MainModule)
    },
    {
      path: 'change-password',
      loadChildren: () => import('./change-password/password.module').then(m => m.PasswordModule),
      data: {breadcrumb: 'Смена пароля'}
    },
    {
      path: 'notifications',
      loadChildren: () => import('./notifications/notifications.module').then(m => m.NotificationsModule)
    },
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LkRoutingModule {
}
