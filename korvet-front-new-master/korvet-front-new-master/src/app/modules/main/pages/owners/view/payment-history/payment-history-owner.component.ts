import {Component, OnInit} from '@angular/core';

import {ViewService} from '../view.service';
import {OwnerModel} from '../../../../../../models/owner/owner.models';
import {CrudType} from '../../../../../../common/crud-types';
@Component({templateUrl: './payment-history-owner.component.html'})

export class PaymentHistoryOwnerComponent implements OnInit {
  owner = new OwnerModel();
  typeAppointment = CrudType.Appointment;
  typeLeaving = CrudType.Leaving;


  constructor(
    public ownerViewService: ViewService,
  ) {
    ownerViewService.owner$.subscribe(owner => this.owner = owner);
  }
  ngOnInit() {

  }


}




