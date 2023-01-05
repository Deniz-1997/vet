import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {RoleModel} from '../../../../../../models/role.models';

@Component({templateUrl: './edit.component.html'})

export class EditComponent  implements OnInit {
   listNavigate = ['admin', 'role'];
   titleName = 'Роль доступа';
   type = CrudType.Role;
   model = RoleModel;
   title = 'Создать';

  constructor(
  ) {
  }

  ngOnInit(): void {
  }

}
