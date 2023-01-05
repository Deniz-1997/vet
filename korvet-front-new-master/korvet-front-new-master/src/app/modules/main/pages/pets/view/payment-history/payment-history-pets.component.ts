import {Component, OnInit} from '@angular/core';

import {ViewService} from '../view.service';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {CrudType} from '../../../../../../common/crud-types';

@Component({templateUrl: './payment-history-pets.component.html'})

export class PaymentHistoryPetsComponent implements OnInit {
  pet = new PetModel();
  typeAppointment = CrudType.Appointment;
  typeLeaving = CrudType.Leaving;


  constructor(
    public petsViewService: ViewService

  ) {

    petsViewService.pet.subscribe(pet => this.pet = pet);
  }

  ngOnInit() {
  }
}




