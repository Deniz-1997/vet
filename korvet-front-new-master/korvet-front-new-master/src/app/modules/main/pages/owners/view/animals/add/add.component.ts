import {Component, OnInit} from '@angular/core';
import {ViewService} from '../../view.service';
import {ActivatedRoute, Router} from '@angular/router';

@Component({templateUrl: './add.component.html'})

export class AddComponent implements OnInit {
  constructor(
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute
  ) {

  }

  get owner$() {
    return this.service.owner$;
  }

  get owner() {
    return this.service.owner;
  }

  ngOnInit() {

  }

  navigate(): void {
    this.router.navigate(['../'], {relativeTo: this.route.parent}).then();
  }
}


