import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {Subject} from 'rxjs';
import {takeUntil} from 'rxjs/operators';

@Component({templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})

export class EditComponent implements OnInit {
  id: string;
  private destroy$ = new Subject<any>();

  constructor(
    protected router: Router,
    protected route: ActivatedRoute,
  ) {
    this.route.params.pipe(
      takeUntil(this.destroy$)
    ).subscribe(params => {
      this.id = params['id'];
    });
  }

  ngOnInit() {
  }

  getBackLink() {
    return ['create', null, undefined].indexOf(this.id) > -1 ? '/pets' : '/pets/' + this.id;
  }

  cancel() {
    this.router.navigateByUrl(this.getBackLink()).then();
  }

}
