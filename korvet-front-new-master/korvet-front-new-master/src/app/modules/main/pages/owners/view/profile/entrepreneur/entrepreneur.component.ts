import {Component, OnInit} from '@angular/core';
import {ViewService} from '../../view.service';

@Component({templateUrl: './entrepreneur.component.html'})

export class EntrepreneurComponent implements OnInit {

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
