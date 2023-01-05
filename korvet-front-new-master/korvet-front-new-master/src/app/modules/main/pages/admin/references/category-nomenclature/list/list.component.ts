import { FlatTreeControl } from '@angular/cdk/tree';
import { Component, OnInit } from '@angular/core';
import { MatTreeFlatDataSource, MatTreeFlattener } from '@angular/material/tree';
import { select, Store } from '@ngrx/store';
import { BehaviorSubject, Observable } from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import { CrudType } from 'src/app/common/crud-types';
import { ReferenceCategoryNomenclatureInterface, ReferenceCategoryNomenclatureModel } from 'src/app/models/reference/reference.category.nomenclature.models';

interface Node extends ReferenceCategoryNomenclatureInterface {
  children?: Node[];
}

interface MatTreeNode {
  expandable: boolean;
  name: string;
  level: number;
}

@Component({
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {
  loading$ = new BehaviorSubject(false);
  type = CrudType.ReferenceCategoryNomenclature;
  nomenclatureList: ReferenceCategoryNomenclatureModel[] = [];
  maxLevel: number = 3;
  constructor(protected store: Store<CrudState>) {
  }

  private _transformer = (node: Node, level: number) => {
    return {
      expandable: !!node.children && node.children.length > 0,
      name: node.name,
      level: level,
      id: node.id
    };
  }

  treeControl = new FlatTreeControl<MatTreeNode>(
    node => node.level, node => node.expandable);

  treeFlattener = new MatTreeFlattener(
    this._transformer, node => node.level, node => node.expandable, node => node.children);

  dataSource = new MatTreeFlatDataSource(this.treeControl, this.treeFlattener);

  hasChild = (_: number, node: MatTreeNode) => node.expandable;

  ngOnInit() {
    this.loadData();
  }

  formatData(parent: ReferenceCategoryNomenclatureModel) {
    let treeList: any;
    if (parent) {
      treeList = this.nomenclatureList.filter(n => n.parent != null && n.parent.id == parent.id);
    }
    else {
      treeList = this.nomenclatureList.filter(n => n.parent == null);
    }
    for (let i = 0; i < treeList.length; i++) {
      treeList[i]['children'] = this.formatData(treeList[i]);
    }
    return treeList;
  }

  loadData(offset: number = 0) {
    const limit: number = 100;
    this.loading$.next(true);
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        limit: limit,
        offset: limit * offset,
      },
      onSuccess: (res) => {
        if (res.status === true && res.response) {
          this.nomenclatureList = this.nomenclatureList.concat(res.response.items);
          console.log(res.response.totalCount);
          if (res.response.totalCount > (offset + 1) * limit) {
            this.loadData(++offset);
          } 
          else {
            this.dataSource.data = this.formatData(null);
            this.treeControl.expandAll();
            this.loading$.next(false);
          }
        }
      }
    }));
  }
}
