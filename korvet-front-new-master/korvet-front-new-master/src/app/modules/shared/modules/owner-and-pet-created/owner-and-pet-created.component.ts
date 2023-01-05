import {EditComponent} from '../../../main/pages/owners/edit/edit.component';
import {PetAddFormComponent} from '../pet-add-form/pet-add-form.component';
import {Component, Input, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {FormGroup} from '@angular/forms';
import {LeavingModel} from '../../../../models/leaving/leaving.models';
import {Store} from '@ngrx/store';
import {CrudType} from '../../../../common/crud-types';
import {AuditionService} from '../../../../services/audition.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';


@Component({
  selector: 'app-owner-and-pet-created',
  templateUrl: './owner-and-pet-created.component.html',
})
export class OwnerAndPetCreatedComponent implements OnInit {
  @Input() ownerId: number;
  @Input() owner: OwnerModel;
  @Input() petId: number;
  formGroup: FormGroup;
  type: CrudType.Leaving;
  @Input() leaving: LeavingModel;

  constructor(private dialog: MatDialog,
              public auditionService: AuditionService,
              private store: Store<CrudState>) {
  }

  ngOnInit() {
}


  addOwner($event): void {
    if ($event) {
      $event.preventDefault();
    }
    const dialogRef = this.dialog.open(EditComponent, {
      data: {
        openDialog : true,
      }

    });
    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.ownerId = res.id;
        this.owner = res;
        this.auditionService.dataOwner(res);
        if (!this.owner.pets || this.owner.pets.length == 0) {
          this.addPet($event);
        }
      }
    });
  }

  addPet($event): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.ownerId !== undefined) {
      const dialogRef = this.dialog.open(PetAddFormComponent, {
        data: {
          openDialog: true,
          owner: this.owner
        }
      });
      dialogRef.afterClosed().subscribe(res => {
        if (res) {
          this.petId = res.pet.id;
          this.auditionService.dataPet(res.pet);
        }
      });
    }
  }
}

