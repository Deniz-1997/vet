import {Component, Input, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {NotifyService} from '../../../../services/notify.service';
import {MatDialog} from '@angular/material/dialog';
import {ModalSelectPetComponent} from '../modal-select-pet/modal-select-pet.component';
import {CrudType} from 'src/app/common/crud-types';
import {Urls} from '../../../../common/urls';
import {CrudDataType} from 'src/app/api/api-connector/crud/crud.config';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {AuthTokenService} from 'src/app/api/auth/auth-token.service';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-print-forms',
  templateUrl: './print-forms.component.html',
  styleUrls: ['./print-forms.component.css']
})
export class PrintFormsComponent implements OnInit {
  @Input() ownerId: number;
  @Input() petId: number;
  @Input() appointmentId: number;
  @Input() leavingId: number;

  @Input() pets;
  @Input() owners;
  @Input() appointments;
  @Input() leaving;

  @Input() printType: string;
  @Input() partition = 'Owner';
  @Input() size = 'normal';

  printForms$: Observable<CrudDataType[]>;
  currentPet: number;
  type = CrudType.PrintForms;

  constructor(
    private store: Store<CrudState>,
    private notify: NotifyService,
    private dialog: MatDialog,
    private tokenService: AuthTokenService,
  ) {
  }

  ngOnInit() {

    this.printForms$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {
          enabled: true,
          '~type': this.partition
        }
      }
    }));
  }

  onPrintForms(printForm, format) {
    // TODO нужно добавить проверку на Owners и сделать выборку владельцев
    if (printForm.type.indexOf('Pet') !== -1 && typeof this.pets !== 'undefined') {
      if (this.pets.length === 1) {
        this.currentPet = this.pets[0].pet.id;
        this.showPrintForm(printForm.id, format);
      } else if (this.pets.length > 1) {
        const pets = this.pets.map(item => item.pet);
        this.openDialog(pets, printForm);
      } else {
        this.notify.handleMessage('Сначала нужно добавить животное к владельцу', 'danger', 5000);
      }
    } else {
      this.showPrintForm(printForm.id, format);
    }
  }

  showPrintForm(printFormId, format = 'docx') {

    const params: { id?: number, type?: string, access_token?: string, type_id?: number, owner_id?: number, pet_id?: number, appointment_id?: number, leaving_id?: number } = {};
    params.id = printFormId;

    if (this.ownerId) {
      params.owner_id = this.ownerId;
    }

    if (this.petId) {
      params.pet_id = this.petId;
    }

    if (this.appointmentId) {
      params.appointment_id = this.appointmentId;
    }
    if (this.leavingId) {
      params.leaving_id = this.leaving;
    }

    params.type = this.partition;

    switch (params.type.toLowerCase()) {
      case 'owner':
        params.type_id = params.owner_id;
        break;
      case 'appointment':
        params.type_id = params.appointment_id;
        break;
      case 'leaving':
        params.type_id = params.leaving_id;
        break;
      case 'pet':
        params.type_id = params.pet_id;
        break;
    }

    const token = this.tokenService.get();

    params.access_token = token.access_token;

    const qs = Object.keys(params)
      .map(key => `${key}=${params[key]}`)
      .join('&');

    let url = Urls.api + 'print/download/?' + qs;

    if (format === 'pdf') {
      url += '&pdf=1';
    }

    console.log(url);
    window.open(url);
  }

  openDialog(pets, printForm): void {
    const dialogRef = this.dialog.open(ModalSelectPetComponent, {
      data: pets
    });

    dialogRef.afterClosed().subscribe((currentPetId: number) => {
      this.currentPet = currentPetId;
      this.showPrintForm(printForm.id);
    });
  }

}
