import {Component, Inject, OnInit, Optional} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {Observable} from 'rxjs';
import {ActionGroupModel} from 'src/app/models/action/action.group.models';

@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  itemsAutoComplete: Observable<Array<ActionGroupModel>>;
  crudTypeAutoComplete = CrudType.ActionGroup;
  listNavigate = ['admin', 'references', 'action-group'];
  titleName = 'Группы действий';
  title = 'Создать';
  type = CrudType.ActionGroup;
  model = ActionGroupModel;

  constructor() {
  }

  ngOnInit(): void {
  }
}
