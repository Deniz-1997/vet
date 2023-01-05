import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {DeathComponent} from './death/death.component';
import {RegisterComponent} from './register/register.component';

const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '',
        redirectTo: 'profile',
        pathMatch: 'full'
      },
      {
        path: 'profile',
        loadChildren: () => import('./profile/profile.module').then(m => m.ProfileModule),
      },
      {
        path: 'register',
        data: {breadcrumb: 'Регистрация отлова'},
        children: [
          {
            path: '',
            component: RegisterComponent,
          },
          {
            path: ':registerId',
            component: RegisterComponent
          }
        ]
      },
      {
        path: 'death',
        component: DeathComponent,
        data: {breadcrumb: 'Регистрация смерти'},
      },
    ]
  },
  {
    path: 'edit',
    loadChildren: () => import('../edit/edit.module').then(m => m.EditModule),
    data: {breadcrumb: 'Редактирование'},
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewRoutingModule {
}
