import {Component, OnDestroy, OnInit} from '@angular/core';
import {PetModel} from '../../../../../models/pet/pet.models';
import {Observable, Subscription} from 'rxjs';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {ViewService} from './view.service';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {filter} from 'rxjs/operators';

@Component({
  selector: 'app-pets-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit, OnDestroy {
  tabs: { link: string | string[], title: string }[] = [
    {link: ['profile'], title: 'Профиль и Владельцы'},
    {link: ['card'], title: 'Амбулаторная карта'},
    {link: ['history'], title: 'История обращений'},
    {link: ['documents'], title: 'Документы'},
    {link: ['researchs'], title: 'Анализы'},
    {link: ['payment-history'], title: 'История оплаты'},
  ];
  pet = new PetModel();
  loading$: Observable<boolean>;
  private subscriptions: Subscription[] = [];
  iconType: string;

  constructor(
    public petsViewService: ViewService,
    private route: ActivatedRoute,
    public router: Router,
    private brdSrv: BreadcrumbsService,
  ) {
    let s = route.paramMap.subscribe(routeParam => {
      this.petsViewService.id = routeParam.get('id');
    });
    this.subscriptions.push(s);
    this.loading$ = petsViewService.loading$;
    s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.pet = pet;
        this.iconType = this.pet.type.icon?.code;
        this.brdSrv.replaceLabelByIndex(this.pet.name, 2);
      }
    });
    this.subscriptions.push(s);
    s = this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        if (this.pet && this.pet.name) {
          this.brdSrv.replaceLabelByIndex(this.pet.name, 2);
        }
      });
    this.subscriptions.push(s);
  }

  ngOnInit() {

  }

  isEmptyOwner() {
    return !this.pet.owners || this.pet.owners.length <= 0;
  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

}
