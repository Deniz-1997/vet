import {Component, OnDestroy, OnInit} from '@angular/core';

@Component({templateUrl: './not-found.component.html', styleUrls: ['./not-found.component.css']})
export class NotFoundComponent implements OnInit, OnDestroy {

  constructor() {
  }

  ngOnInit() {
    const body = document.getElementsByTagName('body')[0];
    body.classList.add('page-404');
  }

  ngOnDestroy() {
    const body = document.getElementsByTagName('body')[0];
    body.classList.remove('page-404');
  }

}
