import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {OwnerAddComponent} from './profile/owner-add/owner-add.component';
import {AddComponent as EventAdd} from './profile/events/add/add.component';
import {AddComponent as AppointmentAdd} from './profile/appointments/add/add.component';
import {AddLeavingComponent} from './view/history/leaving-add/add-leaving.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [{
  path: '',
  canActivate: [AuthGuard],
  data: {breadcrumb: 'Животные', title: 'Животные', pageTitle: 'Животные'},
  children: [
    {
      path: 'create',
      component: EditComponent,
    },
    {
      path: ':id',
      loadChildren: () => import('./view/view.module').then(m => m.ViewModule),
      data: {breadcrumb: 'Профиль животного', title: 'Профиль животного'},
    },
    {
      path: ':id',
      data: {breadcrumb: 'Профиль животного', title: 'Профиль животного'},
      children: [
        {
          path: 'edit',
          component: EditComponent,
          data: {breadcrumb: 'Редактировать животное', title: 'Редактировать животное'}
        },
        {
          path: 'add-owner',
          component: OwnerAddComponent,
          data: {title: 'Добавить владельца', breadcrumb: 'Добавить владельца для животного'}
        },
        {
          path: 'events/:idEvent',
          component: EventAdd,
          data: {breadcrumb: 'Добавить мероприятие', title: ''}
        },
        {
          path: 'add-appointment',
          component: AppointmentAdd,
          data: {breadcrumb: 'Запись на прием', title: ''}
        },
        {
          path: 'add-leaving',
          component: AddLeavingComponent,
          data: {breadcrumb: 'Запись на выезд', title: ''}
        },
      ]
    },
    {
      path: '',
      component: ListComponent,
    }
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PetsRoutingModule {
}
