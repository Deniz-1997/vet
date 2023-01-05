import {Component, OnInit} from '@angular/core';
import {ViewService} from '../../view.service';

@Component({templateUrl: './farm.component.html'})
export class FarmComponent implements OnInit {

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
