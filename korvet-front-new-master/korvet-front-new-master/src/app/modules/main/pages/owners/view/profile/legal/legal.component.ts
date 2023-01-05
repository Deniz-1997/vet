import {Component, OnInit} from '@angular/core';
import {ViewService} from '../../view.service';

@Component({templateUrl: './legal.component.html'})

export class LegalComponent implements OnInit {

  constructor(
    private service: ViewService,
  ) {
  }

  get owner() {
    return this.service.owner;
  }

  ngOnInit() {
  }

}
