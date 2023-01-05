import {Component, OnInit, ViewChild} from '@angular/core';
import {ViewContainerDirective} from '../../../../../shared/directives/view-container.directive';
import {Store} from '@ngrx/store';
import {PersonType} from '../../../../../../utils/person-type';
import {EntrepreneurComponent} from './entrepreneur/entrepreneur.component';
import {ViewService} from '../view.service';
import {IndividualComponent} from './individual/individual.component';
import {LegalComponent} from './legal/legal.component';
import {FarmComponent} from './farm/farm.component';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './profile.component.html'})

export class ProfileComponent implements OnInit {

  @ViewChild(ViewContainerDirective, {static: true}) appViewContainer: ViewContainerDirective;
  type = CrudType.Owner;

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
  ) {
  }

  get owner$() {
    return this.service.owner$;
  }

  ngOnInit() {
    this.owner$.subscribe(owner => {
      switch (owner.type) {
        case PersonType.ENTREPRENEUR:
          this.appViewContainer.loadComponent(EntrepreneurComponent);
          break;
        case PersonType.INDIVIDUAL_PERSON:
          this.appViewContainer.loadComponent(IndividualComponent);
          break;
        case PersonType.LEGAL_ENTITY:
          this.appViewContainer.loadComponent(LegalComponent);
          break;
        case PersonType.FARM:
          this.appViewContainer.loadComponent(FarmComponent);
          break;
        default:
          this.appViewContainer.clearComponent();
          break;
      }
    });
  }

}
