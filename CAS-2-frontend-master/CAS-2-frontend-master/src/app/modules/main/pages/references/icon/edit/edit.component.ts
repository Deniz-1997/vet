import {Component, OnInit} from '@angular/core';
import {ReferenceIconModel} from 'src/app/models/reference/icon.model';
import {CrudType} from '../../../../../../common/crud-types';


@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  listNavigate = ['admin', 'references', 'icon'];
  titleName = 'Иконку';
  model = ReferenceIconModel;
  type = CrudType.ReferenceIcon;

  constructor() {
  }

  ngOnInit(): void {
  }
}
