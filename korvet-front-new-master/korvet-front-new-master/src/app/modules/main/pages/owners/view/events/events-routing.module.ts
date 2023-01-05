import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {EventsComponent} from './events.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'add',
    pathMatch: 'full'
  },
  {
    path: 'add',
    component: EventsComponent,
    data: {breadcrumb: 'Добавить мероприятие'}
  },
  {
    path: ':eventId',
    component: EventsComponent,
    data: {breadcrumb: 'Редактировать мероприятие'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EventsRoutingModule {
}
