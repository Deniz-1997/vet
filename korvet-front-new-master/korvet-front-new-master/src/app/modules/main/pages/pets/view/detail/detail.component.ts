import {Component, Input, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {CrudType} from 'src/app/common/crud-types';
import {ModalConfirmComponent} from 'src/app/modules/shared/components/modal-confirm/modal-confirm.component';
import {PetModel} from '../../../../../../models/pet/pet.models';

@Component({
  selector: 'app-pets-view-detail',
  templateUrl: './detail.component.html',
})
export class DetailComponent implements OnInit {
  @Input() pet: PetModel;
  @Input() ownerId: number;
  @Input() showName: boolean;
  @Input() petToOwnerId: number;
  typeIcon: string;

  constructor(private dialog: MatDialog, private store: Store<CrudState>, private router: Router) {
  }

  ngOnInit() {
    if (this.pet) {
      this.typeIcon = this.pet.type?.icon?.code;
    }
  }

  getData(row, prop) {
    if (!row) {
      return '';
    }
    const arr = prop.split('.');
    while (arr.length && (row = row[arr.shift()])) {
    }
    return row;
  }

  deletePetToOwners(): void {
    const dialog = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить животное у владельца?',
        headComment: 'Действие необратимо. Животное удалено не будет, его можно будет найти позже в списке животных.',
        actions: [
          {title: 'Отмена', class: 'btn-st btn-st--left btn-st--gray', action: false},
          {title: 'Удалить', class: 'btn-st btn-st--right btn-st--red', action: true},
        ]
      }
    });
    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.PetToOwner,
          params: {id: this.petToOwnerId},
          onSuccess: () => this.store.dispatch(new LoadGetAction({type: CrudType.Owner, params: this.ownerId}))
        }));
      }
    });
  }

}
