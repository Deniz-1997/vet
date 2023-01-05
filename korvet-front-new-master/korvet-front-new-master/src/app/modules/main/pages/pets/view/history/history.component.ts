import {Component, OnInit} from '@angular/core';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {ViewService} from '../view.service';

@Component({
  templateUrl: './history.component.html',
})

export class HistoryComponent implements OnInit {

  pet = new PetModel();

  constructor(
    public petsViewService: ViewService,
  ) {
    petsViewService.pet.subscribe(pet => this.pet = pet);
  }

  ngOnInit() {
  }

}
