import { Routes} from '@angular/router';
import {AuthGuard} from '../api/auth/auth.guard';

export function  routerList(listComponet: any ,
                            createComponet: any,
                            breadcrumbCreate: string = 'Создать отчет',
                            breadcrumbUpdate: string = 'Редактировать отчет'): Routes {
  return [
    {
      path: '',
      canActivate: [AuthGuard],
      children: [
        {
          path: '',
          data: {title: ''},
          component: listComponet
        },
        {
          path: 'create',
          component: createComponet,
          data: {breadcrumb: breadcrumbCreate}
        },
        {
          path: ':id',
          component: createComponet,
          data: { breadcrumb: breadcrumbUpdate}
        }
      ]
    }
  ];
}

