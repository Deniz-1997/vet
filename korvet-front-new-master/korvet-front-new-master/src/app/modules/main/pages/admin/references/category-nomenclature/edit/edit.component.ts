import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { select, Store } from '@ngrx/store';
import { ActivatedRoute, Router } from '@angular/router';
import { NotifyService } from '../../../../../../../services/notify.service';
import { ReferenceItemModels } from '../../../../../../../models/reference/reference.item.models';
import { BreadcrumbsService } from '../../../../../../../services/breadcrumbs.service';
import { CrudType } from 'src/app/common/crud-types';
import { ReferenceCategoryNomenclatureModel } from '../../../../../../../models/reference/reference.category.nomenclature.models';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({ templateUrl: './edit.component.html' })

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['store', 'reference-category-nomenclature'];
  protected titleName = 'Категории номенклатуры';
  public categories: ReferenceCategoryNomenclatureModel[] = [];
  categoryFields = { 0: 'id', 1: 'name', 2: 'parent', 3: 'sort' };
  crudType = CrudType;


  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ReferenceCategoryNomenclature, ReferenceCategoryNomenclatureModel);
  }

  getMaxParentCount(item, currentLevel = 0, maxParentLevel = 3): boolean {
    if ((currentLevel) >= maxParentLevel) {
      return true;
    }
    if (item['parent'] && currentLevel < maxParentLevel) {
      return this.getMaxParentCount(item['parent'], ++currentLevel, maxParentLevel);
    }
    return false;
  }

  protected setModel() {
    this.route.queryParams.subscribe(
      (queryParam: any) => {
        if (queryParam['parent']) {
          this.item.parent = { id: +queryParam['parent'], name: queryParam['parentName'] };
        }
      }
    );
    this.formGroup = new FormGroup({
      parent: new FormControl(this.item.parent ? { id: this.item.parent.id, name: this.item.parent.name } : null),
      name: new FormControl(this.item.name, [Validators.required]),
      sort: new FormControl(this.item.sort, [Validators.required]),
    });

  }

  filter(items) {
    let varifiedItems = [];
    while (items.length != varifiedItems.length) {
      for (const i in items) {
        if (!varifiedItems.find(n => n == items[i]['id'])) {
          if (this.getMaxParentCount(items[i])) {
            items.splice(i, 1);
            break;
          }
          else {
            varifiedItems.push(items[i]['id']);
          }
        }
      }
    }
  }
}
