import {Component, OnInit} from '@angular/core';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {ViewService} from '../view.service';

@Component({
  selector: 'app-pets-view-card',
  templateUrl: './card.component.html'
})
export class CardComponent implements OnInit {
  pet = new PetModel();

  constructor(
    public petsViewService: ViewService,
  ) {
    petsViewService.pet.subscribe(pet => {
      this.pet = pet;
    });
  }

  ngOnInit() {
  }

}
